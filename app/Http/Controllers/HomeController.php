<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement; 
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
{
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Si es miembro de algún grupo, mandarlo a su dashboard principal
        foreach ($user->roles as $role) {
            $grupoRoles = ['catequesis', 'juveniles', 'acutis', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor'];
            if (in_array($role->name, $grupoRoles)) {
                return redirect()->route('grupos.dashboard', $role->name);
            }
        }
    }

    $announcements = Announcement::where('is_active', true)->orderBy('order')->latest()->get();
    return view('home', compact('announcements'));
}

public function terminos()
    {
        return view('legal.terminos');
    }

    public function privacidad()
    {
        return view('legal.privacidad');
    }

}