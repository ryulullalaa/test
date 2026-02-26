<script type="text/javascript">
    $(function () {
        const permission = {!! $grade !!};
        $('#parish').hide();
        $('#team').hide();
        $('#group').hide();

        if ([1,2,3,4,5,6,7,8,9].includes(permission)) {
            $('#parish').show();
            $('#team').show();
            $('#group').show();
        } else if (permission == 10) {
            $('#team').show();
            $('#group').show();
        } else if (permission == 11) {
            $('#group').show();
        }

        // old value 처리
        const urlParams = new URLSearchParams(window.location.search);
        window.history.replaceState(null, null, window.location.pathname);

        if (urlParams == "") {
            $('#parish').val({!! $affiliation->parish !!});
            $('#team').val({!! $affiliation->team !!});
            $('#group').val({!! $affiliation->group !!});
        } else {
            $('#start').val(urlParams.get('start'));
            $('#end').val(urlParams.get('end'));
            $('#parish').val(urlParams.get('parish'));
            $('#team').val(urlParams.get('team'));
            $('#group').val(urlParams.get('group'));
            $('#identity').val(urlParams.get('identity'));
        }

        // datetimepicker
        var today = new Date();
        $('#datetimepicker7').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            defaultDate: moment(today).subtract(6, 'days').startOf('day'),
        });
        $('#datetimepicker8').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            defaultDate: moment(today).endOf('day'),
            maxDate: moment(today).endOf('day'),
        });

        conditionCheck();
    });

    $('#parish').change(function() {
        if (this.value == 0 || this.value == 4 || this.value == 5) {
            $('#team').val(0);
            $('#group').val(-1);
            $('#team').attr('disabled', true);
            $('#group').attr('disabled', true);
        } else {
            $('#team').attr('disabled', false);
        }
    })

    $('#team').change(function() {
        if (this.value != 0) {
            $('#group').attr('disabled', false);
        } else {
            $('#group').val(-1);
            $('#group').attr('disabled', true);
        }
    })

    function conditionCheck() {
        if ($('#team').css('display') == 'none') {
            $('#group').val(-1);
        }

        if ($('#parish').val() == 0 || $('#parish').val() == 4 || $('#parish').val() == 5) {
            $('#team').val(0);
            $('#group').val(-1);
            $('#team').attr('disabled', true);
            $('#group').attr('disabled', true);
        } else {
            $('#team').attr('disabled', false);
        }

        if ($('#team').val() != 0) {
            $('#group').attr('disabled', false);
        } else {
            $('#group').val(-1);
            $('#group').attr('disabled', true);
        }
    }
</script>
<style>
@media (max-width: 800px) {
    .form-inline input {
        margin: 10px 0;
    }
    .form-inline {
        flex-direction: column;
        align-items: stretch;
    }
    .input-group-text {
        margin-top: 10px;
    }
    .form-group {
        margin: 0.2rem;
    }
    .search-button {
        display: contents;
    }
    .export-button {
        display: none;
    }
}
</style>