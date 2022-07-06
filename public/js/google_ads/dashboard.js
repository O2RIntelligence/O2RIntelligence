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
    super.dataFilterActivities({
      onChange: self.getDashboardData,
      onPressFilter: self.getDashboardData,
    });
    self.getDashboardData();
    self.chartTypeActivities();
  }

  initiateLineChartOne(datasets) {
    $('#line_chart_one').replaceWith('<canvas id="line_chart_one" style="width:100%;height: 400px;"></canvas>');
    new Chart("line_chart_one", {
      type: "line",
      data: datasets,
      // data: {
      //   labels: xValues,
      //   datasets: [{
      //     label: "My First dataset 1",
      //     data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478],
      //     borderColor: "red",
      //     fill: false
      //   },
      //   {
      //     label: "My First dataset 2",
      //     data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      //     borderColor: "green",
      //     fill: false
      //   }, {
      //     label: "My First dataset 3",
      //     data: [300,700,2000,5000,6000,4000,2000,1000,200,100],
      //     borderColor: "blue",
      //     fill: false
      //   }
      // ]
      // },
      options: {
        legend: {display: true}
      }
    });
  };

  initiateLineChartTwo(datasets) {
    $('#line_chart_two').replaceWith('<canvas id="line_chart_two" style="width:100%;height: 400px;"></canvas>');
    
    new Chart("line_chart_two", {
      type: "line",
      data: datasets,
      // data: {
      //   labels: xValues,
      //   datasets: [{
      //     label: "My First dataset 1",
      //     data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478],
      //     borderColor: "red",
      //     fill: false
      //   },
      //   {
      //     label: "My First dataset 2",
      //     data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      //     borderColor: "green",
      //     fill: false
      //   }, {
      //     label: "My First dataset 3",
      //     data: [300,700,2000,5000,6000,4000,2000,1000,200,100],
      //     borderColor: "blue",
      //     fill: false
      //   }
      // ]
      // },
      options: {
        legend: {display: true}
      }
    });
  }

  chartTypeActivities() {
    const self = this;

    // $(document).on('click', '[data-action="account_type"]', function () {
    //   const chartType = $(this).attr('data-name');
    //   self.populateChartData(chartType, "daily_cost");
    //   self.populateChartData(chartType, "hourly_cost");
    // });
  }

  getDashboardData() {
    const self = this;
    const {account_type, date_filter} = self.dataFilterState; 

    self.sendHttpRequest({
      method: "post",
      url: "/google-ads/dashboard/data",
      useCsrf: true,
      data: {
        startDate: date_filter?.from ?? "",
        endDate: date_filter?.to ?? "",
      },
      onSuccess(data) {
        self.setState({
          dashboard: data,
        });

        self.populateCards();
        self.populateChartData(account_type, "daily_cost");
        self.populateChartData(account_type, "hourly_cost");
      }
    });
  }

  /**
   * Populate the dashboard cards
   */
  populateCards() {
    const self = this;

    if (self.state.dashboard?.dailyProjection) {
      let {totalDailyCost, totalDailyRunRate} = self.state.dashboard?.dailyProjection;
      totalDailyCost = Number(totalDailyCost)?.toFixed(2);
      totalDailyRunRate = Number(totalDailyRunRate)?.toFixed(2);

      $('#daily_total_cost').text(`${totalDailyCost}$`);
      $('#daily_run_rate').text(`${totalDailyRunRate}$`);
    }

    if (self.state.dashboard?.monthlyProjection) {
      let {totalMonthlyCost, totalMonthlyRunRate} = self.state.dashboard?.monthlyProjection;
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
    const { selected_accounts } = self.dataFilterState;

    if (dashboardState) {
      let data = chartType === "hourly_cost" ? dashboardState?.hourlyCostGraphData : chartType === "daily_cost" ? dashboardState?.dailyCostGraphData : null;
      let labels = data?.label;
      console.log(chartType);
      let graphDataList = type === "general" ? data?.data : type === "master_accounts" ? data?.masterAccountData : type === "sub_accounts" ? data?.subAccountData : [];
      let graphData = [];

      if (type === "general") {
        graphData = this.utils.createChartData(labels, [
          {
            name: "General",
            data: graphDataList,
          }
        ], chartType);

      } else if (type === "master_accounts" || type === "sub_accounts") {
        if(selected_accounts && selected_accounts?.length > 0) {
          let filteredData = [];

          console.log("graphDataList", graphDataList)
          selected_accounts?.forEach(function (accountId) {
            graphDataList?.forEach(function (item) {
              if (Number(item?.account_id) === Number(accountId)) {
                console.log(item)
                filteredData.push(item);
              }
            });
          });

          graphDataList = filteredData;
        }

        graphData = this.utils.createChartData(labels, graphDataList);
      }

      if (chartType === "daily_cost") {
        self.initiateLineChartOne(graphData);
      } else if (chartType === "hourly_cost") {
        self.initiateLineChartTwo(graphData);
      }

      console.log("graphData", graphData);
    }
  }
}

const DASHBOARD = new Dashboard();
DASHBOARD.init();