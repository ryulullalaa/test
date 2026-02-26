<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Report;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Affiliation;
use DB;

class WorshipController extends Controller
{
    public function index(Request $request)
    {
        $member = User::findOrFail(Auth::user()->id)->member;
        $permit_member = [4, 6, 7, 8, 10, 11];

        if (in_array($member->grade_id, $permit_member)) {
            if ($member->grade_id == 4 || $member->grade_id == 6 || $member->grade_id == 7) {
                $affiliation_id = [1, 2, 3, 4];
            } else if ($member->grade_id == 8) {
                $affi = Affiliation::find($member->affiliation->id);
                $current_team = Affiliation::where([
                    ['parish', $affi->parish],
                    ['team', '!=', 0],
                ])->get();

                $affiliation_id = [];
                foreach ($current_team as $key => $value) {
                    if(!in_array($value, $affiliation_id, true)){
                        array_push($affiliation_id, $value->id);
                    }
                }
            } else if ($member->grade_id == 10) {
                $affi = Affiliation::find($member->affiliation->id);
                $current_team = Affiliation::where([
                    ['parish', $affi->parish],
                    ['team', $affi->team],
                ])->get();

                $affiliation_id = [];
                foreach ($current_team as $key => $value) {
                    if(!in_array($value, $affiliation_id, true)){
                        array_push($affiliation_id, $value->id);
                    }
                }
            }
            else if ($member->grade_id == 11) $affiliation_id = [$member->affiliation->id];

            if ($request->ajax()) {
                $data = Report::whereIn('affiliation_id', $affiliation_id)->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($column) use ($member, $affiliation_id) {
                        $date = date_create($column->created_at);

                        if ($member->grade_id == 4 || $member->grade_id == 6 || $member->grade_id == 7) {
                            $affi = Affiliation::find($column->affiliation_id);
                            switch ($affi->parish) {
                                case null: $affiliation = '[실행위원] '; break;
                                default: $affiliation = '['.$affi->parish.'교구 임원단] '; break;
                            }
                        } else if (in_array($member->grade_id, [8, 10, 11])) {
                            $affi = Affiliation::find($column->affiliation_id);
                            $affiliation = '['.$affi->parish.'교구 '.$affi->team.'팀 '.$affi->group.'그룹] ';
                        }

                        return $affiliation . date_format($date, 'Y-m-d')
                            .'<a href="'.route('worship.attendance', $column->id).'"><button type="button" class="btn btn-outline-dark btn-sm ml-2">출석</button></a>'
                            .'<a href="'.route('worship.spirituality', $column->id).'"><button type="button" class="btn btn-outline-dark btn-sm ml-2">영성</button></a>';
                    })
                    ->rawColumns(['created_at'])
                    ->make(true);
            }
        }

        return view('worship.index');
    }

    public function showAttendance(Request $request, $report_id)
    {
        if ($request->ajax()) {
            $data = Attendance::with(['member' => function($query) {
                $query->select('id', 'name', 'active');
            }])
            ->select(
                'id',
                'report_id',
                'member_id',
                'attendance',
                'reason',
                'visit_way',
                'visit_result',
                'reason_detail',
            )
            ->where('report_id', $report_id)
            ->get()
            ->where('member.active', 1);

            return Datatables::of($data)->make(true);
        }

        $created_at = Report::find($report_id)->created_at;
        $report_title = date_format($created_at, 'Y년 m월 d일');

        return view('worship.attendance', [
            'report_id' => $report_id,
            'report_title' => $report_title,
        ]);
    }

    public function showSpirituality(Request $request, $report_id)
    {
        if ($request->ajax()) {
            $data = Attendance::with(['member' => function($query) {
                $query->select('id', 'name', 'active');
            }])
            ->select(
                'id',
                'report_id',
                'member_id',
                'worship_am',
                'worship_pm',
                'worship_wed',
                'worship_sat',
                'worship_dawn',
                'read_bible',
                'training',
                'situation',
            )
            ->where('report_id', $report_id)
            ->get()
            ->where('member.active', 1);

            return Datatables::of($data)->make(true);
        }

        $created_at = Report::find($report_id)->created_at;
        $report_title = date_format($created_at, 'Y년 m월 d일');

        return view('worship.spirituality', [
            'report_id' => $report_id,
            'report_title' => $report_title,
        ]);
    }

    public function save(Request $request)
    {
        date_default_timezone_set('Asia/Seoul');
        $data = $request->input('result');

        DB::transaction(function () use ($data) {
            if (isset($data[0]['attendance'])) {
                foreach ($data as $key => $value) {
                    $attendance = Attendance::find($value['id']);
                    $attendance->attendance = $value['attendance'];
                    $attendance->reason = $value['reason'];
                    $attendance->visit_way = $value['visit_way'];
                    $attendance->visit_result = $value['visit_result'];
                    $attendance->reason_detail = $value['reason_detail'];
                    $attendance->updated_at = time();
                    $attendance->save();
                }
            } else if (isset($data[0]['worship_am'])) {
                foreach ($data as $key => $value) {
                $attendance = Attendance::find($value['id']);
                    $attendance->worship_am = $value['worship_am'];
                    $attendance->worship_pm = $value['worship_pm'];
                    $attendance->worship_wed = $value['worship_wed'];
                    $attendance->worship_sat = $value['worship_sat'];
                    $attendance->worship_dawn = $value['worship_dawn'];
                    $attendance->read_bible = $value['read_bible'];
                    $attendance->training = $value['training'];
                    $attendance->situation = $value['situation'];
                    $attendance->updated_at = time();
                    $attendance->save();
                }
            }
        });
    }
}