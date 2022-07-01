class ActivityReport extends GoogleAdsManager {
  constructor() {
    super();

    this.getActivityReportData = this.getActivityReportData.bind(this);
    this.initialState = {
      reportData: [],
    };
  }

  init() {
    super.init();
    const self = this;
    super.dataFilterActivities();
    this.getActivityReportData();
  }

  getActivityReportData() {
    const self = this;
    self.sendHttpRequest({
      method: "post",
      url: "/api/google-ads/activity-report/data",
      useCsrf: true,
      data: {
        startDate: '',
        endDate: '',
      },
      onSuccess(data) {
        self.setState({
          reportData: data,
        });
        console.log(self.state.reportData.monthlyForecastData);
      }
    });
  }
}

const ACTIVITY_REPORT = new ActivityReport();
ACTIVITY_REPORT.init();