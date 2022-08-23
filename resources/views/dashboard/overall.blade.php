<div class="income-report">
    <div class="loader">
        <div class="lds-hourglass"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-4">
                            <b style="line-height:35px;">{{ __('Income Channel Overview') }}</b>
                        </div>
                        @if(count($seats))
                            <div class="col-md-7">
                                <select name="seats" class="form-control select2" multiple>
                                    @foreach ($seats as $seat)
                                        <option value="{{ $seat['id'] }}" selected>{{ $seat['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="refresh-seats btn-black btn btn-sm btn-block" style="margin-top: 2px;">{{__('Refresh')}}</button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <h5 class="pull-left">{{__('Generic report date scope')}}:</h5>
                    @include('partials.date-filters')
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="impression_chart" style="max-width:100%;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 non-partner">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_Seat" style="max-width:500px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="@if($user->isRole('seat')) col-md-6 @else col-md-4 @endif">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_Env" style="max-width:450px;min-height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="@if($user->isRole('seat')) col-md-6 @else col-md-3 @endif">
            <div class="box grid-box with-border">
                <div class="box-body">
                    <div class="isResizable">
                        <div class="chart-container">
                            <canvas id="Donught_Integration" style="max-width:450px;min-height:200px;"></canvas>
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
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datatables-vertical-container-table">
                        <i class="fa fa-list"></i>
                    </button>
                    <b>{{ __('Revenue Overview') }}</b>
                </div>
                <div class="box-body table-responsive">
                    <table id="vertical-container-table" class="table table-hover grid-table">
                        <thead>
                            <tr>
                                <th>Seat</th>
                                <th>Impressions</th>
                                <th>Revenue</th>
                                <th>Media Cost</th>
                                <th>Operation Fee</th>
                                <th>Scoring Fee</th>
                                <th>Gross Profit</th>
                                <th>Partner Fee</th>
                                <th>Net Profit</th>
                                <th>Profit Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr class="info">
                                <th>
                                {{__('Totals')}}
                                </th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-4">
            <div class="box grid-box">
                <div class="box-body table-responsive">
                    <div class="table-header">
                        {{ __('Media Type') }}
                    </div>
                    <table id="environment-type" class="table table-bordered table-hover grid-table">
                        <thead>
                            <tr class="info">
                                <th></th>
                                <th colspan="2"  class="text-center">CTV</th>
                                <th colspan="2"  class="text-center">InApp</th>
                            </tr>
                            <tr>
                                <th>Seat</th>
                                <th class="text-center">Revenue</th>
                                <th class="text-center">%</th>
                                <th  class="text-center">Revenue</th>
                                <th  class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box grid-box">
                <div class="box-body table-responsive" style="max-height: 324px;">
                    <div class="table-header">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datatables-source-type">
                            <i class="fa fa-list"></i>
                        </button>
                        {{ __('Top suppliers') }}</div>
                    <table id="source-type" class="table table-bordered table-hover grid-table">
                        <thead>
                            <tr>
                                <th>Seat</th>
                                <th>Source</th>
                                <th>Imp</th>
                                <th>Revenue</th>
                                <th>Fill Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box grid-box">
                <div class="box-body table-responsive" style="max-height: 324px;">
                    <div class="table-header">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datatables-advertiser-type">
                            <i class="fa fa-list"></i>
                        </button>
                        {{ __('Top demand') }}</div>
                    <table id="advertiser-type" class="table table-bordered table-hover grid-table">
                        <thead>
                            <tr>
                                <th>Seat</th>
                                <th>Demand Name</th>
                                <th>Imp</th>
                                <th>Revenue</th>
                                <th>Fill Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

@include('partials/report-script')
<script>
        var current_page = "overall-report";
</script>
