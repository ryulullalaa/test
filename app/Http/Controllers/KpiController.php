<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Services\KpiService;
use App\Models\Affiliation;
use App\Models\Member;
use Auth;
use DB;

class KpiController extends Controller
{
    public function now(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            // 차트 데이터: 설정한 기간의 출석률 데이터
            $chartData = DB::table('attendances')
                ->select([
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                    DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                    DB::raw('DATE_FORMAT(attendances.created_at, "%Y-%m-%d") as date'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupBy('date')
                ->get()
                ->groupBy('date');

            // 테이블 데이터: 출석률 데이터
            $tableData = DB::table('attendances')
                ->select([
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                    DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get()
                ->sortBy('affiliation_id')
                ->groupBy('affiliation_id');
        } else {
            // 차트 데이터: 설정한 기간의 출석률 데이터
            if ($request->parish == 0) {
                $chartData = DB::table('attendances')
                    ->select([
                        DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                        DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                        DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                        DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                        DB::raw('DATE_FORMAT(attendances.created_at, "%Y-%m-%d") as date'),
                    ])
                    ->leftJoin('members', 'attendances.member_id', 'members.id')
                    ->leftJoin('grades', 'members.grade_id', 'grades.id')
                    ->where([
                        ['members.active', 1],
                        ['grades.desc', '!=', 1], // 실행위원 제외
                        // ['grades.desc', '!=', 2], // 임원단 제외
                    ])
                    ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                    ->whereBetween('attendances.created_at', [$request->start, $request->end])
                    ->groupBy('date')
                    ->get()
                    ->groupBy('date');
            } else {
                $chartData = DB::table('attendances')
                    ->select([
                        DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                        DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                        DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                        DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                        DB::raw('DATE_FORMAT(attendances.created_at, "%Y-%m-%d") as date'),
                    ])
                    ->leftJoin('members', 'attendances.member_id', 'members.id')
                    ->leftJoin('grades', 'members.grade_id', 'grades.id')
                    ->where([
                        ['members.active', 1],
                        ['grades.desc', '!=', 1], // 실행위원 제외
                        ['grades.desc', '!=', 2], // 임원단 제외
                    ])
                    ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                    ->whereBetween('attendances.created_at', [$request->start, $request->end])
                    ->groupBy('date')
                    ->get()
                    ->groupBy('date');
            }

            // 테이블 데이터: 출석률 데이터
            $tableData = DB::table('attendances')
                ->select([
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                    DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupByRaw($service->groupByRange($request))
                ->get()
                ->sortBy('affiliation_id')
                ->groupBy('affiliation_id');
        }

        // 그룹 데이블 데이터: 그룹 선택시에 필요한 데이터
        // 230118 임원단,실행위원도 이름이 나오도록 변경
        $groupTableBase = DB::table('attendances')
            ->select([
                'members.id',
                'members.name',
                DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
            ])
            ->leftJoin('members', 'attendances.member_id', 'members.id')
            ->leftJoin('grades', 'members.grade_id', 'grades.id')
            ->whereBetween('attendances.created_at', [$request->start, $request->end]);

        if ($request->parish == 4) {
            $groupTableData = $groupTableBase
                ->where([
                    ['members.active', 1], ['grades.desc', 2], // 임원단만
                ])
                ->groupBy('members.id')
                ->get();
        } else if ($request->parish == 5) {
            $groupTableData = $groupTableBase
                ->where([
                    ['members.active', 1], ['grades.desc', 1], // 실행위원만
                ])
                ->groupBy('members.id')
                ->get();
        } else {
            $affiliation_id = $service->getAffiliations($request);

            $groupTableData = $groupTableBase
                ->where([
                    ['members.active', 1], ['grades.desc', '!=', 1], ['grades.desc', '!=', 2]
                ])
                ->whereIn('members.affiliation_id', $affiliation_id)
                ->groupBy('members.id')
                ->get();
        }

        // 임원단 데이터: 전체 교구 볼 때 같이 보기 위해서 추가
        $execData = DB::table('attendances as at')
            ->select([
                'm.affiliation_id', 'affiliations.parish', 'affiliations.team', 'affiliations.group',
                DB::raw('count(CASE WHEN at.attendance = 0 THEN 1 END) as notEntered'),
                DB::raw('count(CASE WHEN at.attendance = 1 THEN 1 END) as online'),
                DB::raw('count(CASE WHEN at.attendance = 2 THEN 1 END) as offline'),
                DB::raw('count(CASE WHEN at.attendance = 3 THEN 1 END) as absent'),
            ])
            ->leftJoin('members as m', 'at.member_id', 'm.id')
            ->leftJoin('affiliations', 'm.affiliation_id', 'affiliations.id')
            ->leftJoin('grades as g', 'm.grade_id', 'g.id')
            ->where([
                ['m.active', 1],
                ['g.desc', 2], // 임원단만
            ])
            ->whereBetween('at.created_at', [$request->start, $request->end])
            ->groupByRaw($service->groupByRange($request))
            ->get()
            ->groupBy('affiliation_id');

        $data = [
            'sl' => $service->getSearchLength(),
            'grade' => $service->getGrade(),
            'affiliation' => $service->getAffiliation(),
            'tableRange' => $service->groupByRange($request),
            'rangeOption' => $request->parish,
            'chartData' => $chartData,
            'tableData' => $tableData,
            'groupTableData' => $groupTableData,
            'execData' => $execData,
        ];

        return view('kpi.now', $data);
    }

    public function absent(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            // 테이블 데이터: 미입력 사유 데이터
            $totalData = DB::table('attendances')
                ->select([
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as attendance'),
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.reason = 1 THEN 1 END) as company'),
                    DB::raw('count(CASE WHEN attendances.reason = 2 THEN 1 END) as school'),
                    DB::raw('count(CASE WHEN attendances.reason = 3 THEN 1 END) as sick'),
                    DB::raw('count(CASE WHEN attendances.reason = 4 THEN 1 END) as business'),
                    DB::raw('count(CASE WHEN attendances.reason = 5 THEN 1 END) as parttime'),
                    DB::raw('count(CASE WHEN attendances.reason = 6 THEN 1 END) as family'),
                    DB::raw('count(CASE WHEN attendances.reason = 7 THEN 1 END) as etc'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();

            $tableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                    DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupBy('members.id')
                ->get();
        } else {
            // 테이블 데이터: 미입력 사유 데이터
            $totalData = DB::table('attendances')
                ->select([
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as attendance'),
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.reason = 1 THEN 1 END) as company'),
                    DB::raw('count(CASE WHEN attendances.reason = 2 THEN 1 END) as school'),
                    DB::raw('count(CASE WHEN attendances.reason = 3 THEN 1 END) as sick'),
                    DB::raw('count(CASE WHEN attendances.reason = 4 THEN 1 END) as business'),
                    DB::raw('count(CASE WHEN attendances.reason = 5 THEN 1 END) as parttime'),
                    DB::raw('count(CASE WHEN attendances.reason = 6 THEN 1 END) as family'),
                    DB::raw('count(CASE WHEN attendances.reason = 7 THEN 1 END) as etc'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();

            $tableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                    DB::raw('count(CASE WHEN attendances.attendance = 1 THEN 1 END) as online'),
                    DB::raw('count(CASE WHEN attendances.attendance = 2 THEN 1 END) as offline'),
                    DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as absent'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupBy('members.id')
                ->orderBy('absent', 'desc')
                ->orderBy('parish')
                ->orderBy('team')
                ->orderBy('group')
                ->get();
        }

        $data = [
            'sl' => $service->getSearchLength(),
            'grade' => $service->getGrade(),
            'affiliation' => $service->getAffiliation(),
            'totalData' => $totalData,
            'tableData' => $tableData,
        ];

        return view('kpi.absent', $data);
    }

    public function spirit(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            $tableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('sum(attendances.worship_dawn) as worship_dawn'),
                    DB::raw('sum(attendances.read_bible) as read_bible'),
                    DB::raw('avg(attendances.worship_dawn) as worship_dawn_avg'),
                    DB::raw('avg(attendances.read_bible) as read_bible_avg'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 1 THEN 1 END) as worship_am1'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 2 THEN 1 END) as worship_am2'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 1 THEN 1 END) as worship_pm1'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 2 THEN 1 END) as worship_pm2'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 1 THEN 1 END) as worship_wed1'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 2 THEN 1 END) as worship_wed2'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 1 THEN 1 END) as worship_sat1'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 2 THEN 1 END) as worship_sat2'),
                    DB::raw('count(CASE WHEN attendances.training != 0 THEN 1 END) as training1'),
                    DB::raw('count(CASE WHEN attendances.training = 0 THEN 1 END) as training2'),
                    DB::raw('count(CASE WHEN attendances.training = 1 THEN 1 END) as lt'),
                    DB::raw('count(CASE WHEN attendances.training = 2 THEN 1 END) as plt'),
                    DB::raw('count(CASE WHEN attendances.training = 3 THEN 1 END) as sct'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupByRaw($service->groupByRange($request))
                ->get()
                ->sortBy('affiliation_id')
                ->groupBy('affiliation_id');

            $groupTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('sum(attendances.worship_dawn) as worship_dawn'),
                    DB::raw('sum(attendances.read_bible) as read_bible'),
                    DB::raw('avg(attendances.worship_dawn) as worship_dawn_avg'),
                    DB::raw('avg(attendances.read_bible) as read_bible_avg'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 1 THEN 1 END) as worship_am1'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 2 THEN 1 END) as worship_am2'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 1 THEN 1 END) as worship_pm1'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 2 THEN 1 END) as worship_pm2'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 1 THEN 1 END) as worship_wed1'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 2 THEN 1 END) as worship_wed2'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 1 THEN 1 END) as worship_sat1'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 2 THEN 1 END) as worship_sat2'),
                    DB::raw('count(CASE WHEN attendances.training != 0 THEN 1 END) as training1'),
                    DB::raw('count(CASE WHEN attendances.training = 0 THEN 1 END) as training2'),
                    DB::raw('count(CASE WHEN attendances.training = 1 THEN 1 END) as lt'),
                    DB::raw('count(CASE WHEN attendances.training = 2 THEN 1 END) as plt'),
                    DB::raw('count(CASE WHEN attendances.training = 3 THEN 1 END) as sct'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupBy('members.id')
                ->get();
        } else {
            $tableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('sum(attendances.worship_dawn) as worship_dawn'),
                    DB::raw('sum(attendances.read_bible) as read_bible'),
                    DB::raw('avg(attendances.worship_dawn) as worship_dawn_avg'),
                    DB::raw('avg(attendances.read_bible) as read_bible_avg'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 1 THEN 1 END) as worship_am1'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 2 THEN 1 END) as worship_am2'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 1 THEN 1 END) as worship_pm1'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 2 THEN 1 END) as worship_pm2'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 1 THEN 1 END) as worship_wed1'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 2 THEN 1 END) as worship_wed2'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 1 THEN 1 END) as worship_sat1'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 2 THEN 1 END) as worship_sat2'),
                    DB::raw('count(CASE WHEN attendances.training != 0 THEN 1 END) as training1'),
                    DB::raw('count(CASE WHEN attendances.training = 0 THEN 1 END) as training2'),
                    DB::raw('count(CASE WHEN attendances.training = 1 THEN 1 END) as lt'),
                    DB::raw('count(CASE WHEN attendances.training = 2 THEN 1 END) as plt'),
                    DB::raw('count(CASE WHEN attendances.training = 3 THEN 1 END) as sct'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupByRaw($service->groupByRange($request))
                ->get()
                ->sortBy('affiliation_id')
                ->groupBy('affiliation_id');

            $groupTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    DB::raw('sum(attendances.worship_dawn) as worship_dawn'),
                    DB::raw('sum(attendances.read_bible) as read_bible'),
                    DB::raw('avg(attendances.worship_dawn) as worship_dawn_avg'),
                    DB::raw('avg(attendances.read_bible) as read_bible_avg'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 1 THEN 1 END) as worship_am1'),
                    DB::raw('count(CASE WHEN attendances.worship_am = 2 THEN 1 END) as worship_am2'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 1 THEN 1 END) as worship_pm1'),
                    DB::raw('count(CASE WHEN attendances.worship_pm = 2 THEN 1 END) as worship_pm2'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 1 THEN 1 END) as worship_wed1'),
                    DB::raw('count(CASE WHEN attendances.worship_wed = 2 THEN 1 END) as worship_wed2'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 1 THEN 1 END) as worship_sat1'),
                    DB::raw('count(CASE WHEN attendances.worship_sat = 2 THEN 1 END) as worship_sat2'),
                    DB::raw('count(CASE WHEN attendances.training != 0 THEN 1 END) as training1'),
                    DB::raw('count(CASE WHEN attendances.training = 0 THEN 1 END) as training2'),
                    DB::raw('count(CASE WHEN attendances.training = 1 THEN 1 END) as lt'),
                    DB::raw('count(CASE WHEN attendances.training = 2 THEN 1 END) as plt'),
                    DB::raw('count(CASE WHEN attendances.training = 3 THEN 1 END) as sct'),
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->groupBy('members.id')
                ->get();
        }

