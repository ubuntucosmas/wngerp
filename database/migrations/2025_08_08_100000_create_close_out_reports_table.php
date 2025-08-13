<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('close_out_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            // Section 1: Project Information
            $table->string('project_title')->nullable();
            $table->string('client_name')->nullable();
            $table->string('project_code')->nullable();
            $table->string('project_officer')->nullable();
            $table->date('set_up_date')->nullable();
            $table->date('set_down_date')->nullable();
            $table->string('site_location')->nullable();

            // Section 2: Project Scope Summary
            $table->text('scope_summary')->nullable();

            // Section 3: Procurement & Inventory
            $table->text('materials_requested_notes')->nullable();
            $table->text('items_sourced_externally')->nullable();
            $table->text('store_issued_items')->nullable();
            $table->text('inventory_returns_balance')->nullable();
            $table->text('procurement_challenges')->nullable();

            // Section 4: Fabrication & Quality Control
            $table->date('production_start_date')->nullable();
            $table->text('packaging_labeling_status')->nullable();
            $table->text('qc_findings_resolutions')->nullable();
            $table->text('production_challenges')->nullable();

            // Section 5: On-Site Setup Summary
            $table->text('setup_dates')->nullable();
            $table->string('estimated_setup_time')->nullable();
            $table->string('actual_setup_time')->nullable();
            $table->text('team_composition')->nullable();
            $table->text('onsite_challenges')->nullable();
            $table->text('client_interactions')->nullable();
            $table->text('safety_issues')->nullable();

            // Section 6: Client Handover
            $table->date('handover_date')->nullable();
            $table->string('client_signoff_status')->nullable();
            $table->text('client_feedback_qr')->nullable();
            $table->text('post_handover_adjustments')->nullable();

            // Section 7: Set-Down & Debrief Summary
            $table->text('condition_of_items_returned')->nullable();
            $table->text('site_clearance_status')->nullable();
            $table->text('debrief_notes')->nullable();

            // Section 8: Attachments Checklist
            $table->boolean('att_deliverables_ppt')->default(false);
            $table->boolean('att_cutlist')->default(false);
            $table->boolean('att_site_survey')->default(false);
            $table->boolean('att_project_budget')->default(false);
            $table->boolean('att_mrf_or_material_list')->default(false);
            $table->boolean('att_qc_checklist')->default(false);
            $table->boolean('att_setup_setdown_checklists')->default(false);
            $table->boolean('att_client_feedback_form')->default(false);

            // Section 10: Final Approval
            $table->string('po_name_signature')->nullable();
            $table->date('po_signature_date')->nullable();
            $table->string('supervisor_reviewed_by')->nullable();
            $table->date('supervisor_review_date')->nullable();

            // Legacy/general fields kept for compatibility
            $table->text('project_client_details')->nullable();
            $table->text('budget_vs_actual_summary')->nullable();
            $table->text('issues_encountered')->nullable();
            $table->text('client_feedback_summary')->nullable();
            $table->text('po_recommendations')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('close_out_reports');
    }
};


