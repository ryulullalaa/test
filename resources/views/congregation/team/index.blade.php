@extends('congregation.index')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <table class="table table-bordered yajra-datatable w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>이름</th>
                            <th>기수</th>
                            <th>직책</th>
                            <th>교구</th>
                            <th>팀</th>
                            <th>그룹</th>
                            <th>성별</th>
                            <th>관리번호</th>
                            <th>신분</th>
                            <th>활동상태</th>
                            <th>비활동사유</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    input[type="text"] {
        text-align: center;
        border: 0px solid;
    }
    select.form-control {
        border: 0px solid;
    }
</style>
@endsection

@section('script')
<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            aLengthMenu: [[100, 500, 1000, -1], [100, 500, 1000, "All"]],
            pageLength: 100,
            ajax: "{{ route('congregation.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false
                },
                {
                    data: 'inception',
                    name: 'inception'
                },
                {
                    data: 'grade_id',
                    name: 'grade_id'
                },
                {
                    data: 'affiliation.parish',
                    name: 'affiliation.parish'
                },
                {
                    data: 'affiliation.team',
                    name: 'affiliation.team'
                },
                {
                    data: 'affiliation.group',
                    name: 'affiliation.group'
                },
                {
                    data: 'sex',
                    name: 'sex'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    data: 'identity',
                    name: 'identity'
                },
                {
                    data: 'active',
                    name: 'active'
                },
                {
                    data: 'inactive',
                    name: 'inactive'
                },
            ],
            columnDefs: [{
                "targets": "_all",
                "className": "text-center align-middle",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).css({'min-width': '80px', 'padding': '0px'});
                }
            },{
                "targets": 9,
                "data": 'identity',
                "render": function(data, type, full, meta) {
                    switch (data) {
                        case 1: data = '직장인'; break;
                        case 2: data = '학생'; break;
                        case 3: data = '취업준비'; break;
                        case 4: data = '입시준비'; break;
                        case 5: data = '입시준비'; break;
                        case 6: data = '입시준비'; break;
                        case 7: data = '기타'; break;
                        default: break;
                    }

                    return data;
                },
            }]
        });
    });
</script>
@endsection