        // 임원단 데이터: 전체 교구 볼 때 같이 보기 위해서 추가
        $execData = DB::table('attendances')
        ->select([
            'members.id',
            'members.name',
            'members.affiliation_id',
            'affiliations.parish',
            'affiliations.team',
            'affiliations.group',
            DB::raw('sum(attendances.worship_dawn) as worship_dawn'),
            DB::raw('sum(attendances.read_bible) as read_bible'),
            DB::raw('avg(attendances.worship_dawn) as worship_dawn_avg'),
            DB::raw('avg(attendances.read_bible) as read_bible_avg'),
            DB::raw('count(CASE WHEN attendances.worship_am = 1 THEN 1 END) as worship_am1'),
            DB::raw('count(CASE WHEN attendances.worship_am = 2 THEN 1 END) as worship_am2'),
            DB::raw('count(CASE WHEN attendances.worship_pm = 1 THEN 1 END) as worship_pm1'),
            DB::raw('count(CASE WHEN attendances.worship_pm = 2 THEN 1 END) as worship_pm2'),
            DB::raw('count(CASE WHEN attendances.worship_wed = 1 THEN 1 END) as worship_wed1'),
            DB::raw('count(CASE WHEN attendances.worship_wed = 2 THEN 1 END) as worship_wed2'),
            DB::raw('count(CASE WHEN attendances.worship_sat = 1 THEN 1 END) as worship_sat1'),
            DB::raw('count(CASE WHEN attendances.worship_sat = 2 THEN 1 END) as worship_sat2'),
            DB::raw('count(CASE WHEN attendances.training != 0 THEN 1 END) as training1'),
            DB::raw('count(CASE WHEN attendances.training = 0 THEN 1 END) as training2'),
            DB::raw('count(CASE WHEN attendances.training = 1 THEN 1 END) as lt'),
            DB::raw('count(CASE WHEN attendances.training = 2 THEN 1 END) as plt'),
            DB::raw('count(CASE WHEN attendances.training = 3 THEN 1 END) as sct'),
        ])
        ->leftJoin('members', 'attendances.member_id', 'members.id')
        ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
        ->where('members.active', 1)
        ->whereIn('members.affiliation_id', [2, 3, 4])
        ->whereBetween('attendances.created_at', [$request->start, $request->end])
        ->groupByRaw('members.id')
        ->get();

