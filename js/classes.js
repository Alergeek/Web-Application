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
		throw "unimplemented method";
	};

	User.prototype.changeEmail = function( newEmail, password ) {
		throw "unimplemented method";
	};

	User.prototype.changePassword = function( oldPassword, newPassword ) {
		throw "unimplemented method";
	};

	return User;
})();


var Blacklist = (function() {

    function Blacklist( ingredients ) {
        this.items = ingredients;
    }

    Blacklist.load = function(auth) {
        return new Promise( function(resolve, reject) {
            $.ajax({
                url: "api/v1/blacklist/" + auth + '/',
                method: "GET",
                statusCode: {
                    401: function () {
                        reject({status: 401});
                    },
                    200: function (data) {
                        var result = []; //new Blacklist(data);

                        for (i = 0; i < data.length; ++i) {
                            result.push(new Ingredient(data[i].id, data[i].name, true, data[i].categories));
                        }
                        resolve(result);
                    }
                }
            });
        });
    };

/*    Blacklist.prototype.add = function(id, auth) {
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/blacklist/" + id + "/" + auth + '/',
                method: "PUT",
                statusCode: {
                    403: function() {
                        reject({status: 403 });
                    },
                    200: function() {
                        resolve(true);
                    }
                }
            });
        });
    };

    Blacklist.prototype.remove = function(id, auth) {
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/blacklist/" + id + "/" + auth + '/',
                method: "DELETE",
                statusCode: {
                    403: function() {
                        reject({status: 403 });
                    },
                    200: function() {
                        resolve(true);
                    }
                }
            });
        });
    };*/

    return Blacklist;
})();


var Ingredient = (function() {

    function Ingredient( id, name, blacklisted, categories ) {
        this.id = id;
        this.name = name;
        this._blacklisted = blacklisted;
        this.categories = categories;
    }

    Object.defineProperty(Ingredient.prototype, "isBlacklisted", {
        enumerable: true,
        get: function() {
            return this._blacklisted;
        },
        set: function(val){
            if(this._blacklisted && !val) {
                var _this = this;
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "api/v1/blacklist/" + _this.id + "/62872019ec4cca2462fe/",
                        method: "DELETE",
                        statusCode: {
                            401: function() {
                                reject({status: 401 });
                            },
                            200: function() {
                                _this._blacklisted = false;
                                resolve(true);
                            }
                        }
                    });
                });
            }
            else if(!this._blacklisted && val) {
                var _this = this;
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "api/v1/blacklist/" + _this.id + "/62872019ec4cca2462fe/",
                        method: "PUT",
                        statusCode: {
                            401: function() {
                                reject({status: 401 });
                            },
                            200: function() {
                                _this._blacklisted = true;
                                resolve(true);
                            }
                        }
                    });
                });
            } else {
                return new Promise(function(resolve, reject) {
                    resolve(true);
                });
            }
        }
    });

    Ingredient.getAll = function(auth) {
        return new Promise( function(resolve, reject) {
            $.ajax({
                url: "api/v1/ingredient/",
                method: "GET",
                statusCode: {
                    401: function () {
                        reject({status: 401});
                    },
                    200: function (data) {
                        var result = [];
                        var blacklist = [];
                        Blacklist.load(auth).then(function(bl) {
                            for (i = 0; i < bl.length; ++i) {
                                blacklist.push(bl[i].id);
                            }
                            for (i = 0; i < data.length; ++i) {
                                if(blacklist.indexOf(data[i].id) === -1) {
                                    result.push(new Ingredient(data[i].id, data[i].name, false, data[i].categories));
                                }
                                else
                                {
                                    result.push(new Ingredient(data[i].id, data[i].name, true, data[i].categories));
                                }
                            }
                            resolve(result);
                        });
                    }
                }
            });
        });
    };

    Ingredient.search = function(auth, search_string) {
        return new Promise(function(resolve, reject) {
            Ingredient.getAll(auth).then(function(all) {
                var result = [];
                for (i = 0; i < all.length; ++i) {
                    var contains_search = false;
                    if(all[i].name.toLowerCase().indexOf(search_string.toLowerCase()) === -1) {
                        for (j = 0; j < all[i].categories.length; ++j) {
                            if(all[i].categories[j].name.toLowerCase().indexOf(search_string.toLowerCase()) !== -1) {
                                contains_search = true;
                            }
                        }
                    } else {
                        contains_search = true;
                    }

                    if(contains_search) {
                        result.push(all[i]);
                    }
                }
                resolve(result);

            });
        });
    };

    return Ingredient;
})();