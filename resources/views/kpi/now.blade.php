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
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card p-2 mb-4">
                        <form class="form-inline" action="{{ route('kpi.now') }}">
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
                    <div class="table-area">
                        <div style="display: none">
                            {{ $notEntered=0, $online=0, $offline=0, $absent=0 }}
                            @if ($rangeOption == 0)
                                @foreach ($execData as $data)
                                    {{ $notEntered += $data[0]->notEntered }}
                                    {{ $online += $data[0]->online }}
                                    {{ $offline += $data[0]->offline }}
                                    {{ $absent += $data[0]->absent }}
                                @endforeach
                            @endif
                        </div>
                        <table class="table table-bordered yajra-datatable w-100">
                            <thead>
                                <tr>
                                    <th>구분</th>
                                    <th>활동교인수</th>
                                    <th>온라인</th>
                                    <th>현장</th>
                                    <th>출석합계</th>
                                    <th>출석율</th>
                                    <th>결석</th>
                                    {{-- <th>미입력</th> --}}
                                    <th>미입력율</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($tableData) != 1)
                                <tr>
                                    <div style="display: none">
                                        {{ $parish = '' }}
                                        {{ $team = '' }}
                                        @foreach ($tableData as $data)
                                            {{ $parish = $data[0]->parish }}
                                            {{ $team = $data[0]->team }}
                                            @break
                                        @endforeach
                                    </div>
                                    @if ($tableRange == 'affiliations.parish')
                                    <td>합계</td>
                                    @elseif ($tableRange == 'affiliations.team')
                                    <td>{{ $parish }}교구</td>
                                    @elseif ($tableRange == 'affiliations.group')
                                    <td>{{ $parish }}교구 {{ $team }}팀</td>
                                    @endif

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->notEntered + $data[0]->online + $data[0]->offline + $data[0]->absent }}
                                        @endforeach
                                    </div>
                                    <td>{{ $total + $notEntered + $online + $offline + $absent }}</td>

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->online }}
                                        @endforeach
                                    </div>
                                    <td>{{ $total + $online }}</td>

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->offline }}
                                        @endforeach
                                    </div>
                                    <td>{{ $total + $offline }}</td>

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += ($data[0]->online + $data[0]->offline) }}
                                        @endforeach
                                    </div>
                                    <td>{{ $total + $online + $offline }}</td>

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        {{ $total2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->online + $data[0]->offline }}
                                            {{ $total2 += $data[0]->online + $data[0]->offline + $data[0]->absent }}
                                        @endforeach
                                        {{ $total = $total + $online + $offline }}
                                        {{ $total2 = $total2 + $online + $offline + $absent}}
                                    </div>
                                    <td>
                                        {{
                                            $total + $total2 > 0
                                            ? round($total / ($total2 == 0 ? 1 : $total2) * 100, 2)
                                            : 0
                                        }}%
                                    </td>

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->absent }}
                                        @endforeach
                                    </div>
                                    <td>{{ $total + $absent }}</td>

                                    {{-- <div style="display: none">
                                        {{ $total = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->notEntered }}
                                        @endforeach
                                    </div>
                                    <td>{{ $total }}</td> --}}

                                    <div style="display: none">
                                        {{ $total = 0 }}
                                        {{ $total2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $total += $data[0]->notEntered }}
                                            {{
                                                $total2 += $data[0]->online + $data[0]->offline + $data[0]->absent + $data[0]->notEntered
                                            }}
                                        @endforeach
                                        {{ $total = $total + $notEntered }}
                                        {{ $total2 = $total2 + $online + $offline + $absent + $notEntered }}
                                    </div>
                                    <td>{{ round($total / ($total2 == 0 ? 1 : $total2) * 100, 2) }}%</td>
                                </tr>
                                @endif

                                @foreach ($tableData as $data)
                                <tr>
                                    @if ($rangeOption == 4)
                                    <td>임원단</td>
                                    @elseif ($rangeOption == 5)
                                    <td>실행위원</td>
                                    @elseif ($tableRange == 'affiliations.parish')
                                    <td>{{ $data[0]->parish }}교구</td>
                                    @elseif ($tableRange == 'affiliations.team')
                                    <td>{{ $data[0]->parish }}교구 {{ $data[0]->team }}팀</td>
                                    @elseif ($tableRange == 'affiliations.group')
                                    <td>{{ $data[0]->parish }}교구 {{ $data[0]->team }}팀 {{ $data[0]->group }}그룹</td>
                                    @endif
                                    <td>{{ $data[0]->notEntered + $data[0]->online + $data[0]->offline + $data[0]->absent }}</td>
                                    <td>{{ $data[0]->online }}</td>
                                    <td>{{ $data[0]->offline }}</td>
                                    <td>{{ $data[0]->online + $data[0]->offline }}</td>
                                    <td>
                                        {{
                                            round((
                                                $data[0]->online + $data[0]->offline)
                                                /
                                                (($data[0]->online + $data[0]->offline + $data[0]->absent) == 0
                                                    ? 1
                                                    : $data[0]->online + $data[0]->offline + $data[0]->absent)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>{{ $data[0]->absent }}</td>
                                    {{-- <td>{{ $data[0]->notEntered }}</td> --}}
                                    <td>
                                        {{
                                            $data[0]->notEntered == 0
                                                ? 0
                                                : round(
                                                    ($data[0]->notEntered > 0 ? $data[0]->notEntered : 1)
                                                    /
                                                    ($data[0]->online + $data[0]->offline + $data[0]->absent + $data[0]->notEntered)
                                                * 100, 2)
                                        }}%
                                    </td>
                                </tr>
                                @endforeach

                                @if ($rangeOption == 0)
                                <tr id="exec">
                                    <td>임원단</td>
                                    <td>{{ $notEntered + $online + $offline + $absent }}</td>
                                    <td>{{ $online }}</td>
                                    <td>{{ $offline }}</td>
                                    <td>{{ $online + $offline }}</td>
                                    <td>
                                        {{
                                            round((
                                                $online + $offline)
                                                /
                                                (($online + $offline + $absent) == 0
                                                    ? 1
                                                    : $online + $offline + $absent)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>{{ $absent }}</td>
                                    <td>
                                        {{
                                            $notEntered == 0
                                                ? 0
                                                : round(
                                                    ($notEntered > 0 ? $notEntered : 1)
                                                    /
                                                    ($online + $offline + $absent + $notEntered)
                                                * 100, 2)
                                        }}%
                                    </td>
                                </tr>
                                @endif

                                @if (count($tableData) == 1)
                                @foreach ($groupTableData as $data)
                                    <tr>
                                        <td>
                                            <a href="{{ url('/kpi/member/'.$data->id)}}" class="text-primary">
                                                {{ $data->name }}
                                            </a>
                                        </td>
                                        <td>{{ $data->notEntered + $data->online + $data->offline + $data->absent }}</td>
                                        <td>{{ $data->online }}</td>
                                        <td>{{ $data->offline }}</td>
                                        <td>{{ $data->online + $data->offline }}</td>
                                        <td>
                                            {{
                                                round(
                                                    ($data->online + $data->offline)
                                                    /
                                                    (($data->online + $data->offline + $data->absent) ? ($data->online + $data->offline + $data->absent) : 1)
                                                * 100, 2)
                                            }}%
                                        </td>
                                        <td>{{ $data->absent }}</td>
                                        {{-- <td>{{ $data->notEntered }}</td> --}}
                                        <td>
                                            {{
                                                $data->notEntered == 0
                                                    ? 0
                                                    : round(
                                                        ($data->notEntered > 0 ? $data->notEntered : 1)
                                                        /
                                                        ($data->online + $data->offline + $data->absent + $data->notEntered)
                                                    * 100, 2)
                                            }}%
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-4">
                        <canvas id="chart"></canvas>
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
    .table-area {
        overflow: auto;
    }
    .table {
        text-align: center;
        white-space: nowrap;
        overflow: scroll;
    }
</style>
@include('kpi.component.search')
<script type="text/javascript">
    var chartData = {!! $chartData !!};

    var chart = new Chart(
        document.getElementById('chart'),
        {
            data: {
                labels: Object.keys(chartData),
                datasets: [{
                    type: 'line',
                    label: '출석률',
                    yAxisID: 'y1',
                    backgroundColor: 'rgb(76, 76, 76)',
                    borderColor: 'rgb(76, 76, 76)',
                    data: Object.values(chartData).map(function (key) {
                        onoff = key[0]['online'] + key[0]['offline'];

                        return parseFloat((onoff) / ((onoff + key[0]['absent']) == 0
                            ? 1 : onoff + key[0]['absent']) * 100).toFixed(2);
                    }),
                },{
                    type: 'bar',
                    label: '온라인',
                    backgroundColor: 'rgb(183, 240, 177)',
                    borderColor: 'rgb(183, 240, 177)',
                    data: Object.values(chartData).map(function (key) {
                        return key[0]['online'];
                    }),
                },{
                    type: 'bar',
                    label: '현장',
                    backgroundColor: 'rgb(178, 204, 255)',
                    borderColor: 'rgb(178, 204, 255)',
                    data: Object.values(chartData).map(function (key) {
                        return key[0]['offline'];
                    }),
                },{
                    type: 'bar',
                    label: '결석',
                    backgroundColor: 'rgb(255, 167, 167)',
                    borderColor: 'rgb(255, 167, 167)',
                    data: Object.values(chartData).map(function (key) {
                        return key[0]['absent'];
                    }),
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: false,
                        text: '집계'
                    },
                },
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        position: 'left',
                    },
                    y1: {
                        max: 100,
                        min: 0,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        }
    );

    function exportExcel() {
        $(".yajra-datatable").table2excel({
            exclude: ".noExl",
            name: "now",
            filename: "now" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>