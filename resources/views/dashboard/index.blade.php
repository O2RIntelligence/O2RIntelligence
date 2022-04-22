<div class="dashboard-report">
    <div class="loader">
        <div class="lds-hourglass"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <div class="pull-left">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-info active">
                            <input name="page_type" type="radio" value="dates" checked> {{ __('General') }}
                        </label>
                        <label class="btn btn-info">
                            <input  name="page_type" type="radio" value="seats"> {{ __('Seats') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
            <div class="box grid-box with-border">
                <div class="box-header with-border text-center">
                    <b>{{ __('Daily Net Income Projection') }}</b>
                </div>
                <div class="box-body text-center">
                    <h2 id="DNI_Projection"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box grid-box with-border">
                <div class="box-header with-border text-center">
                    <b>{{ __('Monthly Net Income Projection') }}</b>
                </div>
                <div class="box-body text-center">
                    <h2 id="MNI_Projection"></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box">
                <div class="box-header with-border text-center">
                    <b>{{ __('Daily NET Income') }}</b>
                </div>
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="DNetIncome_container" style="width:100%;height:auto;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box">
                <div class="box-header with-border text-center">
                    <b>{{ __('Hourly NET Income') }}</b>
                </div>
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="HNetIncome_container" style="width:100%;height:auto;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

@include('partials/report-script')
<script>
    var current_page = "dashboard";
</script>