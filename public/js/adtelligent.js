class adtelligent {
    constructor(seat) {
        this.seat = seat;
    }

    async request(params, route = '', base = '') {
        var url = (base.length > 0 ? base : window["ADTELLIGENT_BASE_URL"]);
        route = route.length > 0 ? "/" + route : "";
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: url + route,
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