class Dashboard extends GoogleAdsManager {
  constructor() {
    super();

    this.initialState = {
      dashboard: {},
    };

    this.getDashboardData = this.getDashboardData.bind(this);
    this.populateCards = this.populateCards.bind(this);
    this.chartTypeActivities = this.chartTypeActivities.bind(this);
  }

  init() {
    super.init();
    const self = this;

    self.initiateLineChartOne();
    self.getDashboardData();
    self.chartTypeActivities();
  }

  initiateLineChartOne() {
    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    let lineChartCanvasOne = $("#line_chart_one").get(0).getContext("2d");
    let lineChartOne = new Chart(lineChartCanvasOne);
    // This will get the first returned node in the jQuery collection.

    let lineChartOneData = {
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
        }
      ]
    };

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


    // Line chart 2
    let lineChartCanvasTwo = $("#line_chart_two").get(0).getContext("2d");
    let lineChartTwo = new Chart(lineChartCanvasTwo);
// This will get the first returned node in the jQuery collection.

    let lineChartTwoData = {
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
    $(document).on('click', '[data-action="chat_type"]', function () {
      const chartType = $(this).find('input').val();

      alert(chartType);
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
}

const DASHBOARD = new Dashboard();
DASHBOARD.init();