<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'Tekpol') {
            return redirect()->route('tekpol.dashboard');
        }

        if ($user && $user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }
}
