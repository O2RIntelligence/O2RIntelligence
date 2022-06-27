class Dashboard extends GoogleAdsManager {
  constructor() {
    super();

    this.initialState = {
      dashboard: {},
    };

    this.getDashboardData = this.getDashboardData.bind(this);
    this.populateCards = this.populateCards.bind(this);
    this.chartTypeActivities = this.chartTypeActivities.bind(this);
    this.initiateLineChartOne = this.initiateLineChartOne.bind(this);
    this.initiateLineChartTwo = this.initiateLineChartTwo.bind(this);
  }

  init() {
    super.init();
    const self = this;

    self.getDashboardData();
    self.chartTypeActivities();
  }

  initiateLineChartOne(datasets) {
    //--------------
    //- AREA CHART -
    //--------------

    $("#line_chart_one").replaceWith('<canvas id="line_chart_one" style="width:100%;height:auto;"></canvas>');

    // Get context with jQuery - using jQuery's .get() method.
    let lineChartCanvasOne = $("#line_chart_one").get(0).getContext("2d");
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
    lineChartOne.Line(lineChartOneData, lineChartOneOptions);
  }

  initiateLineChartTwo(datasets) {
    // Line chart 2
    $("#line_chart_two").replaceWith('<canvas id="line_chart_two" style="width:100%;height:auto;"></canvas>');
    let lineChartCanvasTwo = $("#line_chart_two").get(0).getContext("2d");
    let lineChartTwo = new Chart(lineChartCanvasTwo);
// This will get the first returned node in the jQuery collection.

    let lineChartTwoData = datasets;

    let lineChartTwoOptions = {
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
    lineChartTwoOptions.datasetFill = false;
    lineChartTwo.Line(lineChartTwoData, lineChartTwoOptions);
  }

  chartTypeActivities() {
    const self = this;

    $(document).on('click', '[data-action="chat_type"]', function () {
      const chartType = $(this).attr('data-name');
      self.populateChartData("general", chartType);
      self.populateChartData("general", chartType);
    });
  }

  getDashboardData() {
    const self = this;

    self.sendHttpRequest({
      method: "post",
      url: "/google-ads/dashboard/data",
      useCsrf: true,
      data: {
        startDate: '2022-06-27',
        endDate: '2022-06-27',
      },
      onSuccess(data) {
        self.setState({
          dashboard: data,
        });

        self.populateCards();
        self.populateChartData("general", "daily_cost");
        self.populateChartData("general", "hourly_cost");
      }
    });
  }

  /**
   * Populate the dashboard cards
   */
  populateCards() {
    const self = this;

    if (self.state.dashboard?.dailyProjection) {
      let { totalDailyCost, totalDailyRunRate } = self.state.dashboard?.dailyProjection;
      totalDailyCost = Number(totalDailyCost)?.toFixed(2);
      totalDailyRunRate = Number(totalDailyRunRate)?.toFixed(2);

      $('#daily_total_cost').text(`${totalDailyCost}$`);
      $('#daily_run_rate').text(`${totalDailyRunRate}$`);
    }

    if (self.state.dashboard?.monthlyProjection) {
      let { totalMonthlyCost, totalMonthlyRunRate } = self.state.dashboard?.monthlyProjection;
      totalMonthlyCost = Number(totalMonthlyCost)?.toFixed(2);
      totalMonthlyRunRate = Number(totalMonthlyRunRate)?.toFixed(2);

      $('#monthly_total_cost').text(`${totalMonthlyCost}$`);
      $('#monthly_run_rate').text(`${totalMonthlyRunRate}$`);
    }
  }

  /**
   * Populate chart data
   * @param type {"general" | "master_accounts" | "sub_accounts"}
   * @param chartType {"daily_cost" | "hourly_cost"}
   */
  populateChartData(type, chartType) {
    const self = this;
    let dashboardState = self.state.dashboard;

    if (dashboardState) {
      let data = chartType === "hourly_cost" ? dashboardState?.hourlyCostGraphData : chartType === "daily_cost" ? dashboardState?.dailyCostGraphData : null;
      let labels = chartType === "daily_cost" ? data?.totalDailyCostGraphLabel : data?.totalHourlyCostGraphData;
      let graphData = [];

      if (type === "general") {
        graphData = this.utils.createChartData(labels, [
          {
            name: "TEST",
            [chartType === "daily_cost" ? "dailyCostGraphData" : "hourlyCostGraphData"]: chartType === "daily_cost" ? data?.totalDailyCostGraphData : data?.totalHourlyCostGraphData,
          }
        ], chartType);

      } else if(type === "master_accounts") {
        graphData = this.utils.createChartData(labels, data?.masterAccountData, chartType);
      } else if(type === "sub_accounts") {
        graphData = this.utils.createChartData(labels, data?.subAccountData, chartType);
      }

      if(chartType === "daily_cost") {
        self.initiateLineChartOne(graphData);
      } else if(chartType === "hourly_cost") {
        self.initiateLineChartTwo(graphData);
      }
    }
  }
}

const DASHBOARD = new Dashboard();
DASHBOARD.init();