<div class="income-report">
    <div class="loader">
        <div class="lds-hourglass"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="pull-left">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-info active">
                                <input id="general_media" class="media_type" name="media_type" type="radio" value="general" checked> {{ __('General') }}
                            </label>
                            <label class="btn btn-info">
                                <input class="media_type" name="media_type" type="radio" value="media-s"> {{ __('Media-S') }}
                            </label>
                            <label class="btn btn-info">
                                <input class="media_type" name="media_type" type="radio" value="media-t"> {{ __('Media-T') }}
                            </label>
                        </div>
                    </div>
                    @include('partials.date-filters')
                </div>
            </div>
        </div>
    </div>

    @if($user->isRole('administrator') && count($seats))
        <div class="row">
            <div class="col-md-12">
                <div class="box grid-box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-4">
                                <b style="line-height:35px;">{{ __('Media Sources reports') }}</b>
                            </div>
                            <div class="col-md-7">
                                <select name="seats" class="form-control select2" multiple>
                                    @foreach ($seats as $seat)
                                        <option value="{{ $seat['id'] }}" selected>{{ $seat['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="btn-black btn btn-sm btn-block refresh-seats" style="margin-top: 2px;">{{__('Refresh')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="General_Chart" class="row">
        <div class="col-md-3">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_G_Source" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_G_CTV" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_G_inApp" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_G_Int" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="MT_Charts" class="row hiddenChart">
        <div class="col-md-4">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_MT_Seat" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_MT_Env" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_MT_Int" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="MS_Chart" class="row hiddenChart">
        <div class="col-md-4">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_MS_Seat" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_MS_Env" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_MS_Int" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box">
                <div class="box-header with-border">
                    <b style="line-height: 30px;">{{ __('Overall Performance Report') }}</b>

                    <div class="pull-right">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-sm btn-info active">
                                <input class="filter-overall" name="filter-overall" type="radio" value="" checked> {{ __('General') }}
                            </label>
                            <label class="btn btn-sm btn-info">
                                <input class="filter-overall" name="filter-overall" type="radio" value="ctv"> {{ __('CTV') }}
                            </label>
                            <label class="btn btn-sm btn-info">
                                <input class="filter-overall" name="filter-overall" type="radio" value="mobile_app"> {{ __('inApp') }}
                            </label>
                        </div>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-sm btn-warning active">
                                <input class="overall-view-type" name="overall-view-type" type="radio" value="table" chekced> <i class="fa fa-list-alt"></i>
                            </label>
                            <label class="btn btn-sm btn-warning">
                                <input class="overall-view-type" name="overall-view-type" type="radio" value="graph"> <i class="fa fa-area-chart"></i>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="box-body table-responsive">
                    <table id="overall-performance-container-table" class="table table-hover grid-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Environment</th>
                                <th>Ad Requests</th>
                                <th>Impressions</th>
                                <th>Total Revenue</th>
                                <th>eCPM</th>
                                <th class="success">$/AD Req in Mil.</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                    <div id="overall-performance-graph" class="hiddenChart">
                        <div class="isResizable">
                            <div class="chart-container">
                                <canvas id="overall_performance_chart" style="max-width:100%;min-height:200px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box">
                <div class="box-header with-border">
                    <b style="line-height: 30px;">{{ __('Source compare') }}</b>

                    <div class="pull-right">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-sm btn-info active">
                                <input class="filter-sourceCompare" name="filter-sourceCompare" type="radio" value="" checked> {{ __('General') }}
                            </label>
                            <label class="btn btn-sm btn-info">
                                <input class="filter-sourceCompare" name="filter-sourceCompare" type="radio" value="ctv"> {{ __('CTV') }}
                            </label>
                            <label class="btn btn-sm btn-info">
                                <input class="filter-sourceCompare" name="filter-sourceCompare" type="radio" value="mobile_app"> {{ __('inApp') }}
                            </label>
                        </div>
                    </div>

                </div>
                <div class="box-body table-responsive">
                    <table id="compare-performance-container-table" class="table table-hover table-bordered grid-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th colspan="5" class="text-center">{{ __('Media - T') }}</th>
                                <th colspan="5" class="text-center">{{ __('Media - S') }}</th>
                            </tr>
                            <tr>
                                <th class="info">Date</th>
                                <th>Ad Requests</th>
                                <th>Impressions</th>
                                <th>Total Revenue</th>
                                <th>$/AD Req in Mil.</th>
                                <th>Fill Rate(%)</th>
                                <th>Ad Requests</th>
                                <th>Impressions</th>
                                <th>Total Revenue</th>
                                <th class="success">$/AD Req in Mil.</th>
                                <th>Fill Rate(%)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>

@include('partials/report-script')
<script>
    var current_page = "media_sources";
</script>
