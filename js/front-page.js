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
            if ( window.localStorage ) {
                window.localStorage.setItem('authToken', result.authToken);
            }
            loadUserPage();

        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                $('div.login_error').html("Login fehlgeschlagen, bitte Zugangsdaten prüfen!");
            }
        });
    });

    $('form.register').submit(function(e){
        e.preventDefault();

        var $inputs = $('form.register > input');
        var $email = $('form.register > input[name="email"]');
        var $password = $('form.register > input[name="password"]');
        var $password2 = $('form.register > input[name="password2"]');
        var $checkAgb = $('form.register > input[name="agb"]');
        var $regError = $('div.register_error');

        // var emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
        var emailRegex = /.+@.+/;

        if(!emailRegex.test($email.val())) {
            $regError.html("Ungültige Email-Adresse!");
            $inputs.removeClass('error');
            $email.addClass('error');
            return;
        }

        if (!$password.val() || $password.val() === '') {
            $regError.html("Bitte trage dein Passwort ein!");
            $inputs.removeClass('error');
            $password.addClass('error');
            $password2.addClass('error');
            return;
        }

        if ($password.val() !== $password2.val()) {
            $regError.html("Die Passwörter stimmen nicht überein.");
            $inputs.removeClass('error');
            $password.addClass('error');
            $password2.addClass('error');
            return;
        }

        if (!$checkAgb.is(':checked')) {
            $regError.html("Bitte lies und akzeptiere die AGB.");
            $inputs.removeClass('error');
            $checkAgb.addClass('error');
            return;
        }

        User.create( $email.val(), $password.val() ).then( function( result ) {
            currentUser = new User(result.email, result.authToken, []);
            if ( window.localStorage ) {
                window.localStorage.setItem('authToken', result.authToken);
            }

            loadUserPage();
        }).catch( function( err ) {
            if ( err.status >= 400 ) {
                regError.html("Fehler bei der Registrierung!");
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