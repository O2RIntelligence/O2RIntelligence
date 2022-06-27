class Dashboard extends GoogleAdsManager {
  constructor() {
    super();

    this.initialState = {
      dashboard: {},
    };

    this.getDashboardData = this.getDashboardData.bind(this);
  }

  init() {
    const self = this;
    super.init();

    self.getDashboardData();
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
      }
    });
  }
}

const DASHBOARD = new Dashboard();
DASHBOARD.init();