 
    <div class="content">
        <div class="box-body" style="padding-left: 0; padding--right: 0">
            <div class="row">
                <div class="col-md-5">  
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-info active" data-action="account_type" data-name="general">
                            <input name="account_type" type="radio" value="general"> General
                        </label>
                        <label class="btn btn-info" data-action="account_type" data-name="master_accounts">
                            <input name="account_type" type="radio" value="master_accounts"> Master Accounts
                        </label>
                        <label class="btn btn-info" data-action="account_type" data-name="sub_accounts">
                            <input name="account_type" type="radio" value="sub_accounts"> Sub Accounts
                        </label>
                    </div>
                </div>
                <div class="col-md-7 text-right">
                    <div class="pull-right time-periods">
                        <input type="hidden" name="date_period" value="today">
                        <button data-period="today" class="btn btn-secondary change-period active" type="button">Today</button>
                        <button data-period="yesterday" class="btn btn-secondary change-period" type="button">Yesterday</button>
                        <button data-period="last7" class="btn btn-secondary change-period" type="button">Last 7 Days</button>
                        <button data-period="last30" class="btn btn-secondary change-period" type="button">This Month</button>
                        <button data-period="custom" class="btn btn-secondary change-period" id="daterange-btn" type="button">
                            <i class="fa fa-calendar"></i> Custom Range
                            <i class="fa fa-caret-down"></i>
                        </button>  
                    </div>
                </div>
            </div> 
        </div>

        
        <div class="box grid-box with-border"> 
            <div class="box-body">  
                <div class="row"> 
                    <div class="col-md-11 text-right">
                        <select name="account-filter" id="account-filter" class="form-control" multiple>
                        </select>
                    </div>
                    <div class="col-md-1 text-right">
                        <button class="refresh-seats btn-black btn btn-block" style="max-height: 32px" data-action="filter-search">Refresh</button>
                    </div>
                 </div> 
            </div>
        </div>  
    </div>
 