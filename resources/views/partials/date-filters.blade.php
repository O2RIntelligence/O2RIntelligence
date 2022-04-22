<div class="pull-right time-periods">
    <input type="hidden" name="date_period" value="today">
    <button data-period="today" class="btn btn-secondary change-period" type="button">{{ __('Today' )}}</button>
    <button data-period="yesterday" class="btn btn-secondary change-period" type="button">{{ __('Yesterday' )}}</button>
    <button data-period="last7" class="btn btn-secondary change-period" type="button">{{ __('Last 7 Days' )}}</button>
    <button data-period="last30" class="btn btn-secondary change-period" type="button">{{ __('This Month' )}}</button>
    <button data-period="custom" class="btn btn-secondary change-period show-daterange" type="button">
        {{ __('Custom Range' )}}
        <span class="caret"></span>
    </button>
</div>
<div class="clear"></div>
<div class="custom-daterange">
    <div class="input-group input-daterange">
        <input type="text" name="start_date" class="form-control " value="{{ \Carbon\Carbon::now()->subDays(1)->format('Y-m-d') }}">
        <div class="input-group-addon">to</div>
        <input type="text" name="end_date" class="form-control" value="{{ \Carbon\Carbon::now()->subDays(1)->format('Y-m-d') }}">
    </div>
    <div class="form-group">
        <button class="filter-dates btn btn-block btn-primary" type="button">{{ __('Filter') }}</button>
    </div>
</div>