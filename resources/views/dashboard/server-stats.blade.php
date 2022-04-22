<div class="dashboard-report">
    <div class="loader">
        <div class="lds-hourglass"></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box grid-box with-border">
                <div class="box-header with-border text-center">
                    <b>{{ __('Impressions by Day') }}</b>
                </div>
                <div class="box-body text-center">
                    <h2 id="DNI_Projection"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box grid-box with-border">
                <div class="box-header with-border text-center">
                    <b>{{ __('Impressions by Hour') }}</b>
                </div>
                <div class="box-body text-center">
                    <h2 id="MNI_Projection"></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <section class="content-header">
            <h1>
                Proxy Control
            </h1>
        </section>
        <br>

        <div class="col-md-12">
            <div id="proxyStats" class="row">

                <div id="proxyClone" class="proxy-card col-md-4 hidden">
                    <h3 class="text-center">
                        <img width="35px" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgZGF0YS1uYW1lPSJMYXllciAxIiBpZD0iTGF5ZXJfMSIgdmlld0JveD0iMCAwIDY0IDY0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxkZWZzPjxzdHlsZT4uY2xzLTEsLmNscy0ze2ZpbGw6bm9uZTtzdHJva2U6IzM3YTg0OTtzdHJva2UtbGluZWNhcDpyb3VuZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLXdpZHRoOjJweDt9LmNscy0ye2ZpbGw6IzM3YTg0OTt9LmNscy0ze3N0cm9rZS1kYXNoYXJyYXk6MC4xMSA0LjU2O308L3N0eWxlPjwvZGVmcz48dGl0bGUvPjxsaW5lIGNsYXNzPSJjbHMtMSIgeDE9IjE0IiB4Mj0iNTAiIHkxPSIyMiIgeTI9IjIyIi8+PGxpbmUgY2xhc3M9ImNscy0xIiB4MT0iMTQiIHgyPSI1MCIgeTE9IjM0IiB5Mj0iMzQiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik01MCw0NlYxMmEyLDIsMCwwLDAtMi0ySDE2YTIsMiwwLDAsMC0yLDJWNDZaIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTIiIGN4PSI0MyIgY3k9IjE2IiByPSIyIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTIiIGN4PSI0MyIgY3k9IjI4IiByPSIyIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTIiIGN4PSI0MyIgY3k9IjQwIiByPSIyIi8+PGxpbmUgY2xhc3M9ImNscy0xIiB4MT0iNDAiIHgyPSIyNCIgeTE9IjU0IiB5Mj0iNTQiLz48bGluZSBjbGFzcz0iY2xzLTEiIHgxPSIzNiIgeDI9IjM1Ljk1IiB5MT0iNDAiIHkyPSI0MCIvPjxsaW5lIGNsYXNzPSJjbHMtMyIgeDE9IjMxLjM5IiB4Mj0iMjQuMzMiIHkxPSI0MCIgeTI9IjQwIi8+PGxpbmUgY2xhc3M9ImNscy0xIiB4MT0iMjIuMDUiIHgyPSIyMiIgeTE9IjQwIiB5Mj0iNDAiLz48bGluZSBjbGFzcz0iY2xzLTEiIHgxPSIzNiIgeDI9IjM1Ljk1IiB5MT0iMjgiIHkyPSIyOCIvPjxsaW5lIGNsYXNzPSJjbHMtMyIgeDE9IjMxLjM5IiB4Mj0iMjQuMzMiIHkxPSIyOCIgeTI9IjI4Ii8+PGxpbmUgY2xhc3M9ImNscy0xIiB4MT0iMjIuMDUiIHgyPSIyMiIgeTE9IjI4IiB5Mj0iMjgiLz48bGluZSBjbGFzcz0iY2xzLTEiIHgxPSIzNiIgeDI9IjM1Ljk1IiB5MT0iMTYiIHkyPSIxNiIvPjxsaW5lIGNsYXNzPSJjbHMtMyIgeDE9IjMxLjM5IiB4Mj0iMjQuMzMiIHkxPSIxNiIgeTI9IjE2Ii8+PGxpbmUgY2xhc3M9ImNscy0xIiB4MT0iMjIuMDUiIHgyPSIyMiIgeTE9IjE2IiB5Mj0iMTYiLz48bGluZSBjbGFzcz0iY2xzLTEiIHgxPSIzMiIgeDI9IjMyIiB5MT0iNDYiIHkyPSI1NCIvPjwvc3ZnPg==">
                        &nbsp;<span class="heading-text" style="font-weight:bold; text-transform: uppercase;"></span>
                    </h3>
                    <div class="box grid-box">
                        <div class="box-body table-responsive">
                            <table class="table table-hover grid-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Count</th>
                                        <th>REQ</th>
                                        <th>OPPS</th>
                                        <th>FILL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="info">
                                        <th>
                                        {{__('Avg')}}
                                        </th>
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
        </div>
    </div>


    <div id="environmentStats" class="row">
        <div id="environmentClone" class="col-md-4 hidden">

            <h3 class="text-center">
                <span style="border-bottom:1px solid #ccc; padding-bottom:16px;">
                    <img src="data:image/png;base64,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">
                    &nbsp;Server environment <span class="heading-text" style="font-weight:bold; text-transform: uppercase;"></span>
                    <div style="clear:both;"></div>
                </span>
                <div class="row" style="padding-top: 10px;">
                    <h4 class="col-md-6 text-right"><img width="32px" src="data:image/png;base64,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"> <b>Proxies:</b> <span class="proxies-count"></span></h4>
                    <h4 class="col-md-6 text-left"><img width="32px" src="data:image/png;base64,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"> <b>Servers:</b> <span class="servers-count"></span></h4>
                </div>
            </h3>

            <div class="box grid-box">
                <div class="box-body table-responsive">
                    <table class="table table-hover grid-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>REQ</th>
                                <th>OPP</th>
                                <th>IMP</th>
                                <th>OPP FILL</th>
                                <th>Imp FILL</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="box grid-box">
                <div class="box-body table-responsive">
                    <table id="overall-stats" class="table table-hover grid-table">
                        <thead>
                            <tr>
                                <th>ENV</th>
                                <th>ID</th>
                                <th>Seat</th>
                                <th>Traffic Type</th>
                                <th>Daily REQ</th>
                                <th>Daily IMP</th>
                                <th>Daily FILL</th>
                                <th>Current REQ</th>
                                <th>Current IMP</th>
                                <th>Current FILL</th>
                                <th>Server Type</th>
                                <th>Server Status</th>
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

<script>
    var current_page = "server-stats";
    window["environments"] = {!! json_encode($environments) !!};
</script>
@include('partials/report-script')
