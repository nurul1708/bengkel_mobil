<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // 1. Ambil data mekanik
        $team = User::where('role', 'mekanik')->get();

        return view('team.index', [
            'title' => 'Team',
            'team' => $team
        ]);
    }
}
