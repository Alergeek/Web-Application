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
$(document).ready(function() {
    var $register = $('#register'),
        $login = $('#login');

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


        var $email = $('input[name="email"]');
        var $password = $('input[name="password"]');
        var $password2 = $('input[name="password2"]'); 
        var $checkAgb = $('input[name="agb"]');

        if ($email.val() === '') {
            $('div.register_error').html("Bitte trage deine Email-Adresse ein!");
            $email.css('border-color', '#F00');
            return;
        }

        if (!$password.val() || $password.val() === '') {
            $('div.register_error').html("Bitte trage dein Passwort ein!");
            $password.css('border-color', '#F00');
            $password2.css('border-color', '#F00');
            return;
        }

        if ($password.val() !== $password2.val()) {
            $('div.register_error').html("Die Passwörter stimmen nicht überein.");
            $password.css('border-color', '#F00');
            $password2.css('border-color', '#F00');
            return;
        }

        if (!$checkAgb.is(':checked')) {
            $('div.register_error').html("Bitte lies und akzeptiere die AGB.");
            return;
        }

        User.create( $email.val(), $password.val() ).then( function( result ) {
            console.log(result);
            window.location.replace("http://google.com");
        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                $('div.register_error').html("Register failed, please check credentials!");
            }
        });
    });

    /**
     * Display register form and login form
     */
    $('.toggleregister').click(function() {
        $register.toggle();
        $login.toggle();
    });

});