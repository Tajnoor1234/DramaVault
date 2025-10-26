<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the post-login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return route('admin.dashboard');
        }
        
        return '/';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if user is active (not banned)
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Please contact support.'
            ]);
        }

        // Update last login time and session info
        $user->update([
            'last_login_at' => now(),
            'last_active_at' => now(),
            'session_id' => session()->getId(),
            'last_ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Set session data
        session([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'logged_in_at' => now()->toDateTimeString(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Clear all session data
        $request->session()->flush();
        $request->session()->regenerate();

        Auth::logout();

        return redirect('/');
    }
    
    /**
     * Handle admin login - only allows admin users
     */
    public function adminLogin(Request $request)
    {
        $this->validateLogin($request);
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is active (not banned)
            if (!$user->is_active) {
                Auth::logout();
                return redirect('/')->with('error', 'Your account has been suspended. Please contact support.');
            }
            
            // Check if user is admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return redirect('/')->with('error', 'Access denied. Admin login only.');
            }
            
            $user->update([
                'last_login_at' => now(),
                'last_active_at' => now(),
                'session_id' => session()->getId(),
                'last_ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            session([
                'user_id' => $user->id,
                'user_role' => $user->role,
                'logged_in_at' => now()->toDateTimeString(),
            ]);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return redirect('/')->with('error', 'Invalid credentials.');
    }
}
