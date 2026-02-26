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
                        <form class="form-inline" action="{{ route('kpi.identity') }}">
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
                            <div class="form-group">
                                <select class="form-control mr-sm-2" name="identity" id="identity">
                                    <option value="0">전체</option>
                                    <option value="1">직장인</option>
                                    <option value="2">학생</option>
                                    <option value="3">취업준비</option>
                                    <option value="4">입시준비</option>
                                    <option value="5">군지체</option>
                                    <option value="6">해외지체</option>
                                    <option value="7">기타</option>
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

                    <b>신분통계</b>
                    <table class="table table-bordered yajra-datatable w-100" id="table1">
                        <thead>
                            <tr>
                                <th>구분</th>
                                <th>합계</th>
                                <th>비율</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData as $data)
                            <tr>
                                <td>합계</td>
                                <td>{{ $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc }}명</td>
                                <td>100%</td>
                            </tr>
                            <tr>
                                <td>직장인</td>
                                <td>{{ $data->office }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->office
                                            /
                                            (
                                                $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc == 0
                                                ? 1
                                                : $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            <tr>
                                <td>학생</td>
                                <td>{{ $data->student }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->student
                                            /
                                            (
                                                $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc == 0
                                                ? 1
                                                : $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            <tr>
                                <td>취업준비</td>
                                <td>{{ $data->prepare_emp }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->prepare_emp
                                            /
                                            (
                                                $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc == 0
                                                ? 1
                                                : $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            <tr>
                                <td>입시준비</td>
                                <td>{{ $data->prepare_ent }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->prepare_ent
                                            /
                                            (
                                                $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc == 0
                                                ? 1
                                                : $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            <tr>
                                <td>군지체</td>
                                <td>{{ $data->delay_army }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->delay_army
                                            /
                                            (
                                                $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc == 0
                                                ? 1
                                                : $data->office+$data->student+$data->prepare_emp+$data->prepare_ent+$data->delay_army+$data->delay_overseas+$data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            <tr>
                                <td>해외지체</td>
                                <td>{{ $data->delay_overseas }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->delay_overseas
                                            /
                                            (
                                                $data->office + $data->student + $data->prepare_emp + $data->prepare_ent + $data->etc == 0
                                                ? 1
                                                : $data->office + $data->student + $data->prepare_emp + $data->prepare_ent + $data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            <tr>
                                <td>기타</td>
                                <td>{{ $data->etc }}명</td>
                                <td>
                                    {{
                                        round(
                                            $data->etc
                                            /
                                            (
                                                $data->office + $data->student + $data->prepare_emp + $data->prepare_ent + $data->etc == 0
                                                ? 1
                                                : $data->office + $data->student + $data->prepare_emp + $data->prepare_ent + $data->etc
                                            ) * 100, 2
                                        )
                                    }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tbody>
                        </tbody>
                    </table>

                    <b>신분통계상세</b>
                    <table class="table table-bordered yajra-datatable w-100" id="table2">
                        <thead>
                            <tr>
                                <th>소속</th>
                                <th>이름</th>
                                <th>신분</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailTableData->sortBy(['affiliation_id', 'name']) as $data)
                            <tr>
                                <td>{{ $data->parish }}교구 {{ $data->team }}팀 {{ $data->group }}그룹</td>
                                <td>{{ $data->name }}</td>
                                {{-- 1:직장인,2:학생,3:취업준비,4:입시준비,5:군지체,6:해외지체,7:기타 --}}
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
<script>
    function exportExcel() {
        $("#table1").table2excel({
            exclude: ".noExl",
            name: "identity",
            filename: "identity" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });

        $("#table2").table2excel({
            exclude: ".noExl",
            name: "identity_detail",
            filename: "identity_detail" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    };
</script>
@include('kpi.component.search')