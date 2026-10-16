<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredMitraController extends Controller
{
    /**
     * Display the registration form for mitra.
     */
    public function create()
    {
        return view('auth.register-mitra');
    }

    /**
     * Handle mitra registration request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'company' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create user account (inactive until admin approves)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mitra',
            'active' => false, // Requires admin approval
        ]);

        // Create mitra profile
        Mitra::create([
            'user_id' => $user->id,
            'nama_perusahaan' => $validated['company'],
            'kontak_person' => $validated['name'],
            'no_telp' => $validated['phone'],
        ]);

        return redirect()->route('login')->with('status', 'Akun mitra berhasil dibuat. Tunggu persetujuan admin untuk mengaktifkan akun Anda.');
    }
}
