class adtelligent {
    constructor(seat) {
        this.seat = seat;
    }

    async request(params, route = '', base = '') {
        var url = (base.length > 0 ? base : window["ADTELLIGENT_BASE_URL"]);
        route = route.length > 0 ? "https://ssp.adtelligent.com/api/statistics/ssp2/" + route : "https://ssp.adtelligent.com/api/statistics/ssp2";
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: route,
                headers: {
                    "x-authentication-session-id": this.seat.api_token
                },
                data: params,
                dataType: 'json',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(xhr.status);
                }
            });
        });
    }


}
