class ActivityReport extends GoogleAdsManager {
  constructor() {
    super();

    this.getActivityReportData = this.getActivityReportData.bind(this);
    this.renderActivityTable = this.renderActivityTable.bind(this);
    this.prepareReportTable = this.prepareReportTable.bind(this);
    this.initiateLineChart = this.initiateLineChart.bind(this);

    this.initialState = {
      reportData: [],
      reportTable: null,
    };
  }

  init() {
    super.init();
    const self = this;
    super.dataFilterActivities({
      onChange: self.getActivityReportData,
      onPressFilter: self.renderActivityTable,
    });
    this.getActivityReportData();
    this.prepareReportTable();
  }

  initiateLineChart(datasets) {
    //--------------
    //- AREA CHART -
    //--------------

    $("#activityReportLineChart").replaceWith('<canvas id="activityReportLineChart" style="height:302px"></canvas>');

    // Get context with jQuery - using jQuery's .get() method.
    let lineChartCanvasOne = $("#activityReportLineChart").get(0).getContext("2d");
    let lineChartOne = new Chart(lineChartCanvasOne);
    // This will get the first returned node in the jQuery collection.

    let lineChartOneData = datasets;

    let lineChartOneOptions = {
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
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (let i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
    };

    //-------------
    //- LINE CHART -
    //--------------
    lineChartOneOptions.datasetFill = false;
    const lineChartOneInstance = lineChartOne.Line(lineChartOneData, lineChartOneOptions);
    // document.getElementById("line-chart-one-legend").innerHTML = lineChartOneInstance.generateLegend();
  }

  initiateDoughnutChart() {
    var ctx = document.getElementById("activityReportDonutChart").getContext('2d');

    var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["Tokyo",	"Mumbai",	"Mexico City",	"Shanghai"],
        datasets: [{
          data: [500,	50,	2424,	14040], // Specify the data values array

          borderColor: ['#2196f38c', '#f443368c', '#3f51b570', '#00968896'], // Add custom color border
          backgroundColor: ['#2196f38c', '#f443368c', '#3f51b570', '#00968896'], // Add custom color background (Points and Fill)
          borderWidth: 1 // Specify bar border width
        }]},
      options: {
        responsive: true, // Instruct chart js to respond nicely.
        maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
      }
    });
  }

  prepareReportTable() {
    const self = this;
    self.setState({
      reportTable:
        $('#reportTable').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        })
    });
  }

  getActivityReportData() {
    const self = this;
    const { date_filter } = self.dataFilterState;
    self.sendHttpRequest({
      method: "post",
      url: "/api/google-ads/activity-report/data",
      useCsrf: true,
      data: {
        startDate: date_filter?.from ?? "",
        endDate: date_filter?.to ?? "",
      },
      onSuccess(data) {
        self.setState({
          reportData: data,
        });

        self.renderActivityTable();
      }
    });
  }

  renderActivityTable() {
    const self = this;
    const { account_type, selected_accounts } = self.dataFilterState;
    let data = self.state.reportData.monthlyForecastData.totalData ?? [];

    if (account_type !== "general" && selected_accounts && selected_accounts?.length > 0) {
      let filteredData = [];

      if (account_type === "master_accounts" || account_type === "sub_accounts") {
        data = account_type === "master_accounts" ? self.state.reportData.monthlyForecastData.masterAccountData
          : account_type === "sub_accounts" ? self.state.reportData.monthlyForecastData.subAccountData : [];

        selected_accounts?.forEach(function (accountId) {
          data?.forEach(function (item) {
            if (Number(item?.account_id) === Number(accountId)) {
              filteredData = [
                ...filteredData,
                ...item?.dataTableData ?? [],
              ];
            }
          });
        });
      }

      data = filteredData;
    }

    const table = self.state.reportTable;
    table.clear();

    for (let item of data) {
      table.row.add([
        item?.account_name ?? "",
        item?.cost ?? "",
        Number(item?.account_budget ?? 0).toFixed(2),
        Number(item?.budget_usage_percent ?? 0).toFixed(2),
        Number(item?.monthly_run_rate ?? 0).toFixed(2)
      ]);
    }
    table.draw();

    self.populateLineChartData("general");
    self.initiateDoughnutChart();
  }

  populateLineChartData(type) {
    const self = this;
    let activityReportState = self.state.reportData;

    if (activityReportState) {
      let data = activityReportState?.hourlyCostChartData;
      let labels = data?.label;
      console.log(data);
      
      let graphDataList = data?.data;
      let graphData = [];

      if (type === "general") {
        graphData = this.utils.createChartData(labels, [
          {
            name: "TEST",
            data: graphDataList,
          }
        ], "hourly_cost");

      } else if (type === "master_accounts") {
        graphData = this.utils.createChartData(labels, data?.masterAccountData);
      } else if (type === "sub_accounts") {
        graphData = this.utils.createChartData(labels, data?.subAccountData);
      }

      console.log(graphData);

      self.initiateLineChart(graphData);
    }
  }
}

const ACTIVITY_REPORT = new ActivityReport();
ACTIVITY_REPORT.init();