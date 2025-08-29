<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\PhaseDocument;
use App\Models\ProjectPhase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EnquiryPhaseDocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display documents for a specific enquiry phase.
     */
    public function index(Enquiry $enquiry, ProjectPhase $phase)
    {
        $this->authorize('view', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        $documents = PhaseDocument::where('enquiry_id', $enquiry->id)
            ->where('project_phase_id', $phase->id)
            ->active()
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.phases.documents.enquiry-index', compact('enquiry', 'phase', 'documents'));
    }

    /**
     * Show the form for uploading documents.
     */
    public function create(Enquiry $enquiry, ProjectPhase $phase)
    {
        // $this->authorize('update', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        // Check if user has permission to upload to this phase
        $this->checkPhaseUploadPermission($phase->name);
        
        return view('projects.phases.documents.enquiry-create', compact('enquiry', 'phase'));
    }

    /**
     * Store uploaded documents for enquiry phase.
     */
    public function store(Request $request, Enquiry $enquiry, ProjectPhase $phase)
    {
        // $this->authorize('update', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        $this->checkPhaseUploadPermission($phase->name);

        $request->validate([
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'required|file|max:50240', // 50MB max per file
            'description' => 'nullable|string|max:1000',
        ]);

        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                // Validate file type
                if (!$this->isAllowedFileType($file)) {
                    $errors[] = "File '{$file->getClientOriginalName()}' has an unsupported file type.";
                    continue;
                }

                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $storedName = Str::uuid() . '.' . $extension;
                
                // Create directory path based on enquiry and phase
                $directory = "enquiry-phase-documents/{$enquiry->id}/{$phase->id}";
                $filePath = $directory . '/' . $storedName;

                // Store the file
                $file->storeAs($directory, $storedName, 'public');

                // Create database record
                $document = PhaseDocument::create([
                    'project_phase_id' => $phase->id,
                    'enquiry_id' => $enquiry->id,
                    'phase_name' => $phase->name,
                    'original_filename' => $originalName,
                    'stored_filename' => $storedName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'file_extension' => strtolower($extension),
                    'uploaded_by' => auth()->id(),
                    'description' => $request->description,
                    'is_active' => true,
                ]);

                $uploadedFiles[] = $document;

            } catch (\Exception $e) {
                $errors[] = "Failed to upload '{$file->getClientOriginalName()}': " . $e->getMessage();
            }
        }

        // Auto-complete phase logic for enquiry phases: mark phase as Completed if any document was uploaded
        try {
            \Log::info('Enquiry phase document upload - status update attempt', [
                'enquiry_id' => $enquiry->id,
                'phase_id' => $phase->id,
                'phase_name' => $phase->name,
                'current_status' => $phase->status,
                'uploaded_files_count' => count($uploadedFiles),
            ]);
            
            if (count($uploadedFiles) > 0) {
                $updated = $phase->update(['status' => 'Completed']);
                
                \Log::info('Enquiry phase status update result', [
                    'enquiry_id' => $enquiry->id,
                    'phase_id' => $phase->id,
                    'phase_name' => $phase->name,
                    'update_successful' => $updated,
                    'new_status' => $phase->fresh()->status,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Phase auto-complete on upload failed', [
                'enquiry_id' => $enquiry->id,
                'phase_id' => $phase->id,
                'phase_name' => $phase->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // Prepare response message
        $message = '';
        if (count($uploadedFiles) > 0) {
            $message .= count($uploadedFiles) . ' file(s) uploaded successfully. ';
        }
        if (count($errors) > 0) {
            $message .= count($errors) . ' file(s) failed to upload.';
        }

        $messageType = count($errors) > 0 ? 'warning' : 'success';

        if ($request->ajax()) {
            return response()->json([
                'success' => count($uploadedFiles) > 0,
                'message' => $message,
                'uploaded_count' => count($uploadedFiles),
                'error_count' => count($errors),
                'errors' => $errors,
                'documents' => collect($uploadedFiles)->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'original_filename' => $doc->original_filename,
                        'file_size_human' => $doc->file_size_human,
                        'document_type' => $doc->document_type,
                        'icon_class' => $doc->icon_class,
                        'created_at' => $doc->created_at->format('M d, Y H:i'),
                        'uploader_name' => $doc->uploader->name,
                    ];
                })
            ]);
        }

        return redirect()->route('enquiries.phases.documents.index', [$enquiry, $phase])
            ->with($messageType, $message);
    }

    /**
     * Display the specified document.
     */
    public function show(Enquiry $enquiry, ProjectPhase $phase, PhaseDocument $document)
    {
        $this->authorize('view', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        if ($document->enquiry_id !== $enquiry->id || $document->project_phase_id !== $phase->id) {
            abort(404);
        }

        return view('projects.phases.documents.enquiry-show', compact('enquiry', 'phase', 'document'));
    }

    /**
     * Download the specified document.
     */
    public function download(Enquiry $enquiry, ProjectPhase $phase, PhaseDocument $document)
    {
        $this->authorize('view', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        // if ($document->enquiry_id !== $enquiry->id || $document->project_phase_id !== $phase->id) {
        //     abort(404);
        // }

        if (!$document->fileExists()) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_filename);
    }

    /**
     * Preview the specified document in browser.
     */
    public function preview(Enquiry $enquiry, ProjectPhase $phase, PhaseDocument $document)
    {
        $this->authorize('view', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        // if ($document->enquiry_id !== $enquiry->id || $document->project_phase_id !== $phase->id) {
        //     abort(404);
        // }

        if (!$document->fileExists()) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        
        // Set appropriate headers for inline display
        $headers = [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $document->original_filename . '"',
            'X-Frame-Options' => 'SAMEORIGIN', // Allow iframe embedding from same origin
        ];

        return response()->file($filePath, $headers);
    }

    /**
     * Remove the specified document.
     */
    public function destroy(Enquiry $enquiry, ProjectPhase $phase, PhaseDocument $document)
    {
        // $this->authorize('update', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        $this->checkPhaseUploadPermission($phase->name);
        
        // if ($document->enquiry_id !== $enquiry->id || $document->project_phase_id !== $phase->id) {
        //     abort(404);
        // }

        // Check if user can delete this document
        if ($document->uploaded_by !== auth()->id() && !auth()->user()->hasRole(['admin', 'super-admin', 'project_manager', 'pm'])) {
            return redirect()->back()->with('error', 'You can only delete documents you uploaded.');
        }

        $filename = $document->original_filename;
        $document->delete(); // This will also delete the file due to the model's booted method

        // Auto-revert phase logic for enquiry phases
        // If no documents remain for Design & Concept Development, revert to 'Not Started'
        try {
            if ($phase->name === 'Design & Concept Development') {
                $remainingDocsCount = PhaseDocument::where('enquiry_id', $enquiry->id)
                    ->forPhase('Design & Concept Development')
                    ->active()
                    ->count();
                $designAssetsCount = \App\Models\DesignAsset::where('enquiry_id', $enquiry->id)->count();
                
                // If no documents and no design assets remain, revert to 'Not Started'
                if ($remainingDocsCount === 0 && $designAssetsCount === 0) {
                    $phase->update(['status' => 'Not Started']);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Phase auto-revert on document deletion failed', [
                'enquiry_id' => $enquiry->id,
                'phase_id' => $phase->id,
                'phase_name' => $phase->name,
                'error' => $e->getMessage(),
            ]);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Document '{$filename}' deleted successfully."
            ]);
        }

        return redirect()->route('enquiries.phases.documents.index', [$enquiry, $phase])
            ->with('success', "Document '{$filename}' deleted successfully.");
    }

    /**
     * Bulk download documents as ZIP.
     */
    public function bulkDownload(Enquiry $enquiry, ProjectPhase $phase)
    {
        $this->authorize('view', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        $documents = PhaseDocument::where('enquiry_id', $enquiry->id)
            ->where('project_phase_id', $phase->id)
            ->active()
            ->get();

        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', 'No documents found to download.');
        }

        $zip = new \ZipArchive();
        $zipFileName = Str::slug($enquiry->project_name) . '-' . Str::slug($phase->name) . '-documents.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($documents as $document) {
                if ($document->fileExists()) {
                    $filePath = storage_path('app/public/' . $document->file_path);
                    $zip->addFile($filePath, $document->original_filename);
                }
            }
            $zip->close();
            
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }
        
        return redirect()->back()->with('error', 'Failed to create download archive.');
    }

    /**
     * Get documents via AJAX for dynamic loading.
     */
    public function getDocuments(Enquiry $enquiry, ProjectPhase $phase)
    {
        $this->authorize('view', $enquiry);
        $this->ensureDesignConceptPhase($phase);
        
        $documents = PhaseDocument::where('enquiry_id', $enquiry->id)
            ->where('project_phase_id', $phase->id)
            ->active()
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'documents' => $documents->map(function ($doc) use ($enquiry, $phase) {
                return [
                    'id' => $doc->id,
                    'original_filename' => $doc->original_filename,
                    'file_size_human' => $doc->file_size_human,
                    'document_type' => $doc->document_type,
                    'icon_class' => $doc->icon_class,
                    'created_at' => $doc->created_at->format('M d, Y H:i'),
                    'uploader_name' => $doc->uploader->name,
                    'description' => $doc->description,
                    'download_url' => route('enquiries.phases.documents.download', [$enquiry, $phase, $doc]),
                    'can_delete' => $doc->uploaded_by === auth()->id() || auth()->user()->hasRole(['admin', 'super-admin', 'project_manager', 'pm']),
                ];
            })
        ]);
    }

    /**
     * Check if user has permission to upload to this phase.
     */
    private function checkPhaseUploadPermission($phaseName)
    {
        $user = auth()->user();
        
        // Admin and super-admin can upload to any phase
        if ($user->hasRole(['admin', 'super-admin'])) {
            return true;
        }
        
        // Define phase permissions (same as project phase permissions)
        $phasePermissions = [
            'Design & Concept Development' => ['project_officer', 'project_manager', 'design', 'po', 'pm'],
            'Client Engagement & Briefing' => ['project_officer', 'project_manager', 'po', 'pm'],
            'Project Material List' => ['project_officer', 'project_manager', 'po', 'pm'],
            'Budget & Quotation' => ['project_officer', 'project_manager', 'po', 'pm'],
            'Production' => ['project_manager', 'production', 'pm'],
            'Logistics' => ['project_manager', 'logistics', 'pm'],
            'Event Setup & Execution' => ['project_manager', 'setup', 'pm'],
            'Client Handover' => ['project_manager', 'project_officer', 'pm', 'po'],
            'Set Down & Return' => ['project_manager', 'logistics', 'pm'],
            'Archival & Reporting' => ['project_manager', 'project_officer', 'pm', 'po'],
        ];
        
        $allowedRoles = $phasePermissions[$phaseName] ?? ['project_manager', 'pm'];
        
        if (!$user->hasAnyRole($allowedRoles)) {
            abort(403, 'You do not have permission to upload documents to this phase.');
        }
        
        return true;
    }

    /**
     * Check if file type is allowed.
     */
    private function isAllowedFileType($file)
    {
        $allowedExtensions = [
            // Images
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp',
            // Documents
            'pdf', 'doc', 'docx', 'txt', 'rtf',
            // Spreadsheets
            'xls', 'xlsx', 'csv',
            // Presentations
            'ppt', 'pptx',
            // CAD/Design
            'dwg', 'dxf', 'ai', 'psd', 'sketch',
            // Archives
            'zip', 'rar', '7z',
            // Video (limited)
            'mp4', 'avi', 'mov',
            // Audio (limited)
            'mp3', 'wav',
        ];
        
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, $allowedExtensions);
    }
    /**
     * Ensure we're only handling documents in the second phase during enquiry: Design & Concept Development.
     */
    private function ensureDesignConceptPhase(ProjectPhase $phase)
    {
        if ($phase->name !== 'Design & Concept Development') {
            abort(404, 'Phase documents are only available in the Design & Concept Development phase during enquiry.');
        }
    }
}
