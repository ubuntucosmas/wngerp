<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\MaterialList;
use App\Models\Quote;
use App\Policies\ProjectPolicy;
use App\Policies\ProjectPhasePolicy;
use App\Policies\MaterialListPolicy;
use App\Policies\QuotePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        ProjectPhase::class => ProjectPhasePolicy::class,
        MaterialList::class => MaterialListPolicy::class,
        Quote::class => QuotePolicy::class,
        \App\Models\Enquiry::class => \App\Policies\EnquiryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('access-project-files', function ($user, $project) {
            return app(ProjectPolicy::class)->accessFiles($user, $project);
        });

        Gate::define('manage-project-phases', function ($user, $project) {
            return app(ProjectPolicy::class)->managePhases($user, $project);
        });

        Gate::define('manage-project-budget', function ($user, $project) {
            return app(ProjectPolicy::class)->manageBudget($user, $project);
        });

        Gate::define('assign-project-officer', function ($user, $project) {
            return app(ProjectPolicy::class)->assignOfficer($user, $project);
        });

        // Define enquiry-specific gates
        Gate::define('access-enquiry-files', function ($user, $enquiry) {
            return app(\App\Policies\EnquiryPolicy::class)->accessFiles($user, $enquiry);
        });

        Gate::define('manage-enquiry-phases', function ($user, $enquiry) {
            return app(\App\Policies\EnquiryPolicy::class)->managePhases($user, $enquiry);
        });

        Gate::define('convert-enquiry', function ($user, $enquiry) {
            return app(\App\Policies\EnquiryPolicy::class)->convert($user, $enquiry);
        });
    }
}
