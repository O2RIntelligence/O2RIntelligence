class adtelligent {
    constructor(seat) {
        this.seat = seat;
    }

    async request(params, route = '', base = '') {
        // route = route.length > 0 ? "/" + route : "";
        // var url = this.isLocalNetwork()?"https://ssp.adtelligent.com/api/statistics/ssp2":base;

        var url = (base.length > 0 ? base : (window["ADTELLIGENT_BASE_URL"]??'https://ssp.adtelligent.com/api/statistics/ssp2'));
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

    isLocalNetwork(hostname = window.location.hostname) {
        return (
            (['localhost', '127.0.0.1', '', '::1'].includes(hostname))
            || (hostname.startsWith('192.168.'))
            || (hostname.startsWith('10.0.'))
            || (hostname.endsWith('.local'))
        )
    }


}
