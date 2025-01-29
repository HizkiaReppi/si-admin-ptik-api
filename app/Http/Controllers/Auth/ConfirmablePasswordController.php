<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends Controller
{
    /**
     * Confirm the user's password.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::guard('web')->validate($credentials)) {
            throw ValidationException::withMessages([
                'password' => ['Kata sandi yang dimasukkan salah.'],
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return response()->json([
            'status' => 'success',
            'message' => 'Kata sandi telah dikonfirmasi.',
        ]);
    }
}
