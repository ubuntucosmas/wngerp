<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\CloseOutReport;
use App\Models\CloseOutReportAttachment;
use App\Models\Project;
use App\Services\CloseOutReportGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CloseOutReportController extends Controller
{
    use AuthorizesRequests;
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        // Since we only have one report per project, redirect to show if exists, otherwise to create
        $report = $project->closeOutReports()->latest()->first();
        
        if ($report) {
            return redirect()->route('projects.close-out-report.show', [$project, $report]);
        }
        
        return redirect()->route('projects.close-out-report.create', $project);
    }

    public function showCreatePage(Project $project)
    {
        $this->authorize('edit', $project);
        return view('projects.close-out-report.create', compact('project'));
    }

    public function create(Project $project, CloseOutReportGeneratorService $generator)
    {
        $this->authorize('edit', $project);
        
        // Check if a report already exists for this project
        $existingReport = $project->closeOutReports()->where('status', 'draft')->first();
        if ($existingReport) {
            return redirect()->route('projects.close-out-report.edit', [$project, $existingReport])
                ->with('info', 'A draft report already exists for this project. You can edit it below.');
        }
        
        try {
            $report = $generator->generateFromProject($project);
            
            return redirect()->route('projects.close-out-report.edit', [$project, $report])
                ->with('success', 'Close-out report generated successfully from project data! Please review and update as needed.');
        } catch (\Exception $e) {
            return redirect()->route('projects.close-out-report.create', $project)
                ->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }



    public function show(Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        return view('projects.close-out-report.show', compact('project', 'report'));
    }

    public function edit(Project $project, CloseOutReport $report)
    {
        $this->authorize('edit', $project);
        // Load necessary relationships
        $project->load(['client', 'projectOfficer', 'materialLists', 'budgets', 'siteSurveys']);
        return view('projects.close-out-report.edit-page', compact('project', 'report'));
    }

    public function update(Request $request, Project $project, CloseOutReport $report)
    {
        $this->authorize('edit', $project);
        $validated = $request->validate([
            // Section 1
            'project_title' => 'nullable|string',
            'client_name' => 'nullable|string',
            'project_code' => 'nullable|string',
            'project_officer' => 'nullable|string',
            'set_up_date' => 'nullable|date',
            'set_down_date' => 'nullable|date',
            'site_location' => 'nullable|string',
            // Section 2
            'scope_summary' => 'nullable|string',
            // Section 3
            'materials_requested_notes' => 'nullable|string',
            'items_sourced_externally' => 'nullable|string',
            'store_issued_items' => 'nullable|string',
            'inventory_returns_balance' => 'nullable|string',
            'procurement_challenges' => 'nullable|string',
            // Section 4
            'production_start_date' => 'nullable|date',
            'packaging_labeling_status' => 'nullable|string',
            'qc_findings_resolutions' => 'nullable|string',
            'production_challenges' => 'nullable|string',
            // Section 5
            'setup_dates' => 'nullable|string',
            'estimated_setup_time' => 'nullable|string',
            'actual_setup_time' => 'nullable|string',
            'team_composition' => 'nullable|string',
            'onsite_challenges' => 'nullable|string',
            'client_interactions' => 'nullable|string',
            'safety_issues' => 'nullable|string',
            // Section 6
            'handover_date' => 'nullable|date',
            'client_signoff_status' => 'nullable|string',
            'client_feedback_qr' => 'nullable|string',
            'post_handover_adjustments' => 'nullable|string',
            // Section 7
            'condition_of_items_returned' => 'nullable|string',
            'site_clearance_status' => 'nullable|string',
            'debrief_notes' => 'nullable|string',
            // Section 8 (checkboxes)
            'att_deliverables_ppt' => 'nullable|boolean',
            'att_cutlist' => 'nullable|boolean',
            'att_site_survey' => 'nullable|boolean',
            'att_project_budget' => 'nullable|boolean',
            'att_mrf_or_material_list' => 'nullable|boolean',
            'att_qc_checklist' => 'nullable|boolean',
            'att_setup_setdown_checklists' => 'nullable|boolean',
            'att_client_feedback_form' => 'nullable|boolean',
            // Section 10
            'po_name_signature' => 'nullable|string',
            'po_signature_date' => 'nullable|date',
            'supervisor_reviewed_by' => 'nullable|string',
            'supervisor_review_date' => 'nullable|date',
            // Legacy fields (optional)
            'project_client_details' => 'nullable|string',
            'budget_vs_actual_summary' => 'nullable|string',
            'issues_encountered' => 'nullable|string',
            'client_feedback_summary' => 'nullable|string',
            'po_recommendations' => 'nullable|string',
            // Attachments
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        foreach (['att_deliverables_ppt','att_cutlist','att_site_survey','att_project_budget','att_mrf_or_material_list','att_qc_checklist','att_setup_setdown_checklists','att_client_feedback_form'] as $cb) {
            $validated[$cb] = (bool) $request->boolean($cb);
        }

        $report->update(array_merge($validated, [
            'status' => $request->input('action') === 'submit' ? 'submitted' : ($report->status ?? 'draft'),
        ]));

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('close-out-attachments');
                $report->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('projects.close-out-report.show', [$project, $report])->with('success', 'Close-out report updated');
    }

    public function destroy(Project $project, CloseOutReport $report)
    {
        $this->authorize('edit', $project);
        
        // Only allow deletion of draft reports for safety
        if ($report->status !== 'draft') {
            return redirect()->route('projects.close-out-report.show', [$project, $report])
                ->with('error', 'Only draft reports can be deleted. This report has status: ' . ucfirst($report->status));
        }
        
        try {
            // Store report details for success message before deletion
            $reportTitle = $report->project_title ?: $project->name;
            $reportCode = $report->project_code ?: $project->project_id;
            $attachmentCount = $report->attachments ? $report->attachments->count() : 0;
            
            // Delete the report (model's boot method handles attachments automatically)
            $report->delete();
            
            $message = "Close-out report for '{$reportTitle}' ({$reportCode}) has been deleted successfully.";
            if ($attachmentCount > 0) {
                $message .= " {$attachmentCount} attachment(s) were also removed.";
            }
            
            return redirect()->route('projects.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            \Log::error('Failed to delete close-out report: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'report_id' => $report->id,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('projects.close-out-report.show', [$project, $report])
                ->with('error', 'Failed to delete the report. Please try again or contact support.');
        }
    }

    public function download(Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        $pdf = Pdf::loadView('projects.templates.close-out-report', compact('project', 'report'));
        return $pdf->download('close-out-report-' . $project->project_id . '.pdf');
    }

    public function print(Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        $pdf = Pdf::loadView('projects.templates.close-out-report', compact('project', 'report'));
        return $pdf->stream('close-out-report-' . $project->project_id . '.pdf');
    }

    public function downloadAttachment(Project $project, CloseOutReport $report, CloseOutReportAttachment $attachment)
    {
        $this->authorize('view', $project);
        return Storage::download($attachment->path, $attachment->filename);
    }

    public function destroyAttachment(Project $project, CloseOutReport $report, CloseOutReportAttachment $attachment)
    {
        $this->authorize('edit', $project);
        
        // Delete the physical file if it exists
        if (Storage::exists($attachment->path)) {
            Storage::delete($attachment->path);
        }
        
        $attachment->delete();
        
        return redirect()->route('projects.close-out-report.edit', [$project, $report])
            ->with('success', 'Attachment deleted successfully.');
    }

    public function submit(Project $project, CloseOutReport $report)
    {
        $this->authorize('edit', $project);
        
        $report->update([
            'status' => 'submitted'
        ]);

        return redirect()->route('projects.close-out-report.show', [$project, $report])
            ->with('success', 'Report submitted for approval successfully.');
    }

    public function approve(Project $project, CloseOutReport $report)
    {
        $this->authorize('edit', $project);
        
        $report->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->route('projects.close-out-report.show', [$project, $report])
            ->with('success', 'Report approved successfully.');
    }

    public function reject(Request $request, Project $project, CloseOutReport $report)
    {
        $this->authorize('edit', $project);
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $report->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason']
        ]);

        return redirect()->route('projects.close-out-report.show', [$project, $report])
            ->with('success', 'Report rejected successfully.');
    }

    public function bulkDownload(Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        
        $zip = new \ZipArchive();
        $zipFileName = 'project-' . $project->project_id . '-close-out-files.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            // Add close-out report PDF
            $pdf = Pdf::loadView('projects.templates.close-out-report', compact('project', 'report'));
            $zip->addFromString('close-out-report.pdf', $pdf->output());
            
            // Add all attachments
            if ($report->attachments) {
                foreach ($report->attachments as $attachment) {
                    if (Storage::exists($attachment->path)) {
                        $zip->addFile(storage_path('app/' . $attachment->path), 'attachments/' . $attachment->filename);
                    }
                }
            }
            
            // Add project-related files (if they exist)
            $this->addProjectFilesToZip($zip, $project);
            
            $zip->close();
            
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }
        
        return redirect()->back()->with('error', 'Failed to create download archive.');
    }
    
    private function addProjectFilesToZip(\ZipArchive $zip, Project $project)
    {
        // Add material lists if they exist
        if ($project->materialLists && $project->materialLists->count() > 0) {
            foreach ($project->materialLists as $materialList) {
                // Generate material list PDF/Excel and add to zip
                // This would depend on your material list structure
            }
        }
        
        // Add site surveys if they exist
        if ($project->siteSurveys && $project->siteSurveys->count() > 0) {
            foreach ($project->siteSurveys as $survey) {
                // Generate site survey PDF and add to zip
                // This would depend on your site survey structure
            }
        }
        
        // Add budget files if they exist
        if ($project->budgets && $project->budgets->count() > 0) {
            foreach ($project->budgets as $budget) {
                // Generate budget PDF/Excel and add to zip
                // This would depend on your budget structure
            }
        }
        
        // Add QC checklists if they exist
        // Add setup checklists if they exist
        // Add deliverables if they exist
        
        // You can expand this method based on your actual project file structure
    }

    public function exportWord(Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        
        try {
            // Check if PhpWord is available
            if (!class_exists('\PhpOffice\PhpWord\PhpWord')) {
                return response()->json(['error' => 'Word export functionality is not available. Please install phpoffice/phpword package.'], 503);
            }
            
            // Create Word document using PhpWord
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            
            // Add title
            $section->addTitle('Project Close-Out Report', 1);
            $section->addText('Project: ' . $project->name . ' (' . $project->project_id . ')');
            $section->addTextBreak();
            
            // Add project information
            $section->addTitle('Project Information', 2);
            $table = $section->addTable();
            $table->addRow();
            $table->addCell(3000)->addText('Project Title:');
            $table->addCell(6000)->addText($report->project_title ?: 'Not specified');
            
            $table->addRow();
            $table->addCell(3000)->addText('Client Name:');
            $table->addCell(6000)->addText($report->client_name ?: 'Not specified');
            
            // Add more sections as needed...
            
            $fileName = 'close-out-report-' . $project->project_id . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');
            
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);
            
            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate Word document: ' . $e->getMessage()], 500);
        }
    }

    public function exportAllExcel(Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        
        // Create comprehensive Excel workbook with multiple sheets
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // Close-out report sheet
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Close-Out Report');
        $sheet1->setCellValue('A1', 'Woodnork Green - Project Close-Out Report');
        $sheet1->setCellValue('A3', 'Project Name:');
        $sheet1->setCellValue('B3', $project->name);
        // Add more data...
        
        // Material list sheet (if available)
        if ($project->materialLists && $project->materialLists->count() > 0) {
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('Material List');
            // Add material list data...
        }
        
        // Budget sheet (if available)
        if ($project->budgets && $project->budgets->count() > 0) {
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('Budget');
            // Add budget data...
        }
        
        $fileName = 'project-' . $project->project_id . '-complete-data.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function emailReport(Request $request, Project $project, CloseOutReport $report)
    {
        $this->authorize('view', $project);
        
        try {
            // Generate PDF
            $pdf = Pdf::loadView('projects.templates.close-out-report', compact('project', 'report'));
            
            // Email configuration
            $recipients = $request->input('recipients', ['projectsreports@woodnorkgreen.co.ke']);
            $subject = 'Project Close-Out Report - ' . $project->name . ' (' . $project->project_id . ')';
            
            // Send email with PDF attachment
            \Mail::send('emails.close-out-report', compact('project', 'report'), function ($message) use ($recipients, $subject, $pdf, $project) {
                $message->to($recipients)
                        ->subject($subject)
                        ->attachData($pdf->output(), 'close-out-report-' . $project->project_id . '.pdf');
            });
            
            return response()->json(['success' => true, 'message' => 'Report emailed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}


