@section('title', __('출석관리'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('출석관리') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-3">
                        <div class="flex"><button type="button" class="btn btn-dark" onclick="window.history.back();">Back</button></div>
                        <div class="flex"><p class="h4 font-weight-bold">{{ $report_title }}</p></div>
                        <div class="flex"><button type="button" class="btn btn-primary" onclick="save();">Save</button></div>
                    </div>
                    <table class="table table-bordered yajra-datatable w-100">
                        <thead>
                            <tr>
                                <th>이름</th>
                                <th>주일(낮)</th>
                                <th>주일(저녁)</th>
                                <th>수요</th>
                                <th>토새깨</th>
                                <th>새벽(회)</th>
                                <th>성경읽기(장)</th>
                                <th>훈련참가</th>
                                <th>상황보고</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    input[type="text"] {
        text-align: center;
        border: 0px solid;
    }
    select.form-control {
        border: 0px solid;
    }
</style>
<script type="text/javascript">
    var table;

    function save() {
        var result = [];

        for (var i=0; i<table.data().length; i++) {
            table.row(i).data().worship_am = $('#worship_am' + i + ' option:selected').val();
            table.row(i).data().worship_pm = $('#worship_pm' + i + ' option:selected').val();
            table.row(i).data().worship_wed = $('#worship_wed' + i + ' option:selected').val();
            table.row(i).data().worship_sat = $('#worship_sat' + i + ' option:selected').val();
            table.row(i).data().worship_dawn = $('#worship_dawn' + i).val();
            table.row(i).data().read_bible = $('#read_bible' + i).val();
            table.row(i).data().training = $('#training' + i + ' option:selected').val();
            table.row(i).data().situation = $('#situation' + i).val();

            result.push(table.row(i).data());
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('worship.save', $report_id) }}",
            type: 'POST',
            data: {'result': result},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: '저장되었습니다.'
                })
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: '저장에 실패했습니다.'
                })
            }
        });
    }

    $(function () {
        table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            pageLength: 50,
            ordering: false,
            ajax: "{{ route('worship.spirituality', $report_id) }}",
            columns: [
                {data: 'member.name', name: 'member.name'},
                {data: 'worship_am', name: 'worship_am'},
                {data: 'worship_pm', name: 'worship_pm'},
                {data: 'worship_wed', name: 'worship_wed'},
                {data: 'worship_sat', name: 'worship_sat'},
                {data: 'worship_dawn', name: 'worship_dawn'},
                {data: 'read_bible', name: 'read_bible'},
                {data: 'training', name: 'training'},
                {data: 'situation', name: 'situation'},
            ],
            columnDefs: [{
                "targets": "_all",
                "className": "text-center align-middle",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).css({
                        'min-width': '80px',
                        'padding': '0px',
                    });
                }
            },{
                "targets": 1,
                "data": 'worship_am',
                "render": function(data, type, full, meta) {
                    var v1, v2;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="worship_am${meta['row']}" class="form-control">
                                <option value="0" selected></option>
                                <option value="1"${v1}>참석</option>
                                <option value="2"${v2}>불참</option>
                            </select>`;
                },
            },{
                "targets": 2,
                "data": 'worship_pm',
                "render": function(data, type, full, meta) {
                    var v1, v2;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="worship_pm${meta['row']}" class="form-control">
                                <option value="0" selected></option>
                                <option value="1"${v1}>참석</option>
                                <option value="2"${v2}>불참</option>
                            </select>`;
                },
            },{
                "targets": 3,
                "data": 'worship_wed',
                "render": function(data, type, full, meta) {
                    var v1, v2;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="worship_wed${meta['row']}" class="form-control">
                                <option value="0" selected></option>
                                <option value="1"${v1}>참석</option>
                                <option value="2"${v2}>불참</option>
                            </select>`;
                },
            },{
                "targets": 4,
                "data": 'worship_sat',
                "render": function(data, type, full, meta) {
                    var v1, v2;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="worship_sat${meta['row']}" class="form-control">
                                <option value="0" selected></option>
                                <option value="1"${v1}>참석</option>
                                <option value="2"${v2}>불참</option>
                            </select>`;
                },
            },{
                "targets": 5,
                "data": 'worship_dawn',
                "render": function(data, type, full, meta) {
                    return `<input type="text" class="w-100" id="worship_dawn${meta['row']}" value="${data ?? ''}" maxlength="4" size="4">`;
                },
            },{
                "targets": 6,
                "data": 'read_bible',
                "render": function(data, type, full, meta) {
                    return `<input type="text" class="w-100" id="read_bible${meta['row']}" value="${data ?? ''}" maxlength="4" size="4">`;
                },
            },{
                "targets": 7,
                "data": 'training',
                "render": function(data, type, full, meta) {
                    var v1, v2, v3;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        case 3: v3 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="training${meta['row']}" class="form-control">
                                <option value="0" selected></option>
                                <option value="1"${v1}>LT</option>
                                <option value="2"${v2}>PLT</option>
                                <option value="3"${v3}>새큼터</option>
                            </select>`;
                },
            },{
                "targets": 8,
                "data": 'situation',
                "render": function(data, type, full, meta) {
                    return `<input type="text" class="w-100" id="situation${meta['row']}" value="${data ?? ''}">`;
                },
            }]
        });
    });
</script>