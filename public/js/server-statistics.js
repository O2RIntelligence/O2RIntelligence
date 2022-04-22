    $(function() {
        let EnvironmentFlaggedServers = {};
        const sleep = (ms)  =>  new Promise(resolve => setTimeout(resolve, ms));

        async function getStat(body) {

            const response = await fetch(`${admin_url}/environments/server-stats`, {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                body: JSON.stringify(body)
            });

            return response.json();
        }


        async function everySecond() {
            for (const environment of window["environments"]) {

                const timeStats = await getStat({
                    environmentId: environment.id,
                    type: 'realtime'
                });

                for (const timeframe of Object.keys(timeStats)) {
                    const frameData = _(timeStats[timeframe]).reduce(function(acc, obj) {
                        _(obj).each(function(value, key) { acc[key] = (acc[key] ? acc[key] : 0) + value });
                        return acc;
                    }, {});


                    if($("#env-" + environment.id + "t-" + timeframe).length == 0) 
                        $('#environment-' + environment.id + " tbody").append( $('<tr>').attr('id', "env-" + environment.id + "t-" + timeframe) );
                    else 
                        $("#env-" + environment.id + "t-" + timeframe).html("");

                    $("#env-" + environment.id + "t-" + timeframe)
                    .append($('<td>')
                        .text(timeframe).attr('style', 'font-weight:bold;text-transform:capitalize;')
                    )
                    .append($('<td>')
                        .text(window["formatMoney"](frameData.ReqCount, 0))
                    )
                    .append($('<td>')
                        .text(window["formatMoney"](frameData.OptCount, 0))
                    )
                    .append($('<td>')
                        .text(window["formatMoney"](frameData.ImpCount, 0))
                    )
                    .append($('<td>')
                        .text(window["formatMoney"](frameData.OptCount / frameData.ReqCount * 100, 2) + "%")
                    )
                    .append($('<td>')
                        .text(window["formatMoney"](frameData.ImpCount / frameData.ReqCount * 100, 2) + "%")
                    );
                }
            }

        }

        // @todo, if slow, load multiple data together
        async function stats() {
            // const proxies

            if ($.fn.DataTable.isDataTable('#overall-stats')) {
                $('#overall-stats').DataTable().destroy();
                await sleep(200);
                $("#overall-stats").find('tbody').html("");
                console.log("reset");
            }

            for (const environment of window["environments"]) {
                // get fixed stats
                const fixedStats = await getStat({
                    environmentId: environment.id,
                    type: 'single'
                });

                // group and sum proxies for table
                const EnvironmentProxies = _(fixedStats.ByProxy).groupBy("account")
                    .map((objs, key) => ({
                        'proxyName': key,
                        'ImpCount': _.sumBy(objs, 'ImpCount'),
                        'OptCount': _.sumBy(objs, 'OptCount'),
                        'ReqCount': _.sumBy(objs, 'ReqCount'),
                    }))
                    .keyBy("proxyName")
                    .value();

                for (const proxyName of Object.keys(EnvironmentProxies)) {
                    const proxy = EnvironmentProxies[proxyName];
                    if ($("#proxyStat-" + proxyName).length == 0) {
                        $proxyDiv = $("#proxyClone").clone();
                        $proxyDiv.attr("id", "proxyStat-" + proxyName);
                        $proxyDiv.find('.heading-text').text(proxyName);
                        $proxyDiv.removeClass('hidden');
                        $proxyDiv.appendTo('#proxyStats');
                    }


                    const proxyStatDiv = $("#proxyStat-" + proxyName).find('tbody');

                    proxyStatDiv.append($('<tr>')
                        .append($('<td>')
                            .text("Env" + environment.id).attr('style', 'font-weight:bold;')
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](proxy.ImpCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](proxy.ReqCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](proxy.OptCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](proxy.OptCount / proxy.ReqCount * 100, 2) + "%")
                        )
                    );

                }
                for (const serverStat of fixedStats.ByServer) {
                    let sStatus = "On";
                    if(serverStat.ImpCount == 0) {
                        if(`env${environment.id}s${serverStat.Serverid}` in EnvironmentFlaggedServers) 
                            sStatus = "Off";
                        else 
                            EnvironmentFlaggedServers[`env${environment.id}s${serverStat.Serverid}`] = true;
                    } else if( `env${environment.id}s${serverStat.Serverid}` in EnvironmentFlaggedServers )
                            delete EnvironmentFlaggedServers[`env${environment.id}s${serverStat.Serverid}`];
                    
                    const appendFunction = sStatus == 'Off' ? 'prepend' : 'append';
                    $("#overall-stats tbody")[appendFunction](
                        $('<tr>')
                        .attr('id', `env${environment.id}s${serverStat.Serverid}`)
                        .append($('<td>')
                            .text(environment.id).attr('style', 'font-weight:bold;')
                        )
                        .append($('<td>')
                            .text(serverStat.Serverid).attr('style', 'font-weight:bold;')
                        )
                        .append($('<td>')
                            .text(serverStat.account).attr('style', 'font-weight:bold;')
                        )
                        .append($('<td>')
                            .text(serverStat.typestr)
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](serverStat.ReqCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](serverStat.ImpCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](serverStat.OptCount / serverStat.ReqCount * 100, 2) + "%")
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](serverStat.ReqCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](serverStat.ImpCount, 0))
                        )
                        .append($('<td>')
                            .text(window["formatMoney"](serverStat.OptCount / serverStat.ReqCount * 100, 2) + "%")
                        )
                        .append($('<td>')
                            .text(serverStat.typestr)
                        )
                        .append($('<td>')
                            .text(sStatus)
                        )

                    );

                }
                if($('#environment-' + environment.id).length == 0) {
                    $environmentDiv = $("#environmentClone").clone();
                    $environmentDiv.attr('id', 'environment-' + environment.id);
                    $environmentDiv.find('.heading-text').text("#" + environment.id);
                    $environmentDiv.find('.proxies-count').text(fixedStats.ByProxy.length);
                    $environmentDiv.find('.servers-count').text(fixedStats.ByServer.length);
                    $environmentDiv.removeClass('hidden');
                    $environmentDiv.appendTo('#environmentStats');
                }
                
            }
            


            var table = $('#overall-stats').DataTable({
                dom: 'Qlfrtip',
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    if ( aData[11] == "Off" ){      
                        $(nRow).addClass('bg-danger');
                    }
                }
            });

            if ($.fn.DataTable.isDataTable('.proxy-card table')) 
                $('.proxy-card table').DataTable().destroy();
        

            $('.proxy-card table').DataTable({
                paging: false,
                drawCallback: function() {
                    var api = this.api();
                    api.columns().eq(0).each(function(index) {
                        if (index == 0) return;
                        var sum = api.column(index).data().sum() / Object.keys(window["environments"]).length;
                        var sign = index == 4 ? "%" : "";
                        sum = window["formatMoney"](sum, 2);
                        sum = !sum ? '0' : sum;
                        $(api.column(index).footer()).html( sum + sign)
                    });
                }
            });


        }

        (async () => {
            await stats();
            await everySecond();

            setInterval( async() => {
                await stats();
            }, 10*60*1000); // 5 minutes
            
            while(true) {
                await everySecond();
                await sleep(1000);
            }

        })();

    });
