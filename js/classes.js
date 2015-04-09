// this goes out to all the IE users out there...
if (!Promise) {
	function Promise( defferfn ) {
		this._thenfn = function() {};
		this._catchfn = function() {};
		defferfn( this._thenfn, this._catchfn );
	}

	Promise.prototype.then = function( thenfn ) {
		this._thenfn = thenfn;
		return this;
	};

	Promise.prototype.catch = function( catchfn ) {
		this._catchfn = catchfn;
		return this;
	};
};

var User = (function() {

	function User( email, id, blacklist, devices ) {
		this.email = email;
		this.id = id;
		this.blacklist = blacklist;
		this.devices = devices;
	}

	User.login = function( email, password ) {
		return new Promise( function( resolve, reject ) {
			$.ajax({
	            url: "api/v1/session/",
	            method: "POST",
	            data: {
	                email: email,
	                password: password
	            },
	            statusCode: {
	                403: function() {
	                	reject({status: 403 });
	                },
	                200: function() {
	                	resolve('geht');
	                }
	            }
	        });
	    });
	};

	User.create = function( email, password ) {
		return new Promise( function( resolve, reject ) {
			$.ajax({
	            url: "api/v1/user/",
	            method: "PUT",
	            data: {
	                email: email,
	                password: password
	            },
	            statusCode: {
	                403: function() {
	                	reject({status: 403 });
	                },
	                200: function(data) {
	                	resolve(data);
	                }
	            }
	        });
	    });
	};

	User.prototype.changeEmail = function( newEmail, password ) {
		return new Promise( function( resolve, reject ) {
			$.ajax({
	            url: "api/v1/user/",
	            method: "POST",
	            data: {
	                email: newEmail,
	                password: password
	            },
	            statusCode: {
	                403: function() {
	                	reject({status: 403 });
	                },
	                200: function() {
	                	resolve('geht');
	                }
	            }
	        });
	    });
	};

	User.prototype.changePassword = function( oldPassword, newPassword ) {
		return new Promise( function( resolve, reject ) {
			$.ajax({
	            url: "api/v1/user/",
	            method: "POST",
	            data: {
	                password: oldPassword,
	                newPassword: newPassword
	            },
	            statusCode: {
	                403: function() {
	                	reject({status: 403 });
	                },
	                200: function() {
	                	resolve('geht');
	                }
	            }
	        });
	    });
	};

	return User;
})();