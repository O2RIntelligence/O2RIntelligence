class GoogleAdsManager {
  initialState = {};
  state = {};
  utils = {};
  dataFilterState = {};

  constructor() {
    this.initialState = {};
    this.state = this.initialState;
    this.dataFilterState = {
      account_type: 'general',
      date_filter: {
        from: '',
        to: '',
      },
      accounts: {
        masterAccounts: [],
        subAccounts: [],
      },
    };

    this.setState = this.setState.bind(this);
    this.init = this.init.bind(this);
    this.getFilterAccountList = this.getFilterAccountList.bind(this);
    this.dataFilterActivities = this.dataFilterActivities.bind(this);

    this.utils = {
      /**
       *
       * @param labels
       * @param dataSets
       * @param chartType {"daily_cost" | "hourly_cost"}
       */
      createChartData(labels, dataSets, chartType) {
        let datasets = [];

        if (dataSets && dataSets?.length > 0) {
          dataSets?.forEach((data) => {
            datasets.push({
              label: data?.name,
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: data?.data && data?.data?.length > 0 ? data?.data : new Array(labels?.length).fill(0),
            })
          });
        }

        return {
          labels,
          datasets,
        };
      }
    };
  }

  /**
   * Update the state object
   * @param stateObject
   */
  setState(stateObject) {
    this.state = {
      ...this.state,
      ...stateObject,
    };
  }

  /**
   * send http requests
   * @param props {{
   *   method: "get" | "post" | "delete" | "put" | "patch",
   *   url: string,
   *   data: any,
   *   headers?: any,
   *   useCsrf?: boolean,
   *   onSuccess?(res: any): void,
   *   onError?(res: any): void,
   * }}
   */
  sendHttpRequest(props) {
    const { method, url, data, headers, useCsrf, onSuccess, onError } = props;

    $.ajax({
      method,
      tye: method,
      url,
      headers: {
        'X-CSRF-TOKEN': useCsrf ? __csrf_token : '',
        ...headers,
      },
      data,
      success: onSuccess,
      error: onError,
    });
  }

  init() {

  }

  /**
   * Get all the account list
   */
  getFilterAccountList() {
    const self = this;

    self.sendHttpRequest({
      url: '/google-ads/all-accounts/data',
      method: 'get',
      onSuccess(res) {
        self.dataFilterState.accounts.masterAccounts = res?.masterAccounts ?? [];
        self.dataFilterState.accounts.subAccounts = res?.subAccounts ?? [];

        self.populateFilterAccounts();
      },
    });
  }

  populateFilterAccounts() {
    const self = this;
    const { account_type: accountType, accounts } = self.dataFilterState;
    console.log(accountType);

    const data = accountType === "master_accounts" ? accounts?.masterAccounts : accountType === "sub_accounts" ? accounts.subAccounts : [];

    if (accountType === "general") {
      $('#account-filter').prop('disabled', true);
    } else {
      if (data) {
        let optionsHtml = ``;

        data?.forEach((account) => {
          const id = account?.account_id;
          const name = account?.name;
          optionsHtml += `<option value="${id}">${name}</option>`;
        });

        $('#account-filter').html(optionsHtml).prop('disabled', false);
      }
    }
  }

  dataFilterActivities() {
    const self = this;
    self.getFilterAccountList();

    $(document).on('click', '[data-action="account_type"]', function () {
      const accountType = $(this).attr('data-name');
      self.dataFilterState.account_type = accountType;
      self.populateFilterAccounts();
    });
  }
}