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
        e.preventDefault();

        /**
         * \brief Login on Form submit.
         * Calls POST api/session with email and cleartext password. 404 => login failed, 200 => login successfull.
         */

        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();

        User.login( email, password ).then( function( result ) {
            console.log(result);
            window.location.replace("http://google.com");
        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                $('div.login_error').html("Login failed, please check credentials!");
            }
        });

        //var shaObj = new jsSHA(password, "TEXT");
        //var pw_hash = shaObj.getHash("SHA-1", "HEX");


    });

    $('form.register').submit(function(e){
        e.preventDefault();

        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();

        User.create( email, password ).then( function( result ) {
            console.log(result);
            window.location.replace("http://google.com");
        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                $('div.register_error').html("Register failed, please check credentials!");
            }
        });
    });
});