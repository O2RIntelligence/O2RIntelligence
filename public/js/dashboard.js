(function($) {
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

    var dateParams = {
        "fields": 'ad_requests,ad_opportunities,impressions_good,revenue_channel,ecpm_channel,revenue_total,ecpm,fill_rate_ad_opportunities,scoring_pixalate_s2s_sas_request,environment',
        "is_rtb": 'not_apply',
        "limit": 10000,
        "page": 1,
        "report": "environment",
        "strategy": 'last-collection',
        "type": 'ssp_statistic',
        "tz": 'GMT'
    };

    let pageData = {};

    var campaignParams = {
        "campaign": "1120",
        "fields": 'campaign,environment,revenue_total',
        "is_rtb": 'not_apply',
        "limit": 100,
        "page": 1,
        "report": "campaign,environment",
        "strategy": 'last-collection',
        "type": 'ssp_statistic',
        "tz": 'GMT'
    };

    var page_type = 'dates';

    $("input[name=page_type]").on('change', function() {
        page_type = $(this).val();
        appendCharts();
    });

    function start_loader() {
        $(".loader").show();
    }

    function hide_loader() {
        $(".loader").hide();
    }

    async function getCharts(chartId, dateObject, paramKey) {
        const params = {...dateParams };

        params.report = `${paramKey},${params.report}`;
        params.fields = `${paramKey},${params.fields}`;
        params.date_from = dateObject.from;
        params.date_to = dateObject.to;

        const Camparams = {...campaignParams };
        Camparams.report = `${paramKey},${Camparams.report}`;
        Camparams.fields = `${paramKey},${Camparams.fields}`;
        Camparams.date_from = dateObject.from;
        Camparams.date_to = dateObject.to;

        if (paramKey + "_" + chartId in pageData == false) pageData[paramKey + "_" + chartId] = { seats: {}, dates: {} };

        let total_netrevenue = 0;

        for (seatId of Object.keys(window["seats"])) {
            const seat = window["seats"][seatId];

            let campaignRequest;
            try {
                campaignRequest = await seat.api.request(Camparams);
            } catch (error) {
                console.log(error);
                if (error == 401) top.location.reload();
                continue;
            }

            // get impressions & data
            let response = await seat.api.request(params).catch(e => {
                if (e == 401) top.location.reload();
            });

            // excluded channels data
            if (seat.excluded_channels.length) {
                response = await filterEcludedChannels(seatId, params, response);
            }

            // calculate cost and revenue uniqly to platform << date doesn't matter here >>
            let RecordsCalculated = response.data.map(record => {
                const marketplace_fee = campaignRequest.data.find(m => m[paramKey].id == record[paramKey].id && m.environment.id == record.environment.id);
                return {
                    'date': record[paramKey].id,
                    'environment': record.environment.id,
                    'impressions_good': record.impressions_good,
                    'excluded_impressions': record.excluded_impressions ? record.excluded_impressions : 0,
                    'excluded_revenue': record.excluded_revenue ? record.excluded_revenue : 0,
                    'revenue_total': record.revenue_total,
                    'scanned_requests': record.scoring_pixalate_s2s_sas_request,
                    'marketplace_fee': marketplace_fee ? parseFloat(marketplace_fee.revenue_total) * 0.2 : 0
                };
            });

            RecordsCalculated = RecordsCalculated.map(record => window["mapRecord"](seat, record));
            const RecordsGrouppedCalculated = _(RecordsCalculated).groupBy(function(record) {
                    return record.date;
                })
                .map((objs, key) => ({
                    'date': key,
                    seat: seatId,
                    advertising_fee: _.sumBy(objs, 'advertising_fee'),
                    excluded_impressions: _.sumBy(objs, 'excluded_impressions'),
                    excluded_revenue: _.sumBy(objs, 'excluded_revenue'),
                    gross_profit: _.sumBy(objs, 'gross_profit'),
                    impressions_good: _.sumBy(objs, 'impressions_good'),
                    marketplace_fee: _.sumBy(objs, 'marketplace_fee'),
                    media_cost: _.sumBy(objs, 'media_cost'),
                    net_income: _.sumBy(objs, 'net_income'),
                    net_media_cost: _.sumBy(objs, 'net_media_cost'),
                    net_profit: _.sumBy(objs, 'net_profit'),
                    net_revenue: _.sumBy(objs, 'net_revenue'),
                    operation_fee: _.sumBy(objs, 'operation_fee'),
                    partner_fee: _.sumBy(objs, 'partner_fee'),
                    revenue_total: _.sumBy(objs, 'revenue_total'),
                    scanned_requests: _.sumBy(objs, 'scanned_requests'),
                    scoring_fee: _.sumBy(objs, 'scoring_fee'),
                }))
                .keyBy("date")
                .value();


            pageData[paramKey + "_" + chartId].seats[seat.name] = RecordsGrouppedCalculated;

            // if(seat.name in pageData[paramKey + "_" + chartId] == false) pageData[paramKey + "_" + chartId][seat.name] = {};
            for (const timeframe of Object.keys(RecordsGrouppedCalculated)) {
                // dates or hour processing
                total_netrevenue += parseFloat(RecordsGrouppedCalculated[timeframe].net_income);
                if (timeframe in pageData[paramKey + "_" + chartId].dates == false) {
                    pageData[paramKey + "_" + chartId].dates[timeframe] = Object.assign({}, RecordsGrouppedCalculated[timeframe]);
                    continue;
                } // inserting first value to array

                for (const metric of Object.keys(RecordsGrouppedCalculated[timeframe])) {
                    if (_.isNumber(RecordsGrouppedCalculated[timeframe][metric])) {
                        pageData[paramKey + "_" + chartId].dates[timeframe][metric] = RecordsGrouppedCalculated[timeframe][metric] + pageData[paramKey + "_" + chartId].dates[timeframe][metric];
                    }
                }
            }

        }

        if (paramKey == 'date') {
            console.log(parseInt(momentTime().format("DD")));
            console.log(momentTime().daysInMonth());
            console.log(total_netrevenue);

            var monthlyProjection = (total_netrevenue / (parseInt(momentTime().format("DD")) - 1)) * momentTime().daysInMonth();
            $("#MNI_Projection").text(window["formatMoney"](monthlyProjection, 2) + "$");
        } else {
            console.log(parseInt(momentTime().format("HH")));
            var dailyProjection = total_netrevenue / parseInt(momentTime().format("HH")) * 24;
            $("#DNI_Projection").text(window["formatMoney"](dailyProjection, 2) + "$");
        }


        appendCharts();
    }
    $(function() {
        start_loader();
        getCharts('DNetIncome', { from: momentTime().startOf('month').format("YYYY-MM-DD"), to: momentTime().subtract(1, 'd').format("YYYY-MM-DD") }, 'date');
        getCharts('HNetIncome', { from: momentTime().format("YYYY-MM-DD"), to: momentTime().format("YYYY-MM-DD") }, 'hour');
    });

    function appendCharts() {
        for (scope of Object.keys(pageData)) {
            const chartRevenueData = [];
            const { 0: paramKey, 1: chartId } = scope.split('_');
            let index = 0;

            if (page_type == 'seats') {
                for (const seatName of Object.keys(pageData[scope][page_type])) {
                    chartRevenueData.push({
                        label: seatName,
                        data: Object.keys(pageData[scope][page_type][seatName]).map(m => ({
                            x: pageData[scope][page_type][seatName][m].date.split('-')[2],
                            y: pageData[scope][page_type][seatName][m].net_income
                        })),
                        borderColor: colors[index],
                        pointColor: "rgba(200,122,20,1)",
                    });
                    index++;
                }
            } else {
                chartRevenueData.push({
                    label: 'Seats overview',
                    data: Object.keys(pageData[scope][page_type]).map(m => ({
                        x: pageData[scope][page_type][m].date.split('-')[2],
                        y: pageData[scope][page_type][m].net_income
                    })),
                    borderColor: colors[index],
                    pointColor: "rgba(200,122,20,1)",
                });
            }
            appendDashboardChart(chartRevenueData, paramKey, chartId);

            if (paramKey == 'hour')
                hide_loader();
        }
    }


    function appendDashboardChart(datasets, paramKey, chartId) {
        if (window[chartId]) window[chartId].destroy();
        var ctx = document.getElementById(chartId + "_container").getContext('2d');
        window[chartId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: paramKey == 'date' ? Array.from({ length: momentTime().daysInMonth() }, (v, k) => k + 1) : Array.from({ length: 24 }, (v, k) => k),
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
    }


})(jQuery);