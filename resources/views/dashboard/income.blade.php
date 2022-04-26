<div class="income-report">
    <div class="loader">
        <div class="lds-hourglass"></div>
    </div>
    <div class="col-md-12">
        <div class="box grid-box with-border">
            <div class="box-body">
                <h5 class="pull-left">{{__('Generic report date scope')}}:</h5>
                @include('partials.date-filters')
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box grid-box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-4">
                        <b style="line-height:35px;">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datatables-vertical-container-table">
                                <i class="fa fa-list"></i>
                            </button>
                            {{ __('Income Channel Overview') }}
                        </b>
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
            <div class="box-body table-responsive no-padding">
                <table id="vertical-container-table" class="table table-hover grid-table">
                    <thead>
                        <tr>
                            <th>Seat</th>
                            <th>Revenue</th>
                            <th>Media Cost</th>
                            <th>Operation Fee</th>
                            <th>Gross Profit</th>
                            <th>Partner Fee</th>
                            <th>Profit Margin</th>
                            <th>Net Income</th>
                            <th>Net Inc (%)</th>
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box grid-box with-border">
            <div class="box-body">
                <div class="isResizable">
                    <div class="chart-container">
                        <canvas id="RNP_1" style="width:100%;height:auto;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box grid-box">
            <div class="box-header with-border text-center">

                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#datatables-media-income-container-table">
                    <i class="fa fa-list"></i>
                </button>

                <b>{{ __('Master Income Overview') }}</b>
            </div>
            <div class="box-body table-responsive">
                <table id="media-income-container-table" class="table table-hover grid-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Impressions</th>
                            <th>Scanned Impressions</th>
                            <th>Ad Requests</th>
                            <th>Gross Revenue</th>
                            <th>Net Revenue</th>
                            <th>Media Cost</th>
                            <th>Marketplace fee</th>
                            <th>Ad Serving Fee</th>
                            <th>Scanning Fee</th>
                            <th>Gross Profit</th>
                            <th>Net Income</th>
                            <th></th>
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
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

@include('partials/report-script')
<script>
        var current_page = "income";

</script>
