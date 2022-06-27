<script>
    $("#account-filter").select2({
        placeholder: 'Select'
    })

    $(document).on("click", "#show-daterange", function(){
        $("#date-range-area").slideToggle('slow');
    })
</script>