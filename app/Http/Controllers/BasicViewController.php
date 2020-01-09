<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicViewController extends Controller
{
    public function home() {
        return view('home');
    }
}
