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
var loadFrontPageJS = function() {
    var $register = $('#register'),
        $login = $('#login');

    $('form.login').submit(function(e){
        e.preventDefault();

        /**
         * \brief Login on Form submit.
         * Calls POST api/session with email and cleartext password. 401 => login failed, 200 => login successfull.
         */

        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();

        User.login( email, password ).then( function( result ) {

            currentUser = new User(result.email, result.authToken, []);

            loadUserPage();

        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                $('div.login_error').html("Login fehlgeschlagen, bitte Zugangsdaten prüfen!");
            }
        });
    });

    $('form.register').submit(function(e){
        e.preventDefault();

        var email = $('form.register > input[name="email"]').val();
        var password = $('form.register > input[name="password"]').val();
        var password2 = $('form.register > input[name="password2"]').val();

        var emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

        if(!emailRegex.test(email)) {
            $('div.register_error').html("Ungültige Email-Adresse!");
            return;
        }

        if(password !== password2) {
            $('div.register_error').html("Passwörter stimmen nicht überein!");
            return;
        }

        User.create( email, password ).then( function( result ) {
            currentUser = new User(result.email, result.authToken, []);
            loadUserPage();
        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                $('div.register_error').html("Fehler bei der Registrierung!");
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

};