function addDropdownValue(element) {
  const operation = $(element).attr('data-operation');
  const colIdx = $(element).attr('data-idx');
  $(element).parents('.dropdown').find('[name="selected_filter_operation"]').val(operation);
  $(element).parents('.dropdown').find('[name="selectedColIndex"]').val(colIdx);
}

function clearDropdownValue(element) {
  const key = $(element).attr('data-key');
  $(element).parents('.dropdown').find('[name="selected_filter_operation"]').val('');
  $('#input' + key).val('');
}


class FinancialReport extends GoogleAdsManager {
  constructor() {
    super();

    this.getFinancialReportData = this.getFinancialReportData.bind(this);
    this.prepareFinancialTable = this.prepareFinancialTable.bind(this);
    this.renderFinancialTable = this.renderFinancialTable.bind(this);
    this.htmlTemplates = this.htmlTemplates.bind(this);

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
      onPressFilter: self.renderFinancialTable,
    });
    this.prepareFinancialTable();
    this.getFinancialReportData();
  }

  prepareFinancialTable() {
    const self = this;

    const columns = [
      {
        key: 'date',
        title: 'Date',
        type: 'date',
      },
      {
        key: 'master_account_name',
        title: 'Master Account Name',
        type: 'text',
      },
      {
        key: 'master_account_id',
        title: 'Master Account ID',
        type: 'number',
      },
      {
        key: 'sub_account_name',
        title: 'Sub Account Name',
        type: 'text',
      },
      {
        key: 'sub_account_id',
        title: 'Sub Account Id',
        type: 'number',
      },
      {
        key: 'SPENT_IN_ARS',
        title: 'SPENT in ARS',
        type: 'number',
      },
      {
        key: 'Spent_In_USD',
        title: 'Spent in USD',
        type: 'number',
      },
      {
        key: 'discount',
        title: 'Discount',
        type: 'number',
      },
      {
        key: 'revenue',
        title: 'Revenue',
        type: 'number',
      },
      {
        key: 'Google_Media_Cost',
        title: 'Google Media Cost',
        type: 'number',
      },
      {
        key: 'PlusM_Share',
        title: 'PlusM Share',
        type: 'number',
      },
      {
        key: 'Total_Cost',
        title: 'Total Cost',
        type: 'number',
      },
      {
        key: 'Net_Income',
        title: 'Net Income',
        type: 'number',
      },
      {
        key: 'Net_Income_Percent',
        title: 'Net Income %',
        type: 'number',
      },
    ];

    let theadRow = '<tr>', theadFilterRow = '<tr class="filters">';
    columns.forEach(function (item, index) {
      theadRow += `<th>${item.title}</th>`;
      theadFilterRow += `<td>${self.htmlTemplates().tableFilterItem({
        colIndex: index,
        key: item?.key,
        title: item?.title,
        type: item?.type,
      })}</td>`;
    });
    theadRow += '</tr>';
    theadFilterRow += '</tr>';

    const thead = $('#financialTable thead');
    thead.append(theadRow);
    thead.append(theadFilterRow);

    const table = $('#financialTable').DataTable({
      'responsive': true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "orderCellsTop": true,
      "info": true,
      "autoWidth": false,
      'dom': 'lBfrtip',
      'buttons': [
        'csv', 'excel'
      ],
      initComplete() {
        let api = this.api();

        $(document).on('keyup', '.filters input', function () {
          api.draw();
        });

        $(document).on('click', '.filters .dropdown-item', function () {
          api.draw();
        });
      }
    });


    $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
        var flag = true;

        columns.forEach(function (item) {
          if (flag) {
            var inputValue = $("#input" + item?.key).val();
            var operator = $("#operator" + item?.key).val();
            var colIndex = $("#colIndex" + item?.key).val();
            var cellValue = data[colIndex];

            if (item?.type === 'date' && inputValue && cellValue) {
              inputValue = inputValue.replaceAll('-', '/');
              cellValue = cellValue.replaceAll('-', '/');

              let leftDate = Date.parse(cellValue?.trim());
              let rightDate = Date.parse(inputValue?.trim());

              if (inputValue.trim() !== '') {
                switch (operator) {
                  case 'equal':
                    flag = leftDate == rightDate;
                    break;
                  case 'not_equal':
                    flag = leftDate != rightDate;
                    break;
                  case 'greater_than':
                    flag = leftDate > rightDate;
                    break;
                  case 'greater_equal':
                    flag = leftDate >= rightDate;
                    break;
                  case 'less_than':
                    flag = leftDate < rightDate;
                    break;
                  case 'less_equal':
                    flag = leftDate <= rightDate;
                    break;
                  default:
                    flag = true;
                }
              } else {
                flag = true;
              }

            } else if (item?.type === 'month' && inputValue) {
              inputValue = inputValue.replace('-', '/');

              let leftDate = Date.parse(cellValue.trim());
              let rightDate = Date.parse(inputValue.trim());

              if (inputValue.trim() !== '') {
                switch (operator) {
                  case 'equal':
                    flag = leftDate == rightDate;
                    break;
                  case 'not_equal':
                    flag = leftDate != rightDate;
                    break;
                  case 'greater_than':
                    flag = leftDate > rightDate;
                    break;
                  case 'greater_equal':
                    flag = leftDate >= rightDate;
                    break;
                  case 'less_than':
                    flag = leftDate < rightDate;
                    break;
                  case 'less_equal':
                    flag = leftDate <= rightDate;
                    break;
                  default:
                    flag = true;
                }
              } else {
                flag = true;
              }

            } else {
              if (inputValue?.toLowerCase().trim() !== '') {
                switch (operator) {
                  case 'equal':
                    flag = cellValue?.toLowerCase().trim() == inputValue.toLowerCase().trim();
                    break;
                  case 'not_equal':
                    flag = cellValue?.toLowerCase().trim() != inputValue.toLowerCase().trim();
                    break;
                  case 'greater_than':
                    flag = parseFloat(cellValue.trim().replace(/,/g, '')) > parseFloat(inputValue.trim().replace(/,/g, ''));
                    break;
                  case 'greater_equal':
                    flag = parseFloat(cellValue.trim().replace(/,/g, '')) >= parseFloat(inputValue.trim().replace(/,/g, ''));
                    break;
                  case 'less_than':
                    flag = parseFloat(cellValue.trim().replace(/,/g, '')) < parseFloat(inputValue.trim().replace(/,/g, ''));
                    break;
                  case 'less_equal':
                    flag = parseFloat(cellValue.trim().replace(/,/g, '')) <= parseFloat(inputValue.trim().replace(/,/g, ''));
                    break;
                  default:
                    flag = true;
                }
              } else {
                flag = true;
              }
            }
          }
        });
        return flag;
      }
    );

    table.on('search.dt', function () {
      const columns = table.columns().header().toArray().map(x => x?.innerText);

      let tableFilteredData = [];

      function updateSummationValue(index, value) {
        
      }

      let totalFilteredRows = 0;

      table.rows({ search: 'applied' }).data().each(function (value, index) {
          const rowValues = value;
          if (rowValues && rowValues.length > 0) {

            tableFilteredData.push({
              date: rowValues[0],
              master_account_name: rowValues[1],
              master_account_id: rowValues[2],
              sub_account_name: rowValues[3],
              sub_account_id: rowValues[4],
              spent_in_ars: rowValues[5],
              spent_in_usd: rowValues[6],
              discount: rowValues[7],
              revenue: rowValues[8],
              google_media_cost: rowValues[9],
              plus_m_share: rowValues[10],
              total_cost: rowValues[11],
              net_income: rowValues[12],
              net_income_percent: rowValues[13],
            });
          }

          totalFilteredRows++;
      });
      

      const summation = {
        totalSpentInARS: 0,
        totalSpentInUSD: 0,
        totalRevenue: 0,
        totalGoogleMediaCost: 0,
        totalPlusMShare: 0,
        totalCost: 0,
        totalNetIncome: 0,
        totalNetIncomePercent: 0,
      };

      for (let item of tableFilteredData) {
        summation.totalSpentInARS += Number(item?.spent_in_ars ?? 0);
        summation.totalSpentInUSD += Number(item?.spent_in_usd ?? 0);
        summation.totalRevenue += Number(item?.revenue ?? 0);
        summation.totalGoogleMediaCost += Number(item?.google_media_cost ?? 0);
        summation.totalPlusMShare += Number(item?.plus_m_share ?? 0);
        summation.totalCost += Number(item?.total_cost ?? 0);
        summation.totalNetIncome += Number(item?.net_income ?? 0);
        summation.totalNetIncomePercent += Number(item?.net_income_percent ?? 0);
      }

      let summationHtml = `
        <tr class="info summation">
          <th>Filtered Total</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>$${summation.totalSpentInARS.toFixed(2)}</th>
          <th>$${summation.totalSpentInUSD.toFixed(2)}</th>
          <th></th>
          <th>$${summation.totalRevenue.toFixed(2)}</th>
          <th>$${summation.totalGoogleMediaCost.toFixed(2)}</th>
          <th>$${summation.totalPlusMShare.toFixed(2)}</th>
          <th>$${summation.totalCost.toFixed(2)}</th>
          <th>$${summation.totalNetIncome.toFixed(2)}</th>
          <th>${summation.totalNetIncomePercent.toFixed(2)}%</th>
        </tr>
      `;

      $('#financialTable tfoot tr.summation').remove();
      $(summationHtml).insertBefore($('#financialTable tfoot tr:first-child'));
  });

    self.setState({
      financialTable: table,
    });
  }

  getFinancialReportData() {
    const self = this;
    const {date_filter} = self.dataFilterState;
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


  renderFinancialTable() {
    const self = this;
    const {account_type, selected_accounts} = self.dataFilterState;

    if (self.state.financialData && self.state.financialTable) {
      let data = self.state.financialData.financialInformation.totalData ?? [];

      if (account_type !== "general" && selected_accounts && selected_accounts?.length > 0) {
        const filteredData = [];

        selected_accounts?.forEach(function (accountId) {
          data?.forEach(function (item) {
            const itemId = account_type === "master_accounts" ? item?.master_account_id : account_type === "sub_accounts" ? item?.sub_account_id : 0;

            if (Number(itemId) === Number(accountId)) {
              filteredData.push(item);
            }
          });
        });

        data = filteredData;
      }

      const table = self.state.financialTable;
      table.clear();


      const summation = {
        totalSpentInARS: 0,
        totalSpentInUSD: 0,
        totalRevenue: 0,
        totalGoogleMediaCost: 0,
        totalPlusMShare: 0,
        totalCost: 0,
        totalNetIncome: 0,
        totalNetIncomePercent: 0,
      };


      for (let item of data) {
        table.row.add([
          item?.date ?? "",
          item?.master_account_name ?? "",
          item?.master_account_id ?? "",
          item?.sub_account_name ?? "",
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

        summation.totalSpentInARS += Number(item?.spent_in_ars ?? 0);
        summation.totalSpentInUSD += Number(item?.spent_in_usd ?? 0);
        summation.totalRevenue += Number(item?.revenue ?? 0);
        summation.totalGoogleMediaCost += Number(item?.google_media_cost ?? 0);
        summation.totalPlusMShare += Number(item?.plus_m_share ?? 0);
        summation.totalCost += Number(item?.total_cost ?? 0);
        summation.totalNetIncome += Number(item?.net_income ?? 0);
        summation.totalNetIncomePercent += Number(item?.net_income_percent ?? 0);
      }
      
      table.draw();

      $('#financialTable tfoot').remove();
      $('#financialTable').append(`
        <tfoot class="data-table-footer-row">
          <tr>
            <th>Sub Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>$${summation.totalSpentInARS.toFixed(2)}</th>
            <th>$${summation.totalSpentInUSD.toFixed(2)}</th>
            <th></th>
            <th>$${summation.totalRevenue.toFixed(2)}%</th>
            <th>$${summation.totalGoogleMediaCost.toFixed(2)}</th>
            <th>$${summation.totalPlusMShare.toFixed(2)}</th>
            <th>$${summation.totalCost.toFixed(2)}</th>
            <th>$${summation.totalNetIncome.toFixed(2)}</th>
            <th>${summation.totalNetIncomePercent.toFixed(2)}%</th>
          </tr>
        </tfoot>
      `);
    }
  }

  htmlTemplates() {
    return {
      tableFilterItem: (data) => {
        function dropdownItemHiddenClass() {
          return `dropdown-item ${data?.type === "text" ? "hide-filter-item" : ""}`;
        }

        return `
        <div style="display: flex;" data-contain="filter">
          <input data-name="query" type="${data?.type}"
                 id="input${data?.key}"
                 placeholder="${data?.title}" />
          <div class="dropdown">
              <button 
                      class="btn btn-secondary dropdown-toggle" type="button"
                      id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                      aria-expanded="false">
                  <i class="fa fa-filter"></i>
              </button>
  
              <div class="dropdown-menu custom-filter-dropdown">
                  <a class="dropdown-item text-danger" data-action="clear"
                     style="display: block;" onclick="clearDropdownValue(this)"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     >
                      Clear
                  </a>
                  <a class="dropdown-item" style="display: block;"
                     onclick="addDropdownValue(this)" data-idx="${data?.colIndex}"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     data-operation="equal">Is equal to</a>
                  <a class="dropdown-item" style="display: block;"
                     onclick="addDropdownValue(this)" data-idx="${data?.colIndex}"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     data-operation="not_equal">Is not equal to</a>
                  <a class="dropdown-item ${dropdownItemHiddenClass()}" style="display: block;"
                     onclick="addDropdownValue(this)" data-idx="${data?.colIndex}"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     data-operation="greater_than">Greater than</a>
                  <a class="dropdown-item ${dropdownItemHiddenClass()}" style="display: block;"
                     onclick="addDropdownValue(this)" data-idx="${data?.colIndex}"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     data-operation="greater_equal">Greater equal</a>
                  <a class="dropdown-item ${dropdownItemHiddenClass()}" style="display: block;"
                     onclick="addDropdownValue(this)" data-idx="${data?.colIndex}"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     data-operation="less_than">Less than</a>
                  <a class="dropdown-item ${dropdownItemHiddenClass()}" style="display: block;"
                     onclick="addDropdownValue(this)" data-idx="${data?.colIndex}"
                     data-title="${data?.title}"
                     data-key="${data?.key}"
                     data-operation="less_equal">Less equal</a>
              </div>
              <input type="hidden" id="operator${data?.key}"
                     name="selected_filter_operation" />
              <input type="hidden" id="colIndex${data?.key}"
                     name="selectedColIndex" />
          </div>
        </div>
        `;
      },
    }
  }
}

const FINANCIAL_REPORT = new FinancialReport();
FINANCIAL_REPORT.init();