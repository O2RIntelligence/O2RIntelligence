class GoogleAdsManager {
  initialState = {};
  state = {};
  utils = {};

  constructor() {
    this.initialState = {};
    this.state = this.initialState;
    this.setState = this.setState.bind(this);
    this.init = this.init.bind(this);

    this.utils = {
      /**
       *
       * @param labels
       * @param dataSets
       * @param chartType {"daily_cost" | "hourly_cost"}
       */
      createChartData(labels, dataSets, chartType) {
        let datasets = [];

        if(dataSets && dataSets?.length > 0) {
          dataSets?.forEach((data) => {
            datasets.push({
              label: data?.name,
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: chartType === "daily_cost" ? data?.dailyCostGraphData : chartType === "hourly_cost" ? data?.hourlyCostGraphData : [],
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
}