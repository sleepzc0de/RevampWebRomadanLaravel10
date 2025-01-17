<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Login\LoginModel;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $gambar = LoginModel::orderBy('id', 'DESC')->first();
        // dd($gambar);
        return view('auth.romadan_login', compact('gambar'));
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            Log::info('Login attempt', ['email' => $request->email]);

            $request->authenticate();

            $request->session()->regenerate();

            Log::info('Login successful', ['email' => $request->email]);

            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (ValidationException $e) {
            Log::error('Login validation failed', [
                'email' => $request->email,
                'errors' => $e->errors()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Login error', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
