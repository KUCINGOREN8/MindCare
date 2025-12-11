<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Models\Mood;
use App\Models\Psychologist;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::inRandomOrder()->take(3)->get();
        return view('index', compact('testimonials'));
    }

    // === PATIENT DASHBOARD METHODS === //

    public function showPatientDashboard() {
        $user = Auth::user();

        $upcomingAppointments = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where(function($query) {
                $query->where('date', '>', now()->format('Y-m-d'))->orWhere(function($q) {
                        $q->where('date', '=', now()->format('Y-m-d'))->whereTime('end_time', '>', now()->format('H:i:s'));
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        return view('dashboard.patient.index', compact('user', 'upcomingAppointments'));
    }
    
    public function moodStore(Request $request)
    {
        $request->validate([
            'mood' => ['required', Rule::in(['sad', 'flat', 'good', 'happy', 'blissful'])],
        ]);
        
        $mood = Mood::create([
            'user_id' => auth()->id(),
            'mood' => $request->mood,
        ]);
        
        return back()->with('success', 'Mood berhasil disimpan!')
        ->with('undo_id', $mood->id);
    }
    
    public function undo(Request $request)
    {
        $mood = Mood::find($request->undo_id);

        if ($mood && $mood->user_id == auth()->id()) {
            $mood->delete();
            return back()->with('success', 'Mood berhasil dibatalkan.');
        }

        return back()->with('error', 'Mood tidak ditemukan atau tidak bisa dibatalkan.');
    }

    // === PSYCHOLOGIST DASHBOARD METHODS === //
    
    public function showPsychologistDashboard() {
        $user = Auth::user();
    
        $stats = $this->getPsychologistStats($user->psychologist);
        
        $upcomingAppointments = Appointment::with(['user'])
            ->where('psychologist_id', $user->id)
            ->where('status', 'confirmed')
            ->get()
            ->filter(function ($appointment) {
                return $appointment->is_upcoming;
            })
            ->sortBy('start_date_time')
            ->take(3);
    
        return view('dashboard.psychologist.index', compact('user', 'upcomingAppointments', 'stats'));
    }
    
    protected function getPsychologistStats($psychologist)
    {
        $now = Carbon::now();
        
        return [
            // Stat 1: Total Patients
            'total_patients' => $this->getPsychologistTotalPatients($psychologist),
            'new_patients_month' => $this->getNewPatientsThisMonth($psychologist),
            'total_patients_trend' => $this->calculatePatientsTrend($psychologist),

            // Stat 2: Sessions This Week
            'sessions_this_week' => $this->getPsychologistSessionsThisWeek($psychologist),
            'today_appointments' => $this->getPsychologistTodayAppointments($psychologist),
            'completed_sessions_week' => $this->getCompletedSessionsThisWeek($psychologist),
            'sessions_trend' => $this->calculateSessionsTrend($psychologist),

            // Stat 3: Monthly Revenue
            'monthly_revenue' => $this->getPsychologistMonthlyRevenue($psychologist),
            'revenue_trend' => $this->calculateRevenueTrend($psychologist),
        ];
    }
    
    protected function getPsychologistTotalPatients($psychologist)
    {
        return $psychologist->appointments()
            ->distinct('user_id')
            ->count('user_id');
    }
        
    protected function getNewPatientsThisMonth($psychologist)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        return $psychologist->appointments()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereNotIn('user_id', function($query) use ($psychologist, $startOfMonth) {
                $query->select('user_id')
                    ->from('appointments')
                    ->where('psychologist_id', $psychologist->id)
                    ->where('date', '<', $startOfMonth);
            })
            ->distinct('user_id')
            ->count('user_id');
    }

    protected function getPsychologistSessionsThisWeek($psychologist)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        return $psychologist->appointments()
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['scheduled', 'confirmed', 'completed'])
            ->count();
    }

    protected function getPsychologistTodayAppointments($psychologist)
    {
        return $psychologist->appointments()
            ->whereDate('date', Carbon::today())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->count();
    }
    
        protected function getCompletedSessionsThisWeek($psychologist)
        {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            
            return $psychologist->appointments()
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->where('status', 'completed')
                ->count();
        }
    
    protected function getPsychologistMonthlyRevenue($psychologist)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        return $psychologist->appointments()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereHas('payment', function($query) {
                $query->where('status', 'success');
            })
            ->with('payment')
            ->get()
            ->sum(function($appointment) {
                return $appointment->payment->amount ?? 0;
            });
    }

    protected function calculatePatientsTrend($psychologist)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentPatients = $psychologist->appointments()
            ->where('date', '>=', $currentMonth)
            ->distinct('user_id')
            ->count('user_id');
            
        $lastMonthPatients = $psychologist->appointments()
            ->whereBetween('date', [$lastMonth, $currentMonth->copy()->subDay()])
            ->distinct('user_id')
            ->count('user_id');
            
        return $this->calculatePercentageTrend($currentPatients, $lastMonthPatients);
    }
    
    protected function calculateRevenueTrend($psychologist)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentRevenue = $psychologist->appointments()
            ->where('date', '>=', $currentMonth)
            ->whereHas('payment', function($q) {
                $q->where('status', 'success');
            })
            ->with('payment')
            ->get()
            ->sum(fn($a) => $a->payment->amount ?? 0);
            
        $lastMonthRevenue = $psychologist->appointments()
            ->whereBetween('date', [$lastMonth, $currentMonth->copy()->subDay()])
            ->whereHas('payment', function($q) {
                $q->where('status', 'success');
            })
            ->with('payment')
            ->get()
            ->sum(fn($a) => $a->payment->amount ?? 0);
            
        return $this->calculatePercentageTrend($currentRevenue, $lastMonthRevenue);
    }
    
    protected function calculateSessionsTrend($psychologist)
    {
        $currentWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = $currentWeek->copy()->subDay();
        
        $currentSessions = $psychologist->appointments()
            ->where('date', '>=', $currentWeek)
            ->whereIn('status', ['scheduled', 'confirmed', 'completed'])
            ->count();
            
        $lastWeekSessions = $psychologist->appointments()
            ->whereBetween('date', [$lastWeek, $endOfLastWeek])
            ->whereIn('status', ['scheduled', 'confirmed', 'completed'])
            ->count();
            
        return $this->calculatePercentageTrend($currentSessions, $lastWeekSessions);
    }
    
    protected function calculatePercentageTrend($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        $change = (($current - $previous) / $previous) * 100;
        return round($change, 1);
    }
}
