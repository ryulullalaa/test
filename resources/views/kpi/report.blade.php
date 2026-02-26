@section('title', __('집계'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('집계') }}

            @include('kpi.component.nav')
        </h2>
    </x-slot>

    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="frame" class="p-6 bg-white border-b border-gray-200">
                    <div class="card p-2 mb-4">
                        <form class="form-inline" action="{{ route('kpi.report') }}">
                            <div class="form-group">
                                <div class="form-group mr-sm-2">
                                <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                        <input type="text" name="start" id="start" class="form-control datetimepicker-input" placeholder="FROM" data-target="#datetimepicker7"/>
                                        <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mr-sm-2">
                                <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                        <input type="text" name="end" id="end" class="form-control datetimepicker-input" placeholder="TO" data-target="#datetimepicker8"/>
                                        <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control mr-sm-2" name="parish" id="parish">
                                    <option value="0">교구</option>
                                    @for ($i=$sl->min_parish; $i<=$sl->max_parish; $i++)
                                        <option value="{{ $i }}">{{ $i }}교구</option>
                                    @endfor
                                    <option value="4">임원단</option>
                                    <option value="5">실행위원</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control mr-sm-2" name="team" id="team">
                                    <option value="0">팀</option>
                                    @for ($i=$sl->min_team; $i<=$sl->max_team; $i++)
                                        <option value="{{ $i }}">{{ $i }}팀</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control mr-sm-2" name="group" id="group">
                                    <option value="-1">그룹</option>
                                    @for ($i=$sl->min_group; $i<=$sl->max_group; $i++)
                                        <option value="{{ $i }}">{{ $i }}그룹</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group search-button mr-sm-2">
                                <input type="submit" class="btn btn-secondary" value="검색">
                            </div>
                            <div class="form-group export-button">
                                <input type="button" class="btn btn-success float-right" id="btnExport" value="Export Excel" onclick="exportExcel()" />
                            </div>
                        </form>
                    </div>
                    <div class="table-area text-center overflow-auto">
                        <table class="table table-bordered yajra-datatable w-100">
                            <thead>
                                <tr>
                                    <th class="nowrap">날짜</th>
                                    <th class="nowrap">소속</th>
                                    <th class="nowrap">직책</th>
                                    <th class="nowrap">기수</th>
                                    <th class="nowrap">이름</th>
                                    <th class="nowrap">뉴송출석</th>
                                    <th class="nowrap">결석사유</th>
                                    <th class="nowrap">심방수단</th>
                                    <th class="nowrap">심방결과</th>
                                    <th class="nowrap">심방내용</th>
                                    <th class="nowrap">상황보고 및 기도내용</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tableData->sortBy(['affiliation_id', 'name']) as $data)
                                <tr>
                                    <td class="nowrap">{{ date('Y-m-d', strtotime($data->created_at)) }}</td>
                                    @if ($rangeOption == 4)
                                    <td class="nowrap">{{ $data->parish }}교구 임원단</td>
                                    @elseif ($rangeOption == 5)
                                    <td class="nowrap">실행위원</td>
                                    @else
                                    <td class="nowrap">{{ $data->parish }}교구 {{ $data->team }}팀 {{ $data->group }}그룹</td>
                                    @endif
                                    <td class="nowrap">{{ $data->grade }}</td>
                                    <td class="nowrap">{{ $data->inception }}</td>
                                    <td class="nowrap">
                                        <a href="{{ url('/kpi/member/'.$data->id)}}" class="text-primary">
                                            {{ $data->name }}
                                        </a>
                                    </td>
                                    <td class="nowrap">
                                        @switch($data->attendance)
                                            @case(0) 미입력 @break
                                            @case(1) 온라인 @break
                                            @case(2) 현장 @break
                                            @case(3) 결석 @break
                                            @default
                                        @endswitch
                                    </td>
                                    <td class="nowrap">
                                        @if($data->attendance == 3)
                                        @switch($data->reason)
                                            @case(0) 미입력 @break
                                            @case(1) 회사일 @break
                                            @case(2) 학교일 @break
                                            @case(3) 아픔 @break
                                            @case(4) 출장 @break
                                            @case(5) 알바 @break
                                            @case(6) 집안사정 @break
                                            @case(7) 기타 @break
                                            @default
                                        @endswitch
                                        @endif
                                    </td>
                                    <td class="nowrap">
                                        @if($data->attendance == 3)
                                        @switch($data->visit_way)
                                            @case(0) 미입력 @break
                                            @case(1) 전화 @break
                                            @case(2) 문자 @break
                                            @default
                                        @endswitch
                                        @endif
                                    </td>
                                    <td class="nowrap">
                                        @if($data->attendance == 3)
                                        @switch($data->visit_result)
                                            @case(0) 미입력 @break
                                            @case(1) 심방완료 @break
                                            @case(2) 연락안됨 @break
                                            @case(3) 심방못함 @break
                                            @default
                                        @endswitch
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->attendance == 3)
                                            {{ $data->reason_detail }}
                                        @endif
                                    </td>
                                    <td>{{ $data->situation }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    .form-group .form-control {
        min-width: 100px;
    }
    .nowrap {
        white-space: nowrap;
    }
</style>
@include('kpi.component.search')
<script type="text/javascript">
    function exportExcel() {
        $(".yajra-datatable").table2excel({
            exclude: ".noExl",
            name: "report",
            filename: "report" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>