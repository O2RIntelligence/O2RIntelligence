<div class="clear"></div>

<!-- Modal -->
<div class="modal fade" id="datatables-controls" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Manage Table</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="btn-group" data-toggle="buttons">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-container">
</div>
<style>
    .swal2-popup {
        font-size: 14px !important;
    }

    @if($user->isRole('seat'))
        .non-partner {
        display: none;
    }

    @endif

    .loading {
        float: left;
        top: 50%;
        left: 50%;
        margin-top: 112px;
        margin-left: -61px;
        margin-bottom: -248px;
        border-left: 1px solid #fff;
        border-bottom: 1px solid #fff;
        box-sizing: border-box;
    }

    @keyframes loading {
        0% {
            background-color: #cd0a00;
        }
        30% {
            background-color: #fa8a00;
        }
        50% {
            height: 100px;
            margin-top: 0px;
        }
        80% {
            background-color: #91d700;
        }
        100% {
            background-color: #cd0a00;
        }
    }

    /*@-moz-keyframes loading {
      50% { height: 100px; margin-top: 0px; }
    }
    @-o-keyframes loading {
      50% { height: 100px; margin-top: 0px; }
    }
    @keyframes loading {
      50% { height: 100px; margin-top: 0px; }
    }*/
    .loading .loading-1 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 0.25s;
        animation-delay: 0.25s;
    }

    .loading .loading-2 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 0.5s;
        animation-delay: 0.5s;
    }

    .loading .loading-3 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 0.75s;
        animation-delay: 0.75s;
    }

    .loading .loading-4 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 1s;
        animation-delay: 1s;
    }

    .loading .loading-5 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 1.25s;
        animation-delay: 1.25s;
    }

    .loading .loading-6 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 1.5s;
        animation-delay: 1.5s;
    }

    .loading .loading-7 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 1.75s;
        animation-delay: 1.75s;
    }

    .loading .loading-8 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 2s;
        animation-delay: 2s;
    }

    .loading .loading-9 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 2.25s;
        animation-delay: 2.25s;
    }

    .loading .loading-10 {
        height: 10px;
        width: 30px;
        background-color: #fff;
        display: inline-block;
        margin-top: 90px;
        -webkit-animation: loading 2.5s infinite;
        -moz-animation: loading 2.5s infinite;
        -o-animation: loading 2.5s infinite;
        animation: loading 2.5s infinite;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        -webkit-animation-delay: 2.5s;
        animation-delay: 2.5s;
    }
</style>
<script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
<script>
    window["rates"] = {
        'mobile_rate': {{ config('mobile_rate') }},
        'ctv_rate': {{ config('ctv_rate') }},
        'advertising_fee': {{ config('advertising_fee') }},
        'scoring_fee': {{ config('scoring_fee') }},
    };
    window["user_role"] = @if($user->isRole('administrator')) "admin"
    @else "seat" @endif;
    {{--        @if($user->isRole('administrator') || ($user->isRole('reporter')) || $user->isRole('seats'))--}}
        window["seats"] = {!! json_encode($seats) !!};
    {{--        @else--}}
            {{--        window["seats"] = { "{{ $user->id }}": {id: "{{ $user->id }}", name:"{{ $user->name }}", "partner_fee": "{{ $user->partner_fee }}", "api_token": "{{ $user->api_token }}"} };--}}
            {{--        @endif--}}
        window["ADTELLIGENT_START_URL"] = "{{ config('services.base_url.adtelligent') }}";
    window["ADTELLIGENT_BASE_URL"] = window["ADTELLIGENT_START_URL"] + "/api/statistics/ssp2";
    $.get("../../api/get-ms-channel-ids", function (data, status) {
        window['mt_channel_id'] = data.value.split(',');
    });
    $.get("../../api/get-serving-fee", function (data, status) {
        window['serving_fee'] = data.value;
    });

    let startDate = $("input[name=start_date]").val();
    let endDate = $("input[name=end_date]").val();


    function getPixalateData(startDate, endDate, callback) {
        let pixalateUrl = '{{route('pixalate.get')}}' + '?startDate=' + startDate + '&endDate=' + endDate;

        window['pixalateImpressions'] = null;
        window['pixalateError'] = null;

        $.ajax({
            url: pixalateUrl,
            type: 'GET',
            method: 'GET',
            success: function (res) {
                console.log(res);
                window['pixalateImpressions'] = typeof res === 'string' ? JSON.parse(res) : res;
                if(typeof callback === "function"){
                    callback();
                }
                // console.log(window['pixalateImpressions']);
            },
            error: function (request, status, error) {
                if(typeof callback === "function"){
                    callback();
                }
                let result = request.responseText.match(/<title>(.*)<\/title>/);
                window['pixalateError'] = result[1];
                swal('Pixalate Server Error', result[1], 'error');
            }
        });
    }

    window["user_id"] = {{ $user->id }};
    window["date_default_timezone_get"] = "{{ date_default_timezone_get() }}";
    window["admin_url"] = "{{ url('/admin') }}";
</script>
