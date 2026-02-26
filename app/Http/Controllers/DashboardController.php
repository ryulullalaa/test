<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}