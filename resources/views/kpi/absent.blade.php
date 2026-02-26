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
                        <form class="form-inline" action="{{ route('kpi.absent') }}">
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

                    {{-- 종합 테이블 --}}
                    <table class="table table-bordered yajra-datatable w-100" id="table1">
                        <thead>
                            <tr>
                                <th>구분</th>
                                <th>합계</th>
                                <th>비율</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($totalData as $data)
                                <tr>
                                    <td>결석인원</td>
                                    <td>{{ $data->attendance }}</td>
                                    <td>
                                        @if ($data->attendance == 0) 0
                                        @else {{ round($data->attendance / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>회사일</td>
                                    <td>{{ $data->company }}</td>
                                    <td>
                                        @if ($data->company == 0) 0
                                        @else {{ round($data->company / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>학교일</td>
                                    <td>{{ $data->school }}</td>
                                    <td>
                                        @if ($data->school == 0) 0
                                        @else {{ round($data->school / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>아픔</td>
                                    <td>{{ $data->sick }}</td>
                                    <td>
                                        @if ($data->sick == 0) 0
                                        @else {{ round($data->sick / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>출장</td>
                                    <td>{{ $data->business }}</td>
                                    <td>
                                        @if ($data->business == 0) 0
                                        @else {{ round($data->business / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>알바</td>
                                    <td>{{ $data->parttime }}</td>
                                    <td>
                                        @if ($data->parttime == 0) 0
                                        @else {{ round($data->parttime / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>집안사정</td>
                                    <td>{{ $data->family }}</td>
                                    <td>
                                        @if ($data->family == 0) 0
                                        @else {{ round($data->family / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>기타</td>
                                    <td>{{ $data->etc }}</td>
                                    <td>
                                        @if ($data->etc == 0) 0
                                        @else {{ round($data->etc / ($data->attendance == 0 ? 1 : $data->attendance) * 100, 2) }}
                                        @endif %
                                    </td>
                                </tr>
                                <tr>
                                    <td>미입력</td>
                                    <td>{{ $data->notEntered }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tbody>
                        </tbody>
                    </table>

                    {{-- 결석 테이블 --}}
                    <table class="table table-bordered yajra-datatable w-100" id="table2">
                        <thead>
                            <tr>
                                <th>소속</th>
                                <th>이름</th>
                                <th>결석율</th>
                                <th>결석일수</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData as $data)
                                @if ($data->absent != 0)
                                <tr>
                                    <td>{{ $data->parish }}교구 {{ $data->team }}팀 {{ $data->group }}그룹</td>
                                    <td>
                                        <a href="{{ url('/kpi/member/'.$data->id)}}" class="text-primary">
                                            {{ $data->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $data->absent
                                                /
                                                (($data->online + $data->offline + $data->absent) == 0
                                                    ? 1
                                                    : $data->online + $data->offline + $data->absent)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>{{ $data->absent }}주</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    #frame {
        min-height: 400px;
    }
    .form-group .form-control {
        min-width: 100px;
    }
    .table {
        text-align: center;
        white-space: nowrap;
        overflow: scroll;
    }
</style>
@include('kpi.component.search')
<script>
    function exportExcel() {
        $("#table1").table2excel({
            exclude: ".noExl",
            name: "absent",
            filename: "absent" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });

        $("#table2").table2excel({
            exclude: ".noExl",
            name: "absent_detail",
            filename: "absent_detail" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>