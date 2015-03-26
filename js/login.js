/**
 * Created by morbaes on 26.03.15.
 *
 * @file login.js
 * @brief Login via AJAX.
 *
 * Login to Edible via AJAX-Call.
 *
 *
 */

$(document).ready(function(){

    $('form.login').submit(function(e){

        /**
         * \brief Login on Form submit.
         * Calls POST api/session with email and cleartext password. 404 => login failed, 200 => login successfull.
         */

        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();

        //var shaObj = new jsSHA(password, "TEXT");
        //var pw_hash = shaObj.getHash("SHA-1", "HEX");

        $.ajax({
            url: "api/session",
            method: "POST",
            data: {
                email: email,
                password: password
            },
            statusCode: {
                400: function(){
                    $('div.login_error').html("Login failed, please check credentials!");
                }
            }
        }).done(function(result){
            console.log(result);
            window.location.replace("http://google.com");
        });

        e.preventDefault();
    });

});