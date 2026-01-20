<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement; 

class HomeController extends Controller
{
    public function index()
    {
        // Cargar anuncios activos ordenados
        $announcements = Announcement::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('announcements')); 
    }
}