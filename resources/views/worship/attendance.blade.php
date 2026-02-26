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
                    <table id="table" class="table table-bordered yajra-datatable w-100">
                        <thead>
                            <tr>
                                <th>이름</th>
                                <th>출석</th>
                                <th>결석사유</th>
                                <th>심방수단</th>
                                <th>심방결과</th>
                                <th>심방내용</th>
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
            table.row(i).data().attendance = $('#attendance' + i + ' option:selected').val();
            table.row(i).data().reason = $('#reason' + i + ' option:selected').val();
            table.row(i).data().visit_way = $('#visit_way' + i + ' option:selected').val();
            table.row(i).data().visit_result = $('#visit_result' + i + ' option:selected').val();
            table.row(i).data().reason_detail = $('#reason_detail' + i).val();

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
            ajax: "{{ route('worship.attendance', $report_id) }}",
            columns: [
                {data: 'member.name', name: 'member.name'},
                {data: 'attendance', name: 'attendance'},
                {data: 'reason', name: 'reason'},
                {data: 'visit_way', name: 'visit_way'},
                {data: 'visit_result', name: 'visit_result'},
                {data: 'reason_detail', name: 'reason_detail'},
            ],
            columnDefs: [{
                "targets": "_all",
                "className": "text-center align-middle",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).css({'min-width': '80px', 'padding': '0px'});
                }
            },{
                "targets": 1,
                "data": 'attendance',
                "render": function(data, type, full, meta) {
                    var v1, v2, v3;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        case 3: v3 = ' selected'; break;
                        default: break;
                    }

                    $('#table').on('draw.dt', function () {
                        if (data != 3) {
                            $('#reason'+meta['row']).attr('disabled', true);
                            $('#visit_way'+meta['row']).attr('disabled', true);
                            $('#visit_result'+meta['row']).attr('disabled', true);
                            $('#reason_detail'+meta['row']).attr('disabled', true);
                        } else {
                            $('#reason'+meta['row']).attr('disabled', false);
                            $('#visit_way'+meta['row']).attr('disabled', false);
                            $('#visit_result'+meta['row']).attr('disabled', false);
                            $('#reason_detail'+meta['row']).attr('disabled', false);
                        }
                    });

                    return `<select id="attendance${meta['row']}" class="form-control" onchange="notAttending(this);">
                                <option value="0" selected></option>
                                <option value="1"${v1}>온라인</option>
                                <option value="2"${v2}>현장</option>
                                <option value="3"${v3}>결석</option>
                            </select>`;
                },
            },{
                "targets": 2,
                "data": 'reason',
                "render": function(data, type, full, meta) {
                    var v1, v2, v3, v4, v5, v6, v7;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        case 3: v3 = ' selected'; break;
                        case 4: v4 = ' selected'; break;
                        case 5: v5 = ' selected'; break;
                        case 6: v6 = ' selected'; break;
                        case 7: v7 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="reason${meta['row']}" class="form-control" disabled="disabled">
                                <option value="0" selected></option>
                                <option value="1"${v1}>회사일</option>
                                <option value="2"${v2}>학교일</option>
                                <option value="3"${v3}>아픔</option>
                                <option value="4"${v4}>출장</option>
                                <option value="5"${v5}>알바</option>
                                <option value="6"${v6}>집안사정</option>
                                <option value="7"${v7}>기타</option>
                            </select>`;
                },
            },{
                "targets": 3,
                "data": 'visit_way',
                "render": function(data, type, full, meta) {
                    var v1, v2;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="visit_way${meta['row']}" class="form-control" disabled="disabled">
                                <option value="0" selected></option>
                                <option value="1"${v1}>전화</option>
                                <option value="2"${v2}>문자</option>
                            </select>`;
                },
            },{
                "targets": 4,
                "data": 'visit_result',
                "render": function(data, type, full, meta) {
                    var v1, v2, v3;

                    switch (data) {
                        case 1: v1 = ' selected'; break;
                        case 2: v2 = ' selected'; break;
                        case 3: v3 = ' selected'; break;
                        default: break;
                    }

                    return `<select id="visit_result${meta['row']}" class="form-control" disabled="disabled">
                                <option value="0" selected></option>
                                <option value="1"${v1}>심방완료</option>
                                <option value="2"${v2}>연락안됨</option>
                                <option value="3"${v3}>심방못함</option>
                            </select>`;
                },
            },{
                "targets": 5,
                "data": 'reason_detail',
                "render": function(data, type, full, meta) {
                    return `<input class="w-100" type="text" id="reason_detail${meta['row']}" value="${data ?? ''}" maxlength="200" disabled="disabled">`;
                },
            }]
        });
    });

    function notAttending(event) {
        var id = event.id.replace(/[^0-9]/g,'');

        if (event.value != 3) {
            $('#reason'+id).val(0);
            $('#visit_way'+id).val(0);
            $('#visit_result'+id).val(0);
            $('#reason_detail'+id).val(null);
            $('#reason'+id).attr('disabled', true);
            $('#visit_way'+id).attr('disabled', true);
            $('#visit_result'+id).attr('disabled', true);
            $('#reason_detail'+id).attr('disabled', true);
        } else {
            $('#reason'+id).attr('disabled', false);
            $('#visit_way'+id).attr('disabled', false);
            $('#visit_result'+id).attr('disabled', false);
            $('#reason_detail'+id).attr('disabled', false);
        }
    }
</script>