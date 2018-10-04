/* global token */

jQuery.ajax({
    url: 'https://api.spotify.com/v1/browse/new-releases?country=SE',
    headers: {
        "Authorization": "Bearer " + token,
        "Accept": "application/json; charset=utf-8",
        "Content-Type": "application/json; charset=utf-8"
    },
    success: function (response) {
        console.log(response);
    }
});

