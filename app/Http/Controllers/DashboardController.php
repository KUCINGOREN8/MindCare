<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function showDashboard() {
        $user = Auth::user();

        // $upcomingAppointments
        return view('pages.dashboard.index', compact('user'));
    }
}
