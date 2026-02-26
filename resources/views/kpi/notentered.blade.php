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
                        <form class="form-inline" action="{{ route('kpi.notentered') }}">
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
                                <th>그룹별 미입력</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableData->sortBy(['parish', 'team', 'group']) as $data)
                                @if ($data->notEntered)
                                <tr>
                                    <td>
                                        @if (!is_null($data->parish)) {{ $data->parish }}교구 @endif
                                        @if (!is_null($data->team)) {{ $data->team }}팀 @endif
                                        @if (!is_null($data->group)) {{ $data->group }}그룹 @endif
                                    </td>
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
    .search-button {
        display: contents;
    }
    .export-button {
        display: none;
    }
</style>
<script>
    function exportExcel() {
        $(".yajra-datatable").table2excel({
            exclude: ".noExl",
            name: "notentered",
            filename: "notentered" +'.xls',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
</script>