<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use App\Models\Member;
use App\Models\Affiliation;
use App\Models\Grade;
use DB;
use Auth;
use Hash;

class CongregationController extends Controller
{
    public function index(Request $request)
    {
        $auth = Member::find(Auth::user()->member_id);

        if ($request->ajax()) {
            $grades = Grade::all();

            if (in_array($auth->grade_id, [1, 2, 3, 4, 5, 6, 7, 8])) {
                $data = Member::with('affiliation')
                    ->where('active', '!=', 2)
                    ->latest()
                    ->orderBy('active')
                    ->get();
            } else if (in_array($auth->grade_id, [9])) {
                $data = Member::with('affiliation')
                    ->where('active', '!=', 2)
                    ->latest()
                    ->orderBy('active')
                    ->get()
                    ->where('affiliation.parish', $auth->affiliation->parish)
                    ->whereNotIn('affiliation.id', [1, 2, 3, 4, 5]);
            } else if ($auth->grade_id == 10) {
                $data = Member::with('affiliation')
                    ->where('active', '!=', 2)
                    ->latest()
                    ->orderBy('active')
                    ->get()
                    ->where('affiliation.parish', $auth->affiliation->parish)
                    ->where('affiliation.team', $auth->affiliation->team);
            } else if ($auth->grade_id == 11) {
                $data = Member::with('affiliation')
                    ->where('affiliation_id', $auth->affiliation_id)
                    ->where('active', '!=', 2)
                    ->latest()
                    ->orderBy('active')
                    ->get();
            }

            return Datatables::of($data)
                ->editColumn('name', function($column) {
                    return '<a href="'.route('congregation.show', $column->id).'" class="text-primary">'.$column->name.'</a>'
                        .' '.'<a href="'.route('kpi.member', $column->id).'" class="text-secondary"><i class="fa fa-file"></i></a>';
                })
                ->editColumn('grade_id', function($column) use ($grades) {
                    $grade = $grades->find($column->grade_id)->grade ?: '';

                    return $grade;
                })
                ->editColumn('sex', function($column) {
                    switch ($column->sex) {
                        case 1: $sex = '<i class="fas fa-male text-primary"></i>'; break;
                        case 2: $sex = '<i class="fas fa-female text-danger"></i>'; break;
                        default: $sex = ''; break;
                    }

                    return $sex;
                })
                ->editColumn('active', function($column) {
                    switch ($column->active) {
                        case 0: $active = '<span class="badge badge-danger">Inactive</span>'; break;
                        case 1: $active = '<span class="badge badge-success">Active</span>'; break;
                        default: $active = ''; break;
                    }

                    return $active;
                })
                ->editColumn('inactive', function($column) {
                    $inactives = [
                        NULL => '',
                        0 => '',
                        1 =>'군지체',
                        2 =>'래사랑',
                        3 =>'타교회출석',
                        4 =>'주일예배',
                        5 =>'지방',
                        6 =>'해외',
                        7 =>'연락불가',
                        8 =>'장기결석',
                        9 =>'방문자',
                        10 =>'기타',
                    ];

                    $inactive = $inactives[$column->inactive];

                    return $inactive;
                })
                ->rawColumns(['detail', 'name', 'sex', 'active', 'is_admin'])
                ->make(true);
        }

        if (in_array($auth->grade_id, [1, 2, 3, 4, 5, 6, 7, 8])) return view('congregation.admin.index');
        else if ($auth->grade_id == 10) return view('congregation.team.index');
        else if ($auth->grade_id == 11) return view('congregation.group.index');
    }

    public function show($member_id)
    {
        $grades = Grade::all();
        $member = Member::with('affiliation')->findOrFail($member_id);
        $auth = Member::find(Auth::user()->member_id);

        return view('congregation.show', [
            'auth' => $auth,
            'grades' => $grades,
            'member' => $member,
        ]);
    }

    public function save(Request $request)
    {
        $data = $request->input('result');
        $affiliation = Affiliation::select('id')
            ->where([
                ['parish', $data['parish']],
                ['team', $data['team']],
                ['group', $data['group']],
            ])->first('id');

        DB::transaction(function () use ($data, $affiliation) {
            $member = Member::find($data['id']);
            $member->inception = $data['inception'];
            $member->identity = $data['identity'];
            $member->grade_id = $data['grade_id'];
            $member->affiliation_id = $affiliation->id;
            $member->sex = $data['sex'];
            $member->phone_number = $data['phone_number'];
            $member->is_admin = $data['is_admin'];
            $member->active = $data['active'];
            $member->inactive = $data['inactive'];
            $member->save();

            $user = User::where('member_id', $member->id)->first();
            if (!$user && $data['is_admin'] == 1) { // 관리자 등록
                DB::table('users')->insert([
                    'name' => $member->name,
                    'email' => $member->id.'@newsong-j.com',
                    'member_id' => $member->id,
                    'password' => Hash::make('000000'),
                ]);
            } else if ($user && $data['is_admin'] == 0) { // 관리자 해제
                $user->delete();
            }
        });
    }

    public function regist()
    {
        $auth = Member::find(Auth::user()->member_id);
        $grades = Grade::all();

        return view('congregation.regist', [
            'auth' => $auth,
            'grades' => $grades,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->input('result');
        $affiliation = Affiliation::select('id')
            ->where([
                ['parish', $data['parish']],
                ['team', $data['team']],
                ['group', $data['group']],
            ])->first('id');

        if (!$affiliation) return ;

        // 이름 기수 관리번호 중복시 실패
        $dup_check = Member::where([
            ['name', $data['name']],
            ['inception', $data['inception']],
            ['phone_number', $data['phone_number']],
        ])->first();

        $dup_check = Member::select('id')
            ->where([
                ['members.name', $data['name']],
                ['inception', $data['inception']],
                ['phone_number', $data['phone_number']],
            ])->first();

        if ($dup_check) {
            return response()->json(
                ['error' => '이미 존재하는 유저입니다.'], 404,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        DB::transaction(function () use ($data, $affiliation) {
            $member = new Member;
            $member->name = $data['name'];
            $member->inception = $data['inception'];
            $member->identity = $data['identity'];
            $member->grade_id = $data['grade_id'];
            $member->affiliation_id = $affiliation->id;
            $member->sex = $data['sex'];
            $member->phone_number = $data['phone_number'];
            $member->active = $data['active'];
            $member->inactive = $data['inactive'];
            $member->save();
        });
    }
}
