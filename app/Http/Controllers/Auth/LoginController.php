<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function redirectTo()
    {
        $user = Auth::user();

        if ($user->hasRole('manager')) {
            return route('manager.dashboard');
        } elseif ($user->hasRole('cashier')) {
            return route('cashier.dashboard');
        } elseif ($user->hasRole('entry_clerk')) {
            return route('entryClerk.dashboard');
        }

        return '/dashboard'; // fallback
    }
}