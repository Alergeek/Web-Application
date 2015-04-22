var loadUserPageJS = function () {
    drawBlacklist();
    loadCategories();

    var intervallNewDevice;

    $("#button_blacklist").css("background-color", "#333");

    drawLeftHeader();

    $('#button_blacklist').click(function (e) {

        e.preventDefault();

        $(e.target).css("background-color", "#333");
        $("#button_profile").css("background-color", "#AAA");

        $('.div_new_devices').hide();
        $('.content').children().hide();
        $('.blacklist').show();
        $('#button_blacklist').attr('disabled', 'disabled');
        $('#button_profile').attr('disabled', null);
        $('#button_impressum').attr('disabled', null);
        $('#button_privacy').attr('disabled', null)
    });

    $('#button_profile').click(function (e) {

        e.preventDefault();

        $(e.target).css("background-color", "#333");
        $("#button_blacklist").css("background-color", "#AAA");

        $('#profile_alerts').hide();
        $('#div_device_table').empty();

        var now = new Date();

        var device;
        currentUser.getDevices()
            .then(function (devices) {
                $('#div_device_table').append('<table id="table_devices"></table>');
                for (device = 0; device < devices.length; device++) {
                    if ((devices[device].until * 1000) > now.getTime()) {
                        if (devices[device].authToken != currentUser.authToken) {
                            $('#table_devices').append('<tr class="device"><th>' + devices[device].name + '</th><td><button id="' + devices[device].authToken + '" class="button_delete">X</button></td><tr>');
                            $('#' + devices[device].authToken).click(function (e) {
                                e.preventDefault();

                                if(!confirm("Möchten Sie dieses Gerät wirklich entfernen?")) {
                                    return;
                                }

                                $(this).parent().parent().remove();
                                User.deleteDevice(this.id);
                                displayAlert('Das Gerät wurde entfernt!', 'success');
                            });
                        } else {
                            $('#table_devices').append('<tr class="device_current"><th>' + devices[device].name + '</th><td>Dieses Gerät</td><tr>');
                        }
                    }
                }

                $('.content').children().hide();
                $('.profile').show();
            })
            .catch(function (err) {
                console.log(err);
                if (err.status >= 400) {
                    displayAlert('Fehler beim laden der Geräte!', 'error');
                }
            });

        $('#button_profile').attr('disabled', 'disabled');
        $('#button_blacklist').attr('disabled', null);
        $('#button_impressum').attr('disabled', null);
        $('#button_privacy').attr('disabled', null);
    });

    $('#button_logout').click(function (e) {
        currentUser.logout().then(function (result) {
            if ( window.localStorage ) {
                window.localStorage.clear();
            }
            loadFrontPage();
        });
    });

    $('#button_privacy').click(function (e) {

        e.preventDefault();

        $('.content').children().hide();
        $('.privacy').show();
        $('#button_privacy').attr('disabled', 'disabled');
        $('#button_impressum').attr('disabled', null);
        $('#button_blacklist').attr('disabled', null);
        $('#button_profile').attr('disabled', null);
    });

    $('#button_impressum').click(function (e) {

        e.preventDefault();

        $('.content').children().hide();
        $('.impressum').show();
        $('#button_impressum').attr('disabled', 'disabled');
        $('#button_privacy').attr('disabled', null);
        $('#button_blacklist').attr('disabled', null);
        $('#button_profile').attr('disabled', null);
    });

    $('#button_filter').click(function (e) {

        e.preventDefault();

        $('.div_filter').toggle();
    });

    $('#button_new_device').click(function (e) {

        e.preventDefault();
        var oldNumberOfDevices;

        currentUser.getDevices().then(function (devices) {
            oldNumberOfDevices = devices.length;
        }).catch(function (err) {
            console.log(err);
            if (err.status >= 400) {
                displayAlert('Fehler beim laden der Verbindung!', 'error');
                return;
            }
        });

        var bcType = "ean13";
        //"4060800123459";//"4002627400177";

        if ($('.div_new_device').css('display') == 'none') {
            clearInterval(intervallNewDevice);
            window.alert('Bitte starten Sie jetzt die Edible-App auf Ihrer Vuzix!');

            currentUser.getBarcode(currentUser.authToken)
                .then(function (barcode) {
                    barcode = barcode.toString();

                    intervallNewDevice = setInterval(function () {
                        currentUser.getDevices().then(function (devices) {
                            console.log(devices.length + ';' + oldNumberOfDevices);
                            if (devices.length > oldNumberOfDevices) {
                                clearInterval(intervallNewDevice);
                                $('.div_new_device').hide();
                                $('#div_barode').empty();
                                displayAlert('Das Gerät wurde erfolgreich gekoppelt!', 'success');

                                $('#div_device_table').empty();
                                var now = new Date();
                                var device;
                                currentUser.getDevices()
                                    .then(function (devices) {
                                        $('#div_device_table').append('<table id="table_devices"></table>');
                                        for (device = 0; device < devices.length; device++) {
                                            if ((devices[device].until * 1000) > now.getTime()) {
                                                if (devices[device].authToken != currentUser.authToken) {
                                                    $('#table_devices').append('<tr class="device"><th>' + devices[device].name + '</th><td><button id="' + devices[device].authToken + '" class="button_delete">X</button></td><tr>');
                                                    $('#' + devices[device].authToken).click(function (e) {
                                                        e.preventDefault();
                                                        $(this).parent().parent().remove();
                                                        User.deleteDevice(this.id);
                                                        display.Alert('Das Gerät wurde entfernt!', 'success');
                                                    });
                                                } else {
                                                    $('#table_devices').append('<tr class="device_current"><th>' + devices[device].name + '</th><td>Dieses Gerät</td><tr>');
                                                }
                                            }
                                        }
                                    })
                                    .catch(function (err) {
                                        console.log(err);
                                        if (err.status >= 400) {
                                            displayAlert('Fehler beim laden der Geräte!', 'error');
                                        }
                                    });
                            }
                        }).catch(function (err) {
                            console.log(err);
                            if (err.status >= 400) {
                                displayAlert('Fehler beim laden der Verbindung!', 'error');
                            }
                        });
                    }, 3000);

                    $('#div_barcode').barcode(barcode, bcType, {barWidth: 2, barHeight: 100});
                    $('.div_new_device').show();
                }).catch(function (err) {
                    console.log(err);
                    if (err.status >= 400) {
                        displayAlert('Fehler beim laden der Verbindung!', 'error');
                    }
                });
        } else {
            displayAlert('Sie sind bereits dabei, ein neues Gerät zu koppeln', 'warning');
        }

    });

    $('#button_finished').click(function (e) {

        e.preventDefault();

        $('.div_new_device').hide();
        $('#div_barode').empty();
    });

    $('#button_cancel').click(function (e) {

        e.preventDefault();

        clearInterval(intervallNewDevice);
        $('.div_new_device').hide();
        $('#div_barode').empty();
    });

    $('#save_profile').click(function (e) {
        function handleErr( type ) {
            return function( err ) {
                var errmsg;
                switch(err.status) {
                    case 401:
                        errmsg = 'Fehler beim ändern der ' + type + '!';
                        break;
                    default:
                        errmsg = 'Das aktuelle Passwort ist nicht korrekt.';
                }
                displayAlert(errmsg, 'error');
            };
        }

        function handleSuccess( result ) {
            displayAlert('Daten wurden erfolgreich geändert!', 'success');
            drawLeftHeader();
        }

        e.preventDefault();

        var email = $('#email').val();
        var new_pw = $('#new').val();
        var confirm = $('#confirm').val();
        var old_pw = $('#old').val();

        if (email == "" && new_pw == "" && confirm == "") {
            displayAlert('Sie müssen erst Daten eingeben, um diese abspeichern zu können!', 'warning');
            return;
        } else if (old_pw == '') {
            displayAlert('Bitte geben Sie ihr altes Passwort an!', 'warning');
            return;
        } else if (new_pw != confirm) {
            displayAlert('Das neue Passwort und dessen Bestätigung müssen übereinstimmen!', 'warning');
        } else {
            if(email != "" && new_pw != "") {
                currentUser.changeAll( new_pw, email, old_pw)
                    .then(handleSuccess).catch(handleErr('Email und Passwort'));
            } else if (email != "") {
                currentUser.changeEmail(email, old_pw)
                    .then(handleSuccess).catch(handleErr('Email'));
            } else if (new_pw != "") {
                currentUser.changePassword(old_pw, new_pw)
                    .then(handleSuccess).catch(handleErr('Passwort'));
            }
        }
        $('#table_profile_conf input').val("");

    });
};

function displayAlert(text, style) {

    $("html, body").animate({scrollTop: 0}, "slow");

    if ($('#profile_alerts').children().length != 0) {
        $('#profile_alerts').fadeOut(200, function () {
            $(this).empty()
                .append('<p>' + text + '</p>')
                .fadeIn();
        });
    } else {
        $('#profile_alerts').empty()
            .append('<p>' + text + '</p>')
            .fadeIn();
    }
    $('#profile_alerts').removeClass().addClass('alert_' + style);
    $('#profile_alerts').click(function (e) {
        e.preventDefault();
        $(this).hide();
    });
}

function drawLeftHeader () {
    $('span.edible').text("Edible | " + currentUser.email);
}