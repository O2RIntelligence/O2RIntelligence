(function($) {
    $.get("../../api/get-ms-channel-ids", function(data, status){
        window['mt_channel_id'] = data.value.split(',');
    });
    var chartTitles = {
        MT_Seat: "Revenue By Seat",
        MT_Env: "Revenue By Media Type",
        MT_Int: "Revenue By Integration type",
        MS_Seat: "Revenue By Seat",
        MS_Env: "Revenue By Media Type",
        MS_Int: "Revenue By Integration type",
        G_Source: "Revenue By Source",
        G_CTV: "CTV Revenue By Source",
        G_inApp: "InApp Revenue By Source",
        G_Int: "Integration Type Revenue By Source",
    };

    var colors = [
        "#878BB6",
        "#4ACAB4",
        "#FF8153",
        "#FFEA88",
        "#3498db",
        '#db3497',
        '#5a2d2d',
        '#75830c',
        '#6c779f',
        "#2ecc71",
    ];

    $(function() {
        // init seat APIs
        for (const seatId of Object.keys(window["seats"])) {
            window["seats"][seatId].api = new adtelligent(window["seats"][seatId]);
            window["seats"][seatId].excluded_channels = window["seats"][seatId].excluded_channels.filter(m => m != '');
        }
        var seats = window["seats"];

        function get_selected_seats() {
            /*if (window["user_role"] == 'admin')*/ return $("select[name=seats]").val();
            // else return [window["user_id"]];
        }

        function start_loader() {
            $(".loader").show();
        }

        function hide_loader() {
            $(".loader").hide();
        }

        // setting global variables
        if (typeof current_page != 'undefined' && current_page == 'income') {
            var RNP_1 = false;
            var RNP_1_Data = {
                revenue: [],
                net_profit: []
            }
        }

        if (typeof current_page != 'undefined' && current_page == 'overall-report') {
            window["impressionChart"] = null;
        }

        window["formatMoney"] = function(number, decPlaces) {
            try {
                decPlaces = number.toString().indexOf(".") == -1 ? 0 : decPlaces;
                return number.toLocaleString(
                    undefined, // leave undefined to use the visitor's browser
                    // locale or a string like 'en-US' to override it.
                    {minimumFractionDigits: decPlaces}
                );
                // return number.toFixed(decPlaces).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        window["calculate_media"] = function() {
            try {
                switch (window["media_type"]) {
                    case 'general':
                        $("#MT_Charts").addClass('hiddenChart');
                        $("#MS_Chart").addClass('hiddenChart');
                        $("#General_Chart").show();
                        break;
                    case 'media-s':
                        $("#General_Chart").hide();
                        $("#MT_Charts").addClass("hiddenChart");
                        $("#MS_Chart").removeClass("hiddenChart");
                        break;
                    case 'media-t':
                        $("#General_Chart").hide();
                        $("#MS_Chart").addClass("hiddenChart");
                        $("#MT_Charts").removeClass("hiddenChart");
                        break;
                    default:
                        break;
                }
                appendMediaSource();
             }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        if (typeof current_page != 'undefined' && current_page == 'media_sources') {
            window["impressionChart"] = null;
            window["media_type"] = "general";
            window["calculate_media"]();
            window["media_source_data"] = [];
            selected_compare_env = "";
        }

        function appendMediaSource() {
            try {
                let tableRows = window["media_source_data"];
                if (!tableRows || tableRows.length == 0) return;
                if (window["media_type"] != 'general') {
                    tableRows = tableRows.filter(record => {
                        const type = window["mt_channel_id"].includes(record.channel.id.toString()) ? 'media-s' : 'media-t';
                        return window["media_type"] == type;
                    });
                }
                const records = _(tableRows).groupBy(function(record) {
                        return record.environment.id + "_" + record.date.id;
                    })
                    .map((objs, key) => ({
                        'date': objs[0].date.id,
                        'environment': objs[0].environment.id,
                        'impressions_good': _.sumBy(objs, 'impressions_good'),
                        'ad_requests': _.sumBy(objs, 'ad_requests'),
                        'revenue_total': _.sumBy(objs, 'revenue_total'),
                    })).value();

                if ($.fn.DataTable.isDataTable("#overall-performance-container-table")) {
                    $("#overall-performance-container-table").DataTable().destroy();
                }

                $("#overall-performance-container-table tbody").html('');
                appendOverallPerformanceTable(records);
             }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function appendMediaSourceCharts() {
            try {
                let tableRows = window["media_source_data"];
                let environment = $(".filter-overall:checked").val();
                if (!tableRows || tableRows.length == 0) return;
                if (window["media_type"] != 'general') {
                    tableRows = tableRows.filter(record => {
                        const type = window["mt_channel_id"].includes(record.channel.id.toString()) ? 'media-s' : 'media-t';
                        return window["media_type"] == type;
                    });
                }
                if (environment != '') {
                    tableRows = tableRows.filter(r => r.environment.id == environment);
                }

                tableRows = _(tableRows).groupBy(function(record) {
                    return record.environment.id;
                }).value();

                let OverallPerformanceChart = [];

                var i = 0;
                for (const environment of Object.keys(tableRows)) {
                    const record = tableRows[environment];

                    const values = _(record).groupBy(function(record) {
                        return record.date.id;
                    }).map((objs, key) => ({
                        'x': key,
                        'y': _.sumBy(objs, 'revenue_total') / (_.sumBy(objs, 'ad_requests') / 1000000),
                    })).value();

                    OverallPerformanceChart.push({
                        label: record[0].environment.name,
                        data: values,
                        borderColor: colors[i],
                        pointColor: "rgba(200,122,20,1)",
                    });

                    i++;
                }

                // calculate data for overall_performance_chart
                appendOverallPerformanceChart(OverallPerformanceChart);
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        DateReport();

        function DateReport() {
            try {
                // element functions
                $(".select2").select2();

                $('.input-daterange input').each(function() {
                    $(this).datetimepicker({
                        format: 'YYYY-MM-DD'
                    });
                });

                $(".show-daterange").on("click", function() {
                    $(".custom-daterange").toggle();
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

                $(".filter-dates").on("click", function() {
                    runReportFunction();
                });
                $(".refresh-seats").on('click', function() {
                    runReportFunction();
                });
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }
        function runReportFunction() {
            try {
                if (typeof current_page != 'undefined') {
                    start_loader();
                    switch (current_page) {
                        case 'overall-report':
                            overallAjax();
                            break;

                        case 'income':
                            incomeAjax();
                            break;

                        case 'media_sources':
                            MediaSourceAjax();
                            break;

                        default:
                            break;
                    }
                }
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        window["calculate_dates"] = function(period = null) {
            try {
                if (!period)
                    period = $("input[name=date_period]").val();
                switch (period) {
                    case 'today':
                        var date = moment().utcOffset(0, true).format('YYYY-MM-DD');
                        $("input[name=start_date]").val(date);
                        $("input[name=end_date]").val(date);
                        break;
                    case 'yesterday':
                        var date = moment().utcOffset(0, true).subtract(1, "days").format('YYYY-MM-DD');
                        $("input[name=start_date]").val(date);
                        $("input[name=end_date]").val(date);
                        break;
                    case 'last7':
                        $("input[name=start_date]").val(moment().utcOffset(0, true).subtract(7, "days").format('YYYY-MM-DD'));
                        $("input[name=end_date]").val(moment().utcOffset(0, true).subtract(1, "days").format('YYYY-MM-DD'));
                        break;
                    case 'last30':
                        $("input[name=start_date]").val(moment().utcOffset(0, true).startOf('month').format('YYYY-MM-DD'));
                        $("input[name=end_date]").val(moment().utcOffset(0, true).subtract(1, "days").format('YYYY-MM-DD'));
                        break;
                    default:
                        break;
                }
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        // start
        $("input[value=today]").trigger("click");
        $("button[data-period=today]").trigger("click");

        $(document).on('change', ".media_type", function() {
            try {
                window["media_type"] = $(this).val();
                window["calculate_media"]();
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        });

        $(document).on('change', ".overall-view-type", function() {
            try {
                if ($(this).val() == 'table') {
                    $("#overall-performance-graph").addClass('hiddenChart');
                    $("#overall-performance-container-table_wrapper").show();
                } else {
                    $("#overall-performance-container-table_wrapper").hide();
                    $("#overall-performance-graph").removeClass('hiddenChart');
                }
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        });




        /**
         * Income functions
         */
        async function incomeAjax() {
            try {

                if ($.fn.DataTable.isDataTable('#vertical-container-table')) {
                    $('#vertical-container-table').DataTable().destroy();
                }

                if ($.fn.DataTable.isDataTable('#media-income-container-table')) {
                    $('#media-income-container-table').DataTable().destroy();
                }

                $("#vertical-container-table").find('tbody').html("");
                $("#media-income-container-table").find('tbody').html("");

                var start_date = $("input[name=start_date]").val();
                var end_date = $("input[name=end_date]").val();
                var dateParams = {
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'date,ad_requests,ad_opportunities,impressions_good,revenue_channel,ecpm_channel,revenue_total,ecpm,fill_rate_ad_opportunities,scoring_pixalate_s2s_sas_request,environment',
                    "is_rtb": 'not_apply',
                    "limit": 10000,
                    "page": 1,
                    "report": "date,environment",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };

                var campaignParams = {
                    "campaign": "1120",
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'campaign,date,environment,revenue_total',
                    "is_rtb": 'not_apply',
                    "limit": 100,
                    "page": 1,
                    "report": "campaign,date,environment",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };

                var selected_seats = get_selected_seats();
                var DateMappedRecords = [];
                RNP_1_Data = {
                    revenue: [],
                    net_profit: []
                }
                for (let index = 0; index < selected_seats.length; index++) {
                    // init seat
                    const seatId = selected_seats[index];
                    const seat = seats[seatId];
                    // get campaign 1120 revenue for marketplace_fee
                    let campaignRequest;
                    try {
                        campaignRequest = await seats[seatId].api.request(campaignParams);
                    } catch (e) {
                        console.log(e);
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e == 401) swal(e.name,e.message,'error');//top.location.reload();
                        continue;
                    }

                    // get impressions & data
                    let response = await seats[seatId].api.request(dateParams).catch(e => {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e == 401) swal(e.name,e.message,'error');//top.location.reload();
                    });

                    // excluded channels data
                    if (seats[seatId].excluded_channels.length) {
                        response = await window['filterEcludedChannels'](seatId, dateParams, response);
                    }

                    // group by environment
                    let RecordsEnvGrouped = _(response.data).groupBy(function(record) {
                            return record.environment.id;
                        })
                        .map((objs, key) => ({
                            'environment': key,
                            'impressions_good': _.sumBy(objs, 'impressions_good'),
                            'excluded_impressions': _.sumBy(objs, 'excluded_impressions'),
                            'excluded_revenue': _.sumBy(objs, 'excluded_revenue'),
                            'revenue_total': _.sumBy(objs, 'revenue_total'),
                            'scanned_requests': _.sumBy(objs, 'scoring_pixalate_s2s_sas_request')
                        }))
                        .value();

                    // marketplace fee for environment
                    let CR_EnvGrouped = _(campaignRequest.data).groupBy(function(record) {
                            return record.environment.id;
                        })
                        .map((objs, key) => ({
                            'environment': key,
                            'fee': _.sumBy(objs, 'revenue_total') * 0.2,
                        }))
                        .keyBy("environment")
                        .value();

                    // calculate cost and revenue uniqly to platform << date doesn't matter here >>
                    let RecordsEnvGroupedCalculated = RecordsEnvGrouped.map(record => window["mapRecord"](seat, record, CR_EnvGrouped));
                    // append to table
                    let VC_RowData = _(RecordsEnvGroupedCalculated).reduce((r, c) => _.mergeWith(r, c, (o = 0, s) => o + s), {});
                    if ("revenue_total" in VC_RowData == false) VC_RowData = {
                        "ad_requests": 0,
                        "advertising_fee": 0,
                        "gross_profit": 0,
                        "impressions_good": 0,
                        "marketplace_fee": 0,
                        "media_cost": 0,
                        "net_media_cost": 0,
                        "net_income": 0,
                        "net_profit": 0,
                        "net_revenue": 0,
                        "operation_fee": 0,
                        "partner_fee": 0,
                        "revenue_total": 0,
                        "scanned_requests": 0,
                        "scoring_fee": 0,
                    };

                    AppendVerticalContainerIncomeRow(VC_RowData, seat.name);

                    // push data for first chart
                    RNP_1_Data.revenue.push(VC_RowData.revenue_total);
                    RNP_1_Data.net_profit.push(VC_RowData.net_income);

                    // marketplace fee for environment & date
                    let CR_DateEnvGrouped = _(campaignRequest.data)
                        .keyBy((o) => {
                            return o.date.id + "_" + o.environment.id;
                        })
                        .value();

                    // map data for master income overview
                    let RecordsDateEnvMapped = response.data.map(m => window["mapRecord"](seat, {
                        environment: m.environment.id,
                        date: m.date.id,
                        impressions_good: m.impressions_good,
                        revenue_total: m.revenue_total,
                        scanned_requests: m.scoring_pixalate_s2s_sas_request,
                        ad_requests: m.ad_requests,
                        excluded_impressions: m.excluded_impressions,
                        excluded_revenue: m.excluded_revenue,
                        marketplace_fee: (`${m.date.id}_${m.environment.id}` in CR_DateEnvGrouped) ? CR_DateEnvGrouped[`${m.date.id}_${m.environment.id}`].revenue_total * 0.2 : 0
                    }));

                    DateMappedRecords = [...DateMappedRecords, ...RecordsDateEnvMapped];
                }

                appendVerticalChart();

                // master income overview groupping
                let MI_DateGrouped = _(DateMappedRecords).groupBy(function(record) {
                        return record.date;
                    })
                    .map((objs, key) => ({
                        'date': key,
                        "ad_requests": _.sumBy(objs, "ad_requests"),
                        "gross_profit": _.sumBy(objs, "gross_profit"),
                        "impressions_good": _.sumBy(objs, "impressions_good"),
                        "scoring_fee": _.sumBy(objs, "scoring_fee"),
                        "advertising_fee": _.sumBy(objs, "advertising_fee"),
                        "marketplace_fee": _.sumBy(objs, "marketplace_fee"),
                        "media_cost": _.sumBy(objs, "media_cost"),
                        "net_media_cost": _.sumBy(objs, "net_media_cost"),
                        "net_revenue": _.sumBy(objs, "net_revenue"),
                        "net_profit": _.sumBy(objs, "net_profit"),
                        "operation_fee": _.sumBy(objs, "operation_fee"),
                        "partner_fee": _.sumBy(objs, "partner_fee"),
                        "revenue_total": _.sumBy(objs, "revenue_total"),
                        "net_income": _.sumBy(objs, "net_income"),
                        "scanned_requests": _.sumBy(objs, "scanned_requests")
                    }))
                    .keyBy("date")
                    .value();


                appendMediaIncomeTable(MI_DateGrouped);
                // append vertical container table and allow sorting
                $('#vertical-container-table').DataTable({
                    paging: false,
                    drawCallback: function() {
                        var api = this.api();
                        var modelId = "vertical-container-table";
                        if ($("#datatables-" + modelId).length == 0) {
                            appendTableModal(modelId);
                        }
                        api.columns().eq(0).each(function(index) {
                            if ($("#datatables-" + modelId + "_" + index).length == 0) {
                                appendTableModalOption(modelId, api.column(index).header().textContent, index);
                            }
                            if (index == 0) return;
                            var sum = index == 6 || index == 8 ? api.column(index).data().sum() / Object.keys(seats).length : api.column(index).data().sum();
                            var sign = index == 6 || index == 8 ? "%" : "$";
                            $(api.column(index).footer()).html(sign + window["formatMoney"](sum, 2))
                        });
                    }
                });

                hide_loader();
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        $(document).on('change', ".switch-table-view", function() {
            try {
                var column = $("#" + $(this).data('model')).DataTable().column($(this).data('index'));
                column.visible(!column.visible());
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        });

        function appendTableModal(modalId) {
            try {
                var modal = $("#datatables-controls").clone();
                modal.attr('id', "datatables-" + modalId);
                $(".modal-container").append(modal);
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function appendTableModalOption(modalId, title, index) {
            try {
                $("#datatables-" + modalId).find(".modal-body .btn-group").append(
                    $("<label>").attr('id', "datatables-" + modalId + "_" + index).attr('class', 'btn btn-primary active').append(
                        $("<input>").attr('type', 'checkbox').attr('class', 'switch-table-view').data('model', modalId).data('index', index).attr('checked', 'checked')
                    ).append(title)
                );
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function appendMediaIncomeTable(records) {
            try {
                index = 0;
                records = Object.entries(records);
                for (let [key, record] of records) {
                    const difference = index == 0 ? record.net_income : record.net_income - records[index - 1][1].net_income;
                    const background = index == 0 ? 'transparent' : (difference > 0 ? 'rgb(199 217 246)' : 'rgb(255 0 0)');
                    $("#media-income-container-table").find('tbody')
                        .append($('<tr>')
                            .append($('<td>')
                                .text(record.date)
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](record.impressions_good, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](record.scanned_requests, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](record.ad_requests, 0))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.revenue_total, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.net_revenue, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.media_cost, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.marketplace_fee, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.advertising_fee, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.scoring_fee, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.gross_profit, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.net_income, 2))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](difference, 2))
                                .css('background-color', background)
                            )
                        );
                    index++;
                }

                $('#media-income-container-table').DataTable({
                    paging: false,
                    drawCallback: function() {
                        var api = this.api();
                        var modelId = "media-income-container-table";

                        if ($("#datatables-" + modelId).length == 0) {
                            appendTableModal(modelId);
                        }
                        api.columns().eq(0).each(function(index) {
                            if ($("#datatables-" + modelId + "_" + index).length == 0) {
                                appendTableModalOption(modelId, api.column(index).header().textContent, index);
                            }
                            if (index == 0 || index == 11) return;
                            let sign = [4, 5, 6, 7, 8, 9, 10, 11].includes(index) ? "$" : "";
                            var sum = api.column(index).data().sum();
                            if ([4, 5, 6, 7, 8, 9, 10, 11].includes(index))
                                sum = window["formatMoney"](sum, 2);
                            else
                                sum = window["formatMoney"](sum, 0);
                            $(api.column(index).footer()).html(sign + sum);
                        });
                    }
                });
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        window["mapRecord"] = function(seat, record, CR_Group = false) {
            try {
                if (record.impressions_good == 0) {
                    record.advertising_fee = 0;
                    record.operation_fee = 0;
                    record.media_cost = 0;
                    record.net_media_cost = 0;
                    record.net_revenue = 0;
                    record.gross_profit = 0;
                    record.partner_fee = 0;
                    record.net_profit = 0;
                    record.net_income = 0;
                    record.excluded_impressions = 0;
                    delete record.environment;
                    return record;
                }


                if ("excluded_impressions" in record == false || !record.excluded_impressions) record.excluded_impressions = 0;
                if ("excluded_revenue" in record == false || !record.excluded_revenue) record.excluded_revenue = 0;

                let partner_fee = parseInt(seat.partner_fee);
                // calculate operation cost
                if (CR_Group && !record.marketplace_fee)
                    record.marketplace_fee = (record.environment in CR_Group) ? CR_Group[record.environment].fee : 0;

                record.scoring_fee = (record.scanned_requests / 1000) * window["rates"].scoring_fee;
                let impression_rate = record.impressions_good / 1000;
                record.advertising_fee = impression_rate * window["rates"].advertising_fee;
                record.operation_fee = record.scoring_fee + record.advertising_fee + record.marketplace_fee;
                let media_cost_rate = record.environment === 'mobile_app' ? window["rates"].mobile_rate : window["rates"].ctv_rate;
    // console.log("media cost rate: "+media_cost_rate+", impression: "+record.impressions_good+", Excluded Impressions: "+record.excluded_impressions+", media cost:"+((record.impressions_good/1000)*media_cost_rate));
                record.net_media_cost = partner_fee < 0;
//removed excluded_impressions, excluded_revenue from subtracting, gave inaccurate media cost and other infos
                record.media_cost = ((record.impressions_good) / 1000) * media_cost_rate;
                // calculate media cost
                // determine if this partner is related to us << we take 100% of media cost as revenue >>
                partner_fee = Math.abs(partner_fee);
                record.net_revenue = record.revenue_total - record.marketplace_fee;
                record.gross_profit = record.revenue_total - record.media_cost - record.operation_fee;
                record.partner_fee = (partner_fee != 0) ? (record.gross_profit * partner_fee) / 100 : 0;
                record.net_profit = record.net_media_cost ? record.media_cost : record.gross_profit - record.partner_fee;
                record.net_income = (partner_fee > 0 ? record.media_cost + record.partner_fee : record.revenue_total - record.operation_fee);


                delete record.environment;
                return record;

            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function appendVerticalChart() {
            try {
                var selected_seats = get_selected_seats();
                var selected_seats_names = selected_seats.map(m => seats[m].name);
                var revenue_background = Array(selected_seats.length).fill('rgb(148 211 247)');
                var net_profit_background = Array(selected_seats.length).fill('rgb(0 166 90)');

                var ctx = document.getElementById("RNP_1").getContext('2d');
                if (RNP_1)
                    RNP_1.destroy();

                RNP_1 = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: selected_seats_names,
                        datasets: [{
                            label: 'Revenue',
                            data: RNP_1_Data.revenue,
                            backgroundColor: revenue_background,
                        }, {
                            label: 'Net Income',
                            data: RNP_1_Data.net_profit,
                            backgroundColor: net_profit_background,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        responsive: true,
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return window["formatMoney"](tooltipItem.xLabel, 2);
                                }
                            }
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    // Include a dollar sign in the ticks
                                    callback: function(value, index, values) {
                                        return '$' + window["formatMoney"](value, 2);
                                    }
                                }
                            }]
                        }
                    }
                });

                $(".isResizable").resizable();
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }

        }

        function AppendVerticalContainerIncomeRow(record, seat_name) {
            try {
                $("#vertical-container-table").find('tbody')
                    .append($('<tr>')
                        .append($('<td>')
                            .text(seat_name)
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.revenue_total, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.media_cost, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.operation_fee, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.gross_profit, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.partner_fee, 2))
                        )
                        .append($('<td>')
                            .text(record.net_profit == 0 || record.revenue_total == 0 ||isNaN(record.net_profit / record.revenue_total)? 0 : (((record.net_profit / record.revenue_total) * 100).toFixed(2)) + "%")
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.net_income, 2))
                        )
                        .append($('<td>')
                            .text(isNaN(record.net_income / record.revenue_total)?0:window["formatMoney"](record.net_income / record.revenue_total * 100, 2) + "%")
                        )
                    );
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function AppendVerticalContainerRow(record, seat_name) {
            try {
                $("#vertical-container-table").find('tbody')
                    .append($('<tr>')
                        .append($('<td>')
                            .text(seat_name)
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](record.impressions_good, 0))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.revenue_total, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.media_cost, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.operation_fee, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.gross_profit, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.partner_fee, 2))
                        )
                        .append($('<td>')
                            .text("$" + window["formatMoney"](record.net_profit, 2))
                        )
                        .append($('<td>')
                            .text(record.net_profit == 0 || record.revenue_total == 0|| isNaN(record.net_profit / record.revenue_total)? 0 : window["formatMoney"](((record.net_profit / record.revenue_total) * 100), 2) + "%")
                        )
                    );
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        /**
         * Overall functions
         */

        async function overallAjax() {
            try {

                if ($.fn.DataTable.isDataTable('#vertical-container-table')) {
                    $('#vertical-container-table').DataTable().destroy();
                }

                if ($.fn.DataTable.isDataTable('#source-type')) {
                    $('#source-type').DataTable().destroy();
                }

                if ($.fn.DataTable.isDataTable('#advertiser-type')) {
                    $('#advertiser-type').DataTable().destroy();
                }

                $("#vertical-container-table").find('tbody').html("");
                $("table#environment-type").find('tbody').html("");
                $("table#advertiser-type").find('tbody').html("");


                var start_date = $("input[name=start_date]").val();
                var end_date = $("input[name=end_date]").val();
                var dateParams = {
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'date,hour,campaign_integration_type,environment,ad_requests,ad_opportunities,impressions_good,revenue_channel,ecpm_channel,revenue_total,ecpm,fill_rate_ad_opportunities,scoring_pixalate_s2s_sas_request',
                    "is_rtb": 'not_apply',
                    "limit": 40000,
                    "page": 1,
                    "report": "date,campaign_integration_type,environment,hour",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };

                var campaignParams = {
                    "campaign": "1120",
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'campaign,date,environment,revenue_total',
                    "is_rtb": 'not_apply',
                    "limit": 100,
                    "page": 1,
                    "report": "campaign,date,environment",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };


                var sourceParams = {
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'date,source,impressions_good,revenue_total,fill_rate_ad_opportunities',
                    "is_rtb": 'not_apply',
                    "limit": 30000,
                    "page": 1,
                    "sort": "-impressions_good",
                    "report": "date,source",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };

                var advertiserParams = {
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'date,advertiser,impressions_good,revenue_total,fill_rate_ad_opportunities',
                    "is_rtb": 'not_apply',
                    "limit": 30000,
                    "page": 1,
                    "sort": "-impressions_good",
                    "report": "date,advertiser",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };

                var selected_seats = get_selected_seats();
                var chartRevenueEnv = [];
                var chartRevenueIntegration = [];
                var chartRevenueHour = [];
                var suppliersListMore = [];
                var AdvertiserListMore = [];
                window["DoughnutCharts"] = {
                    Seat: { data: [], labels: [] },
                    Env: { data: [], labels: [] },
                    Integration: { data: [], labels: [] }
                };

                for (let index = 0; index < selected_seats.length; index++) {
                    // init seat
                    const seatId = selected_seats[index];
                    const seat = seats[seatId];
                    // get campaign 1120 revenue for marketplace_fee
                    let campaignRequest;
                    try {
                        campaignRequest = await seats[seatId].api.request(campaignParams);
                    } catch (error) {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (error == 401) swal(e.name,e.message,'error');//top.location.reload();
                        continue;
                    }
                    // get impressions & data
                    let response = await seats[seatId].api.request(dateParams).catch(e => {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e == 401) swal(e.name,e.message,'error');//top.location.reload();
                    });
                    // get data to deduct if has excluded channel
                    if (seats[seatId].excluded_channels.length) {
                        response = await window['filterEcludedChannels'](seatId, dateParams, response);
                    }
                    // revenue by seat chart
                    var totalSeatRevenue = _(response.data).sumBy('revenue_total').toFixed(2);

                    window["DoughnutCharts"]["Seat"].data.push(totalSeatRevenue);
                    window["DoughnutCharts"]["Seat"].labels.push(seat.name);

                    // group records by environment
                    let RecordsEnvNameGrouped = _(response.data).groupBy(function(record) {
                        return record.environment.name;
                    });

                    // group records by integration type
                    let RecordsIntGrouped = _(response.data).groupBy(function(record) {
                        return record.campaign_integration_type.name;
                    });

                    // group records by hour
                    let RecordsHourGrouped = _(response.data).groupBy(function(record) {
                        return record.hour.id;
                    });

                    // calculate revenue for seat
                    let SeatRevenueEnv = RecordsEnvNameGrouped.map((objs, key) => ({
                            'environment': key,
                            'revenue_total': _.sumBy(objs, 'revenue_total'),
                        }))
                        .value();
                    chartRevenueEnv = [...chartRevenueEnv, ...SeatRevenueEnv];
                    // calculate revenue for integration type
                    let SeatRevenueInt = RecordsIntGrouped.map((objs, key) => ({
                            'integration': key,
                            'revenue_total': _.sumBy(objs, 'revenue_total'),
                        }))
                        .value();
                    chartRevenueIntegration = [...chartRevenueIntegration, ...SeatRevenueInt];
                    // calculate impressions for hour
                    var randomRGP = () => Math.random() * 256 >> 0;
                    SeatRevenueImp = RecordsHourGrouped.map((objs, key) => ({
                            'x': key,
                            'y': _.sumBy(objs, 'impressions_good')
                        }))
                        .value();

                    chartRevenueHour.push({
                        label: seat.name,
                        data: SeatRevenueImp,
                        borderColor: colors[index],
                        pointColor: "rgba(200,122,20,1)",
                    })

                    // group by environment
                    let RecordsEnvGrouped = _(response.data).groupBy(function(record) {
                            return record.environment.id;
                        })
                        .map((objs, key) => ({
                            'environment': key,
                            'impressions_good': _.sumBy(objs, 'impressions_good'),
                            'revenue_total': _.sumBy(objs, 'revenue_total'),
                            'excluded_impressions': _.sumBy(objs, 'excluded_impressions'),
                            'excluded_revenue': _.sumBy(objs, 'excluded_revenue'),
                            'scanned_requests': _.sumBy(objs, 'scoring_pixalate_s2s_sas_request')
                        }))
                        .value();

                    // marketplace fee for environment
                    appendEnvTable(_(RecordsEnvGrouped)
                        .keyBy("environment")
                        .value(), seat);

                    // marketplace fee for environment
                    let CR_EnvGrouped = _(campaignRequest.data).groupBy(function(record) {
                            return record.environment.id;
                        })
                        .map((objs, key) => ({
                            'environment': key,
                            'fee': _.sumBy(objs, 'revenue_total') * 0.2,
                        }))
                        .keyBy("environment")
                        .value();

                    // calculate cost and revenue uniqly to platform << date doesn't matter here >>
                    let RecordsEnvGroupedCalculated = RecordsEnvGrouped.map(record => window["mapRecord"](seat, record, CR_EnvGrouped));

                    // append to table
                    let VC_RowData = _(RecordsEnvGroupedCalculated).reduce((r, c) => _.mergeWith(r, c, (o = 0, s) => o + s), {});

                    if (!("revenue_total" in VC_RowData)) VC_RowData = {
                        "advertising_fee": 0,
                        "gross_profit": 0,
                        "impressions_good": 0,
                        "marketplace_fee": 0,
                        "media_cost": 0,
                        "net_media_cost": 0,
                        "net_income": 0,
                        "net_profit": 0,
                        "net_revenue": 0,
                        "operation_fee": 0,
                        "partner_fee": 0,
                        "revenue_total": 0,
                        "scanned_requests": 0,
                        "scoring_fee": 0,
                    };

                    AppendVerticalContainerRow(VC_RowData, seat.name);

                    // group by channel << aka source >>

                    let sourceRequest;
                    try {
                        sourceRequest = await seats[seatId].api.request(sourceParams);
                    } catch (error) {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (error == 401) swal(e.name,e.message,'error');//top.location.reload();
                        continue;
                    }


                    var TakenSuppliersLimit = (selected_seats.length > 1) ? 3 : (sourceRequest.data.length < 10 ? sourceRequest.data.length : 10);
                    var TakenSuppliers = sourceRequest.data.slice(0, TakenSuppliersLimit);
                    appendTopSuppliers(TakenSuppliers, seat.name);
                    if (selected_seats.length > 1 && sourceRequest.data.length > TakenSuppliersLimit) {
                        var toprest10suppliers = sourceRequest.data.slice(TakenSuppliersLimit + 1, (sourceRequest.data.length < TakenSuppliersLimit + 11 ? sourceRequest.data.length : TakenSuppliersLimit + 11));
                        toprest10suppliers = toprest10suppliers.map(m => ({...m, seat: seat.name }));
                        suppliersListMore.push(toprest10suppliers);
                    }

                    // advertiser table
                    // @todo revamp this into using a single function with channel table
                    let advertiserRequest;

                    try {
                        advertiserRequest = await seats[seatId].api.request(advertiserParams);
                    } catch (e) {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e == 401) swal(e.name,e.message,'error');//top.location.reload();
                        continue;
                    }
                    var TakenAdvertiserLimit = (selected_seats.length > 1) ? 3 : (advertiserRequest.data.length < 10 ? advertiserRequest.data.length : 10);
                    var TakenAdvertiser = advertiserRequest.data.slice(0, TakenAdvertiserLimit);
                    appendTopAdvertiser(TakenAdvertiser, seat.name);
                    if (selected_seats.length > 1 && advertiserRequest.data.length > TakenAdvertiserLimit) {
                        var toprest10Advertiser = advertiserRequest.data.slice(TakenAdvertiserLimit + 1, (advertiserRequest.data.length < TakenAdvertiserLimit + 11 ? advertiserRequest.data.length : TakenAdvertiserLimit + 11));
                        toprest10Advertiser = toprest10Advertiser.map(m => ({...m, seat: seat.name }));
                        AdvertiserListMore.push(toprest10Advertiser);
                    }

                }
                if (suppliersListMore.length)
                    suppliersListMore.map(m => appendTopSuppliers(m));

                if (suppliersListMore.length)
                    suppliersListMore.map(m => appendTopAdvertiser(m));
                // append vertical container table and allow sorting
                $('#vertical-container-table').DataTable({
                    paging: false,
                    drawCallback: function() {
                        var api = this.api();
                        var modelId = "vertical-container-table";

                        if ($("#datatables-" + modelId).length == 0) {
                            appendTableModal(modelId);
                        }
                        api.columns().eq(0).each(function(index) {
                            if ($("#datatables-" + modelId + "_" + index).length == 0) {
                                appendTableModalOption(modelId, api.column(index).header().textContent, index);
                            }
                            if (index == 0) return;
                            var sum = index == 8 ? api.column(index).data().sum() / Object.keys(seats).length : api.column(index).data().sum();
                            var sign = index == 8 ? "%" : "$";
                            sign = index === 1 ? '' : sign;
                            $(api.column(index).footer()).html(sign + window["formatMoney"](sum, 2));
                        });
                    }
                });

                // append source table and allow sorting
                $('#source-type').DataTable({
                    paging: false,
                    "order": [
                        [2, "desc"]
                    ],
                    drawCallback: function() {
                        var api = this.api();
                        var modelId = "source-type";
                        if ($("#datatables-" + modelId).length == 0) {
                            appendTableModal(modelId);
                        }
                        api.columns().eq(0).each(function(index) {
                            if ($("#datatables-" + modelId + "_" + index).length == 0) {
                                appendTableModalOption(modelId, api.column(index).header().textContent, index);
                            }
                        });
                    }
                });


                // append advertiser table and allow sorting
                $('#advertiser-type').DataTable({
                    paging: false,
                    "order": [
                        [2, "desc"]
                    ],
                    drawCallback: function() {
                        var api = this.api();
                        var modelId = "advertiser-type";
                        if ($("#datatables-" + modelId).length == 0) {
                            appendTableModal(modelId);
                        }
                        api.columns().eq(0).each(function(index) {
                            if ($("#datatables-" + modelId + "_" + index).length == 0) {
                                appendTableModalOption(modelId, api.column(index).header().textContent, index);
                            }
                        });
                    }
                });

                // show impression chart
                appendImpressionChart(chartRevenueHour);
                // get environment chart data ready
                var environmentChart = _(chartRevenueEnv).groupBy(function(record) {
                        return record.environment;
                    }).map((objs, key) => ({
                        'environment': key,
                        'revenue_total': _.sumBy(objs, 'revenue_total')
                    }))
                    .value();
                environmentChart.map(m => {
                    window["DoughnutCharts"]["Env"].data.push(parseFloat(m.revenue_total).toFixed(2));
                    window["DoughnutCharts"]["Env"].labels.push(m.environment);
                });
                // get environment chart data ready
                var integrationChart = _(chartRevenueIntegration).groupBy(function(record) {
                        return record.integration;
                    }).map((objs, key) => ({
                        'integration': key,
                        'revenue_total': _.sumBy(objs, 'revenue_total').toFixed(2),
                    }))
                    .value();

                integrationChart.map(m => {
                    window["DoughnutCharts"]["Integration"].data.push(parseFloat(m.revenue_total).toFixed(2));
                    window["DoughnutCharts"]["Integration"].labels.push(m.integration);
                });

                for (key of Object.keys(window["DoughnutCharts"])) appendDonughtChart(key);

                hide_loader();
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }


        /**
         * Media Source
         */

        async function MediaSourceAjax() {
            try {
                $("#general_media").trigger('click');
                // $("#general_media").prop("checked", true);
                // window["media_type"] = 'general';


                $("#compare-performance-container-table").find('tbody').html("");
                $("table#overall-performance-container-table").find('tbody').html("");

                if ($.fn.DataTable.isDataTable("#overall-performance-container-table")) {
                    $("#overall-performance-container-table").DataTable().destroy();
                }
                if ($.fn.DataTable.isDataTable("#compare-performance-container-table")) {
                    $("#compare-performance-container-table").DataTable().destroy();
                }

                var start_date = $("input[name=start_date]").val();
                var end_date = $("input[name=end_date]").val();
                var dateParams = {
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'date,channel,campaign_integration_type,environment,ad_requests,ad_opportunities,impressions_good,revenue_channel,ecpm_channel,revenue_total,ecpm,fill_rate_ad_opportunities,scoring_pixalate_s2s_sas_request',
                    "is_rtb": 'not_apply',
                    "limit": 10000,
                    "page": 1,
                    "report": "date,channel,campaign_integration_type,environment",
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT'
                };

                var selected_seats = get_selected_seats();
                window["DoughnutCharts"] = {
                    MT_Seat: { data: [], labels: [] },
                    MT_Env: { data: [], labels: [] },
                    MT_Int: { data: [], labels: [] },
                    MS_Seat: { data: [], labels: [] },
                    MS_Env: { data: [], labels: [] },
                    MS_Int: { data: [], labels: [] },
                };

                if (window["media_type"] == 'general') {
                    window["DoughnutCharts"]['G_Source'] = { data: [], labels: [] };
                    window["DoughnutCharts"]['G_CTV'] = { data: [], labels: [] };
                    window["DoughnutCharts"]['G_inApp'] = { data: [], labels: [] };
                    window["DoughnutCharts"]['G_Int'] = { data: [], labels: [] };
                }

                let DateMappedRecordsOverall = [];
                let DateMappedRecordsCompare = {};
                window["media_source_data"] = [];
                for (let index = 0; index < selected_seats.length; index++) {
                    // init seat
                    const seatId = selected_seats[index];
                    const seat = seats[seatId];

                    // get impressions & data
                    let ReportRequest = await seats[seatId].api.request(dateParams).catch(e => {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e == 401) swal(e.name,e.message,'error');//top.location.reload();
                    });

                    // excluded channels data
                    if (seats[seatId].excluded_channels.length) {
                        ReportRequest = await window['filterEcludedChannels'](seatId, dateParams, ReportRequest);
                    }

                    ReportRequest.data = ReportRequest.data.filter(m => m.environment.id != 'other');

                    let CR_SrcGrouped = _(ReportRequest.data).groupBy(function(record) {
                            return window["mt_channel_id"].includes(record.channel.id.toString()) ? 'MS' : 'MT';
                        })
                        .value();

                    for (const channel of Object.keys(CR_SrcGrouped)) {
                        var totalSeatRevenue = _(CR_SrcGrouped[channel]).sumBy((o) => parseFloat(o.revenue_total)).toFixed(2);
                        window["DoughnutCharts"][channel + "_Seat"].data.push({ value: totalSeatRevenue });
                        window["DoughnutCharts"][channel + "_Seat"].labels.push(seat.name);

                        processChartEntity({
                            [channel]: { revenue_total: totalSeatRevenue }
                        }, 'G_Source', 'channel');



                        // group records by environment
                        let RecordsEnvNameGrouped = _(CR_SrcGrouped[channel]).groupBy(function(record) {
                            return record.environment.name;
                        });

                        // group records by integration type
                        let RecordsIntGrouped = _(CR_SrcGrouped[channel]).groupBy(function(record) {
                            return record.campaign_integration_type.name;
                        });

                        // calculate revenue for seat
                        let ChannelRevenueEnv = RecordsEnvNameGrouped.map((objs, key) => ({
                                'environment': key,
                                'revenue_total': _.sumBy(objs, 'revenue_total'),
                            }))
                            .keyBy('environment')
                            .value();

                        if ("CTV" in ChannelRevenueEnv) {
                            processChartEntity({
                                [channel]: { revenue_total: ChannelRevenueEnv["CTV"].revenue_total }
                            }, 'G_CTV', 'channel');
                        }

                        if ("Mobile App" in ChannelRevenueEnv) {
                            processChartEntity({
                                [channel]: { revenue_total: ChannelRevenueEnv["Mobile App"].revenue_total }
                            }, 'G_inApp', 'channel');
                        }

                        processChartEntity(ChannelRevenueEnv, `${channel}_Env`, 'environment');

                        // calculate revenue for integration type
                        let ChannelRevenueInt = RecordsIntGrouped.map((objs, key) => ({
                                'integration': key,
                                'revenue_total': _.sumBy(objs, 'revenue_total'),
                            }))
                            .keyBy('integration')
                            .value();
                        processChartEntity(ChannelRevenueInt, `${channel}_Int`, 'integration');
                        processChartEntity(ChannelRevenueInt, 'G_Int', 'integration');

                    }

                    // map data for source compare report
                    let RecordsDateEnvChannelGroupped = _(ReportRequest.data).groupBy(function(record) {
                        return record.date.id;
                    }).value();

                    for (const dateKey of Object.keys(RecordsDateEnvChannelGroupped)) {
                        if (dateKey in DateMappedRecordsCompare == false)
                            DateMappedRecordsCompare[dateKey] = RecordsDateEnvChannelGroupped[dateKey];
                        else
                            DateMappedRecordsCompare[dateKey] = [...DateMappedRecordsCompare[dateKey], ...RecordsDateEnvChannelGroupped[dateKey]];
                    }

                    // map data for overall performance report
                    window["media_source_data"] = [...ReportRequest.data, ...window["media_source_data"]];

                    let RecordsDateEnvMapped = _(ReportRequest.data).groupBy(function(record) {
                            return record.environment.id + "_" + record.date.id;
                        })
                        .map((objs, key) => ({
                            'date': objs[0].date.id,
                            'environment': objs[0].environment.id,
                            'impressions_good': _.sumBy(objs, 'impressions_good'),
                            'ad_requests': _.sumBy(objs, 'ad_requests'),
                            'revenue_total': _.sumBy(objs, 'revenue_total'),
                        })).value();

                    DateMappedRecordsOverall = [...DateMappedRecordsOverall, ...RecordsDateEnvMapped];
                }
                // overall performance groupping
                DateMappedRecordsOverall = _(DateMappedRecordsOverall).groupBy(function(record) {
                        return record.environment + "_" + record.date;
                    })
                    .map((objs, key) => ({
                        'date': objs[0].date,
                        'environment': objs[0].environment,
                        'impressions_good': _.sumBy(objs, 'impressions_good'),
                        'ad_requests': _.sumBy(objs, 'ad_requests'),
                        'revenue_total': _.sumBy(objs, 'revenue_total'),
                    })).value();

                appendMediaSourceCharts();
                appendOverallPerformanceTable(DateMappedRecordsOverall);
                // compare performance table
                for (const dateKey of Object.keys(DateMappedRecordsCompare)) {
                    DateMappedRecordsCompare[dateKey] = _(DateMappedRecordsCompare[dateKey]).groupBy(function(record) {
                            return (window["mt_channel_id"].includes(record.channel.id.toString()) ? 'MS' : 'MT') + '_' + record.environment.id;
                        })
                        .map((objs, key) => ({
                            'date': objs[0].date.id,
                            'environment': objs[0].environment.id,
                            'channel': key.split('_')[0],
                            'impressions_good': _.sumBy(objs, 'impressions_good'),
                            'ad_requests': _.sumBy(objs, 'ad_requests'),
                            'revenue_total': _.sumBy(objs, 'revenue_total'),
                        })).value();
                }
                window["compare_performance_records"] = DateMappedRecordsCompare;
                appendComparePerformanceTable();

                for (key of Object.keys(window["DoughnutCharts"])) {
                    window["DoughnutCharts"][key].data = window["DoughnutCharts"][key].data.map(m => "value" in m ? parseFloat(m.value).toFixed(2) : 0);
                    appendDonughtChart(key);
                }

                hide_loader();
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        $(".filter-sourceCompare").on('change', function() {
            try {
                selected_compare_env = $(this).val();
                $("#compare-performance-container-table").find('tbody').html("");
                appendComparePerformanceTable();
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        });

        function appendComparePerformanceTable() {
            try {
                let totalImpressionsMt=0
                let totalRevenueMt=0
                let totalAdRequestsMt = 0
                let totalImpressionsMs=0
                let totalRevenueMs=0
                let totalAdRequestsMs = 0
                for (const key of Object.keys(window["compare_performance_records"])) {
                    const record = window["compare_performance_records"][key];
                    console.log(Object.keys(window["compare_performance_records"]));
                    console.log(window["mt_channel_id"]);
                    let ms, mt;
                    if (selected_compare_env.length) {
                        ms = record.find(m => m.environment == selected_compare_env && m.channel == 'MS');
                        mt = record.find(m => m.environment == selected_compare_env && m.channel == 'MT');
                    } else {
                        let channels = _(record).groupBy(function(m) {
                                return m.channel;
                            })
                            .map((objs, key) => ({
                                'channel': key,
                                'impressions_good': _.sumBy(objs, 'impressions_good'),
                                'revenue_total': _.sumBy(objs, 'revenue_total'),
                                'ad_requests': _.sumBy(objs, 'ad_requests')
                            }))
                            .keyBy("channel")
                            .value();
                        ms = channels["MS"];
                        mt = channels["MT"];
                    }

                    if (!ms || ms == -1) ms = {
                        impressions_good: 0,
                        revenue_total: 0,
                        ad_requests: 0
                    };

                    if (!mt || mt == -1) mt = {
                        impressions_good: 0,
                        revenue_total: 0,
                        ad_requests: 0
                    };
                    totalImpressionsMt += mt.impressions_good;
                    totalRevenueMt += mt.revenue_total;
                    totalAdRequestsMt += mt.ad_requests;
                    totalImpressionsMs += ms.impressions_good;
                    totalRevenueMs += ms.revenue_total;
                    totalAdRequestsMs += ms.ad_requests;

                    $("#compare-performance-container-table").find('tbody')
                        .append($('<tr>')
                            .append($('<td>')
                                .text(key)
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](mt.ad_requests, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](mt.impressions_good, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](mt.revenue_total, 2) + "$")
                            )
                            .append($('<td>')
                                .attr('class', 'success')
                                .text(isNaN(mt.revenue_total / (mt.ad_requests / 1000000))?0:window["formatMoney"](mt.revenue_total / (mt.ad_requests / 1000000), 2) + "$")
                            )
                            .append($('<td>')
                                .attr('class', '')
                                .text(isNaN(mt.impressions_good /mt.ad_requests)?0:window["formatMoney"]((mt.impressions_good /mt.ad_requests)*100) + "%")
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](ms.ad_requests, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](ms.impressions_good, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](ms.revenue_total, 2) + "$")
                            )
                            .append($('<td>')
                                .attr('class', 'success')
                                .text(isNaN(ms.revenue_total / (ms.ad_requests / 1000000))?0:window["formatMoney"](ms.revenue_total / (ms.ad_requests / 1000000), 2) + "$")
                            )
                            .append($('<td>')
                                .attr('class', '')
                                .text(isNaN(ms.impressions_good /ms.ad_requests)?0:window["formatMoney"]((ms.impressions_good /ms.ad_requests)*100) + "%")
                            )
                        );

                }
                $("#compare-performance-container-table").find('tbody')
                    .append($('<tr>')
                        .append($('<th>')
                            .attr('class', 'info')
                            .text("Total")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalAdRequestsMt))
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalImpressionsMt))
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalRevenueMt))
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(isNaN(totalRevenueMt / (totalAdRequestsMt / 1000000))?0:window["formatMoney"](totalRevenueMt / (totalAdRequestsMt / 1000000), 2) + "$")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"]((totalImpressionsMt /totalAdRequestsMt)*100) + "%")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalAdRequestsMs))
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalImpressionsMs))
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalRevenueMs))
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(isNaN(totalRevenueMs / (totalAdRequestsMs / 1000000))?0:window["formatMoney"](totalRevenueMs / (totalAdRequestsMs / 1000000), 2) + "$")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(isNaN(totalImpressionsMs /totalAdRequestsMs)?0:window["formatMoney"]((totalImpressionsMs /totalAdRequestsMs)*100) + "%")
                        )

                    );
                $("#compare-performance-container-table").find('tbody')
                    .append($('<tr>')
                        .append($('<th>')
                            .attr('class', 'info')
                            .text("Combined Total")
                        ).append($('<th>')
                            .attr('class', 'info')
                            .text("Total Ad Requests")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalAdRequestsMt+totalAdRequestsMs))
                        ).append($('<th>')
                            .attr('class', 'info')
                            .text("Total Impressions")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalImpressionsMt+totalImpressionsMs))
                        ).append($('<th>')
                            .attr('class', 'info')
                            .text("Total Revenue")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(window["formatMoney"](totalRevenueMt+totalRevenueMs))
                        ).append($('<th>')
                            .attr('class', 'info')
                            .text("Total $/AD Req in Mil")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(isNaN(totalRevenueMt / (totalAdRequestsMt / 1000000))||isNaN(totalRevenueMs / (totalAdRequestsMs / 1000000))?0:window["formatMoney"]((totalRevenueMt / (totalAdRequestsMt / 1000000)+(totalRevenueMs / (totalAdRequestsMs / 1000000))), 2) + "$")
                        ).append($('<th>')
                            .attr('class', 'info')
                            .text("Total Fill Rate(%)")
                        ).append($('<td>')
                            .attr('class', 'info')
                            .text(isNaN(totalImpressionsMs /totalAdRequestsMs)||isNaN(totalImpressionsMt /totalAdRequestsMt)?0:window["formatMoney"](((totalImpressionsMs /totalAdRequestsMs)+(totalImpressionsMt /totalAdRequestsMt))*100) + "%")
                        )
                    );

                appendDailyChartByHour();
                appendMonthlyChartByday();

            }catch (e) {
                    console.log("Error: "+e);
                    swal(e.name,e.message,"error");
                    hide_loader();
            }
        }

        async function appendDailyChartByHour(){
            try {
                $('#appendDailyChartByHourLoader').show();
                $('#isResizableHour').css('opacity',0);

                var msChannelIds = [];
                $.get("../../api/get-ms-channel-ids", function(data, status){
                    msChannelIds = data.value.split(',');
                    // console.log(msChannelIds);

                });

                var start_date = $("input[name=start_date]").val();
                var timeline = $("input[name=date_period]").val();
                var hour = timeline==='today'?[...Array.from(Array(new Date().getUTCHours()).keys())]:[...Array.from(Array(24).keys())];
                console.log(hour);
                var end_date = $("input[name=end_date]").val();
                var chanelRequestBody = {
                    "report": 'hour,channel',
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'ad_requests,ad_opportunities,impressions_good,scoring_pixalate_s2s_sas_request,revenue_channel,ecpm_channel,revenue_total,ecpm,fill_rate_ad_opportunities,fill_rate_ad_requests,hour,channel',
                    "hour": hour ,
                    "limit": 1000000,
                    "page": 1,
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT',
                    "is_rtb": 'not_apply'
                };
                // console.log(chanelRequestBody.hour);
                var selected_seats = get_selected_seats();
                var DateMappedRecords = Array(24).fill(0);
                var mtAdRequestByHour = Array(24).fill(0);
                var msAdRequestByHour = Array(24).fill(0);
                var combinedAdRequestByHour = Array(24).fill(0);
                var mtImpressionsByHour = Array(24).fill(0);
                var msImpressionsByHour = Array(24).fill(0);
                var combinedImpressionsByHour = Array(24).fill(0);

                var msFillRateByHour = Array(24).fill(0);
                var mtFillRateByHour = Array(24).fill(0);
                var combinedFillRateByHour = Array(24).fill(0);

                for (let index = 0; index < selected_seats.length; index++) {
                    // init seat
                    const seatId = selected_seats[index];
                    const seat = seats[seatId];
                    // get campaign 1120 revenue for marketplace_fee
                    let channelDataByHour;
                    try {
                        channelDataByHour = await seats[seatId].api.request(chanelRequestBody);

                            $(channelDataByHour.data).each(function(key,singleData){
                                // console.log(singleData.channel.id);
                                if(msChannelIds.includes(singleData.channel.id.toString())){
                                    msAdRequestByHour[singleData.hour.id]+=singleData.ad_requests;
                                    // console.log(msAdRequestByHour[singleData.hour.id]);
                                    msImpressionsByHour[singleData.hour.id]+=singleData.impressions_good;

                                }else{
                                    mtAdRequestByHour[singleData.hour.id]+=singleData.ad_requests;
                                    mtImpressionsByHour[singleData.hour.id]+=singleData.impressions_good;

                                }

                            });

                        // console.log(channelDataByHour.data);
                        // console.log({{json_encode($array_without_keys)}});

                    } catch (e) {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e === 401) console.log("got 401");//top.location.reload();
                        continue;
                    }
                }


                // console.log(msAdRequestByHour);

                $(hour).each(function (key,singleHour) {
                    msFillRateByHour[key] = ((msImpressionsByHour[key]/msAdRequestByHour[key])*100).toFixed(3);
                    mtFillRateByHour[key] = ((mtImpressionsByHour[key]/mtAdRequestByHour[key])*100).toFixed(3);
                    combinedAdRequestByHour[key] = combinedAdRequestByHour[key]+msAdRequestByHour[key]+mtAdRequestByHour[key];
                    combinedImpressionsByHour[key] = combinedImpressionsByHour[key]+msImpressionsByHour[key]+mtImpressionsByHour[key];

                });

                $(hour).each(function (key,singleHour) {
                    combinedFillRateByHour[key] = ((combinedImpressionsByHour[key]/combinedAdRequestByHour[key])*100).toFixed(3);
                });
                // console.log("msAdRequestByHour: "+ msAdRequestByHour);
                // console.log("msImpressionsByHour: "+ msImpressionsByHour);
                // console.log("combinedAdRequestByHour: "+ combinedAdRequestByHour);
                // console.log("combinedImpressionsByHour: "+ combinedImpressionsByHour);
                // console.log("mtAdRequestByHour: "+ mtAdRequestByHour);
                // console.log("mtImpressionsByHour: "+ mtImpressionsByHour);

                const labels = Array.from(Array(24).keys());
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: ['Media-S'],
                            // backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: msFillRateByHour,
                        },
                        {
                            label: ['Media-T'],
                            // backgroundColor: 'rgb(8 76 199)',
                            borderColor: 'rgb(8 76 199)',
                            data: mtFillRateByHour,
                        },
                        {
                            label: ['Combined'],
                            // backgroundColor: 'rgb(8 199 58)',
                            borderColor: 'rgb(8 199 58)',
                            data: combinedFillRateByHour,
                        },
                    ]
                };
                const config = {
                    type: 'line',
                    data: data,
                    options: {}
                };
                const graphs3 = new Chart(document.getElementById('mt_daily_chart_by_hour'), config);
                // var chartStatus = Chart.getChart("#mt_daily_chart_by_hour");
                // if(graphs3!== undefined){
                    if(!graphs3.reset()){
                        console.log("Could not reset graph");
                    }
                // }

                const myChart = new Chart(
                    graphs3,
                    config
                );
                $('#appendDailyChartByHourLoader').hide()
                $('#isResizableHour').css('opacity',1);

            }catch (e) {
            console.log("Error: "+e);
            swal(e.name,e.message,"error");
            hide_loader();
            }
        }

         async function appendMonthlyChartByday(){
            try {
                $('#appendMonthlyChartBydayLoader').show();
                $('#isResizableDay').css('opacity',0);
                var msChannelIds = [];
                $.get("../../api/get-ms-channel-ids", function(data, status){
                    msChannelIds = data.value.split(',');
                    // console.log(msChannelIds);

                });
                 var firstday = function(y,m){
                     return  new Date(y, m , 1).getDate();
                 }
                 var lastday = function(y,m){
                     return  new Date(y, m +1, 0).getDate();
                 }
                var start_date = $("input[name=start_date]").val();
                var date = new Date(start_date);
                // start_date = new Date(date.getFullYear()+"-"+date.getMonth()+1+"-"+firstday(date.getFullYear(), date.getMonth()));

                var dateCount = lastday(date.getFullYear(), date.getMonth());
                var dateCountArray = [...Array.from(Array(dateCount).keys())] ;
                var end_date = $("input[name=end_date]").val();
                if(start_date === end_date){
                    start_date = new Date(date.getFullYear(), date.getMonth(), 1).toLocaleDateString();
                    end_date = new Date(date.getFullYear(), date.getMonth() + 1, 0).toLocaleDateString();
                }
                var chanelRequestBody = {
                    "report": 'date,channel',
                    "date_from": start_date,
                    "date_to": end_date,
                    "fields": 'ad_requests,ad_opportunities,impressions_good,scoring_pixalate_s2s_sas_request,revenue_channel,ecpm_channel,revenue_total,ecpm,fill_rate_ad_opportunities,fill_rate_ad_requests,date,channel',
                    "limit": 1000000,
                    "page": 1,
                    "strategy": 'last-collection',
                    "type": 'ssp_statistic',
                    "tz": 'GMT',
                    "is_rtb": 'not_apply'
                };
                // console.log(chanelRequestBody.hour);
                var selected_seats = get_selected_seats();
                var DateMappedRecords = Array(dateCount).fill(0);
                var mtAdRequestByDay = Array(dateCount).fill(0);
                var msAdRequestByDay = Array(dateCount).fill(0);
                var combinedAdRequestByDay = Array(dateCount).fill(0);
                var mtImpressionsByDay = Array(dateCount).fill(0);
                var msImpressionsByDay = Array(dateCount).fill(0);
                var combinedImpressionsByDay = Array(dateCount).fill(0);

                var msFillRateByDay = Array(dateCount).fill(0);
                var mtFillRateByDay = Array(dateCount).fill(0);
                var combinedFillRateByDay = Array(dateCount).fill(0);

                for (let index = 0; index < selected_seats.length; index++) {
                    // init seat
                    const seatId = selected_seats[index];
                    const seat = seats[seatId];
                    // get campaign 1120 revenue for marketplace_fee
                    let channelDataByDay;
                    try {
                        channelDataByDay = await seats[seatId].api.request(chanelRequestBody);

                            $(channelDataByDay.data).each(function(key,singleData){
                                // console.log(singleData.channel.id);
                                var date = new Date(singleData.date.id);
                                date = date.getDate()-1;
                                if(msChannelIds.includes(singleData.channel.id.toString())){
                                    msAdRequestByDay[date]+=singleData.ad_requests;
                                    // console.log(msAdRequestByDay[date]);
                                    msImpressionsByDay[date]+=singleData.impressions_good;

                                }else{
                                    mtAdRequestByDay[date]+=singleData.ad_requests;
                                    mtImpressionsByDay[date]+=singleData.impressions_good;
                                    // console.log(msAdRequestByDay[date]);
                                }

                            });

                        // console.log(mtImpressionsByDay);
                        // console.log({{json_encode($array_without_keys)}});

                    } catch (e) {
                        console.log("Error: "+e);
                        swal(e.name,e.message,"error");
                        hide_loader();
                        if (e === 401) swal(e.name,e.message,'error');//console.log("got 401");//top.location.reload();
                        continue;
                    }
                }


                // console.log(msAdRequestByDay);

                $(dateCountArray).each(function (key,singleDay) {
                    msFillRateByDay[key] = ((msImpressionsByDay[key]/msAdRequestByDay[key])*100).toFixed(3);
                    // console.log("key: "+key+" "+msImpressionsByDay[key]+"/"+msAdRequestByDay[key])+" *100 = "+((msImpressionsByDay[key]/msAdRequestByDay[key])*100);
                    mtFillRateByDay[key] = ((mtImpressionsByDay[key]/mtAdRequestByDay[key])*100).toFixed(3);
                    combinedAdRequestByDay[key] = combinedAdRequestByDay[key]+msAdRequestByDay[key]+mtAdRequestByDay[key];
                    combinedImpressionsByDay[key] = combinedImpressionsByDay[key]+msImpressionsByDay[key]+mtImpressionsByDay[key];

                });

                $(dateCountArray).each(function (key,singleDay) {
                    combinedFillRateByDay[key] = ((combinedImpressionsByDay[key]/combinedAdRequestByDay[key])*100).toFixed(3);
                });
                // console.log("msAdRequestByDay: "+ msAdRequestByDay);
                // console.log("msImpressionsByDay: "+ msImpressionsByDay);
                // console.log("combinedAdRequestByDay: "+ combinedAdRequestByDay);
                // console.log("combinedImpressionsByDay: "+ combinedImpressionsByDay);
                // console.log("mtAdRequestByDay: "+ mtAdRequestByDay);
                // console.log("mtImpressionsByDay: "+ mtImpressionsByDay);
                //
                // console.log("msFillRateByDay: "+ msFillRateByDay);
                // console.log("mtFillRateByDay: "+ mtFillRateByDay);
                // console.log("combinedFillRateByDay: "+ combinedFillRateByDay);

                const labels = getAllDatesOfCurrnetMonth();
                // console.log(labels);
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: ['Media-S'],
                            // backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: msFillRateByDay,
                        },
                        {
                            label: ['Media-T'],
                            // backgroundColor: 'rgb(8 76 199)',
                            borderColor: 'rgb(8 76 199)',
                            data: mtFillRateByDay,
                        },
                        {
                            label: ['Combined'],
                            // backgroundColor: 'rgb(8 199 58)',
                            borderColor: 'rgb(8 199 58)',
                            data: combinedFillRateByDay,
                        },
                    ]
                };
                const config = {
                    type: 'line',
                    data: data,
                    options: {}
                };
                const graphsMonthly = new Chart(document.getElementById('mt_monthly_chart_by_day'), config);
                // var chartStatus = Chart.getChart("#mt_daily_chart_by_hour");
                // if(graphs3!== undefined){
                    if(!graphsMonthly.reset()){
                        console.log("Could not reset graph");
                    }
                // }

                const myChart = new Chart(
                    graphsMonthly,
                    config
                );
                $('#appendMonthlyChartBydayLoader').hide();
                $('#isResizableDay').css('opacity',1);

            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function getAllDatesOfCurrnetMonth(){
            try {
                var date = new Date();
                var month = date.getMonth();
                date.setDate(1);
                var all_days = [];
                while (date.getMonth() === month) {
                    var d = date.getFullYear() + '-' + (date.getMonth()+1).toString().padStart(2, '0') + '-' + date.getDate().toString().padStart(2, '0');
                    all_days.push(d);
                    date.setDate(date.getDate() + 1);
                }
                return all_days;
            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }

        function appendOverallPerformanceTable(records) {
            try {
                for (let index = 0; index < records.length; index++) {
                    const record = records[index];

                    $("#overall-performance-container-table").find('tbody')
                        .append($('<tr>')
                            .append($('<td>')
                                .text(record.date)
                            )
                            .append($('<td>')
                                .text(record.environment)
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](record.ad_requests, 0))
                            )
                            .append($('<td>')
                                .text(window["formatMoney"](record.impressions_good, 0))
                            )
                            .append($('<td>')
                                .text("$" + window["formatMoney"](record.revenue_total, 2))
                            )
                            .append($('<td>')
                                .text(isNaN(record.revenue_total / (record.impressions_good / 1000))?0:window["formatMoney"](record.revenue_total / (record.impressions_good / 1000), 2))
                            )
                            .append($('<td>')
                                .attr('class', 'success')
                                .text(isNaN(record.revenue_total / (record.ad_requests / 1000000))?0:window["formatMoney"](record.revenue_total / (record.ad_requests / 1000000), 2) + "$")
                            )

                        );
                }
                var table = $('#overall-performance-container-table').DataTable({
                    orderCellsTop: true,
                    initComplete: function() {
                        var api = this.api();

                        // On every keypress in this input
                        $(".filter-overall")
                            .on('change', function(e) {
                                // e.stopPropagation();

                                var colIdx = 1;
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != '' ?
                                        regexr.replace('{search}', '(((' + this.value + ')))') :
                                        '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                                appendMediaSourceCharts();
                            });
                    },
                });

            }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
        }




    });


    function processChartEntity(List, chartId, keyname) {
        try {
            for (key of Object.keys(List)) {
                List[key].revenue_total = parseFloat(List[key].revenue_total);
                const chartIndex = window["DoughnutCharts"][chartId].data.findIndex(x => x[keyname] === key);
                if (chartIndex != -1)
                    window["DoughnutCharts"][chartId].data[chartIndex].value += List[key].revenue_total;
                else {
                    let toPush = { value: List[key].revenue_total };
                    toPush[keyname] = key;
                    window["DoughnutCharts"][chartId].data.push(toPush);
                    window["DoughnutCharts"][chartId].labels.push(key);
                }
            }
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }


    function appendEnvTable(EnvData, seat) {
        try {
            if ("ctv" in EnvData == false) EnvData["ctv"] = { revenue_total: 0.0 };
            if ("mobile_app" in EnvData == false) EnvData["mobile_app"] = { revenue_total: 0.0 };
            var total = _(EnvData).values().sumBy('revenue_total').toFixed(2);
            var table = $("table#environment-type");
            table.find('tbody')
                .append($('<tr>')
                    .append($('<td>')
                        .text(seat.name)
                    )
                    .append($('<td>')
                        .text("$" + (window["formatMoney"](EnvData["ctv"].revenue_total, 2)))
                    )
                    .append($('<td>')
                        .text(isNaN(EnvData["ctv"].revenue_total / total)?0:"%" + (window["formatMoney"](EnvData["ctv"].revenue_total / total * 100, 2)))
                    )
                    .append($('<td>')
                        .text("$" + (window["formatMoney"](EnvData["mobile_app"].revenue_total, 2)))
                    )
                    .append($('<td>')
                        .text(isNaN(EnvData["mobile_app"].revenue_total / total)?0:"%" + (window["formatMoney"](EnvData["mobile_app"].revenue_total / total * 100, 2)))
                    )
                );
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }

    function appendTopAdvertiser(advData, seat = false) {
        try {
            var table = $("table#advertiser-type");
            for (let index = 0; index < advData.length; index++) {
                const record = advData[index];
                if ("advertiser" in record == false) continue;
                table.find('tbody')
                    .append($('<tr>')
                        .append($('<td>')
                            .text(seat ? seat : record.seat)
                        )
                        .append($('<td>')
                            .text("#" + record.advertiser.id + " " + record.advertiser.name)
                        )
                        .append($('<td>')
                            .text(record.impressions_good)
                        )
                        .append($('<td>')
                            .text("$" + record.revenue_total.toFixed(2))
                        )
                        .append($('<td>')
                            .text("%" + record.fill_rate_ad_opportunities)
                        )

                    );
            }
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }

    function appendTopSuppliers(srcDate, seat = false) {
        try {
            var table = $("table#source-type");
            for (let index = 0; index < srcDate.length; index++) {
                const record = srcDate[index];
                if ("source" in record == false) continue;
                table.find('tbody')
                    .append($('<tr>')
                        .append($('<td>')
                            .text(seat ? seat : record.seat)
                        )
                        .append($('<td>')
                            .text("#" + record.source.id)
                        )
                        .append($('<td>')
                            .text(record.impressions_good)
                        )
                        .append($('<td>')
                            .text("$" + record.revenue_total.toFixed(2))
                        )
                        .append($('<td>')
                            .text("%" + record.fill_rate_ad_opportunities)
                        )

                    );
            }
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }


    function appendOverallPerformanceChart(datasets) {
        try {
            if (window["overallPerformance"]) window["overallPerformance"].destroy();
            var ctx = document.getElementById("overall_performance_chart").getContext('2d');
            window["overallPerformance"] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: datasets[0].data.map(k => k.x),
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            }
                        }
                    },
                    elements: {
                        line: {
                            tension: 0,
                            // shadow / fill
                            fill: false
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                steps: 20,
                                stepValue: 1,
                                max: 20
                            },
                            scaleLabel: {
                                display: true,
                                labelString: ' $/ AD Req in Mil '
                            }
                        }]
                    },
                }
            });

            $(".isResizable").resizable();
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }

    function appendImpressionChart(datasets) {
        try {
            if (window["impressionChart"]) window["impressionChart"].destroy();
            var ctx = document.getElementById("impression_chart").getContext('2d');
            window["impressionChart"] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({ length: 25 }, (v, k) => k),
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            }
                        }
                    },
                    elements: {
                        line: {
                            tension: 0,
                            // shadow / fill
                            fill: false
                        }
                    }
                }
            });

            $(".isResizable").resizable();
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }

    function appendDonughtChart(id) {
        try {
            if ("chart" in window["DoughnutCharts"][id]) window["DoughnutCharts"][id].chart.destroy();

            var ctx = document.getElementById("Donught_" + id).getContext('2d');
            window["DoughnutCharts"][id].chart = new Chart(ctx, {
                type: 'doughnut',

                data: {
                    datasets: [{
                        data: window["DoughnutCharts"][id].data,

                        backgroundColor: [
                            "#878BB6",
                            "#4ACAB4",
                            "#FF8153",
                            "#FFEA88",
                            "#2ecc71",
                            "#3498db",
                        ]
                    }],
                    labels: window["DoughnutCharts"][id].labels
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    title: {
                        display: true,
                        position: "top",
                        text: id in chartTitles ? chartTitles[id] : "Revenue by " + id,
                        fontSize: 13,
                        fontColor: "#111"
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            precision: 2,
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            });
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }

    window['filterEcludedChannels'] = async(seatId, parameters, res) => {
        try {
            let seatExChannelsParams = {...parameters };
            seatExChannelsParams.fields += ',channel';
            seatExChannelsParams.report += ',channel';
            seatExChannelsParams.channel = seats[seatId].excluded_channels.join(',');

            const excludedChannels = await seats[seatId].api.request(seatExChannelsParams).catch(e => {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
                if (e == 401) swal(e.name,e.message,'error');//top.location.reload();
            });
            for (const [index, record] of excludedChannels.data.entries()) {

                const dataIdx = res.data.findIndex(m => {
                    for (param of parameters.report.split(',')) {
                        if (typeof m[param] == 'object' && m[param].id != record[param].id)
                            return false;
                        else if (typeof m[param] != 'object' && m[param] != record[param])
                            return false;
                    }
                    return true;
                })

                if (dataIdx != -1) {
                    res.data[dataIdx].excluded_impressions = "excluded_impressions" in res.data[dataIdx] && res.data[dataIdx].excluded_impressions > 0 ? res.data[dataIdx].excluded_impressions + record.impressions_good : record.impressions_good;
                    res.data[dataIdx].excluded_revenue = "excluded_revenue" in res.data[dataIdx] && res.data[dataIdx].excluded_revenue > 0 ? res.data[dataIdx].excluded_revenue + record.revenue_channel : parseFloat(record.revenue_channel);
                } else if ("excluded_impressions" in res.data[dataIdx] == false) {
                    res.data[dataIdx].excluded_impressions = 0;
                    res.data[dataIdx].excluded_revenue = 0;
                }

            }

            return res;
        }catch (e) {
                console.log("Error: "+e);
                swal(e.name,e.message,"error");
                hide_loader();
            }
    }


    window["momentTime"] = () => moment(new Date().toLocaleString('en-US', { timeZone: 'Atlantic/Faroe' })).utcOffset(0, true);

})(jQuery);
