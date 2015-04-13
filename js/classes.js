var User = (function () {

    function User(email, id, authToken, devices) {
        this.email = email;
        this.id = id;
        this.devices = devices;
        this.authToken = authToken;
    }

    User.login = function (email, password) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: "api/v1/session/",
                method: "POST",
                data: {
                    email: email,
                    password: password
                },
                statusCode: {
                    403: function () {
                        reject({status: 403});
                    },
                    200: function () {
                        resolve('geht');
                    }
                }
            });
        });
    };

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


    return User;
})();

var Ingredient = (function () {

    function Ingredient(id, name, blacklisted, categories) {
        this.id = id;
        this.name = name;
        this._blacklisted = blacklisted;
        this.categories = categories;
    }

    Object.defineProperty(Ingredient.prototype, "isBlacklisted", {
        enumerable: true,
        get: function () {
            return this._blacklisted;
        },
        set: function (val) {
            if (this._blacklisted && !val) {
                var _this = this;
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: "api/v1/blacklist/" + _this.id + "/" + currentUser.authToken + "/",
                        method: "DELETE",
                        statusCode: {
                            401: function () {
                                reject({status: 401});
                            },
                            200: function () {
                                _this._blacklisted = false;
                                resolve(true);
                            }
                        }
                    });
                });
            } else if (!this._blacklisted && val) {
                var _this = this;
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: "api/v1/blacklist/" + _this.id + "/" + currentUser.authToken + "/",
                        method: "PUT",
                        statusCode: {
                            401: function () {
                                reject({status: 401});
                            },
                            200: function () {
                                _this._blacklisted = true;
                                resolve(true);
                            }
                        }
                    });
                });
            } else {
                return new Promise(function (resolve, reject) {
                    resolve(true);
                });
            }
        }
    });

    Ingredient.getBlacklist = function (auth) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: "api/v1/blacklist/" + auth + '/',
                method: "GET",
                statusCode: {
                    401: function () {
                        reject({status: 401});
                    },
                    200: function (data) {
                        var result = [];

                        for (i = 0; i < data.length; ++i) {
                            result.push(new Ingredient(data[i].id, data[i].name, true, data[i].categories));
                        }
                        resolve(result);
                    }
                }
            });
        });
    };

    Ingredient.getAll = function (auth) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: "api/v1/ingredient/",
                method: "GET",
                statusCode: {
                    200: function (data) {
                        var result = [];
                        var blacklist = [];
                        Ingredient.getBlacklist(auth).then(function (bl) {
                            for (i = 0; i < bl.length; ++i) {
                                blacklist.push(bl[i].id);
                            }
                            for (i = 0; i < data.length; ++i) {
                                if (blacklist.indexOf(data[i].id) === -1) {
                                    result.push(new Ingredient(data[i].id, data[i].name, false, data[i].categories));
                                } else {
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

    Ingredient.search = function (auth, searchString) {
        return new Promise(function (resolve, reject) {
            Ingredient.getAll(auth).then(function (all) {
                var searchStrings = searchString.split(',');
                var result = [];
                for (i = 0; i < all.length; ++i) {
                    var containsSearch = false;
                    for (k = 0; k < searchStrings.length; k++) {
                        if (all[i].name.toLowerCase().indexOf(searchStrings[k].toLowerCase().trim()) === -1) {
                            for (j = 0; j < all[i].categories.length; ++j) {
                                if (all[i].categories[j].name.toLowerCase().indexOf(searchStrings[k].toLowerCase().trim()) !== -1) {
                                    containsSearch = true;
                                }
                            }
                        } else {
                            containsSearch = true;
                        }
                    }

                    if (containsSearch) {
                        result.push(all[i]);
                    }
                }
                resolve(result);

            });
        });
    };

    return Ingredient;
})();