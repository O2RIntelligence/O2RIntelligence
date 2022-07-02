class AccountSettings extends GoogleAdsManager {
  constructor() {
    super();

    this.getAccountSettingsData = this.getAccountSettingsData.bind(this);
    this.renderAccountSettingsTable = this.renderAccountSettingsTable.bind(this);
    this.prepareAccountSettingsTable = this.prepareAccountSettingsTable.bind(this);

    this.initialState = {
      accountData: [],
      accountTable: null,
    };

  }

  init() {
    super.init();
    const self = this;
    this.getAccountSettingsData();
    this.prepareAccountSettingsTable();
  }

  prepareAccountSettingsTable() {
    const self = this;
    self.setState({
      accountTable:
        $('#generalVariableTable').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        })
    });
  }

  getAccountSettingsData() {
    const self = this;
    self.sendHttpRequest({
      method: "get",
      url: "/google-ads/general-variables",
      useCsrf: true,
      data: {},
      onSuccess(data) {
        self.setState({
          accountData: data,
        });

        console.log(data[0].data);

        self.renderAccountSettingsTable();
      }
    });
  }

  renderAccountSettingsTable() {
    const self = this;
    let data = self.state.accountData[0].data ?? [];

    const table = self.state.accountTable;
    table.clear();

    for (let item of data) {
      table.row.add([
        item?.official_dollar ?? 0,
        item?.blue_dollar ?? 0,
        Number(item?.plus_m_discount ?? 0).toFixed(2),
      ]);
    }

    table.draw();
  }
}

const ACCOUNT_SETTINGS = new AccountSettings();
ACCOUNT_SETTINGS.init();