        $data = [
            'sl' => $service->getSearchLength(),
            'grade' => $service->getGrade(),
            'affiliation' => $service->getAffiliation(),
            'tableRange' => $service->groupByRange($request),
            'rangeOption' => $request->parish,
            'tableData' => $tableData,
            'groupTableData' => $groupTableData,
            'execData' => $execData,
        ];

        return view('kpi.spirit', $data);
    }

    public function report(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            $tableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'grades.grade',
                    'members.inception',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'reason',
                    'visit_way',
                    'visit_result',
                    'reason_detail',
                    'attendances.attendance',
                    'situation',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();
        } else {
            $tableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'grades.grade',
                    'members.inception',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'reason',
                    'visit_way',
                    'visit_result',
                    'reason_detail',
                    'attendances.attendance',
                    'situation',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();
        }

        $data = [
            'sl' => $service->getSearchLength(),
            'grade' => $service->getGrade(),
            'affiliation' => $service->getAffiliation(),
            'tableData' => $tableData,
            'rangeOption' => $request->parish,
        ];

        return view('kpi.report', $data);
    }

    public function status(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            $ltTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['attendances.training', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();

            $pltTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['attendances.training', 2],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();

            $sctTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['attendances.training', 3],
                ])
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();
        } else {
            $ltTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['attendances.training', 1],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();

            $pltTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['attendances.training', 2],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();

            $sctTableData = DB::table('attendances')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                    'attendances.created_at',
                ])
                ->leftJoin('members', 'attendances.member_id', 'members.id')
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['attendances.training', 3],
                    ['grades.desc', '!=', 1], // 실행위원 제외
                    ['grades.desc', '!=', 2], // 임원단 제외
                ])
                ->whereIn('members.affiliation_id', $service->getAffiliations($request))
                ->whereBetween('attendances.created_at', [$request->start, $request->end])
                ->get();
        }

        $data = [
            'sl' => $service->getSearchLength(),
            'grade' => $service->getGrade(),
            'affiliation' => $service->getAffiliation(),
            'ltTableData' => $ltTableData,
            'pltTableData' => $pltTableData,
            'sctTableData' => $sctTableData,
        ];

        return view('kpi.status', $data);
    }

    public function identity(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            // 테이블 데이터: 비활동 데이터
            $tableData = DB::table('members')
                ->select([
                    DB::raw('count(CASE WHEN members.identity = 1 THEN 1 END) as office'),
                    DB::raw('count(CASE WHEN members.identity = 2 THEN 1 END) as student'),
                    DB::raw('count(CASE WHEN members.identity = 3 THEN 1 END) as prepare_emp'),
                    DB::raw('count(CASE WHEN members.identity = 4 THEN 1 END) as prepare_ent'),
                    DB::raw('count(CASE WHEN members.identity = 5 THEN 1 END) as delay_army'),
                    DB::raw('count(CASE WHEN members.identity = 6 THEN 1 END) as delay_overseas'),
                    DB::raw('count(CASE WHEN members.identity = 7 THEN 1 END) as etc'),
                ])
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->get();

            $detailTableData = DB::table('members')
                ->select([
                    'members.id',
                    'members.name',
                    'members.affiliation_id',
                    'members.identity',
                    'affiliations.parish',
                    'affiliations.team',
                    'affiliations.group',
                ])
                ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->where(function ($query) use ($request) {
                    if ($request->identity != 0) {
                        $query->where('members.identity', $request->identity);
                    }
                })
                ->get();
        } else {
            // 테이블 데이터: 비활동 데이터
            $tableData = DB::table('members as m')
                ->select([
                    DB::raw('count(CASE WHEN m.identity = 1 THEN 1 END) as office'),
                    DB::raw('count(CASE WHEN m.identity = 2 THEN 1 END) as student'),
                    DB::raw('count(CASE WHEN m.identity = 3 THEN 1 END) as prepare_emp'),
                    DB::raw('count(CASE WHEN m.identity = 4 THEN 1 END) as prepare_ent'),
                    DB::raw('count(CASE WHEN m.identity = 5 THEN 1 END) as delay_army'),
                    DB::raw('count(CASE WHEN m.identity = 6 THEN 1 END) as delay_overseas'),
                    DB::raw('count(CASE WHEN m.identity = 7 THEN 1 END) as etc'),
                ])
                ->leftJoin('grades as g', 'm.grade_id', 'g.id')
                ->where([
                    ['m.active', 1],
                    ['g.desc', '!=', 1], // 실행위원 제외
                ])
                ->where(function ($query) use ($request) {
                    if ($request->identity == 0) return $query;
                    $query->where('m.identity', $request->identity);
                })
                ->whereIn('m.affiliation_id', $service->getAffiliations($request))
                ->get();

            $detailTableData = DB::table('members as m')
                ->select([
                    'm.id', 'm.name', 'm.affiliation_id', 'm.identity',
                    'a.parish', 'a.team', 'a.group',
                ])
                ->leftJoin('affiliations as a', 'm.affiliation_id', 'a.id')
                ->leftJoin('grades as g', 'm.grade_id', 'g.id')
                ->where([
                    ['m.active', 1],
                    ['g.desc', '!=', 1], // 실행위원 제외
                ])
                ->where(function ($query) use ($request) {
                    if ($request->identity == 0) return $query;
                    $query->where('m.identity', $request->identity);
                })
                ->whereIn('m.affiliation_id', $service->getAffiliations($request))
                ->get();
        }

        $data = [
            'sl' => $service->getSearchLength(),
            'grade' => $service->getGrade(),
            'affiliation' => $service->getAffiliation(),
            'tableData' => $tableData,
            'detailTableData' => $detailTableData,
        ];

        return view('kpi.identity', $data);
    }

    public function inactive(Request $request, KpiService $service)
    {
        if ($request->parish == 4 || $request->parish == 5) {
            if ($request->parish == 4) $grade_desc = 2;
            else if ($request->parish == 5) $grade_desc = 1;

            // 테이블 데이터: 비활동 데이터
            $tableData = DB::table('members')
                ->select([
                    DB::raw('count(CASE WHEN members.active = 1 THEN 1 END) as active'),
                    DB::raw('count(CASE WHEN members.active = 0 THEN 1 END) as inactive'),
                    DB::raw('count(CASE WHEN members.inactive = 1 THEN 1 END) as army'),
                    DB::raw('count(CASE WHEN members.inactive = 2 THEN 1 END) as raesarang'),
                    DB::raw('count(CASE WHEN members.inactive = 3 THEN 1 END) as outside'),
                    DB::raw('count(CASE WHEN members.inactive = 4 THEN 1 END) as worship'),
                    DB::raw('count(CASE WHEN members.inactive = 5 THEN 1 END) as local'),
                    DB::raw('count(CASE WHEN members.inactive = 6 THEN 1 END) as overseas'),
                    DB::raw('count(CASE WHEN members.inactive = 7 THEN 1 END) as unable_contact'),
                    DB::raw('count(CASE WHEN members.inactive = 8 THEN 1 END) as long_absence'),
                    DB::raw('count(CASE WHEN members.inactive = 9 THEN 1 END) as visit'),
                    DB::raw('count(CASE WHEN members.inactive = 10 THEN 1 END) as etc'),
                ])
                ->leftJoin('grades', 'members.grade_id', 'grades.id')
                ->where([
                    ['members.active', 1],
                    ['grades.id', '!=', 1], // 교역자 제외
                    ['grades.desc', $grade_desc], // 임원단 또는 실행위원만
                ])
                ->get();

            $data = [
                'sl' => $service->getSearchLength(),
                'grade' => $service->getGrade(),
                'affiliation' => $service->getAffiliation(),
                'tableData' => $tableData,
            ];
        } else {
            // 테이블 데이터: 비활동 데이터
            $tableData = DB::table('members as m')
                ->select([
                    DB::raw('count(CASE WHEN m.active = 1 THEN 1 END) as active'),
                    DB::raw('count(CASE WHEN m.active = 0 THEN 1 END) as inactive'),
                    DB::raw('count(CASE WHEN m.inactive = 1 THEN 1 END) as army'),
                    DB::raw('count(CASE WHEN m.inactive = 2 THEN 1 END) as raesarang'),
                    DB::raw('count(CASE WHEN m.inactive = 3 THEN 1 END) as outside'),
                    DB::raw('count(CASE WHEN m.inactive = 4 THEN 1 END) as worship'),
                    DB::raw('count(CASE WHEN m.inactive = 5 THEN 1 END) as local'),
                    DB::raw('count(CASE WHEN m.inactive = 6 THEN 1 END) as overseas'),
                    DB::raw('count(CASE WHEN m.inactive = 7 THEN 1 END) as unable_contact'),
                    DB::raw('count(CASE WHEN m.inactive = 8 THEN 1 END) as long_absence'),
                    DB::raw('count(CASE WHEN m.inactive = 9 THEN 1 END) as visit'),
                    DB::raw('count(CASE WHEN m.inactive = 10 THEN 1 END) as etc'),
                ])
                ->leftJoin('grades as g', 'm.grade_id', 'g.id')
                ->where([
                    ['m.active', '!=', 2],
                    ['g.desc', '!=', 1], // 실행위원 제외
                ])
                ->whereIn('m.affiliation_id', $service->getAffiliations($request))
                ->get();

            $data = [
                'sl' => $service->getSearchLength(),
                'grade' => $service->getGrade(),
                'affiliation' => $service->getAffiliation(),
                'tableData' => $tableData,
            ];
        }

        return view('kpi.inactive', $data);
    }

    public function member(Request $request, KpiService $service, $member_id)
    {
        $member_info = DB::table('members')
            ->select([
                'members.id',
                'members.name',
                'members.grade_id',
                'members.inception',
                'members.sex',
                'members.identity',
                'affiliations.parish',
                'affiliations.team',
                'affiliations.group',
            ])
            ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
            ->where('members.id', $member_id)
            ->get();

        $totalData = DB::table('attendances')
            ->select([
                'members.id',
                DB::raw('count(CASE WHEN attendances.attendance = 1 OR attendances.attendance = 2 THEN 1 END) as attendance1'),
                DB::raw('count(CASE WHEN attendances.attendance = 3 THEN 1 END) as attendance2'),
                DB::raw('count(CASE WHEN attendances.training != 0 THEN 1 END) as training1'),
                DB::raw('count(CASE WHEN attendances.training = 0 THEN 1 END) as training2'),
                DB::raw('sum(attendances.worship_dawn) as worship_dawn'),
                DB::raw('sum(attendances.read_bible) as read_bible'),
                DB::raw('avg(attendances.worship_dawn) as worship_dawn_avg'),
                DB::raw('avg(attendances.read_bible) as read_bible_avg'),
                DB::raw('count(CASE WHEN attendances.worship_am = 1 THEN 1 END) as worship_am1'),
                DB::raw('count(CASE WHEN attendances.worship_am = 2 THEN 1 END) as worship_am2'),
                DB::raw('count(CASE WHEN attendances.worship_pm = 1 THEN 1 END) as worship_pm1'),
                DB::raw('count(CASE WHEN attendances.worship_pm = 2 THEN 1 END) as worship_pm2'),
                DB::raw('count(CASE WHEN attendances.worship_wed = 1 THEN 1 END) as worship_wed1'),
                DB::raw('count(CASE WHEN attendances.worship_wed = 2 THEN 1 END) as worship_wed2'),
                DB::raw('count(CASE WHEN attendances.worship_sat = 1 THEN 1 END) as worship_sat1'),
                DB::raw('count(CASE WHEN attendances.worship_sat = 2 THEN 1 END) as worship_sat2'),
            ])
            ->leftJoin('members', 'attendances.member_id', 'members.id')
            ->where('members.id', $member_id)
            ->get();

        $tableData = DB::table('attendances')
            ->select([
                'members.id',
                'attendances.attendance',
                'attendances.reason',
                'attendances.reason_detail',
                'attendances.situation',
                'attendances.worship_dawn',
                'read_bible',
                'worship_am',
                'worship_pm',
                'worship_wed',
                'worship_sat',
                DB::raw('DATE_FORMAT(attendances.created_at, "%Y-%m-%d") as created_at'),
            ])
            ->leftJoin('members', 'attendances.member_id', 'members.id')
            ->where('members.id', $member_id)
            ->get()
            ->sortByDesc('created_at')
            ->groupBy('created_at');

        $data = [
            'memberInfo' => $member_info,
            'totalData' => $totalData,
            'tableData' => $tableData,
        ];

        return view('kpi.member', $data);
    }

    public function notentered(Request $request, KpiService $service)
    {
        $member = DB::table('members as m')
            ->leftJoin('affiliations as a', 'm.affiliation_id', 'a.id')
            ->where('m.id', Auth::user()->member_id)
            ->get();

        if ($member) {
            $parish = $member[0]->parish;
            $team = $member[0]->team;
            $group = $member[0]->group;
            $grade_id = $member[0]->grade_id;
        }

        $base = DB::table('attendances')
            ->select([
                'members.affiliation_id',
                'affiliations.parish',
                'affiliations.team',
                'affiliations.group',
                DB::raw('count(CASE WHEN attendances.attendance = 0 THEN 1 END) as notEntered'),
                DB::raw('count(CASE WHEN attendances.attendance = 1 OR 2 OR 3 THEN 1 END) as att'),
            ])
            ->leftJoin('members', 'attendances.member_id', 'members.id')
            ->leftJoin('affiliations', 'members.affiliation_id', 'affiliations.id')
            ->where('members.active', 1)
            ->whereBetween('attendances.created_at', [\Carbon\Carbon::today()->subDay(6), \Carbon\Carbon::today()->endofday()]);

        if (in_array($grade_id, [1, 2, 3, 4, 5, 6])) {
            # [전체 교구] 1:교역자,2:행정부장,3:실행위원,4:총괄관리자,5:회장단,6:임원단총괄
            $tableData = $base
                ->groupBy('affiliation_id')->get();
        } else if ($grade_id == 8) {
            # [각 교구] 8:교구임원단
            $tableData = $base
                ->where('affiliations.parish', $parish)
                ->groupBy('affiliation_id')->get();
        } else if ($grade_id == 10) {
            # [각 팀] 10:팀장
            $tableData = $base
                ->where([
                    ['affiliations.parish', $parish],
                    ['affiliations.team', $team],
                ])->groupBy('affiliation_id')->get();
        } else {
            # [각 그룹] 7:임원단서기,11:그룹장,12:그룹원,13:부서장,14:PLT,15:새큼터,16:새가족,17:워메,18:N그룹원
            $tableData = $base
                ->where([
                    ['affiliations.parish', $parish],
                    ['affiliations.team', $team],
                    ['affiliations.group', $group],
                ])->groupBy('affiliation_id')->get();
        }

        $data = [
            'tableData' => $tableData,
        ];

        return view('kpi.notentered', $data);
    }
}
