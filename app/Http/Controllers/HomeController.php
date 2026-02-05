<?php

namespace App\Http\Controllers;
use App\Models\Work;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    

    public function index()
    {
        $works = Work::with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return view('user.home', compact('works'));
    }
}
