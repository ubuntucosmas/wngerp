<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    /**
     * Show the logistics dashboard for the project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function index(Project $project)
    {
        $project->load('client');
        
        return view('projects.files.logistics', [
            'project' => $project,
            'activeTab' => 'logistics'
        ]);
    }

    /**
     * Show the loading sheet for the project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function showLoadingSheet(Project $project)
    {
        $project->load('client');
        
        return view('projects.logistics.loading-sheet', [
            'project' => $project,
            'activeTab' => 'loading-sheet'
        ]);
    }

    /**
     * Show the booking sheet for the project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function showBookingSheet(Project $project)
    {
        $project->load('client');
        
        return view('projects.logistics.booking-sheet', [
            'project' => $project,
            'activeTab' => 'booking-sheet'
        ]);
    }

    // Other methods can be added here later when needed
    // - create()
    // - store()
    // - show()
    // - edit()
    // - update()
    // - destroy()
}
