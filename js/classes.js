var User = (function () {

    function User(email, authToken) {
        this.email = email;
        this.authToken = authToken;
    }

    User.login = function( email, password ) {
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/session/",
                method: "POST",
                data: {
                    email: email,
                    password: password
                }
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject(JSON.parse('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}'));
            });
        });
    };

    User.prototype.logout = function( email, password ) {
        var _this = this;
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/session/" + _this.authToken + "/",
                method: "DELETE"
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
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
                }
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
            });
        });
    };

    User.prototype.changeEmail = function( newEmail, password ) {
        var _this = this;
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/user/" + _this.authToken + "/",
                method: "POST",
                data: {
                    email: newEmail,
                    password: password
                }
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
            });
        });
    };

    User.prototype.changePassword = function( oldPassword, newPassword ) {
        var _this = this;
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/user/" + _this.authToken + "/",
                method: "POST",
                data: {
                    password: oldPassword,
                    newPassword: newPassword
                }
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
            });
        });
    };

    User.prototype.getDevices = function(){
        var _this = this;
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/session/" + _this.authToken + "/",
                method: "GET"
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
            });
        });
    };

    User.deleteDevice = function(auth){
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/session/" + auth + "/",
                method: "DELETE"
            }).done(function(data, textStatus, jqXHR){
                resolve(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
            });
        });
    };

    User.prototype.getBarcode = function(){
        var _this = this;
        return new Promise( function( resolve, reject ) {
            $.ajax({
                url: "api/v1/session/" + _this.authToken + "/",
                method: "PUT"
            }).done(function(data, textStatus, jqXHR){
                var barcode = data.barcode;
                resolve(barcode);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
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
                        method: "DELETE"
                    }).done(function(data, textStatus, jqXHR){
                        _this._blacklisted = false;
                        resolve(true);
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
                    });
                });
            } else if (!this._blacklisted && val) {
                var _this = this;
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: "api/v1/blacklist/" + _this.id + "/" + currentUser.authToken + "/",
                        method: "PUT"
                    }).done(function(data, textStatus, jqXHR){
                        _this._blacklisted = true;
                        resolve(true);
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
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
                method: "GET"
            }).done(function(data, textStatus, jqXHR){
                var result = [];

                for (i = 0; i < data.length; ++i) {
                    result.push(new Ingredient(data[i].id, data[i].name, true, data[i].categories));
                }
                resolve(result);
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
            });
        });
    };

    Ingredient.getAll = function (auth) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: "api/v1/ingredient/",
                method: "GET"
            }).done(function(data, textStatus, jqXHR) {
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
            }).fail(function(jqXHR, textStatus, errorThrown){
                reject('{"status":"' + jqXHR.status + '", "error":"' + errorThrown + '"}');
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