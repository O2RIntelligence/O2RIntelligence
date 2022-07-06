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
        from: moment().utcOffset(0, true).format('YYYY-MM-DD'),
        to: moment().utcOffset(0, true).format('YYYY-MM-DD'),
      },
      accounts: {
        masterAccounts: [],
        subAccounts: [],
      },

      selected_accounts: [],
    };

    this.setState = this.setState.bind(this);
    this.init = this.init.bind(this);
    this.getFilterAccountList = this.getFilterAccountList.bind(this);
    this.dataFilterActivities = this.dataFilterActivities.bind(this);

    this.utils = {
      /**
       * Create chart data
       * @param labels
       * @param dataSets
       */
      createChartData: (labels, dataSets) => {
        console.log("dataSets", dataSets);
        let datasets = []; 
        if (dataSets && dataSets?.length > 0) {
          const color = this.utils.getRandomColor();
          dataSets?.forEach((data) => {
            datasets.push({
              label: data?.name,
              fill: 'transparent',
              borderColor: color,  
              backgroundColor: '#ecf0f5',
              borderWidth: 2,
              // pointColor: "rgba(210, 214, 222, 1)",
              // pointStrokeColor: "#c1c7d1",
              // pointHighlightFill: "#fff",
              // pointHighlightStroke: "rgba(220,220,220,1)",
              data: data?.data && data?.data?.length > 0 ? data?.data : new Array(labels?.length).fill(0),
            })
          });
        }

        return {
          labels,
          datasets,
        };
      },

      /**
       * Create doughnut chart data
       * @param labels
       * @param dataSets
       */
       createDoughnutChartData: (labels, dataSets) => {
        let datasets = []; 
        if (dataSets && dataSets?.length > 0) {
          
          dataSets?.forEach((data) => {
            const colors = [];

            for (let i = 0; i < labels.length; i++) {
              colors.push(this.utils.getRandomColor());
            }

            datasets.push({
              // data: [500,	50,	2424,	14040], // Specify the data values array
              //     borderColor: ['#2196f38c', '#f443368c', '#3f51b570', '#00968896'], // Add custom color border
              //     backgroundColor: ['#2196f38c', '#f443368c', '#3f51b570', '#00968896'], // Add custom color background (Points and Fill)
              //     borderWidth: 1 // Specify bar border width

              label: data?.name,
              fill: '#f443368c', 
              borderColor: colors,
              backgroundColor: colors,
              borderWidth: 1,
              // pointColor: "rgba(210, 214, 222, 1)",
              // pointStrokeColor: "#c1c7d1",
              // pointHighlightFill: "#fff",
              // pointHighlightStroke: "rgba(220,220,220,1)",
              data: data?.data && data?.data?.length > 0 ? data?.data : new Array(labels?.length).fill(0),
            })
          });
        }

        return {
          labels,
          datasets,
        };
      },

      //   2196f38c
      // f443368c
      // 3f51b570
      // 00968896

      getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      },

      /**
       * Create the filter dates
       * @param period {"today" | "yesterday" | "last7" | "last30" | null}
       */
      createFilterDate: (period = null) => {
        const self = this;
        try {
          if (!period)
            period = $("input[name=date_period]").val();
          let date = null;
          let startDateInput = $("input[name=start_date]");
          let endDateInput = $("input[name=end_date]");
          let fromDate = null, toDate = null;

          switch (period) {
            case 'today':
              date = moment().utcOffset(0, true).format('YYYY-MM-DD');
              startDateInput.val(date);
              endDateInput.val(date);
              fromDate = date;
              toDate = date;
              break;
            case 'yesterday':
              date = moment().utcOffset(0, true).subtract(1, "days").format('YYYY-MM-DD');
              fromDate = date;
              toDate = date;
              startDateInput.val(date);
              endDateInput.val(date);
              break;
            case 'last7':
              fromDate = moment().utcOffset(0, true).subtract(7, "days").format('YYYY-MM-DD');
              toDate = moment().utcOffset(0, true).subtract(1, "days").format('YYYY-MM-DD');
              startDateInput.val(fromDate);
              endDateInput.val(toDate);
              break;
            case 'last30':
              fromDate = moment().utcOffset(0, true).startOf('month').format('YYYY-MM-DD');
              toDate = moment().utcOffset(0, true).subtract(1, "days").format('YYYY-MM-DD');
              startDateInput.val(fromDate);
              endDateInput.val(toDate);
              break;
            default:
              break;
          }

          self.dataFilterState.date_filter.from = fromDate;
          self.dataFilterState.date_filter.to = toDate;
        } catch (e) {
          console.log("Error: " + e);
          swal("Error occurred!", "error");
        }
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

  /**
   * Populate the filter account list dropdown
   */
  populateFilterAccounts() {
    const self = this;
    const { account_type: accountType, accounts } = self.dataFilterState;

    // Reset the existing selected accounts
    self.dataFilterState.selected_accounts = [];

    const data = accountType === "master_accounts" ? accounts?.masterAccounts : accountType === "sub_accounts" ? accounts.subAccounts : [];

    if (accountType === "general") {
      $('#account-filter').prop('disabled', true);
      $('#account-filter').val('').trigger('change');
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

  /**
   * Data filter activities
   * @param props {{
   *   onChange?(data: any): void,
   *   onPressFilter?(data: any): void,
   * }}
   */
  dataFilterActivities(props) {
    const self = this;
    self.getFilterAccountList();

    $("#account-filter").select2({
      placeholder: 'Select'
    }).on('change', function () {
      self.dataFilterState.selected_accounts = $(this).val();
      if (typeof props?.onPressFilter === "function") {
        props?.onPressFilter(self.dataFilterState);
      }
    });

    //Date range as a button
    $('#daterange-btn').daterangepicker({}, function (start, end, label) {
      self.dataFilterState.date_filter.from = start.format('YYYY-MM-DD');
      self.dataFilterState.date_filter.to = end.format('YYYY-MM-DD');

      if (typeof props?.onChange === "function") {
        props?.onChange(self.dataFilterState);
      }
    });

    $(document).on('click', '[data-action="account_type"]', function () {
      const accountType = $(this).attr('data-name');
      self.dataFilterState.account_type = accountType;
      self.populateFilterAccounts();
      if (typeof props?.onPressFilter === "function") {
        props?.onPressFilter(self.dataFilterState);
      }
    });

    $(".change-period").on("click", function () {
      $(".time-periods button").removeClass("active");
      $(this).addClass("active");
      let period = $(this).data('period');
      $("input[name=date_period]").val(period);
      if (period !== 'custom') {
        $(".custom-daterange").hide();

        // Update the date range variables
        self.utils.createFilterDate(period);

        if (typeof props?.onChange === "function") {
          props?.onChange(self.dataFilterState);
        }
      }
    });

    $(document).on('click', '[data-action="filter-search"]', function () {
      if (typeof props?.onPressFilter === "function") {
        props?.onPressFilter(self.dataFilterState);
      }
    });
  }
}