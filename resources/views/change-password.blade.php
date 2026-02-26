@section('title', __('홈'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('홈') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form class="form" onsubmit="return false;" novalidate="novalidate">
                    <div class="flex justify-between mb-3">
                        <div class="flex"><button type="button" class="btn btn-dark" onclick="window.history.back();">Back</button></div>
                        <div class="flex"><button type="submit" class="btn btn-primary">Save</button></div>
                    </div>
                    <div class="container mt-6">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">새 비밀번호</label>
                                    <div class="col-sm-8">
                                    <input type="password" class="form-control text-center" id="new_password" name="new_password" pattern=".{4,}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">비밀번호 확인</label>
                                    <div class="col-sm-8">
                                    <input type="password" class="form-control text-center" id="password_confirm" name="password_confirm" pattern=".{4,}" required>
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
<script>
    $('.form').validate();

    (function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('form');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    if ($('#new_password').val() == $('#password_confirm').val()) {
                        save();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '비밀번호를 확인해주세요.'
                        })
                    }
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
    })();

    function save() {
        var result = {
            'new_password': $('#new_password').val(),
            'password_confirm': $('#password_confirm').val(),
        };

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('home.change-password.save') }}",
            type: 'POST',
            data: {'result': result},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: '비밀번호가 변경되었습니다.'
                }).then((result) => {

                })
            }
        });
    }
</script>
