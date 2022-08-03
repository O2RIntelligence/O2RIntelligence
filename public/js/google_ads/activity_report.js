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

  initiateDoughnutChart(datasets) {
    $('#activityReportDonutChart').replaceWith('<canvas id="activityReportDonutChart" style="height:302px"></canvas>');
    
    var ctx = document.getElementById("activityReportDonutChart").getContext('2d');
    console.log('donut chart dataset', datasets)
    var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: datasets,
      // data: {
      //   labels: ["Tokyo",	"Mumbai",	"Mexico City",	"Shanghai"],
      //   datasets: [{
      //     data: [500,	50,	2424,	14040], // Specify the data values array
      //     borderColor: ['#2196f38c', '#f443368c', '#3f51b570', '#00968896'], // Add custom color border
      //     backgroundColor: ['#2196f38c', '#f443368c', '#3f51b570', '#00968896'], // Add custom color background (Points and Fill)
      //     borderWidth: 1 // Specify bar border width
      //   }]},
      options: {
        responsive: true, // Instruct chart js to respond nicely.
        maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
      }
    });
  }

  initiateLineChart(datasets) {
    $('#activityReportLineChart').replaceWith('<canvas id="activityReportLineChart" style="height:302px"></canvas>');
    
    new Chart("activityReportLineChart", {
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
        legend: { display: true }
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

    if (self.state.reportData && self.state.reportTable) {
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
          item?.date ?? "",
          item?.account_name ?? "",
          item?.cost ? `$${item?.cost}` : "",
          `$${Number(item?.account_budget ?? 0).toFixed(2)}`,
          `${Number(item?.budget_usage_percent ?? 0).toFixed(2)}%`,
          `$${Number(item?.monthly_run_rate ?? 0).toFixed(2)}`
        ]);
      }
      table.draw();

      self.populateLineChartData(account_type);
      self.populateDoughnutChartData(account_type);
    }
  }

  populateDoughnutChartData(type) {
    const self = this;
    let activityReportState = self.state.reportData;

    if (activityReportState) {
      let data = activityReportState?.donutChartData;
      let graphData = this.utils.createDoughnutChartData(data?.subAccountData);
      console.log("DOUNT CHART DATASET", graphData);

      self.initiateDoughnutChart(graphData);
    }
  }

  populateLineChartData(type) {
    const self = this;
    let activityReportState = self.state.reportData;

    if (activityReportState) {
      let data = activityReportState?.hourlyCostChartData;
      let labels = data?.label;

      let graphDataList = data?.data;
      let graphData = [];

      if (type === "general") {
        graphData = this.utils.createChartData(labels, [
          {
            name: "General",
            data: graphDataList,
          }
        ], "hourly_cost");

      } else if (type === "master_accounts") {
        graphData = this.utils.createChartData(labels, data?.masterAccountData);
      } else if (type === "sub_accounts") {
        graphData = this.utils.createChartData(labels, data?.subAccountData);
      }

      console.log("Line chart graphdata", graphData);

      self.initiateLineChart(graphData);
    }
  }
}

const ACTIVITY_REPORT = new ActivityReport();
ACTIVITY_REPORT.init();