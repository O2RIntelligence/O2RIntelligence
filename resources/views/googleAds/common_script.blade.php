<script>
    $("#account-filter").select2({
        placeholder: 'Select'
    })

    $(document).on("click", "#show-daterange", function(){
        $("#date-range-area").slideToggle('slow');
    })

    $("#start_date").datepicker({
         format: 'yyyy-mm-dd',
    });
    $("#end_date").datepicker({
         format: 'yyyy-mm-dd',
    });

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
</script>