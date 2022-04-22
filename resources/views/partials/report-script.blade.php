<div class="clear"></div>

<!-- Modal -->
<div class="modal fade" id="datatables-controls" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

<script>
        window["rates"] = {
            'mobile_rate': {{ config('mobile_rate') }},
            'ctv_rate': {{ config('ctv_rate') }},
            'advertising_fee': {{ config('advertising_fee') }},
            'scoring_fee': {{ config('scoring_fee') }},
        };
        window["user_role"] = @if($user->isRole('administrator')) "admin" @else "seat" @endif;
        @if($user->isRole('administrator') || ($user->isRole('reporter')))
        window["seats"] = {!! json_encode($seats) !!};
        @else
        window["seats"] = { "{{ $user->id }}": {id: "{{ $user->id }}", name:"{{ $user->name }}", "partner_fee": "{{ $user->partner_fee }}", "api_token": "{{ $user->api_token }}"} };
        @endif
        window["ADTELLIGENT_START_URL"] = "{{ env('ADTELLIGENT_BASE_URL') }}";
        window["ADTELLIGENT_BASE_URL"] = window["ADTELLIGENT_START_URL"] + "/api/statistics/ssp2";
        window["mt_channel_id"] = '{{ config('mt_channel_id') }}';
        window["mt_channel_id"] = window["mt_channel_id"].split(',');
        window["user_id"] = {{ $user->id }};
        window["date_default_timezone_get"] =  "{{ date_default_timezone_get() }}";
        window["admin_url"] = "{{ url('/admin') }}";
</script>
