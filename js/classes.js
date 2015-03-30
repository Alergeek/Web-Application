var User = (function() {
	function User( email, id, blacklist, devices ) {

	}

	User.login = function( email, password ) {
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
	};

	User.create = function( email, password ) {
		
	};

	User.prototype.changeEmail = function( newEmail, password ) {
		// body...
	};

	User.prototype.changePassword = function( oldPassword, newPassword ) {
		// body...
	};

	return User;
})();