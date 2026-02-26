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
                        <form class="form-inline" action="{{ route('kpi.spirit') }}">
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
                            {{
                                $exec_worship_dawn=0,
                                $exec_read_bible=0,
                                $exec_worship_dawn_avg=0,
                                $exec_read_bible_avg=0,
                                $exec_worship_am1=0,
                                $exec_worship_am2=0,
                                $exec_worship_pm1=0,
                                $exec_worship_pm2=0,
                                $exec_worship_wed1=0,
                                $exec_worship_wed2=0,
                                $exec_worship_sat1=0,
                                $exec_worship_sat2=0,
                                $exec_training1=0,
                                $exec_training2=0,
                                $exec_lt=0,
                                $exec_plt=0,
                                $exec_sct=0
                            }}
                            @foreach ($execData as $data)
                                {{ $exec_worship_dawn += $data->worship_dawn }}
                                {{ $exec_read_bible += $data->read_bible }}
                                {{ $exec_worship_dawn_avg += $data->worship_dawn_avg }}
                                {{ $exec_read_bible_avg += $data->read_bible_avg }}
                                {{ $exec_worship_am1 += $data->worship_am1 }}
                                {{ $exec_worship_am2 += $data->worship_am2 }}
                                {{ $exec_worship_pm1 += $data->worship_pm1 }}
                                {{ $exec_worship_pm2 += $data->worship_pm2 }}
                                {{ $exec_worship_wed1 += $data->worship_wed1 }}
                                {{ $exec_worship_wed2 += $data->worship_wed2 }}
                                {{ $exec_worship_sat1 += $data->worship_sat1 }}
                                {{ $exec_worship_sat2 += $data->worship_sat2 }}
                                {{ $exec_training1 += $data->training1 }}
                                {{ $exec_training2 += $data->training2 }}
                                {{ $exec_lt += $data->lt }}
                                {{ $exec_plt += $data->plt }}
                                {{ $exec_sct += $data->sct }}
                            @endforeach
                        </div>
                        <table class="table table-bordered yajra-datatable w-100">
                            <thead>
                                <tr>
                                    <th>소속</th>
                                    <th>새벽기도</th>
                                    <th>성경읽기</th>
                                    <th>주일낮</th>
                                    <th>주일저녁</th>
                                    <th>수요</th>
                                    <th>토새깨</th>
                                    <th>훈련참석율</th>
                                    <th>LT</th>
                                    <th>PLT</th>
                                    <th>새큼터</th>
                                    <th>훈련참석일</th>
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
                                        {{ $worship_dawn = 0 }}
                                        {{ $worship_dawn_avg = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $worship_dawn += $data[0]->worship_dawn }}
                                            {{ $worship_dawn_avg += $data[0]->worship_dawn_avg }}
                                        @endforeach
                                        {{ $worship_dawn += $exec_worship_dawn }}
                                        {{ $worship_dawn_avg += $exec_worship_dawn_avg }}
                                    </div>
                                    <td>{{ $worship_dawn }}회 ({{ round($worship_dawn_avg, 1) }}회)</td>

                                    <div style="display: none">
                                        {{ $read_bible = 0 }}
                                        {{ $read_bible_avg = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $read_bible += $data[0]->read_bible }}
                                            {{ $read_bible_avg += $data[0]->read_bible_avg }}
                                        @endforeach
                                        {{ $read_bible += $exec_read_bible }}
                                        {{ $read_bible_avg += $exec_read_bible_avg }}
                                    </div>
                                    <td>{{ $read_bible }}장 ({{ round($read_bible_avg, 1) }}장)</td>

                                    <div style="display: none">
                                        {{ $worship_am1 = 0 }}
                                        {{ $worship_am2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $worship_am1 += $data[0]->worship_am1 }}
                                            {{ $worship_am2 += $data[0]->worship_am2 }}
                                        @endforeach
                                        {{ $worship_am1 += $exec_worship_am1 }}
                                        {{ $worship_am2 += $exec_worship_am2 }}
                                    </div>
                                    <td>
                                        {{
                                            round(
                                                $worship_am1
                                                /
                                                ($worship_am1 + $worship_am2 == 0
                                                    ? 1 : $worship_am1 + $worship_am2)
                                            * 100, 2)
                                        }}%
                                    </td>

                                    <div style="display: none">
                                        {{ $worship_pm1 = 0 }}
                                        {{ $worship_pm2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $worship_pm1 += $data[0]->worship_pm1 }}
                                            {{ $worship_pm2 += $data[0]->worship_pm2 }}
                                        @endforeach
                                        {{ $worship_pm1 += $exec_worship_pm1 }}
                                        {{ $worship_pm2 += $exec_worship_pm2 }}
                                    </div>
                                    <td>
                                        {{
                                            round(
                                                $worship_pm1
                                                /
                                                ($worship_pm1 + $worship_pm2 == 0
                                                    ? 1 : $worship_pm1 + $worship_pm2)
                                            * 100, 2)
                                        }}%
                                    </td>

                                    <div style="display: none">
                                        {{ $worship_wed1 = 0 }}
                                        {{ $worship_wed2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $worship_wed1 += $data[0]->worship_wed1 }}
                                            {{ $worship_wed2 += $data[0]->worship_wed2 }}
                                        @endforeach
                                        {{ $worship_wed1 += $exec_worship_wed1 }}
                                        {{ $worship_wed2 += $exec_worship_wed2 }}
                                    </div>
                                    <td>
                                        {{
                                            round(
                                                $worship_wed1
                                                /
                                                ($worship_wed1 + $worship_wed2 == 0
                                                    ? 1 : $worship_wed1 + $worship_wed2)
                                            * 100, 2)
                                        }}%
                                    </td>

                                    <div style="display: none">
                                        {{ $worship_sat1 = 0 }}
                                        {{ $worship_sat2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $worship_sat1 += $data[0]->worship_sat1 }}
                                            {{ $worship_sat2 += $data[0]->worship_sat2 }}
                                        @endforeach
                                        {{ $worship_sat1 += $exec_worship_sat1 }}
                                        {{ $worship_sat2 += $exec_worship_sat2 }}
                                    </div>
                                    <td>
                                        {{
                                            round(
                                                $worship_sat1
                                                /
                                                ($worship_sat1 + $worship_sat2 == 0
                                                    ? 1 : $worship_sat1 + $worship_sat2)
                                            * 100, 2)
                                        }}%
                                    </td>

                                    <div style="display: none">
                                        {{ $training1 = 0 }}
                                        {{ $training2 = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $training1 += $data[0]->training1 }}
                                            {{ $training2 += $data[0]->training2 }}
                                        @endforeach
                                        {{ $training1 += $exec_training1 }}
                                        {{ $training2 += $exec_training2 }}
                                    </div>
                                    <td>
                                        {{
                                            round(
                                                $training1
                                                /
                                                ($training1 + $training2 == 0
                                                    ? 1 : $training1 + $training2)
                                            * 100, 2)
                                        }}%
                                    </td>

                                    <div style="display: none">
                                        {{ $lt = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $lt += $data[0]->lt }}
                                        @endforeach
                                        {{ $lt += $exec_lt }}
                                    </div>
                                    <td>{{ $lt }}명</td>

                                    <div style="display: none">
                                        {{ $plt = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $plt += $data[0]->plt }}
                                        @endforeach
                                        {{ $plt += $exec_plt }}
                                    </div>
                                    <td>{{ $plt }}명</td>

                                    <div style="display: none">
                                        {{ $sct = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $sct += $data[0]->sct }}
                                        @endforeach
                                        {{ $sct += $exec_sct }}
                                    </div>
                                    <td>{{ $sct }}명</td>

                                    <div style="display: none">
                                        {{ $totalTraining = 0 }}
                                        @foreach ($tableData as $data)
                                            {{ $totalTraining += $data[0]->lt + $data[0]->plt + $data[0]->sct }}
                                        @endforeach
                                        {{ $totalTraining += ($exec_lt + $exec_plt + $exec_sct) }}
                                    </div>
                                    <td>{{ $totalTraining }}명</td>
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
                                    <td>{{ $data[0]->worship_dawn }}회 ({{ round($data[0]->worship_dawn_avg, 1) }}회)</td>
                                    <td>{{ $data[0]->read_bible }}장 ({{ round($data[0]->read_bible_avg, 1) }}장)</td>
                                    <td>
                                        {{
                                            round(
                                                $data[0]->worship_am1
                                                /
                                                ($data[0]->worship_am1 + $data[0]->worship_am2 == 0
                                                    ? 1 : $data[0]->worship_am1 + $data[0]->worship_am2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $data[0]->worship_pm1
                                                /
                                                ($data[0]->worship_pm1 + $data[0]->worship_pm2 == 0
                                                    ? 1 : $data[0]->worship_pm1 + $data[0]->worship_pm2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $data[0]->worship_wed1
                                                /
                                                ($data[0]->worship_wed1 + $data[0]->worship_wed2 == 0
                                                    ? 1 : $data[0]->worship_wed1 + $data[0]->worship_wed2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $data[0]->worship_sat1
                                                /
                                                ($data[0]->worship_sat1 + $data[0]->worship_sat2 == 0
                                                    ? 1 : $data[0]->worship_sat1 + $data[0]->worship_sat2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $data[0]->training1
                                                /
                                                ($data[0]->training1 + $data[0]->training2 == 0
                                                    ? 1 : $data[0]->training1 + $data[0]->training2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>{{ $data[0]->lt }}일</td>
                                    <td>{{ $data[0]->plt }}일</td>
                                    <td>{{ $data[0]->sct }}일</td>
                                    <td>{{ $data[0]->lt + $data[0]->plt + $data[0]->sct }}일</td>
                                </tr>
                                @endforeach

                                @if ($rangeOption == 0)
                                <tr id="exec">
                                    <td>임원단</td>
                                    <td>{{ $exec_worship_dawn }}회 ({{ round($exec_worship_dawn_avg, 1) }}회)</td>
                                    <td>{{ $exec_read_bible }}장 ({{ round($exec_read_bible_avg, 1) }}장)</td>
                                    <td>
                                        {{
                                            round(
                                                $exec_worship_am1
                                                /
                                                ($exec_worship_am1 + $exec_worship_am2 == 0
                                                    ? 1 : $exec_worship_am1 + $exec_worship_am2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $exec_worship_pm1
                                                /
                                                ($exec_worship_pm1 + $exec_worship_pm2 == 0
                                                    ? 1 : $exec_worship_pm1 + $exec_worship_pm2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $exec_worship_wed1
                                                /
                                                ($exec_worship_wed1 + $exec_worship_wed2 == 0
                                                    ? 1 : $exec_worship_wed1 + $exec_worship_wed2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $exec_worship_sat1
                                                /
                                                ($exec_worship_sat1 + $exec_worship_sat2 == 0
                                                    ? 1 : $exec_worship_sat1 + $exec_worship_sat2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>
                                        {{
                                            round(
                                                $exec_training1
                                                /
                                                ($exec_training1 + $exec_training2 == 0
                                                    ? 1 : $exec_training1 + $exec_training2)
                                            * 100, 2)
                                        }}%
                                    </td>
                                    <td>{{ $exec_lt }}일</td>
                                    <td>{{ $exec_plt }}일</td>
                                    <td>{{ $exec_sct }}일</td>
                                    <td>{{ $exec_lt + $exec_plt + $exec_sct }}일</td>
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
                                        <td>{{ $data->worship_dawn }}회 ({{ round($data->worship_dawn_avg, 1) }}회)</td>
                                        <td>{{ $data->read_bible }}장 ({{ round($data->read_bible_avg, 1) }}장)</td>
                                        <td>
                                            {{
                                                round(
                                                    $data->worship_am1
                                                    /
                                                    ($data->worship_am1 + $data->worship_am2 == 0
                                                        ? 1 : $data->worship_am1 + $data->worship_am2)
                                                * 100, 2)
                                            }}%
                                        </td>
                                        <td>
                                            {{
                                                round(
                                                    $data->worship_pm1
                                                    /
                                                    ($data->worship_pm1 + $data->worship_pm2 == 0
                                                        ? 1 : $data->worship_pm1 + $data->worship_pm2)
                                                * 100, 2)
                                            }}%
                                        </td>
                                        <td>
                                            {{
                                                round(
                                                    $data->worship_wed1
                                                    /
                                                    ($data->worship_wed1 + $data->worship_wed2 == 0
                                                        ? 1 : $data->worship_wed1 + $data->worship_wed2)
                                                * 100, 2)
                                            }}%
                                        </td>
                                        <td>
                                            {{
                                                round(
                                                    $data->worship_sat1
                                                    /
                                                    ($data->worship_sat1 + $data->worship_sat2 == 0
                                                        ? 1 : $data->worship_sat1 + $data->worship_sat2)
                                                * 100, 2)
                                            }}%
                                        </td>
                                        <td>
                                            {{
                                                round(
                                                    $data->training1
                                                    /
                                                    ($data->training1 + $data->training2 == 0
                                                        ? 1 : $data->training1 + $data->training2)
                                                * 100, 2)
                                            }}%
                                        </td>
                                        <td>{{ $data->lt }}일</td>
                                        <td>{{ $data->plt }}일</td>
                                        <td>{{ $data->sct }}일</td>
                                        <td>{{ $data->lt + $data->plt + $data->sct }}일</td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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
    .table-area {
        overflow: auto;
    }
    .table {
        text-align: center;
        white-space: nowrap;
        overflow: scroll;
    }
</style>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams == "") {
        $('#exec').css('display', 'none');
    }

    function exportExcel() {
        $(".yajra-datatable").table2excel({
            exclude: ".noExl",
            name: "spirit",
            filename: "spirit" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>
@include('kpi.component.search')