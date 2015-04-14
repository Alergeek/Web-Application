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

$(document).ready(function(){
    loadFrontPage();
});