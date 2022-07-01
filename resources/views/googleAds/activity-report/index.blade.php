@section('css')
    {{-- page css link here  --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css">
    <style>
        #donutChart {
            height: 250px !important;
            width: auto !important
        }

        #donut-legend ul {
            list-style: none;
            margin-top: 10px
        }

        #donut-legend ul li {
            display: inline-block;
            position: relative;
            margin: 4px 6px;
            border-radius: 5px;
            padding: 2px 8px 2px 37px;
            font-size: 14px;
            cursor: default;
            -webkit-transition: background-color 200ms ease-in-out;
            -moz-transition: background-color 200ms ease-in-out;
            -o-transition: background-color 200ms ease-in-out;
            transition: background-color 200ms ease-in-out;
        }

        #donut-legend li span {
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            width: 30px;
            height: 100%;
            border-radius: 5px;
        }
    </style>

@endsection
@extends('googleAds.partials.layout')
@section('content')
    {{--    <div class="content-wrapper" id="pjax-container" style="min-height: 860px;">--}}
    <style type="text/css"></style>

    <div id="app">
        <section class="content-header">
            <h1>
                Activity Report
                <small> </small>
            </h1>
            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{url('google-ads/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                    Activity report
                </li>
            </ol>
            <!-- breadcrumb end -->
        </section>

        @include('googleAds.filter')
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box grid-box">
                        <table class="table table-hover grid-table" id="reportTable">
                            <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Total Cost</th>
                                <th>Account Budget</th>
                                <th>Budget Usage %</th>
                                <th>Monthly Run Rate</th>
                            </tr>
                            </thead>
                            <!-- <tbody> 
                              <tr>
                                <td>Acount name one</td>
                                <td>10000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Acount name one</td>
                                <td>10000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Acount name one</td>
                                <td>10000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Acount name one</td>
                                <td>10000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Acount name one</td>
                                <td>10000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Acount name one</td>
                                <td>10000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>10</td>
                              </tr>
                            </tbody> -->
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Donut Chart</h3>
                        </div>
                        <div class="box-body text-center">
                            <canvas id="donutChart" style="height:250px"></canvas>
                            <div id="donut-legend" class="donut-legend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Line Chart</h3>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="lineChartOne" style="height:302px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection
@section('js')
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script>
    <script src="{{asset('new-dashboard/chart.js')}}"></script>

    <script>
      var current_page = "GoogleAds dashboard";
      const __csrf_token = "{{@csrf_token()}}";
    </script>
    <script src="/js/google_ads/google_ads_manager.js"></script>
    <script src="/js/google_ads/activity_report.js"></script>


    <script>
      $(function () {

        //--------------
        //- AREA CHART -
        //--------------

        // Get context with jQuery - using jQuery's .get() method.
        var lineChartCanvasOne = $("#lineChartOne").get(0).getContext("2d");
        var lineChartOne = new Chart(lineChartCanvasOne);
        // This will get the first returned node in the jQuery collection.

        var lineChartOneData = {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          datasets: [
            {
              label: "Electronics",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
              label: "Digital Goods",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [28, 48, 40, 19, 86, 27, 90]
            }
          ]
        };

        var lineChartOneOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
        };


        //-------------
        //- LINE CHART -
        //--------------
        lineChartOneOptions.datasetFill = false;
        lineChartOne.Line(lineChartOneData, lineChartOneOptions);


        /* ChartJS */
        var pieChartCanvas = document.getElementById("donutChart").getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [{
          value: 700,
          color: "#f56954",
          highlight: "#f56954",
          label: "Chrome",
          labelColor: 'white',
          labelFontSize: '16'
        }, {
          value: 500,
          color: "#00a65a",
          highlight: "#00a65a",
          label: "IE",
          labelColor: 'white',
          labelFontSize: '16'
        }, {
          value: 400,
          color: "#f39c12",
          highlight: "#f39c12",
          label: "FireFox",
          labelColor: 'white',
          labelFontSize: '16'
        }, {
          value: 600,
          color: "#00c0ef",
          highlight: "#00c0ef",
          label: "Safari",
          labelColor: 'white',
          labelFontSize: '16'
        }, {
          value: 300,
          color: "#3c8dbc",
          highlight: "#3c8dbc",
          label: "Opera",
          labelColor: 'white',
          labelFontSize: '16'
        }, {
          value: 100,
          color: "#d2d6de",
          highlight: "#d2d6de",
          label: "Navigator",
          labelColor: 'white',
          labelFontSize: '16'
        }];
        var pieOptions = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 50, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: true,
          legendTemplate: '<ul>' + '<% for (var i=0; i<segments.length; i++) { %>' + '<li>' + '<span style=\"background-color:<%=segments[i].fillColor%>\"></span>' + '<% if (segments[i].label) { %><%= segments[i].label %><% } %>' + '</li>' + '<% } %>' + '</ul>'
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        var myChart = pieChart.Doughnut(PieData, pieOptions);
        document.getElementById("donut-legend").innerHTML = myChart.generateLegend();
      });
    </script>
@endsection
