/**
 * Created by morbaes on 08.04.15.
 */

var currentIngredients;

function drawBlacklist() {
    Ingredient.getBlacklist(currentUser.authToken).then(function (blacklist) {
        drawList(blacklist);

    })
        .catch(function (err) {
            console.log(err);
            displayAlert('Fehler beim Laden der Blacklist!', 'error');
        });
}

function searchInArray(array, id) {
    for (i = 0; i < array.length; i++) {
        if (array[i].id === id) {
            return array[i];
        }
    }
}

function drawList(ingredients) {
    var blDiv = $('div.blacklist div.search div.div_result ul.list_result');
    var oldAmount;
    if(currentIngredients) {
        oldAmount = currentIngredients.length;
    } else {
        oldAmount = 0;
    }
    currentIngredients = ingredients.slice();
    blDiv.empty();
    for (i = 0; i < currentIngredients.length; ++i) {
        var newIngredient;
        if (currentIngredients[i].isBlacklisted) {
            newIngredient = $('<li><input type="checkbox" id="ing_' + currentIngredients[i].id + '" checked /><label for="ing_' + currentIngredients[i].id + '">' + currentIngredients[i].name + '</label></li>');
        } else {
            newIngredient = $('<li><input type="checkbox" id="ing_' + currentIngredients[i].id + '" /><label for="ing_' + currentIngredients[i].id + '">' + currentIngredients[i].name + '</label></li>');
        }
        newIngredient.change(function (event) {
            if ($(event.target).is(':checked')) {
                searchInArray(currentIngredients, parseInt(event.target.id.replace('ing_', ''))).isBlacklisted = true;
            } else {
                searchInArray(currentIngredients, parseInt(event.target.id.replace('ing_', ''))).isBlacklisted = false;
            }
        });
        if(i >= oldAmount) {
            newIngredient.hide();
        }
        blDiv.append(newIngredient);
        if(i >= oldAmount) {
            newIngredient.show('fast');
        }
    }
}

function search() {
    var searchString = $('input.search').val();
    if (searchString === "") {
        drawBlacklist();
    } else {
        Ingredient.search(currentUser.authToken, searchString).then(function (result) {
            drawList(result);
        })
            .catch(function (err) {
                console.log(err);
                displayAlert('Fehler beim Laden der Zutaten!', 'error');
            });
    }
}

function loadCategories() {
    $.ajax({
        url: "api/v1/category/",
        method: "GET",
        statusCode: {
            200: function (data) {
                var catDiv = $('ul.list_filter');
                var searchInput = $('input.search');
                catDiv.empty();
                for (i = 0; i < data.length; i++) {
                    var newCat = $('<li><input type="checkbox" id="cat_' + data[i].id + '"/><label for="cat_' + data[i].id + '">' + data[i].name + '</label></li>');
                    newCat.change(function (event) {
                        var catName = $('label[for="' + event.target.id + '"]').text();
                        $('input[type="checkbox"][id="cat_showall"]').attr('checked', false);
                        if ($(event.target).is(':checked')) {
                            if (searchInput.val() === "") {
                                searchInput.val(catName);
                            } else {
                                searchInput.val(searchInput.val() + ',' + catName);
                            }
                            searchInput.keyup();
                        } else {
                            var newSearch = searchInput.val().replace(catName, '').replace(',,', ',').replace(/^,/, '').replace(/,$/, '');
                            searchInput.val(newSearch);
                            searchInput.keyup();
                        }
                    });
                    catDiv.append(newCat);
                }
                var showAll = $('<li><input type="checkbox" id="cat_showall"/><label for="cat_showall">Alle anzeigen</label></li>');
                showAll.change(function (event) {
                    var categories = $('input[id^="cat_"]').not(event.target);
                    $('input.search').val("");
                    if ($(event.target).is(':checked')) {
                        categories.each(function () {
                            $(this).prop('checked', true);
                            $(this).trigger('change');
                        });
                        $(event.target).prop('checked', true);
                    } else {
                        categories.each(function () {
                            $(this).prop('checked', false);
                            $(this).trigger('change');
                        });
                        $(event.target).prop('checked', false);
                    }
                });
                catDiv.prepend(showAll);
            },
            error: function(err) {
                console.log(err);
                displayAlert('Fehler beim Laden der Kategorien!', 'error');
            }
        }
    });
}