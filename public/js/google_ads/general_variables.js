class GeneralVariables extends GoogleAdsManager {
  constructor() {
    super();

    this.getGeneralVariablesData = this.getGeneralVariablesData.bind(this);
    this.renderGeneralVariablesTable = this.renderGeneralVariablesTable.bind(this);
    this.prepareGeneralVariablesTable = this.prepareGeneralVariablesTable.bind(this);

    this.initialState = {
      variablesData: [],
      variablesTable: null,
    };
  }

  init() {
    super.init();
    const self = this;
    this.getGeneralVariablesData();
    this.prepareGeneralVariablesTable();
  }

  prepareGeneralVariablesTable() {
    const self = this;
    self.setState({
      variablesTable:
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

  getGeneralVariablesData() {
    const self = this;
    self.sendHttpRequest({
      method: "get",
      url: "/google-ads/general-variables",
      useCsrf: true,
      data: {},
      onSuccess(data) {
        self.setState({
          variablesData: data,
        });

        console.log(data[0].data);

        self.renderGeneralVariablesTable();
      }
    });
  }

  renderGeneralVariablesTable() {
    const self = this;
    let data = self.state.variablesData[0].data ?? [];

    const table = self.state.variablesTable;
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

const GENERAL_VARIABLES = new GeneralVariables();
GENERAL_VARIABLES.init();