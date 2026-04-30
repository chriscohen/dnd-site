<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerate the session ID to prevent session fixation attacks.
            $request->session()->regenerate();

            // Return HTTP 204 no content.
            return response()->noContent();
        }

        // Avoid specifying an exact error message to prevent information leakage.
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.']
        ]);
    }

    public function logout(Request $request)
    {
        // Invalidate the user's session.
        Auth::guard('web')->logout();

        // Invalidate the PHP session.
        $request->session()->invalidate();

        // Regenerate the session token to prevent session hijacking.
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
