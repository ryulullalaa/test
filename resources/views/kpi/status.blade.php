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
                        <form class="form-inline" action="{{ route('kpi.status') }}">
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
                    <b>LT 참가자 명단</b>
                    <table class="table table-bordered yajra-datatable w-100" id="table1">
                        <thead>
                            <tr>
                                <th>날짜</th>
                                <th>소속</th>
                                <th>이름</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ltTableData->sortBy(['parish', 'team', 'group']) as $data)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($data->created_at)) }}</td>
                                <td>{{ $data->parish }}교구 {{ $data->team }}팀 {{ $data->group }}그룹</td>
                                <td>
                                    <a href="{{ url('/kpi/member/'.$data->id)}}" class="text-primary">
                                        {{ $data->name }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <b>PLT 참가자 명단</b>
                    <table class="table table-bordered yajra-datatable w-100" id="table2">
                        <thead>
                            <tr>
                                <th>날짜</th>
                                <th>소속</th>
                                <th>이름</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pltTableData->sortBy(['parish', 'team', 'group']) as $data)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($data->created_at)) }}</td>
                                <td>{{ $data->parish }}교구 {{ $data->team }}팀 {{ $data->group }}그룹</td>
                                <td>
                                    <a href="{{ url('/kpi/member/'.$data->id)}}" class="text-primary">
                                        {{ $data->name }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <b>새큼터 참가자 명단</b>
                    <table class="table table-bordered yajra-datatable w-100" id="table3">
                        <thead>
                            <tr>
                                <th>날짜</th>
                                <th>소속</th>
                                <th>이름</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sctTableData->sortBy(['parish', 'team', 'group']) as $data)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($data->created_at)) }}</td>
                                <td>{{ $data->parish }}교구 {{ $data->team }}팀 {{ $data->group }}그룹</td>
                                <td>
                                    <a href="{{ url('/kpi/member/'.$data->id)}}" class="text-primary">
                                        {{ $data->name }}
                                    </a>
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
<script type="text/javascript">
    function exportExcel() {
        $("#table1").table2excel({
            exclude: ".noExl",
            name: "LT",
            filename: "LT" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });

        $("#table2").table2excel({
            exclude: ".noExl",
            name: "PLT",
            filename: "PLT" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });

        $("#table3").table2excel({
            exclude: ".noExl",
            name: "새큼터",
            filename: "새큼터" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>