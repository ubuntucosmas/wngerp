<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * Handle authenticated sessions.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // Authenticate the user
        $request->authenticate();
        
        // Regenerate the session to avoid session fixation attacks
        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        // Redirect based on user's role
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return redirect()->route('admin.dashboard');
        } 
        elseif ($user->hasRole('hr')) {
            return redirect()->route('hr.dashboard');
        } 
        elseif ($user->hasRole('store') || $user->hasRole('storeadmin') || $user->hasRole('logistics') || $user->hasRole('procurement')) {
            return redirect()->route('inventory.dashboard');
        } 
        elseif ($user->hasRole('pm') || $user->hasRole('po')) {
            return redirect()->route('projects.index');
        } 



        // Default redirection (for other roles like customer, etc.)
        return redirect()->route('login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Invalidate the session to protect against session fixation
        $request->session()->invalidate();

        // Regenerate the CSRF token to protect against CSRF attacks
        $request->session()->regenerateToken();

        return redirect('/');
    }
}