<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use App\Models\User;
use App\Models\Member;
use Hash;
use Log;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function changePassword()
    {
        return view('change-password');
    }

    public function savePassword(Request $request)
    {
        $data = $request->input('result');

        DB::transaction(function () use ($data) {
            $member = Auth::user();
            $member->password = Hash::make($data['new_password']);
            $member->save();
        });
    }

    public function resetPassword()
    {
        return view('reset-password');
    }

    public function reset(Request $request)
    {
        $email = $request->input('result');

        // $member = Auth::user()->where('email', $email)->first();
        // Log::debug($member);

        DB::transaction(function () use ($email) {
            $member = Auth::user()->where('email', $email)->first();
            $member->password = Hash::make('000000');
            $member->save();
        });
    }
}
