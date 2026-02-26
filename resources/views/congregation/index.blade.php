@section('title', __('재적관리'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('재적관리') }}
        </h2>
    </x-slot>

    @yield('content')
</x-app-layout>
<!-- <script src="//code.jquery.com/jquery-3.5.1.js"></script> -->
<!-- <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
<script src="//cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
<script src="//cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<style>
    .dt-buttons {
        display: inline-block;
        font-weight: 400;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: #33aa88;;
        border: 1px solid #ccffcc;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    table {
        white-space: nowrap;
    }
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting:after {
        top: 0.2em;
    }
    .sorting_asc:before, .sorting_asc:after,
    .sorting_desc:before, .sorting_desc:after {
        top: 0.2em;
    }
</style>
@yield('style')
@yield('script')