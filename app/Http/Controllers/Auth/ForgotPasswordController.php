<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form request reset password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Kirim link reset password via email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        // Pesan dibuat sama untuk email terdaftar maupun tidak, agar penyerang
        // tidak bisa memakai form ini untuk mengecek email mana yang terdaftar.
        return back()->with('status', 'Jika email tersebut terdaftar, link reset password telah dikirim.');
    }
}
