<div class="main-report">
    <div class="loader">
        <div class="lds-hourglass"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box with-border">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-7">
                            <b style="line-height:35px;">{{ __('Filters') }}</b>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <form action="" class="filters-form">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <label>{{ __('Select Report') }}:</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="btn-group report-fields" data-toggle="buttons">
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="form-group">
                            <div class="row" @if($user->isRole('seat')) style="display:none;" @endif>
                                <div class="col-md-2 text-center">
                                    <label>{{ __('Select Seats') }}:</label>
                                </div>
                                <div class="col-md-10">
                                    <select name="seats" class="form-control select2" multiple>
                                        @foreach ($seats as $seat)
                                            <option value="{{ $seat['id'] }}" selected>{{ $seat['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <label>{{ __('Select Date Reange') }}:</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="btn-group date-fields" data-toggle="buttons">
                                        <label class="btn btn-info active">
                                            <input class="date-field" name="date_period" type="radio" value="today"> {{ __('Today') }}
                                        </label>
                                        <label class="btn btn-info">
                                            <input class="date-field" name="date_period" type="radio" value="yesterday"> {{ __('Yesterday') }}
                                        </label>

                                        <label class="btn btn-info">
                                            <input class="date-field" name="date_period" type="radio" value="last7"> {{ __('Last 7 days') }}
                                        </label>
                                        <label class="btn btn-info">
                                            <input class="date-field" name="date_period" type="radio" value="last30"> {{ __('This Month') }}
                                        </label>
                                        <label class="btn btn-info">
                                            <input class="date-field" name="date_period" type="radio" value="custom"> {{ __('Custom range') }}
                                        </label>
                                    </div>
                                    <div class="custom-daterange">
                                        <div class="input-group input-daterange">
                                            <input type="text" name="start_date" class="form-control " value="{{ date('Y-m-d') }}">
                                            <div class="input-group-addon">to</div>
                                            <input type="text" name="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="field-inputs">
                            <div class="fields-container">
                                <div style="display:none;" class="form-group col-md-6 clonable-field">
                                        <div class="col-md-2 text-center">
                                            <label></label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="filters" name="dummy" multiple></select>
                                            <div class="text-right">
                                                <div class="checkbox" style="display: inline-block; margin-right:5px;">
                                                    <label>
                                                    <input class="summ" value="1" type="checkbox"> Summary
                                                    </label>
                                                </div>
                                                <div class="checkbox" style="display: inline-block;">
                                                    <label>
                                                    <input class="exclude" value="1" type="checkbox"> Exclude
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <label>{{ __('Metric Filter') }}:
                                        <button class="btn btn-default btn-sm add-metric" type="button"> <i class="fa fa-plus"></i> </button>

                                    </label>

                                </div>
                                <div class="col-md-10 metric-container">

                                    <div class="form-group  metric-row clonable-metric" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select class="metric"></select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control operation">
                                                    <option value="eq">=</option>
                                                    <option value="ne">!=</option>
                                                    <option value="gt">></option>
                                                    <option value="ge">>=</option>
                                                    <option value="lt"><</option>
                                                    <option value="le"><=</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-danger delete-metric-row" type="button"> <i class="fa fa-trash"></i> </button>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="run-report btn btn-info btn-lg">Run Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="box grid-box with-border">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-7">
                            <b style="line-height:35px;">{{ __('Results') }}</b>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table id="report" class="table table-bordered table-hover grid-table">
                        <thead></thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<select style="display: none" id="filterColumn" name="filterColumn[]" multiple></select>

<div class="clear"></div>
@include('partials/report-script')
