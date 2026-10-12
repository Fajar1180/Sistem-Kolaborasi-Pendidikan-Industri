<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard disesuaikan dengan peran pengguna (FR-02).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'user' => $user,
            'roleLabel' => match ($user->role) {
                'admin' => 'Admin',
                'dosen' => 'Dosen',
                'mitra' => 'Mitra Industri',
                default => 'Mahasiswa',
            },
        ]);
    }
}
