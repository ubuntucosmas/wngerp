<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function showQuotation(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        return view('projects.files.quotation', compact('project'));
    }

    public function uploadQuotation(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120', // Increased max size and added more mimes
        ]);

        if ($request->file('file')->isValid()) {
            // Store the file in a project-specific directory
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs(
                'projects/' . $project->id . '/quotations',
                $fileName,
                'public'
            );

            // Here you would typically save the $path to your database, 
            // associating it with the project's quotation record.
            // For example, if Project model has a 'quotation_path' attribute:
            // $project->quotation_path = $path;
            // $project->save();

            // Save the path to the project model
            $project->quotation_path = $path;
            $project->save();


            return redirect()->route('projects.quotation.index', $project)
                             ->with('success', 'Quotation uploaded successfully.')
                             ->with('file_path', $path); // Keep file_path for immediate feedback if needed
        }

        return back()->with('error', 'File upload failed. Please try again.');
    }

    public function showSiteSurvey(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        return view('projects.files.site-survey', compact('project'));
    }

    public function uploadSiteSurvey(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120', // Increased max size and added more mimes
        ]);

        if ($request->file('file')->isValid()) {
            // Store the file in a project-specific directory
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs(
                'survey/' . $project->id . '/site-survey',
                $fileName,
                'public'
            );

            // Here you would typically save the $path to your database, 
            // associating it with the project's quotation record.
            // For example, if Project model has a 'quotation_path' attribute:
            // $project->quotation_path = $path;
            // $project->save();

            // Save the path to the project model
            $project->site_survey_path = $path;
            $project->save();


            return redirect()->route('projects.files.site-survey', $project)
                             ->with('success', 'Site Survey uploaded successfully.')
                             ->with('file_path', $path); // Keep file_path for immediate feedback if needed
        }

        return back()->with('error', 'File upload failed. Please try again.');
    }

}


