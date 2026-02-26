<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Attendance;

class TotalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Attendance::with(['member' => function($query) {
                $query->select('id', 'name');
            }])->get();

            return Datatables::of($data)->make(true);
        }

        return view('total.index');
    }
}