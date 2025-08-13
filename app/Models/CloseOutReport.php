<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CloseOutReport extends Model
{
    use HasFactory;
    
    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();
        
        // When deleting a report, also delete associated attachments and files
        static::deleting(function ($report) {
            if ($report->attachments && $report->attachments->count() > 0) {
                foreach ($report->attachments as $attachment) {
                    // Delete the physical file if it exists
                    if (Storage::exists($attachment->path)) {
                        Storage::delete($attachment->path);
                    }
                    // Delete the attachment record
                    $attachment->delete();
                }
            }
        });
    }

    protected $fillable = [
        'project_id',
        // Section 1
        'project_title','client_name','project_code','project_officer','set_up_date','set_down_date','site_location',
        // Section 2
        'scope_summary',
        // Section 3
        'materials_requested_notes','items_sourced_externally','store_issued_items','inventory_returns_balance','procurement_challenges',
        // Section 4
        'production_start_date','packaging_labeling_status','qc_findings_resolutions','production_challenges',
        // Section 5
        'setup_dates','estimated_setup_time','actual_setup_time','team_composition','onsite_challenges','client_interactions','safety_issues',
        // Section 6
        'handover_date','client_signoff_status','client_feedback_qr','post_handover_adjustments',
        // Section 7
        'condition_of_items_returned','site_clearance_status','debrief_notes',
        // Section 8
        'att_deliverables_ppt','att_cutlist','att_site_survey','att_project_budget','att_mrf_or_material_list','att_qc_checklist','att_setup_setdown_checklists','att_client_feedback_form',
        // Section 10
        'po_name_signature','po_signature_date','supervisor_reviewed_by','supervisor_review_date',
        // Legacy/general
        'project_client_details','budget_vs_actual_summary','issues_encountered','client_feedback_summary','po_recommendations',
        'status',
        // User tracking
        'created_by','approved_by','rejected_by','approved_at','rejected_at','rejection_reason',
    ];

    protected $casts = [
        'set_up_date' => 'date',
        'set_down_date' => 'date',
        'production_start_date' => 'date',
        'handover_date' => 'date',
        'po_signature_date' => 'date',
        'supervisor_review_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'att_deliverables_ppt' => 'boolean',
        'att_cutlist' => 'boolean',
        'att_site_survey' => 'boolean',
        'att_project_budget' => 'boolean',
        'att_mrf_or_material_list' => 'boolean',
        'att_qc_checklist' => 'boolean',
        'att_setup_setdown_checklists' => 'boolean',
        'att_client_feedback_form' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->hasMany(CloseOutReportAttachment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}


