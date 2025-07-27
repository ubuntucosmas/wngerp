<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'expected_delivery_date',
        'client_name',
        'project_name',
        'project_deliverables',
        'contact_person',
        'status',
        'assigned_po',
        'follow_up_notes',
        'project_id',
        'enquiry_number',
        'converted_to_project_id',
        'venue',
        'site_survey_skipped',
        'site_survey_skip_reason',
    ];

   protected $casts = [
        'date_received' => 'date:Y-m-d',
        'expected_delivery_date' => 'date:Y-m-d',
        'site_survey_skipped' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enquiry) {
            // Get the current year and month
            $year = date('y');
            $month = date('m');
            
            // Get the last enquiry number for this month
            $lastEnquiry = static::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->orderBy('enquiry_number', 'desc');
                
            
            // Set the new enquiry number
          // $enquiry->enquiry_number = $lastEnquiry ? $lastEnquiry->enquiry_number + 1 : 1;
        });
    }

    public function getFormattedIdAttribute()
    {
        $year = $this->created_at->format('y');
        $month = $this->created_at->format('m');
        $number = str_pad($this->enquiry_number, 3, '0', STR_PAD_LEFT);
        
        return "WNG/IQ/{$year}/{$month}/{$number}";
    }

    public function getDateReceivedAttribute($value)
    {
        return $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function getExpectedDeliveryDateAttribute($value)
    {
        return $value ? date('Y-m-d', strtotime($value)) : null;
    }
    public function project()
    {
    return $this->belongsTo(Project::class, 'converted_to_project_id');
}

    /**
     * Get all of the phases for the enquiry.
     */
    public function phases()
    {
        return $this->morphMany(ProjectPhase::class, 'phaseable');
    }

    /**
     * Create phases for enquiry when it's created
     */
    protected static function booted()
    {
        static::created(function ($enquiry) {
            // Only create the first 4 phases initially
            $phases = config('project_process_phases');
            $firstFourPhases = array_slice($phases, 0, 4);
            
            foreach ($firstFourPhases as $phase) {
                $enquiry->phases()->create([
                    'name' => $phase['name'],
                    'title' => $phase['name'],
                    'icon' => $phase['icon'] ?? null,
                    'summary' => $phase['summary'] ?? null,
                    'description' => $phase['summary'] ?? null,
                    'status' => $phase['status'],
                    'start_date' => null,
                    'end_date' => null,
                ]);
            }
        });
    }

    /**
     * Get phases that should be displayed based on enquiry state
     */
    public function getDisplayablePhases()
    {
        $budgetPhase = $this->phases()->where('name', 'Budget & Quotation')->first();
        
        if ($budgetPhase && $budgetPhase->status === 'Completed') {
            // Show all phases if Budget & Quotation is completed
            return $this->phases()->orderBy('id')->get();
        } else {
            // Show only first 4 phases
            return $this->phases()->orderBy('id')->take(4)->get();
        }
    }

    /**
     * Check if all first 4 phases are completed
     */
    public function areFirstFourPhasesCompleted()
    {
        $firstFourPhases = $this->phases()->orderBy('id')->take(4)->get();
        
        if ($firstFourPhases->count() < 4) {
            return false;
        }
        
        return $firstFourPhases->every(function ($phase) {
            return strtolower($phase->status) === 'completed' || $phase->skipped;
        });
    }

    /**
     * Convert enquiry to project when all 4 phases are completed
     */
    public function convertToProject()
    {
        if (!$this->areFirstFourPhasesCompleted()) {
            return false;
        }

        // Find the client
        $client = \App\Models\Client::where('FullName', $this->client_name)->first();

        if (!$client) {
            return false;
        }

        // Generate Project ID
        $month = now()->format('m');
        $year = now()->format('y');
        $prefix = 'WNG' . $month . $year;

        $lastProject = \App\Models\Project::where('project_id', 'like', $prefix . '%')->latest('created_at')->first();
        $lastNumber = 0;

        if ($lastProject && preg_match('/WNG\d{4}(\d+)/', $lastProject->project_id, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $projectId = $prefix . $newNumber;

        // Find the project officer by name if assigned_po is set
        $projectOfficerId = null;
        if (!empty($this->assigned_po)) {
            $projectOfficer = \App\Models\User::where('name', $this->assigned_po)->first();
            if ($projectOfficer) {
                $projectOfficerId = $projectOfficer->id;
            }
        }

        // Create the project
        $project = \App\Models\Project::create([
            'project_id' => $projectId,
            'name' => $this->project_name ?? 'Project from Enquiry ' . $this->id,
            'client_id' => $client->ClientID,
            'client_name' => $client->FullName,
            'venue' => $this->venue ?? 'TBD',
            'start_date' => $this->expected_delivery_date ?? now(),
            'end_date' => $this->expected_delivery_date ?? now()->addDays(2),
            'project_manager_id' => auth()->id(),
            'project_officer_id' => $projectOfficerId,
            'deliverables' => $this->project_deliverables,
            'follow_up_notes' => $this->follow_up_notes,
            'contact_person' => $this->contact_person,
            'status' => $this->status ?? 'Initiated',
        ]);

        // Transfer existing phases from enquiry to project
        $this->phases()->update([
            'phaseable_id' => $project->id,
            'phaseable_type' => Project::class,
        ]);

        // Transfer material lists from enquiry to project
        $this->materialLists()->update(['project_id' => $project->id]);

        // Transfer quotes from enquiry to project
        $this->quotes()->update(['project_id' => $project->id]);

        // Transfer budgets from enquiry to project
        $this->budgets()->update(['project_id' => $project->id]);

        // Transfer design assets from enquiry to project
        $this->designAssets()->update(['project_id' => $project->id]);

        // Transfer site surveys from enquiry to project
        $this->siteSurveys()->update(['project_id' => $project->id]);

        // Transfer enquiry log from enquiry to project
        $enquiryLog = $this->enquiryLog;
        if ($enquiryLog) {
            $enquiryLog->update(['project_id' => $project->id]);
        }

        // Add remaining phases (from Production onwards) to the project
        $project->createRemainingPhases();

        // Update the enquiry
        $this->converted_to_project_id = $project->id;
        $this->save();

        return $project;
    }

    /**
     * Get material lists for this enquiry
     */
    public function materialLists()
    {
        return $this->hasMany(\App\Models\MaterialList::class, 'enquiry_id');
    }

    public function designAssets()
    {
        return $this->hasMany(\App\Models\DesignAsset::class, 'enquiry_id');
    }

    public function budgets()
    {
        return $this->hasMany(\App\Models\ProjectBudget::class, 'enquiry_id');
    }

    public function quotes()
    {
        return $this->hasMany(\App\Models\Quote::class, 'enquiry_id');
    }

    public function enquiryLog()
    {
        return $this->hasOne(\App\Models\EnquiryLog::class, 'enquiry_id');
    }

    public function siteSurveys()
    {
        return $this->hasMany(\App\Models\SiteSurvey::class, 'enquiry_id');
    }
}
