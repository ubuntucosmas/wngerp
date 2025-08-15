<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PhaseDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_phase_id',
        'project_id',
        'phase_name',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_size',
        'mime_type',
        'file_extension',
        'uploaded_by',
        'description',
        'document_type',
        'is_active',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project phase that owns the document.
     */
    public function projectPhase()
    {
        return $this->belongsTo(ProjectPhase::class);
    }

    /**
     * Get the project that owns the document.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who uploaded the document.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file size in human readable format.
     */
    
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the document type based on file extension.
     */
    public function getDocumentTypeAttribute()
    {
        $extension = strtolower($this->file_extension);
        
        $types = [
            // Images
            'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image', 'bmp' => 'image', 'svg' => 'image', 'webp' => 'image',
            // Documents
            'pdf' => 'pdf', 'doc' => 'document', 'docx' => 'document', 'txt' => 'document', 'rtf' => 'document',
            // Spreadsheets
            'xls' => 'spreadsheet', 'xlsx' => 'spreadsheet', 'csv' => 'spreadsheet',
            // Presentations
            'ppt' => 'presentation', 'pptx' => 'presentation',
            // CAD/Design
            'dwg' => 'cad', 'dxf' => 'cad', 'ai' => 'design', 'psd' => 'design', 'sketch' => 'design',
            // Archives
            'zip' => 'archive', 'rar' => 'archive', '7z' => 'archive', 'tar' => 'archive',
            // Video
            'mp4' => 'video', 'avi' => 'video', 'mov' => 'video', 'wmv' => 'video', 'flv' => 'video',
            // Audio
            'mp3' => 'audio', 'wav' => 'audio', 'flac' => 'audio', 'aac' => 'audio',
        ];
        
        return $types[$extension] ?? 'other';
    }

    /**
     * Get the icon class for the document type.
     */
    public function getIconClassAttribute()
    {
        $icons = [
            'image' => 'fas fa-image text-success',
            'pdf' => 'fas fa-file-pdf text-danger',
            'document' => 'fas fa-file-word text-primary',
            'spreadsheet' => 'fas fa-file-excel text-success',
            'presentation' => 'fas fa-file-powerpoint text-warning',
            'cad' => 'fas fa-drafting-compass text-info',
            'design' => 'fas fa-palette text-purple',
            'archive' => 'fas fa-file-archive text-secondary',
            'video' => 'fas fa-file-video text-info',
            'audio' => 'fas fa-file-audio text-warning',
            'other' => 'fas fa-file text-muted',
        ];
        
        return $icons[$this->document_type] ?? 'fas fa-file text-muted';
    }

    /**
     * Check if the file exists in storage.
     */
    public function fileExists()
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Get the full URL to the file.
     */
    public function getFileUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Delete the file from storage when the model is deleted.
     */
    protected static function booted()
    {
        static::deleting(function ($document) {
            if ($document->fileExists()) {
                Storage::disk('public')->delete($document->file_path);
            }
        });
    }

    /**
     * Scope to get documents for a specific phase.
     */
    public function scopeForPhase($query, $phaseName)
    {
        return $query->where('phase_name', $phaseName);
    }

    /**
     * Scope to get active documents.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}