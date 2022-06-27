class GoogleAdsManager {
  initialState = {};
  state = {};
  utils = {
    initiateLineChartOne(){
          //--------------
          //- AREA CHART -
          //--------------

          // Get context with jQuery - using jQuery's .get() method. 
          var lineChartCanvasOne = $("#lineChartOne").get(0).getContext("2d");
          var lineChartOne = new Chart(lineChartCanvasOne); 
          // This will get the first returned node in the jQuery collection. 

          var lineChartOneData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
              {
                label: "Electronics",
                fillColor: "rgba(210, 214, 222, 1)",
                strokeColor: "rgba(210, 214, 222, 1)",
                pointColor: "rgba(210, 214, 222, 1)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [65, 59, 80, 81, 56, 55, 40]
              },
              {
                label: "Digital Goods",
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: [28, 48, 40, 19, 86, 27, 90]
              }
            ]
          };

          var lineChartOneOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: false,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot: false,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true, 
          };



  
          //-------------
          //- LINE CHART -
          //-------------- 
          lineChartOneOptions.datasetFill = false;
          lineChartOne.Line(lineChartOneData, lineChartOneOptions); 
    }
  };

  constructor() {
    this.initialState = {};
    this.state = this.initialState;
    this.setState = this.setState.bind(this);
    this.init = this.init.bind(this);

    this.utils = {};

    this.init();
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
    this.utils.initiateLineChartOne();
  }
}

const TEST = new GoogleAdsManager();