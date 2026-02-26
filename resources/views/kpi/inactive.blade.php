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
                        <form class="form-inline" action="{{ route('kpi.inactive') }}">
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
                    <table class="table table-bordered yajra-datatable w-100">
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
                                    <td>{{ $data->active + $data->inactive }}명</td>
                                    <td>
                                        {{
                                            round(
                                                ($data->active + $data->inactive)
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>활동</td>
                                    <td>{{ $data->active }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->active
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>비활동</td>
                                    <td>{{ $data->inactive }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->inactive
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>군지체</td>
                                    <td>{{ $data->army }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->army
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>래사랑</td>
                                    <td>{{ $data->raesarang }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->raesarang
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>타교회출석</td>
                                    <td>{{ $data->outside }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->outside
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>주일예배</td>
                                    <td>{{ $data->worship }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->worship
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>지방</td>
                                    <td>{{ $data->local }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->local
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>해외</td>
                                    <td>{{ $data->overseas }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->overseas
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>연락불가</td>
                                    <td>{{ $data->unable_contact }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->unable_contact
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>장기결석</td>
                                    <td>{{ $data->long_absence }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->long_absence
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>방문자</td>
                                    <td>{{ $data->visit }}명</td>
                                    <td>
                                        {{
                                            round(
                                                $data->visit
                                                /
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
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
                                                ($data->active + $data->inactive == 0 ? 1 : $data->active + $data->inactive) * 100, 2
                                            )
                                        }}%
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
@include('kpi.component.search')
<script>
    function exportExcel() {
        $(".yajra-datatable").table2excel({
            exclude: ".noExl",
            name: "inactive",
            filename: "inactive" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>