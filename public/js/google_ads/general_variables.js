class GeneralVariables extends GoogleAdsManager {
  constructor() {
    super();

    this.getGeneralVariablesData = this.getGeneralVariablesData.bind(this);
    this.renderGeneralVariablesTable = this.renderGeneralVariablesTable.bind(this);
    this.prepareGeneralVariablesTable = this.prepareGeneralVariablesTable.bind(this);

    this.initialState = {
      variablesData: [],
      variablesTable: null,
      form: document.forms['GENERAL-VARIABLE-FORM'],
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
        }),
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
        self.renderGeneralVariablesTable();
      }
    });
  }

  renderGeneralVariablesTable() {
    const self = this;
    let data = self.state.variablesData.data ?? [];

    const table = self.state.variablesTable;
    table.clear();

    for (let item of data) {
      table.row.add([
        item?.official_dollar ?? 0,
        item?.blue_dollar ?? 0,
        Number(item?.plus_m_discount ?? 0).toFixed(2),
        `<div class="grid-dropdown-actions dropdown">
          <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-ellipsis-v"></i>
          </a>
          <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
            <li>
              <a href="http://127.0.0.1:8000/admin/auth/users/14/edit">Edit</a>
            </li>
            <li>
              <a href="http://127.0.0.1:8000/admin/auth/users/14">Show</a>
            </li>
            <li>
              <a data-_key="14" href="javascript:void(0);" class="grid-row-action-62b58e69c638a2961">Delete</a>
            </li>
          </ul>
        </div>`
      ]);
    }
    table.draw();
  }
}

const GENERAL_VARIABLES = new GeneralVariables();
GENERAL_VARIABLES.init();