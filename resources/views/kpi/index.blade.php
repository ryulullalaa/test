@section('title', __('집계'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('집계') }}

            @include('kpi.component.nav')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="card p-2 mb-4">
                        <form class="form-inline" action="{{ route('kpi.index') }}">
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
                        </form>
                    </div>
                    <div class="mb-4">
                        <canvas id="chart"></canvas>
                    </div>
                    <table class="table table-bordered yajra-datatable w-100">
                        <thead>
                            <tr>
                                <th>구분</th>
                                <th>활동교인수</th>
                                <th>온라인</th>
                                <th>현장</th>
                                <th>결석</th>
                                <th>미입력</th>
                                <th>출석율</th>
                                <th>미입력율</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData as $data)
                            <tr>
                                @if ($tableRange == 'affiliations.parish')
                                <td>{{ $data[0]->parish }}교구</td>
                                @elseif ($tableRange == 'affiliations.team')
                                <td>{{ $data[0]->parish }}교구 {{ $data[0]->team }}팀</td>
                                @elseif ($tableRange == 'affiliations.group')
                                <td>{{ $data[0]->parish }}교구 {{ $data[0]->team }}팀 {{ $data[0]->group }}그룹</td>
                                @endif
                                <td>{{ $data[0]->notEntered + $data[0]->online + $data[0]->offline + $data[0]->absent }}</td>
                                <td>{{ $data[0]->online }}</td>
                                <td>{{ $data[0]->offline }}</td>
                                <td>{{ $data[0]->absent }}</td>
                                <td>{{ $data[0]->notEntered }}</td>
                                <td>
                                    {{ round(($data[0]->online + $data[0]->offline) / (($data[0]->online + $data[0]->offline + $data[0]->absent) == 0 ? 1 : $data[0]->online + $data[0]->offline + $data[0]->absent) * 100, 2) }}%
                                </td>
                                <td>
                                    {{ round(($data[0]->notEntered > 0 ? $data[0]->notEntered : (($data[0]->online + $data[0]->offline + $data[0]->absent) + $data[0]->notEntered)) / ((($data[0]->online + $data[0]->offline + $data[0]->absent) + $data[0]->notEntered) == 0 ? 1 : (($data[0]->online + $data[0]->offline + $data[0]->absent) + $data[0]->notEntered)) * 100, 2) }}%
                                </td>
                            </tr>
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
    .form-group .form-control {
        min-width: 100px;
    }
    .table {
        text-align: center;
        white-space: nowrap;
        overflow: scroll;
    }
</style>
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

    $(function () {
        const permission = {!! $grade !!};
        $('#parish').hide();
        $('#team').hide();
        $('#group').hide();

        if ([1,2,3,4,5,6,7].includes(permission)) {
            $('#parish').show();
            $('#team').show();
            $('#group').show();
        } else if (permission == 7) {
            $('#team').show();
            $('#group').show();
        } else if (permission == 9) {
            $('#group').show();
        }

        // old value 처리
        const urlParams = new URLSearchParams(window.location.search);
        window.history.replaceState(null, null, window.location.pathname);

        if (urlParams == "") {
            $('#parish').val({!! $affiliation->parish !!});
            $('#team').val({!! $affiliation->team !!});
            $('#group').val({!! $affiliation->group !!});
        } else {
            $('#start').val(urlParams.get('start'));
            $('#end').val(urlParams.get('end'));
            $('#parish').val(urlParams.get('parish'));
            $('#team').val(urlParams.get('team'));
            $('#group').val(urlParams.get('group'));
        }

        // datetimepicker
        $('#datetimepicker7').datetimepicker({
            format: 'YYYY-MM-DD',
        });
        $('#datetimepicker8').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false
        });
        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });

        conditionCheck();
    });

    $('#parish').change(function() {
        if (this.value != 0) {
            $('#team').attr('disabled', false);
        } else {
            $('#team').val(0);
            $('#group').val(0);
            $('#team').attr('disabled', true);
            $('#group').attr('disabled', true);
        }
    })

    $('#team').change(function() {
        if (this.value != 0) {
            $('#group').attr('disabled', false);
        } else {
            $('#group').val(0);
            $('#group').attr('disabled', true);
        }
    })

    function conditionCheck() {
        if ($('#parish').val() != 0) {
            $('#team').attr('disabled', false);
        } else {
            $('#team').val(0);
            $('#group').val(0);
            $('#team').attr('disabled', true);
            $('#group').attr('disabled', true);
        }

        if ($('#team').val() != 0) {
            $('#group').attr('disabled', false);
        } else {
            $('#group').val(0);
            $('#group').attr('disabled', true);
        }
    }
</script>