function initiatePopover() {
  $('[data-toggle="popover"]').popover({
    trigger: 'hover',
  });
}

function addDropdownValue(element) {
  const operation = $(element).attr('data-operation');
  const colIdx = $(element).attr('data-idx');
  const title = $(element).attr('data-title');
  $(element).parents('.dropdown').find('[name="selected_filter_operation"]').val(operation);
  $(element).parents('.dropdown').find('[name="selectedColIndex"]').val(colIdx);
  $('#filterColumn').append('<option selected>' + title + '</option>')
}


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
      onPressFilter: self.renderFinancialTable,
    });
    this.prepareFinancialTable();
    this.getFinancialReportData();
  }

  prepareFinancialTable() {
    const self = this;
    const table = $('#financialTable').DataTable({
      'responsive': true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': ['nosort']
      }],
      'dom': 'lBfrtip',
      'buttons': [
        'csv', 'excel'
      ],
      initComplete() {
        var api = this.api();

        $('#financialTable thead tr').clone(true).addClass('filters').appendTo('#financialTable thead');
        $('#financialTable tr.filters th').removeClass('sorting').addClass('nosort');

        api
          .columns()
          .eq(0)
          .each(function (colIdx) {
            // Set the header cell to contain the input element
            var cell = $('.filters th').eq(
              $(api.column(colIdx).header()).index()
            );

            var title = $(cell).text();
            var type = 'text';
            if (title == 'Date') type = 'date'
            if (title == 'Month') type = 'month'

            $(cell).html(`
              <div style="display: flex;" data-contain="filter">
                <input onclick="event.stopPropagation()" data-name="query" type="${type}" id="input${title.replace(/[^a-zA-Z0-9]/g, '-')}" placeholder="${title}" />
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-filter"></i>
                  </button>

                  <div class="dropdown-menu custom-filter-dropdown">
                    <a class="dropdown-item text-danger" data-action="clear" style="display: block;" onclick="clearDropdownValue(this)" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}">
                    Clear
                    </a>
                    <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="equal">Is equal to</a>
                    <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="not_equal">Is not equal to</a>
                    <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="greater_than">Greater than</a>
                    <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="greater_equal">Greater equal</a>
                    <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="less_than">Less than</a>
                    <a class="dropdown-item" style="display: block;" onclick="addDropdownValue(this)" data-idx="${colIdx}" data-title="${title.replace(/[^a-zA-Z0-9]/g, '-')}" data-operation="less_equal">Less equal</a>
                  </div>
                  <input type="hidden" id="operator${title.replace(/[^a-zA-Z0-9]/g, '-')}" name="selected_filter_operation" />
                  <input type="hidden" id="colIndex${title.replace(/[^a-zA-Z0-9]/g, '-')}" name="selectedColIndex" />
                </div>
              </div>
                            `);

            // On every keypress in this input
            $(
              'input',
              $('.filters th').eq($(api.column(colIdx).header()).index())
            )
              .off('keyup change')
              .on('keyup change', function (e) {
                /*e.stopPropagation();

                // Get the search value
                $(this).attr('title', $(this).val());
                const filterCaseValue = getDropdownValue(this);
                console.log("filterCaseValue", filterCaseValue)

                var cursorPosition = this.selectionStart;
                // Search the column for that value

                var regexr = '({search})';
                let searchRegx = this.value != ''
                    ? regexr.replace('{search}', '(((' + this.value + ')))')
                    : '';

                if (filterCaseValue && filterCaseValue !== '') {
                    switch(filterCaseValue) {
                        case 'not_equal':
                            searchRegx = `^(?!(((${this.value})))$)`;
                            break;

                        case 'not_equal':
                            searchRegx = `^(?!(((${this.value})))$)`;
                            break;
                    }
                }*/

                api
                  // .column(colIdx)
                  // .search(searchRegx, true, false)
                  .draw();

                /*$(this)
                    .focus()[0]
                    .setSelectionRange(cursorPosition, cursorPosition);*/
              });


            $(
              '[data-action="clear"]',
              $('.filters th').eq($(api.column(colIdx).header()).index())
                .on('click', function () {
                  setTimeout(() => {
                    api.draw();
                  }, 10);
                })
            );


            $(
              '.dropdown-item',
              $('.filters th').eq($(api.column(colIdx).header()).index())
                .on('click', function () {

                  api.draw();
                })
            );
          });

        $('tr.headers:not(.filters) th').append(`
                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                data-html="true" data-trigger="focus" data-placement="right"
                data-toggle="popover"
                title="Only Equal to and Not Equal to filter option is applied for filtering Text data
                All the filter options can be applied for Number value."></i>
                            `);
        initiatePopover();
      }
    });


    $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
        var filterableColumns = $('#filterColumn').val();
        filterableColumns = Array.from(new Set(filterableColumns))
        // console.log(filterableColumns)
        var flag = true;

        filterableColumns.forEach(function (item, index) {
          if (flag) {
            var inputValue = $("#input" + item).val();
            var operator = $("#operator" + item).val();
            var colIndex = $("#colIndex" + item).val();
            var cellValue = data[colIndex];

            if (item == 'Date' && inputValue) {
              inputValue = inputValue.replaceAll('-', '/');

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

            } else if (item == 'Month' && inputValue) {
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
              if (inputValue.toLowerCase().trim() !== '') {
                switch (operator) {
                  case 'equal':
                    flag = cellValue.toLowerCase().trim() == inputValue.toLowerCase().trim();
                    break;
                  case 'not_equal':
                    flag = cellValue.toLowerCase().trim() != inputValue.toLowerCase().trim();
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

    self.setState({
      financialTable: table,
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


  renderFinancialTable() {
    const self = this;
    const { account_type, selected_accounts } = self.dataFilterState;

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

      for (let item of data) {
        table.row.add([
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
      }
      table.draw();
    }
  }
}

const FINANCIAL_REPORT = new FinancialReport();
FINANCIAL_REPORT.init();