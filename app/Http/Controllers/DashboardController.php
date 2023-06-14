<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = "Othman Salim";
        $title = "Store";


        return view('dashboard.index', [
            'user' => Auth::user()->name,
            'title' => $title,
        ]);
    }
}
