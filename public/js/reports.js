function addDropdownValue(element) {
    const operation = $(element).attr('data-operation');
    const colIdx = $(element).attr('data-idx');
    const title = $(element).attr('data-title');
    $(element).parents('.dropdown').find('[name="selected_filter_operation"]').val(operation);
    $(element).parents('.dropdown').find('[name="selectedColIndex"]').val(colIdx);
    $('#filterColumn').append('<option selected>' + title + '</option>')
}

function getDropdownValue(element) {
    return $(element).parent().find('[name="selected_filter_operation"]').val();
}

function clearDropdownValue(element) {
    const title = $(element).attr('data-title');
    $(element).parents('.dropdown').find('[name="selected_filter_operation"]').val('');
    $('#input' + title).val('');
}

(function ($) {

    var filter_fields = {
        'date': 'Date',
        'seat': 'Seat',
        'hour': 'Hours',
        'channel': 'Channel',
        'source': 'Source',
        'sid': 'Sid',
        'advertiser': 'Advertiser',
        'campaign': 'Campaign',
        'campaign_integration_type': 'Campaign Integration Type',
        'campaign_tag_type': 'Campaign Tag Type',
        'adserver': 'Ad Server',
        'domain': 'Domain',
        'domain_top': 'Top Domain',
        'player_size': 'Player Size',
        'country': 'Country',
        'region': 'Region',
        'city_name': 'City',
        'os': 'Operating System',
        'useragent': 'User Agent',
        'useragent_version': 'User Agent Version',
        'environment': 'Environment',
        'app_name': 'App Name',
        'app_bundle': 'Bundle ID',
        'ad_type': 'Ad Type',
        'month': 'Month',
        'device_type': 'Device Type',
        'source_type': 'Source Type',
        'ad_requests': 'Ad Requests',
        'ad_opportunities': 'Add Opportunities',
        'impressions_good': 'Impressions',
        'revenue_channel': 'Channel Revenue',
        'revenue_total': 'Toal Revenue',
        'ecpm': 'eCPM, USD',
        'ecpm_channel': 'eCPM Channel',
        'fill_rate_ad_opportunities': 'Fill Rate (Ad Ops)',
        'fill_rate_ad_requests': 'Fill Rate (Ad Req)',
        'scoring_pixalate_s2s_sas_request': 'Scanned Requests'

    };
    var showIdFields = ['Channel', 'Source', 'Campaign', 'Advertiser'];
    var sortingIndexs = ['date', 'hour', 'channel', 'source', 'sid', 'advertiser', 'campaign', 'campaign_integration_type', 'campaign_tag_type', 'adserver', 'domain', 'domain_top', 'player_size', 'country', 'region', 'city_name', 'os', 'useragent', 'useragent_version', 'environment', 'app_name', 'app_bundle', 'ad_type', 'month', 'device_type', 'source_type', 'ad_opportunities', 'ad_requests', 'ecpm', 'ecpm_channel', 'fill_rate_ad_opportunities', 'fill_rate_ad_requests', 'impressions_good', 'scoring_pixalate_s2s_sas_request', 'revenue_channel', 'revenue_total'];
    var metrics = [];
    var table_settings = {
        limit: 1000000,
        page: 1,
        strategy: 'last-collection',
        type: 'ssp_statistic',
        tz: 'GMT',
        is_rtb: "not_apply"
    }
    var ReportDatatable;
    var resizeActive = true;

    function start_loader() {
        $(".loader").show();
    }

    function hide_loader() {
        $(".loader").hide();
    }

    $(function () {
        ReportFieldsCheckboxes();
        ReportFieldsInput();
        ReportMetricSelect();
    });

    $(document).on('change', ".date-field", function () {
        window["calculate_dates"]($(this).val());
        if ($(this).val() == 'custom') {
            $(".custom-daterange").show();
        } else {
            $(".custom-daterange").hide();
        }
    });

    function ReportFieldsCheckboxes() {
        for (const key of Object.keys(filter_fields)) {
            if (['ad_opportunities', 'ad_requests', 'ecpm', 'ecpm_channel', 'fill_rate_ad_opportunities', 'fill_rate_ad_requests', 'impressions_good', 'scoring_pixalate_s2s_sas_request', 'revenue_channel', 'revenue_total', 'seat'].includes(key))
                continue;

            $(".report-fields").append(
                $("<label>")
                    .attr('class', 'btn btn-primary')
                    .append(
                        $("<input>")
                            .attr('class', 'selected-fields')
                            .attr('data-field', key)
                            .attr('type', 'checkbox')
                            .attr('name', `fields[${key}]`)
                    )
                    .append(filter_fields[key])
            )
        }
        $(".report-fields").find('label:first-child').click();
    }

    async function getFieldOptions(fieldName) {

        if (fieldName == 'month') return [];

        var selected_seats = $("select[name=seats]").val();
        var start_date = $("input[name=start_date]").val();
        var end_date = $("input[name=end_date]").val();

        var sourceParams = {
            "date_from": start_date,
            "date_to": end_date,
            "limit": 10000,
            "page": 1,
            "report": "date," + fieldName,
            "type": 'ssp_statistic',
            "tz": 'GMT'
        };

        FieldValues = [];
        for (let index = 0; index < selected_seats.length; index++) {
            // init seat
            const seatId = selected_seats[index];
            const seat = window["seats"][seatId];

            var FieldRequest;

            try {
                FieldRequest = await seat.api.request(sourceParams, "dictionary/" + fieldName);
            } catch (error) {
                if (error == 401) swal('Unauthenticated',seat['name'] + ' API not authenticated','error');//top.location.reload();
                continue;
            }

            if (typeof FieldRequest != 'object' || FieldRequest.success == false)
                return [];

            FieldValues = [...FieldValues, ...FieldRequest.data.map(m => ({ ...m, seatId }))];
        }

        return FieldValues.filter((v, i, a) => a.findIndex(t => (t.id === v.id)) === i);
    }

    $(document).on("change", '.selected-fields', function () {
        var fieldName = $(this).data("field");
        if (fieldName == 'date' || fieldName == 'month') return;

        if (this.checked) {
            FieldsSelectOptions(fieldName);
        } else {
            $("#field_" + fieldName).find('select').val(null).trigger("change");
            $("#field_" + fieldName).hide();
        }
    });


    $(document).on("click", '.delete-metric-row', function () {
        $("#metric-" + $(this).data('metric-id')).remove();
    });

    $(document).on("click", '.add-metric', function () {

        var metric = $(".clonable-metric").clone();
        metric.removeClass("clonable-metric");
        metric.removeAttr("style");
        var index = $(".metric-row").length - 1;
        metric.attr('id', 'metric-' + index);
        metric.find('select[class="metric"]').attr('name', 'metric_keys[' + index + ']');
        metric.find('select.operation').attr('name', 'metric_operations[' + index + ']');
        metric.find('input').attr('name', 'metric_values[' + index + ']');
        metric.find('.delete-metric-row').data('metric-id', index);

        $(".metric-container").append(metric);

        $("select[name=" + '"metric_keys[' + index + ']"' + "]").select2({
            width: "100%"
        });
    });




    async function FieldsSelectOptions(fieldName) {
        var options = await getFieldOptions(fieldName);
        var selectBox = $("#field_" + fieldName).find('select');
        for (let index = 0; index < options.length; index++) {
            const option = options[index];
            selectBox.append(
                $("<option>").data('seat-id', option.seatId).attr("value", option.id).text(option.name)
            );
        }
        selectBox.select2("destroy");
        selectBox.select2({
            width: "100%",
            closeOnSelect: false
        });
        $("#field_" + fieldName).show();
    }

    function ReportFieldsInput() {
        for (const fieldName of Object.keys(filter_fields)) {
            if ($(".field-inputs #field_" + fieldName).length) continue;

            var fieldInput = $(".clonable-field").clone();
            fieldInput.removeClass('clonable-field');
            fieldInput.attr('id', 'field_' + fieldName)
            fieldInput.find('select').attr('name', 'filters[' + fieldName + ']');
            fieldInput.find('.text-center label').text("Select " + filter_fields[fieldName] + ":");
            fieldInput.find('.summ').attr('name', 'filters_summary[' + fieldName + ']');
            fieldInput.find('.exclude').attr('name', 'filters_exclude[' + fieldName + ']');
            $(".field-inputs .fields-container").append(fieldInput);
            $('select[name="filters[' + fieldName + ']"]').select2({ closeOnSelect: false });
        }

    }

    async function ReportMetricSelect() {

        try{
            var selected_seats = $("select[name=seats]").val();

            let MetricRequest;

            try {
                MetricRequest = await window["seats"][selected_seats[0]].api.request({}, "metrics", window["ADTELLIGENT_START_URL"] + "/api/statistics/ssp_statistic");
            } catch (error) {
                seat = window["seats"][selected_seats[0]];
                if (error == 401) swal('Unauthenticated',seat['name'] + ' API not authenticated','error');//top.location.reload();
            }
            if (!MetricRequest) return 0;
            metrics = MetricRequest.data.metrics;
            for (const key of Object.keys(metrics)) {
                const metric = metrics[key];
                if (metric.is_filterable) {
                    $("select.metric").append(
                        $("<option>").attr('value', metric.system_name).text(metric.name)
                    );
                }
            }
        } catch (e) {
            console.log("Error: "+e);
            swal(e.name,e.message,"error");
            hide_loader();
        }
    }

    $(".run-report").on('click', function () {
        buildRequest();
    });


    async function buildRequest() {
        start_loader();

        var params = { report: [], fields: ['ad_requests', 'ad_opportunities', 'impressions_good', 'scoring_pixalate_s2s_sas_request', 'revenue_channel', 'ecpm_channel', 'revenue_total', 'ecpm', 'fill_rate_ad_opportunities', 'fill_rate_ad_requests'], };
        // var seat_filters = {};
        // requested fields
        $('.selected-fields').map(function () {
            if ($(this).prop("checked")) {
                var name = $(this).attr('name').replace('fields[', '').replace(']', '');
                params.fields.push(name);
                params.report.push(name);
            }
        });
        params.fields = params.fields.join(',');
        params.report = params.report.join(',');
        // date filter
        params.date_from = $("input[name=start_date]").val();
        params.date_to = $("input[name=end_date]").val();
        // custom filters
        $('.filters').map(function () {
            if ($(this).val() && $(this).val().length) {
                var name = $(this).attr('name').replace('filters[', '').replace(']', '');
                $.each($(this).select2('data'), function (key, item) {
                    if (name in params == false) params[name] = [];
                    params[name].push(item.id);
                    // var seatId = $(item.element).data('seat-id')
                    // if(seatId in seat_filters == false) seat_filters[seatId] = [];

                });
                if (name in params) params[name] = params[name].join(',');
            }
        });

        params.exclude_filter = [];
        $('.exclude').map(function () {
            if ($(this).prop('checked')) {
                var name = $(this).attr('name').replace('filters_exclude[', '').replace(']', '');
                params.exclude_filter.push(name);
            }
        });
        params.exclude_filter = params.exclude_filter.join(',');


        params.summary_filter = [];
        $('.summ').map(function () {
            if ($(this).prop('checked')) {
                var name = $(this).attr('name').replace('fields[', '').replace(']', '');
                params.summary_filter.push(name);
            }
        });
        params.summary_filter = params.summary_filter.join(',');


        // metrics
        const metric_length = $(".metric").length - 1;
        if (metric_length)
            params._filter = [];

        for (let index = 0; index < metric_length; index++) {
            const key = $("select[name='metric_keys[" + index + "]']").val();
            const op = $("select[name='metric_operations[" + index + "]']").val();
            const value = $("input[name='metric_values[" + index + "]']").val();
            params._filter.push(key + " " + op + " " + value);
        }
        if ("_filter" in params) params._filter = params._filter.join(" and ");

        params = { ...params, ...table_settings };

        var selected_seats = $("select[name=seats]").val();
        var data = [];
        var totals = {};
        var total_rows = {};

        for (let index = 0; index < selected_seats.length; index++) {
            // init seat
            const seatId = selected_seats[index];
            const seat = window["seats"][seatId];

            var FieldRequest;

            try {
                FieldRequest = await seat.api.request(params);
            } catch (error) {
                if (error == 401) swal('Unauthenticated',seat['name'] + ' API not authenticated','error'); //top.location.reload();
                continue;
            }

            FieldRequest.data = FieldRequest.data.map(m => ({ ...m, seat: seat.name }));
            FieldRequest.data = FieldRequest.data.filter(m => m.ad_requests != 0);

            data = [...data, ...FieldRequest.data];
            totals = "total_rows" in totals == false ? FieldRequest.totals : _.mergeWith({}, totals, FieldRequest.totals, _.add);

        }

        if (data.length) {
            // rows = _(data).groupBy(record => params.report.split(',').map(field => typeof record[field] == 'object' ? record[field].id : record[field]))
            //     .map((objs, key) => {
            //         let resp = {};
            //         Object.keys(objs[0]).map(field => {
            //             resp[field] = _.isNumber(objs[0][field]) ? _.sumBy(objs, field) : objs[0][field];
            //         });
            //         return resp;
            //     })
            //     .value();

            if ($.fn.DataTable.isDataTable('#report')) {
                $('#report').DataTable().destroy();
            }

            const table = $("table#report");
            table.find('thead').html('<tr></tr>');
            table.find('tbody').html('');
            table.find('tfoot').html('<tr class="info"></tr>');
            const thead = table.find('thead > tr');
            const tbody = table.find('tbody');
            const tfoot = table.find('tfoot > tr');
            var columns = Object.keys(data[0]);

            columns = columns.sort((a, b) => sortingIndexs.findIndex(m => m == a) - sortingIndexs.findIndex(m => m == b));
            for (const column of columns) {
                const columnName = column in filter_fields ? filter_fields[column] : column;
                thead.append($("<th>").text(columnName));
                if (showIdFields.includes(columnName))
                    thead.append($("<th>").text(columnName.substring(0, 3) + " ID"));


            }

            let calculateNet = false;
            if (columns.includes("environment") && window["user_role"] == 'admin') {
                calculateNet = true;
                thead.append($("<th>").text("Net Revenue"));
            }
            const showIdFieldsLower = showIdFields.map(m => m.toLowerCase());

            for (let index = 0; index < data.length; index++) {
                const row = data[index];
                const arrangedCols = Object.keys(row).sort((a, b) => sortingIndexs.findIndex(m => m == a) - sortingIndexs.findIndex(m => m == b));
                let tr = $("<tr>");
                for (col of arrangedCols) {
                    let colValue = _.isNumber(row[col]) ? window["formatMoney"](row[col], 2) : (typeof row[col] == 'object' ? row[col].name : row[col]);
                    tr.append($("<td>").text(colValue));
                    if (showIdFieldsLower.includes(col) && typeof row[col] == 'object')
                        tr.append($("<td>").text(row[col].id));

                }

                if (calculateNet) {
                    const netIncome = getNetIncome(row);
                    tr.append($("<td>").text(window["formatMoney"](netIncome, 2)));
                }

                tbody.append(tr);
            }
            delete totals.total_rows;
            totals.seat = "Totals";
            const arrangedFooter = Object.keys(totals).sort((a, b) => sortingIndexs.findIndex(m => m == a) - sortingIndexs.findIndex(m => m == b));
            for (const foot of arrangedFooter) {
                let colValue = totals[foot] != null && _.isNumber(totals[foot]) ? window["formatMoney"](totals[foot], 2) : totals[foot];
                // if (foot == 'date') colValue = 'Totals';
                if (totals == 0.00) colValue = '-';
                tfoot.append($("<td>").text(colValue));
                if (showIdFieldsLower.includes(foot))
                    tfoot.append($("<td>").text("-"));
            }

            if (calculateNet) {
                tfoot.append($("<td>").text(""));
            }


            $('#report thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#report thead');

            var firstDraw = true;
            ReportDatatable = $('table#report').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength', 'colvis', 'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                orderCellsTop: true,
                colReorder: true,
                "order": [
                    [arrangedFooter.length - 1, "desc"]
                ],
                "drawCallback": function (settings) {
                    $('#report_wrapper thead th').resizable();
                    if (calculateNet) {
                        var api = this.api();
                        var index = api.columns().count() - 1;
                        $(api.column(index).footer()).html(window["formatMoney"](api.column(index).data().sum(), 2));
                    }

                },
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            function filterData(value) {

                            }



                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            var type = 'text';
                            if (title == 'Date') type = 'date'
                            if (title == 'Month') type = 'month'
                            $(cell).html(`
                                <div style="display: flex;" data-contain="filter">
                                    <input data-name="query" type="${type}" id="input${title.replace(/[^a-zA-Z0-9]/g, '-')}" placeholder="${title}" />
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-filter"></i>
                                        </button>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item text-danger" data-action="clear" style="display: block;" onclick="clearDropdownValue(this)" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}">
                                            Clear
                                            </a>
                                            <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="equal">Is equal to</a>
                                            <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="not_equal">Is not equal to</a>
                                            <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="greater_than">Greater than</a>
                                            <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="greater_equal">Greater equal</a>
                                            <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="less_than">Less than</a>
                                            <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="less_equal">Less equal</a>
                                        </div>
                                        <input type="hidden" id="operator${title.replace(/[^a-zA-Z0-9]/g, '-')}" name="selected_filter_operation" />
                                        <input type="hidden" id="colIndex${title.replace(/[^a-zA-Z0-9]/g, '-')}" name="selectedColIndex" />
                                    </div>
                                </div>
                            `);

                            // On every keypress in this input
                            $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    /*e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    const filterCaseValue = getDropdownValue(this);
                                    console.log("filterCaseValue", filterCaseValue)

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value

                                    var regexr = '({search})';
                                    let searchRegx = this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '';

                                    if (filterCaseValue && filterCaseValue !== '') {
                                        switch(filterCaseValue) {
                                            case 'not_equal':
                                                searchRegx = `^(?!(((${this.value})))$)`;
                                                break;

                                            case 'not_equal':
                                                searchRegx = `^(?!(((${this.value})))$)`;
                                                break;
                                        }
                                    }*/

                                    api
                                        // .column(colIdx)
                                        // .search(searchRegx, true, false)
                                        .draw();

                                    /*$(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);*/
                                });


                                $(
                                    '[data-action="clear"]',
                                    $('.filters th').eq($(api.column(colIdx).header()).index())
                                    .on('click', function() {
                                        setTimeout(() => {
                                            api.draw();
                                        }, 10);
                                    })
                                );


                                $(
                                    '.dropdown-item',
                                    $('.filters th').eq($(api.column(colIdx).header()).index())
                                    .on('click', function() {

                                        api.draw();
                                    })
                                );
                        });
                }
            });

            hide_loader();

        }

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var filterableColumns = $('#filterColumn').val();
                filterableColumns = Array.from(new Set(filterableColumns))
                // console.log(filterableColumns)
                var flag = true;

                filterableColumns.forEach(function (item, index) {
                    if (flag) {
                        var inputValue = $("#input" + item).val();
                        var operator = $("#operator" + item).val();
                        var colIndex = $("#colIndex" + item).val();
                        var cellValue = data[colIndex];

                        if (item == 'Date' && inputValue) {
                            inputValue = inputValue.replaceAll('-', '/');

                            let leftDate = Date.parse(cellValue);
                            let rightDate = Date.parse(inputValue);

                            switch(operator) {
                                case 'equal':
                                    flag = leftDate == rightDate;
                                    break;
                                case 'not_equal':
                                    flag = leftDate != rightDate;
                                    break;
                                case 'greater_than':
                                    flag = leftDate > rightDate;
                                    break;
                                case 'greater_equal':
                                    flag = leftDate >= rightDate;
                                    break;
                                case 'less_than':
                                    flag = leftDate < rightDate;
                                    break;
                                case 'less_equal':
                                    flag = leftDate <= rightDate;
                                    break;
                                default:
                                    flag = true;
                            }

                        } else if (item == 'Month' && inputValue) {
                            inputValue = inputValue.replace('-', '/');

                            let leftDate = Date.parse(cellValue);
                            let rightDate = Date.parse(inputValue);

                            switch(operator) {
                                case 'equal':
                                    flag = leftDate == rightDate;
                                    break;
                                case 'not_equal':
                                    flag = leftDate != rightDate;
                                    break;
                                case 'greater_than':
                                    flag = leftDate > rightDate;
                                    break;
                                case 'greater_equal':
                                    flag = leftDate >= rightDate;
                                    break;
                                case 'less_than':
                                    flag = leftDate < rightDate;
                                    break;
                                case 'less_equal':
                                    flag = leftDate <= rightDate;
                                    break;
                                default:
                                    flag = true;
                            }

                        } else  {
                            switch (operator) {
                                case 'equal':
                                    flag = cellValue.toLowerCase() == inputValue.toLowerCase();
                                    break;
                                case 'not_equal':
                                    flag = cellValue.toLowerCase() != inputValue.toLowerCase();
                                    break;
                                case 'greater_than':
                                    flag = parseFloat(cellValue.replace(/,/g, '')) > parseFloat(inputValue.replace(/,/g, ''));
                                    break;
                                case 'greater_equal':
                                    flag = parseFloat(cellValue.replace(/,/g, '')) >= parseFloat(inputValue.replace(/,/g, ''));
                                    break;
                                case 'less_than':
                                    flag = parseFloat(cellValue.replace(/,/g, '')) < parseFloat(inputValue.replace(/,/g, ''));
                                    break;
                                case 'less_equal':
                                    flag = parseFloat(cellValue.replace(/,/g, '')) <= parseFloat(inputValue.replace(/,/g, ''));
                                    break;
                                default:
                                    flag = true;
                            }
                        }
                    }
                });
                return flag;
            }
        );

        function getNetIncome(record) {
            const seatId = Object.keys(window["seats"]).find(x => window["seats"][x].name == record.seat);
            const seat = window["seats"][seatId];
            let partner_fee = parseInt(seat.partner_fee);
            record.marketplace_fee = 0;
            record.scoring_fee = (parseInt(record.scoring_pixalate_s2s_sas_request) / 1000) * window["rates"].scoring_fee;
            let impression_rate = parseInt(record.impressions_good) / 1000;
            record.advertising_fee = impression_rate * window["rates"].advertising_fee;
            record.operation_fee = record.scoring_fee + record.advertising_fee + record.marketplace_fee;
            let media_cost_rate = record.environment.id == 'mobile_app' ? window["rates"].mobile_rate : window["rates"].ctv_rate;
            record.net_media_cost = partner_fee < 0;
            record.media_cost = (record.impressions_good / 1000) * media_cost_rate;
            partner_fee = Math.abs(partner_fee);
            record.net_revenue = parseFloat(record.revenue_total) - record.marketplace_fee;
            record.gross_profit = parseFloat(record.revenue_total) - record.media_cost - record.operation_fee;
            record.partner_fee = (partner_fee != 0) ? (record.gross_profit * partner_fee) / 100 : 0;
            record.net_profit = record.net_media_cost ? record.media_cost : record.gross_profit - record.partner_fee;
            const net_income = (partner_fee > 0 ? record.media_cost + record.partner_fee : parseFloat(record.revenue_total) - record.operation_fee);
            return net_income;
        }

    }

    $(document).on("keypress", function (e) {
        if (ReportDatatable && e.key == 'd') {
            if (resizeActive) {
                ReportDatatable.colReorder.disable();
            } else {
                ReportDatatable.colReorder.enable(true);
            }
            resizeActive = !resizeActive;
        }
    });


})(jQuery);
