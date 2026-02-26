@section('title', __('상세페이지'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('상세페이지') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-3">
                        <div class="flex"><button type="button" class="btn btn-dark" onclick="window.history.back();">Back</button></div>
                    </div>

                    <table class="table table-bordered yajra-datatable w-100">
                        <thead>
                            <tr>
                                <th>이름</th>
                                <th>직책</th>
                                <th>교구</th>
                                <th>팀</th>
                                <th>그룹</th>
                                <th>기수</th>
                                <th>성별</th>
                                <th>신분</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($memberInfo as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>
                                    @switch($data->grade_id)
                                        @case(1) 교역자 @break
                                        @case(2) 행정부장 @break
                                        @case(3) 실행위원 @break
                                        @case(4) 총괄관리자 @break
                                        @case(5) 회장단 @break
                                        @case(6) 임원단총괄 @break
                                        @case(7) 임원단서기 @break
                                        @case(8) 교구임원단 @break
                                        @case(9) 임원단 @break
                                        @case(10) 팀장 @break
                                        @case(11) 그룹장 @break
                                        @case(12) 그룹원 @break
                                        @case(13) 부서장 @break
                                        @case(14) PLT @break
                                        @case(15) 새큼터 @break
                                        @case(16) 새가족 @break
                                        @case(17) 워메 @break
                                        @case(18) N그룹원 @break
                                        @default
                                    @endswitch
                                </td>
                                <td>{{ $data->parish }}</td>
                                <td>{{ $data->team }}</td>
                                <td>{{ $data->group }}</td>
                                <td>{{ $data->inception }}기</td>
                                <td>
                                    @switch($data->sex)
                                        @case(1) 남자 @break
                                        @case(2) 여자 @break
                                        @default
                                    @endswitch
                                </td>
                                <td>
                                    @switch($data->identity)
                                        @case(1) 직장인 @break
                                        @case(2) 학생 @break
                                        @case(3) 취업준비 @break
                                        @case(4) 입시준비 @break
                                        @case(5) 군지체 @break
                                        @case(6) 해외지체 @break
                                        @case(7) 기타 @break
                                        @default
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table table-bordered yajra-datatable w-100">
                        <thead>
                            <tr>
                                <th>구분</th>
                                <th>출석율</th>
                                <th>활동참여율</th>
                                <th>새벽기도</th>
                                <th>성경읽기</th>
                                <th>주일낮</th>
                                <th>주일저녁</th>
                                <th>수요예배</th>
                                <th>토새깨</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($totalData as $data)
                            <tr>
                                <td>합계</td>
                                <td>{{ $data->attendance1 }}회</td>
                                <td>{{ $data->training1 }}회</td>
                                <td>{{ $data->worship_dawn }}회</td>
                                <td>{{ $data->read_bible }}장</td>
                                <td>{{ $data->worship_am1 }}회</td>
                                <td>{{ $data->worship_pm1 }}회</td>
                                <td>{{ $data->worship_wed1 }}회</td>
                                <td>{{ $data->worship_sat1 }}회</td>
                            </tr>
                            <tr>
                                <td>평균</td>
                                <td>
                                    {{
                                        round(
                                            $data->attendance1
                                            /
                                            (($data->attendance1 + $data->attendance2) == 0
                                                ? 1 : ($data->attendance1 + $data->attendance2))
                                        * 100, 2)
                                    }}%
                                </td>
                                <td>
                                    {{
                                        round(
                                            $data->training1
                                            /
                                            (($data->training1 + $data->training2) == 0
                                                ? 1 : ($data->training1 + $data->training2))
                                        * 100, 2)
                                    }}%
                                </td>
                                <td>{{ round($data->worship_dawn_avg, 1) }}회</td>
                                <td>{{ round($data->read_bible_avg, 1) }}장</td>
                                <td>
                                    {{
                                        round(
                                            $data->worship_am1
                                            /
                                            (($data->worship_am1 + $data->worship_am2) == 0
                                                ? 1 : ($data->worship_am1 + $data->worship_am2))
                                        * 100, 2)
                                    }}%
                                </td>
                                <td>
                                    {{
                                        round(
                                            $data->worship_pm1
                                            /
                                            (($data->worship_pm1 + $data->worship_pm2) == 0
                                                ? 1 : ($data->worship_pm1 + $data->worship_pm2))
                                        * 100, 2)
                                    }}%
                                </td>
                                <td>
                                    {{
                                        round(
                                            $data->worship_wed1
                                            /
                                            (($data->worship_wed1 + $data->worship_wed2) == 0
                                                ? 1 : ($data->worship_wed1 + $data->worship_wed2))
                                        * 100, 2)
                                    }}%
                                </td>
                                <td>
                                    {{
                                        round(
                                            $data->worship_sat1
                                            /
                                            (($data->worship_sat1 + $data->worship_sat2) == 0
                                                ? 1 : ($data->worship_sat1 + $data->worship_sat2))
                                        * 100, 2)
                                    }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table table-bordered yajra-datatable w-100">
                        <thead>
                            <tr>
                                <th>날짜</th>
                                <th>출석여부</th>
                                <th>결석사유</th>
                                <th>결석상세사유</th>
                                <th>상황보고</th>
                                <th>새벽기도</th>
                                <th>성경읽기</th>
                                <th>주일낮</th>
                                <th>주일저녁</th>
                                <th>수요예배</th>
                                <th>토새깨</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData as $data)
                                <tr>
                                    <td>{{ $data[0]->created_at }}</td>
                                    <td>
                                        @switch($data[0]->attendance)
                                            @case(0) 미입력 @break
                                            @case(1) 온라인 @break
                                            @case(2) 현장 @break
                                            @case(3) 결석 @break
                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($data[0]->reason)
                                            @case(0) 미입력 @break
                                            @case(1) 회사일 @break
                                            @case(2) 학교일 @break
                                            @case(3) 아픔 @break
                                            @case(4) 출장 @break
                                            @case(5) 개인사정 @break
                                            @default
                                        @endswitch
                                    </td>
                                    <td>{{ $data[0]->reason_detail }}</td>
                                    <td>{{ $data[0]->situation }}</td>
                                    <td>{{ $data[0]->worship_dawn }}회</td>
                                    <td>{{ $data[0]->read_bible }}장</td>
                                    <td>
                                        @switch($data[0]->worship_am)
                                            @case(0) 미입력 @break
                                            @case(1) 참석 @break
                                            @case(2) 불참 @break
                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($data[0]->worship_pm)
                                            @case(0) 미입력 @break
                                            @case(1) 참석 @break
                                            @case(2) 불참 @break
                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($data[0]->worship_wed)
                                            @case(0) 미입력 @break
                                            @case(1) 참석 @break
                                            @case(2) 불참 @break
                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($data[0]->worship_sat)
                                            @case(0) 미입력 @break
                                            @case(1) 참석 @break
                                            @case(2) 불참 @break
                                            @default
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    table {
        text-align: center;
    }
</style>
