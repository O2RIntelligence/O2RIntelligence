class FinancialReport extends GoogleAdsManager {
  constructor() {
    super();

    this.getFinancialReportData = this.getFinancialReportData.bind(this);
    this.initialState = {
      reportData: [],
    };
  }

  init() {
    super.init();
    const self = this;
    super.dataFilterActivities();
    this.getFinancialReportData();
  }

  getFinancialReportData() {
    const self = this;
    self.sendHttpRequest({
      method: "post",
      url: "/api/google-ads/financial-report/data",
      useCsrf: true,
      data: {
        startDate: '',
        endDate: '',
      },
      onSuccess(data) {
        self.setState({
          reportData: data,
        });
        console.log(self.state.reportData.financialInformation);
      }
    });
  }

}

const FINANCIAL_REPORT = new FinancialReport();
FINANCIAL_REPORT.init();