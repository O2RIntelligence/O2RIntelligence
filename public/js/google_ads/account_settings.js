class AccountSettings extends GoogleAdsManager {
  constructor() {
    super();

    this.getAccountSettingsData = this.getAccountSettingsData.bind(this);
    this.renderAccountSettingsTable = this.renderAccountSettingsTable.bind(this);
    this.prepareAccountSettingsTable = this.prepareAccountSettingsTable.bind(this);
    this.openFormDialog = this.openFormDialog.bind(this);
    this.formActivities = this.formActivities.bind(this);
    this.submitFormData = this.submitFormData.bind(this);
    this.checkStatus = this.checkStatus.bind(this);

    this.initialState = {
      accountData: [],
      accountTable: null,
      form: document.forms['ACCOUNT-SETTINGS-FORM'],
    };
    this.state = this.initialState;
  }

  init() {
    super.init();
    const self = this;
    this.getAccountSettingsData();
    this.prepareAccountSettingsTable();
    this.formActivities();
  }
  formActivities() {
    const self = this;
    $(document).on('click', '#createFormDialog', function () {
      self.openFormDialog();
      self.state.form['id'].value = "";
      self.state.form['accountName'].value = "";
      self.state.form['accountId'].value = "";
      self.state.form['developerToken'].value = "";
      self.state.form['discount'].value = "";
      self.state.form['revenueConversionRate'].value = ""
    });

    $(document).on('click', '#editFormDialog', function () {
      self.openFormDialog();
      const index = $(this).attr('data-index');
      const data = self.state.accountData[index];

      self.state.form['id'].value = data?.id;
      self.state.form['accountName'].value = data?.name;
      self.state.form['accountId'].value = data?.account_id;
      self.state.form['developerToken'].value = data?.developer_token;
      self.state.form['discount'].value = data?.discount;
      self.state.form['revenueConversionRate'].value = data?.revenue_conversion_rate;
    });

    $(document).on('click', '#checkStatus', function () {
      const id = $(this).attr('data-id');
      self.checkStatus(id);
    });

    $(document).on('submit', '[name="ACCOUNT-SETTINGS-FORM"]', function (e) {
      e.preventDefault();
      self.submitFormData();
    });

    $(document).on('click', '[data-action="changeStatus"]', function () {
      const id = $(this).attr('data-id');
      self.updateStatus(id);
    });

  }
  getFormData() {
    const self = this;
    return {
      id: self.state.form['id'].value,
      accountName: self.state.form['accountName'].value,
      accountId: self.state.form['accountId'].value,
      developerToken: self.state.form['developerToken'].value,
      discount: self.state.form['discount'].value,
      revenueConversionRate: self.state.form['revenueConversionRate'].value
    };
  }
  submitFormData() {
    const self = this;
    const data = {
      name: self.getFormData().accountName,
      account_id: self.getFormData().accountId,
      developer_token: self.getFormData().developerToken,
      discount: self.getFormData().discount,
      revenue_conversion_rate: self.getFormData().revenueConversionRate,
    };

    let type = "store"
    if (self.getFormData().id !== "") {
      data.id = self.getFormData().id;
      type = "update"
    }
    self.sendHttpRequest({
      method: "post",
      url: "/google-ads/master-account/" + type,
      useCsrf: true,
      data,
      onSuccess(data) {
        console.log(data.success);
        self.getAccountSettingsData();
        self.openFormDialog("hide");
        if (data.success) {
          swal("Save successfully!", 'success');
        } else {
          swal(data.message, '');
        }
      }
    });
  }

  updateStatus(id) {
    const self = this;
    self.sendHttpRequest({
      method: "post",
      url: "/google-ads/master-account/status",
      useCsrf: true,
      data: { id },
      onSuccess() {
        swal("Change successfully!", 'success');
      }
    });
  }

  prepareAccountSettingsTable() {
    const self = this;
    self.setState({
      accountTable:
        $('#accountSettingsTable').DataTable({
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
      url: "/google-ads/master-accounts",
      useCsrf: true,
      data: {},
      onSuccess(data) {
        self.setState({
          accountData: data.data,
        });
        self.renderAccountSettingsTable();
      }
    });
  }

  checkStatus(id) {
    const self = this;
    self.sendHttpRequest({
      method: "post",
      url: "/google-ads/master-account/check-access",
      useCsrf: true,
      data: { id },
      onSuccess(data) {
        swal(`${data?.message}`, 'success');
      }
    });
  }

  renderAccountSettingsTable() {
    const self = this;
    let data = self.state.accountData ?? [];

    const table = self.state.accountTable;
    table.clear();

    data?.forEach(function (item, index) {
      table.row.add([
        item?.name ?? "",
        item?.account_id ?? "",
        item?.developer_token ?? "",
        `${Number(item?.discount ?? 0).toFixed(2)}%`,
        Number(item?.revenue_conversion_rate ?? 0).toFixed(2),
        `<label class="switch">
          <input  data-action="changeStatus"  data-id="${item.id}" type="checkbox" ${Number(item?.is_active) === 1 ? "checked" : ""} >
          <span class="slider round"></span>
        </label>`,
        `<div class="grid-dropdown-actions dropdown">
        <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-ellipsis-v"></i>
        </a>
        <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
          <li>
            <a id="editFormDialog" href="javascript:void(0)" data-index="${index}">Edit</a>
          </li>
          <li>
             <a data-id="${item.id}" id="checkStatus" href="javascript:void(0);">Check Status</a>
          </li>
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
    $('#account-setting-modal').modal(action);
  }
}

const ACCOUNT_SETTINGS = new AccountSettings();
ACCOUNT_SETTINGS.init();