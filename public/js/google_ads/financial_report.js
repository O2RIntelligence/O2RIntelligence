class FinancialReport extends GoogleAdsManager {
  constructor() {
    super();

    this.getFinancialReportData = this.getFinancialReportData.bind(this);
    this.prepareFinancialTable = this.prepareFinancialTable.bind(this);
    this.renderFinancialTable = this.renderFinancialTable.bind(this);

    this.initialState = {
      financialData: [],
      financialTable: null,
    };
  }

  init() {
    super.init();
    const self = this;
    super.dataFilterActivities();
    this.prepareFinancialTable();
    this.getFinancialReportData();
  }
  prepareFinancialTable() {
    const self = this;
    self.setState({
      financialTable:
        $('#financialTable').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        })
    });
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
          financialData: data,
        });
        self.renderFinancialTable()
      }
    });
  }
  renderFinancialTable() {
    const self = this;
    console.log(self.state.financialData.financialInformation.totalData);
    const data = self.state.financialData.financialInformation.totalData;

    const table = self.state.financialTable;
    for (let item of data) {
      table.row.add([
        item?.master_account_name ?? "",
        item?.master_account_id ?? "",
        item?.sub_account_id ?? "",
        Number(item?.spent_in_ars ?? 0).toFixed(2),
        Number(item?.spent_in_usd ?? 0).toFixed(2),
        Number(item?.discount ?? 0),
        Number(item?.revenue ?? 0).toFixed(2),
        Number(item?.google_media_cost ?? 0).toFixed(2),
        Number(item?.plus_m_share ?? 0).toFixed(2),
        Number(item?.total_cost ?? 0).toFixed(2),
        Number(item?.net_income ?? 0).toFixed(2),
        Number(item?.net_income_percent ?? 0).toFixed(2),
      ]);
    }
    table.draw();
  }
}

const FINANCIAL_REPORT = new FinancialReport();
FINANCIAL_REPORT.init();