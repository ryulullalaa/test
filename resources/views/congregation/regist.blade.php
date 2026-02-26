@section('title', __('재적관리'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('재적관리') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form class="form" onsubmit="return false;">
                    <div class="flex justify-between mb-3">
                        <div class="flex"><button type="button" class="btn btn-dark" onclick="window.history.back();">Back</button></div>
                        <div class="flex"><button type="submit" class="btn btn-primary">Save</button></div>
                    </div>
                    <div class="container mt-6">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">이 름</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-center" id="name" name="name" pattern="^[가-힣]+$" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">교 구</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-center" id="parish" name="parish" pattern="[0-9]{0,3}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">팀</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-center" id="team" name="team" pattern="[0-9]{0,3}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">그 룹</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-center" id="group" name="group" pattern="[0-9]{0,3}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">기 수</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-center" id="inception" name="inception" pattern="[0-9]{0,3}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">신 분</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" id="identity" name="identity">
                                        <option value="1">직장인</option>
                                        <option value="2">학생</option>
                                        <option value="3">취업준비</option>
                                        <option value="4">입시준비</option>
                                        <option value="5">군지체</option>
                                        <option value="6">해외지체</option>
                                        <option value="7">기타</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">직 책</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" id="grade" name="grade">
                                        <option value="11" selected>{{ $grades->find(11)->grade }}</option>
                                        <option value="12">{{ $grades->find(12)->grade }}</option>
                                        <option value="13">{{ $grades->find(13)->grade }}</option>
                                        <option value="14">{{ $grades->find(14)->grade }}</option>
                                        <option value="15">{{ $grades->find(15)->grade }}</option>
                                        <option value="16">{{ $grades->find(16)->grade }}</option>
                                        <option value="17">{{ $grades->find(17)->grade }}</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">성 별</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" id="sex" name="sex">
                                        <option value="1">남</option>
                                        <option value="2">여</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">관리번호</label>
                                    <div class="col-sm-8">
                                    <input type="tel" class="form-control text-center" id="phone_number" name="phone_number" placeholder="전화번호 뒤 4자리" pattern="[0-9]{4}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">활성상태</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" id="active">
                                        <option value="1">활동</option>
                                        <option value="0">비활동</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="inactive-group" style="display: none;">
                                    <label class="col-sm-4 col-form-label font-weight-bold">비활성사유</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" id="inactive">
                                        <option value="1">군지체</option>
                                        <option value="2">래사랑</option>
                                        <option value="3">방문자</option>
                                        <option value="4">주일예배</option>
                                        <option value="5">지방</option>
                                        <option value="6">해외</option>
                                        <option value="7">기타</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    #grade, #sex, #active, #inactive, #identity, #is_admin {
        text-align-last: center;
    }
</style>
<script>
    $(document).ready(function() {
        if ({{ $auth->grade_id }} != 4) {
            $('#is_admin').attr('disabled', 'disabled');
        }
    });

    $('.form').validate({
        messages: {
            name: '이름을 입력해 주세요.',
            phone_number: '전화번호 뒤 4자리를 입력해 주세요.',
            team: '팀을 입력해 주세요.',
            group: '그룹을 입력해 주세요.',
        },
    });

    (function() {
        'use strict';
        var grade = document.getElementById('grade');
        grade.addEventListener('change', (event) => {
            if (event.target.value <= 8) {
                $('#team').val('');
                $('#group').val('');
                $('#team').attr('readonly', true);
                $('#group').attr('readonly', true);
                $('#team').removeAttr('required');
                $('#group').removeAttr('required');
            } else {
                $('#team').removeAttr('readonly');
                $('#group').removeAttr('readonly');
                $('#team').attr('required', true);
                $('#group').attr('required', true);
            }
        });

        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('form');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        save();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            var active = document.getElementById('active');
            active.addEventListener('change', (event) => {
                if (event.target.value == 1) {
                    $('#inactive-group').hide();
                } else {
                    $('#inactive-group').show();
                }
            });
        }, false);
    })();

    function save() {
        var result = {
            'name': $('#name').val(),
            'parish': $('#parish').val(),
            'team': $('#team').val(),
            'group': $('#group').val(),
            'inception': $('#inception').val(),
            'identity': $('#identity').val(),
            'grade_id': $('#grade').val(),
            'sex': $('#sex').val(),
            'phone_number': $('#phone_number').val(),
            'active': $('#active').val(),
            'inactive': $('#active').val() != 0 ? null : $('#inactive').val(),
        };

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('congregation.store') }}",
            type: 'POST',
            data: {'result': result},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: '저장되었습니다.'
                })
            },
            error: function(request) {
                Swal.fire({
                    icon: 'error',
                    title: JSON.parse(request.responseText).error,
                })
            },
        });
    }
</script>