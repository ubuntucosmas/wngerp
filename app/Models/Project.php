<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enquiry;
use App\Models\DesignAsset;
use App\Models\SiteSurvey;
use App\Models\BookingOrder;
use App\Models\EnquiryLog;
use App\Models\Client;
use App\Models\User;
use App\Models\Quote;
use App\Models\Phase;
use App\Models\SetDownReturn;
use App\Models\ArchivalReport;
use App\Models\HandoverReport;
use App\Models\SetupReport;
use App\Models\LogisticsReport;
use App\Models\ProjectPhase;
use App\Models\ProjectBudget;


class Project extends Model
{
    use HasFactory;

    /**
     * Get the enquiry associated with the project.
     */
    public function enquiry()
    {
        return $this->hasOne(Enquiry::class, 'converted_to_project_id');
    }

    // Specify the table (optional if the table name matches the plural of the model name)
    protected $table = 'projects';

    // Allow mass assignment for these fields
    protected $fillable = [
        'project_id',
        'name',
        'client_name',
        'client_id',
        'venue',
        'start_date',
        'end_date',
        'project_manager_id',
        'project_officer_id',
        'status',
        'deliverables',
        'follow_up_notes',
        'contact_person',
    ];

    public function siteSurveys()
    {
        return $this->hasMany(SiteSurvey::class);
    }
    public function loadingSheets()
    {
        return $this->hasMany(LoadingSheet::class);
    }
    public function bookingOrder()
    {
        return $this->hasOne(BookingOrder::class);
    }
    public function bookingOrders()
    {
        return $this->hasMany(BookingOrder::class);
    }
    public function enquiryLog()
    {
        return $this->hasOne(EnquiryLog::class);
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function enquirySource()
    {
        return $this->hasOne(Enquiry::class, 'converted_to_project_id');
    }

    public function materialLists()
    {
        return $this->hasMany(MaterialList::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'ClientID');
    }

    // Relationships
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function projectOfficer()
    {
        return $this->belongsTo(User::class, 'project_officer_id');
    }
    
    /**
     * Get all design assets for the project.
     */
    public function designAssets()
    {
        return $this->hasMany(DesignAsset::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function budgets()
    {
        return $this->hasMany(ProjectBudget::class);
    }

    /**
     * Get all of the phases for the project.
     */
    public function phases()
    {
        return $this->morphMany(ProjectPhase::class, 'phaseable');
    }


public function getProgressAttribute()
{
    $total = $this->phases->count();
    $complete = $this->phases->filter(function ($phase) {
        return strtolower($phase->status) === 'complete';
    })->count();

    $progress = $total > 0 ? round(($complete / $total) * 100) : 0;

    return $progress;
}


public function updateStatusIfComplete()
{
    // Recalculate progress
    $progress = $this->progress;

    if ($progress === 100 && $this->status !== 'closed') {
        $this->status = 'closed';
        $this->save();
    } elseif ($progress < 100 && $this->status === 'closed') {
        // Optional: reopen if new phases added or progress reduced
        $this->status = 'active';
        $this->save();
    }
}




public function setupReports()
{
    return $this->hasMany(SetupReport::class);
}

public function production()
{
    return $this->hasOne(Production::class);
}


/**
 * Get all of the handover reports for the project.
 */
public function handoverReports()
{
    return $this->hasMany(HandoverReport::class);
}

/**
 * Get all of the set down return reports for the project.
 */
public function setDownReturns()
{
    return $this->hasMany(SetDownReturn::class);
}

/**
 * Get all of the archival reports for the project.
 */
public function archivalReports()
{
    return $this->hasMany(ArchivalReport::class);
}

protected static function booted()
{
    // Phases are now created for enquiries and transferred to projects when conversion happens
    // No automatic phase creation for projects
}

/**
 * Create remaining phases when the 4th phase (Budget & Quotation) is completed
 */
/**
 * Create remaining phases when the 4th phase (Budget & Quotation) is completed
 */
public function createRemainingPhases()
{
    // Get all phases from config
    $allPhases = config('project_process_phases');
    $remainingPhases = array_slice($allPhases, 4); // Skip first 4 phases
    
    foreach ($remainingPhases as $phase) {
        // Check if phase already exists
        $existingPhase = $this->phases()->where('name', $phase['name'])->first();
        
        if (!$existingPhase) {
            $this->phases()->create([
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
    }
    
    return true;
}

/**
 * Get phases that should be displayed for a project.
 * For projects, all phases are always displayed.
 */
public function getDisplayablePhases()
{
    return $this->phases()->orderBy('id')->get();
}

}