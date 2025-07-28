<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Project;

class CheckProjectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Super admins and PMs can access all projects
        if ($user->hasAnyRole(['super-admin', 'pm'])) {
            return $next($request);
        }
        
        // For POs, check if they are assigned to this project
        if ($user->hasRole('po')) {
            $projectId = $request->route('project');
            
            if ($projectId) {
                // If project is passed as model binding
                if ($projectId instanceof Project) {
                    $project = $projectId;
                } else {
                    // If project is passed as ID
                    $project = Project::find($projectId);
                }
                
                if (!$project) {
                    abort(404, 'Project not found');
                }
                
                // Check if the PO is assigned to this project
                if ($project->project_officer_id !== $user->id) {
                    abort(403, 'You do not have permission to access this project. You can only access projects assigned to you.');
                }
            }
        }
        
        return $next($request);
    }
}