<script>
    $("#account-filter").select2({
        placeholder: 'Select'
    })

    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    // $(document).on("click", "#show-daterange", function(){
    //     $("#date-range-area").slideToggle('slow');
    // })

    // $("#start_date").datepicker({
    //      format: 'yyyy-mm-dd',
    // });
    // $("#end_date").datepicker({
    //      format: 'yyyy-mm-dd',
    // });

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