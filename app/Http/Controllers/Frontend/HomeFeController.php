<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\medsos\medsos;
use Illuminate\Http\Request;

class HomeFeController extends Controller
{
    public function index()
    {
        $medsos = medsos::all();
        // dd($medsos);
        return view('frontend.home_fe', compact('medsos'));
    }
}
