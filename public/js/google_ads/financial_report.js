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
    super.dataFilterActivities({
      onChange: self.getFinancialReportData,
    });
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
    const { date_filter } = self.dataFilterState;
    self.sendHttpRequest({
      method: "post",
      url: "/api/google-ads/financial-report/data",
      useCsrf: true,
      data: {
        startDate: date_filter?.from ?? "",
        endDate: date_filter?.to ?? "",
      },
      onSuccess(data) {
        self.setState({
          financialData: data,
        });
        self.renderFinancialTable()
      }
    });
  }

  /**
   * 
   * @param {{
   *  data?: any,
   * }} props 
   */
  renderFinancialTable(props) {
    const self = this;
    let data = props?.data ?? self.state.financialData.financialInformation.totalData;
    console.log(data);

    const { account_type, selected_accounts } = self.dataFilterState;
    if (account_type === "master_accounts") {


    } else if (account_type === "sub_accounts") {

    }

    const table = self.state.financialTable;
    table.clear();
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