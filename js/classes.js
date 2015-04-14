

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

	User.prototype.changeEmail = function( auth, newEmail, password ) {
		return new Promise( function( resolve, reject ) {
			$.ajax({
	            url: "api/v1/user/"+auth+"/",
	            method: "POST",
	            data: {
	                email: newEmail,
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

	User.prototype.changePassword = function( auth, oldPassword, newPassword ) {
		return new Promise( function( resolve, reject ) {
			$.ajax({
	            url: "api/v1/user/"+auth+"/",
	            method: "POST",
	            data: {
	                password: oldPassword,
	                newPassword: newPassword
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
	
	User.prototype.getDevices = function(auth){
		return new Promise( function( resolve, reject ) {
			$.ajax({
				url: "api/v1/session/"+auth+"/",
				method: "GET",
				statusCode: {
					403: function(){
						reject({status: 403 });
					},
					200: function(data){
						resolve(data);
					}
				}
			});
		});
	};
	
	User.prototype.deleteDevice = function(auth){
		return new Promise( function( resolve, reject ) {
			$.ajax({
				url: "api/v1/session/"+auth+"/",
				method: "DELETE",
				statusCode: {
					403: function(){
						reject({status: 403 });
					},
					200: function(data){
						resolve(data);
					}
				}
			});
		});
	};
	
	User.prototype.getBarcode = function(auth){
		return new Promise( function( resolve, reject ) {
			$.ajax({
				url: "api/v1/session/"+auth+"/",
				method: "PUT",
				statusCode: {
					403: function(){
						reject({status:403});
					},
					200: function(data){
						var barcode = data.barcode;
						resolve(barcode);
					}
				}
			});
		});
	};

	return User;
})();