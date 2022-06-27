class Dashboard extends GoogleAdsManager {
  constructor() {
    super();

    this.initialState = {
      dashboard: {},
    };

    this.getDashboardData = this.getDashboardData.bind(this);
    this.populateCards = this.populateCards.bind(this);
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