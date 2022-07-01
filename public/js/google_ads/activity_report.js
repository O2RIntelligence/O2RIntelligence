class ActivityReport extends GoogleAdsManager {
  constructor() {
    super();

    this.getActivityReportData = this.getActivityReportData.bind(this);
    this.renderActivityTable = this.renderActivityTable.bind(this);
    this.prepareReportTable = this.prepareReportTable.bind(this);

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
    });
    this.getActivityReportData();
    this.prepareReportTable();
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
    console.log(self.state.reportData.monthlyForecastData.totalData);
    const data = self.state.reportData.monthlyForecastData.totalData;

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
  }
}

const ACTIVITY_REPORT = new ActivityReport();
ACTIVITY_REPORT.init();