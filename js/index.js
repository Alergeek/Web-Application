/**
 * Created by morbaes on 14.04.15.
 */

var currentUser;

var loadUserPage = function() {
    $.ajax({
        url: "user-page.html",
        method: "GET",
        statusCode: {
            200: function (data) {
                $('div#page-content').html(data);
                loadUserPageJS();
            }
        }
    });
};

var loadFrontPage = function() {
    $.ajax({
        url: "front-page.html",
        method: "GET",
        statusCode: {
            200: function (data) {
                $('div#page-content').html(data);
                loadFrontPageJS();
            }
        }
    });
};

$(document).ready(function() {
    var token;
    if( window.localStorage ) {
        if( token = window.localStorage.getItem('authToken') ) {
            $.get('api/v1/user/' + token + '/')
            .done(function( data ) {
                currentUser = new User(data.email, token, []);
                loadUserPage();
            }).fail(function() {
                loadFrontPage();
            });
        } else {
            loadFrontPage();
        }
    } else {
        loadFrontPage();
    }
});