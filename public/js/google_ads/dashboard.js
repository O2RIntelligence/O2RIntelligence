class Dashboard extends GoogleAdsManager {
  constructor() {
    super();

    this.initialState = {};
  }

  getDashboardData() {
    this.sendHttpRequest({
      method: "post",
      url: "/google-ads/dashboard/data",
      useCsrf: true,
      data: {
        startDate: '2022-06-27',
        endDate: '2022-06-27',
      }
    });
  }
}

const DASHBOARD = new Dashboard();
