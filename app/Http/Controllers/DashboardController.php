<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Work;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role?->name;

        $data = [];

        if ($role === 'admin') {
            $data = [
                'totalWorks'   => Work::count(),
                'pendingWorks' => Work::where('status', 'pending')->count(),
                'approvedWorks'=> Work::where('status', 'approved')->count(),
            ];
        }

        if ($role === 'uploader') {
            $data = [
                'myWorks' => $user->works()->latest()->get(),
            ];
        }

        return view('dashboard', compact('role', 'data'));
    }
}
