<script>
    $("#account-filter").select2({
        placeholder: 'Select'
    })

    //Date range as a button
    $('#daterange-btn').daterangepicker();

    $(".change-period").on("click", function() { 
        $(".time-periods button").removeClass("active");
        $(this).addClass("active");
        var period = $(this).data('period');
        $("input[name=date_period]").val(period);
        if (period != 'custom') {
            $(".custom-daterange").hide();
            window["calculate_dates"]();
            runReportFunction();
        }
    });


    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', startDate);
    }).on('clearDate', function (selected) {
        $('#end_date').datepicker('setStartDate', null);
    });

    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function (selected) {
        $('#start_date').datepicker('setEndDate', null);
    });
</script>