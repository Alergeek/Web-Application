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

        var email = $('form.register > input[name="email"]');
        var password = $('form.register > input[name="password"]');
        var password2 = $('form.register > input[name="password2"]');
        var checkAgb = $('form.register > input[name="agb"]');

        var emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

        if(!emailRegex.test(email.val())) {
            $('div.register_error').html("Ungültige Email-Adresse!");
            return;
        }

        if (!password.val() || password.val() === '') {
            $('div.register_error').html("Bitte trage dein Passwort ein!");
            password.css('border-color', '#F00');
            password2.css('border-color', '#F00');
            return;
        }

        if (password.val() !== password2.val()) {
            $('div.register_error').html("Die Passwörter stimmen nicht überein.");
            password.css('border-color', '#F00');
            password2.css('border-color', '#F00');
            return;
        }

        if (!checkAgb.is(':checked')) {
            $('div.register_error').html("Bitte lies und akzeptiere die AGB.");
            return;
        }

        User.create( email.val(), password.val() ).then( function( result ) {
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