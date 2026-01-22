<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Simpel login redirect untuk demo, asumsikan user 1 adalah admin
    public function login()
    {
        // Dalam production, ini harus return view('auth.login');
        // Untuk demo cepat ini, kita auto-login user pertama (admin)
        if (!Auth::check()) {
            Auth::loginUsingId(1);
        }
        return redirect()->route('dashboard');
    }
}