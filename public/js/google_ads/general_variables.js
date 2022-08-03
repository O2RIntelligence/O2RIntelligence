class GeneralVariables extends GoogleAdsManager {
  constructor() {
    super();

    this.getGeneralVariablesData = this.getGeneralVariablesData.bind(this);
    this.renderGeneralVariablesTable = this.renderGeneralVariablesTable.bind(this);
    this.prepareGeneralVariablesTable = this.prepareGeneralVariablesTable.bind(this);
    this.openFormDialog = this.openFormDialog.bind(this);
    this.formActivities = this.formActivities.bind(this);
    this.submitFormData = this.submitFormData.bind(this);

    this.initialState = {
      variablesData: [],
      variablesTable: null,
      form: document.forms['GENERAL-VARIABLE-FORM'],
    };

    this.state = this.initialState;
  }

  init() {
    super.init();
    const self = this;
    this.getGeneralVariablesData();
    this.prepareGeneralVariablesTable();
    this.formActivities();
  }

  formActivities() {
    const self = this;
    $(document).on('click', '#createFormDialog', function () {
      self.openFormDialog();
      self.state.form['id'].value = "";
      self.state.form['officialDollar'].value = "";
      self.state.form['blueDollar'].value = "";
      self.state.form['plusmDiscount'].value = "";
    });

    $(document).on('click', '#editFormDialog', function () {
      self.openFormDialog();
      const index = $(this).attr('data-index');
      const data = self.state.variablesData[index];

      self.state.form['id'].value = data?.id;
      self.state.form['officialDollar'].value = data?.official_dollar;
      self.state.form['blueDollar'].value = data?.blue_dollar;
      self.state.form['plusmDiscount'].value = data?.plus_m_discount;
    });

    $(document).on('submit', '[name="GENERAL-VARIABLE-FORM"]', function (e) {
      e.preventDefault();
      self.submitFormData();
    });
  }
  getFormData() {
    const self = this;
    return {
      id: self.state.form['id'].value,
      officialDollar: self.state.form['officialDollar'].value,
      blueDollar: self.state.form['blueDollar'].value,
      plusmDiscount: self.state.form['plusmDiscount'].value
    };
  }

  submitFormData() {
    const self = this;
    const data = {
      official_dollar: self.getFormData().officialDollar,
      blue_dollar: self.getFormData().blueDollar,
      plus_m_discount: self.getFormData().plusmDiscount,
    };
    let type = "store"
    if (self.getFormData().id !== "") {
      data.id = self.getFormData().id;
      type = "update"
    }

    self.sendHttpRequest({
      method: "post",
      url: "/google-ads/general-variable/" + type,
      useCsrf: true,
      data,
      onSuccess() {
        self.getGeneralVariablesData();
        self.openFormDialog("hide");
        swal("Save successfully!", 'success');
      }
    });
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
          variablesData: data?.data,
        });
        self.renderGeneralVariablesTable();
      }
    });
  }

  renderGeneralVariablesTable() {
    const self = this;
    let data = self.state.variablesData ?? [];

    const table = self.state.variablesTable;
    table.clear();

    data?.forEach(function (item, index) {
      table.row.add([
        item?.official_dollar ?? 0,
        item?.blue_dollar ?? 0,
        `${Number(item?.plus_m_discount ?? 0).toFixed(2)}%`,
        `<div class="grid-dropdown-actions dropdown">
          <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-ellipsis-v"></i>
          </a>
          <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
            <li>
              <a id="editFormDialog" href="javascript:void(0)" data-index="${index}">Edit</a>
            </li>
            <!-- <li>
               <a data-_key="14" href="javascript:void(0);" class="grid-row-action-62b58e69c638a2961">Delete</a>
             </li>
            -->
          </ul>
        </div>`
      ]);
    });
    table.draw();
  }

  /**
   * 
   * @param {{
   *  action: 'show' | 'hide'
   * }} action 
   */
  openFormDialog(action = 'show') {
    $('#general-variable-setting-modal').modal(action);
  }
}

const GENERAL_VARIABLES = new GeneralVariables();
GENERAL_VARIABLES.init();