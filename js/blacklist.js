/**
 * Created by morbaes on 08.04.15.
 */

var currentIngredients;
var authToken = '62872019ec4cca2462fe';
var currentUser = new User(2, 'test@example.com', authToken, []);

function drawBlacklist()
{
    Ingredient.getBlacklist(currentUser.authToken).then(function(blacklist) {
        drawList(blacklist);

    });
}

function searchInArray(array, id) {
    for (i = 0; i < array.length; i++) {
        if(array[i].id === id) {
            return array[i];
        }
    }
}

function drawList(ingredients) {
    var bl_div = $('div.blacklist div.search div.div_result ul.list_result');
    currentIngredients = ingredients.slice();
    bl_div.empty();
    for (i = 0; i < currentIngredients.length; ++i) {
        var newIngredient;
        if(currentIngredients[i].isBlacklisted) {
            newIngredient = $('<li><input type="checkbox" id="ing_' + currentIngredients[i].id + '" checked /><label for="ing_' + currentIngredients[i].id + '">' + currentIngredients[i].name + '</label></li>');
        } else {
            newIngredient = $('<li><input type="checkbox" id="ing_' + currentIngredients[i].id + '" /><label for="ing_' + currentIngredients[i].id + '">' + currentIngredients[i].name + '</label></li>');
        }
        newIngredient.change(function(event) {
            if($(event.target).is(':checked')) {
                searchInArray(currentIngredients, parseInt(event.target.id.replace('ing_', ''))).isBlacklisted = true;
            } else {
                searchInArray(currentIngredients, parseInt(event.target.id.replace('ing_', ''))).isBlacklisted = false;
            }
        });
        bl_div.append(newIngredient);
    }
}

function search()
{
    var search_string = $('input.search').val();
    if(search_string === "")
    {
        drawBlacklist();
    } else {
        Ingredient.search(currentUser.authToken, search_string).then(function (result) {
            drawList(result);
        });
    }
}

function loadCategories()
{
    $.ajax({
        url: "api/v1/category/",
        method: "GET",
        statusCode: {
            200: function(data) {
                var cat_div = $('ul.list_filter');
                var searchInput = $('input.search');
                cat_div.empty();
                for (i = 0; i < data.length; i++) {
                    var new_cat = $('<li><input type="checkbox" id="cat_'+ data[i].id +'"/><label for="cat_'+ data[i].id +'">'+data[i].name+'</label></li>');
                    new_cat.change(function(event) {
                        var catName = $('label[for="'+ event.target.id +'"]').text();
                        $('input[type="checkbox"][id="cat_showall"]').attr('checked', false);
                        if($(event.target).is(':checked')) {
                            if(searchInput.val() === "")
                            {
                                searchInput.val(catName);
                            } else {
                                searchInput.val(searchInput.val() + '|' + catName);
                            }
                            searchInput.keyup();
                        } else {
                            var newSearch = searchInput.val().replace(catName, '').replace('||', '|').replace(/^\|/, '').replace(/\|$/, '');
                            searchInput.val(newSearch);
                            searchInput.keyup();
                        }
                    });
                    cat_div.append(new_cat);
                }
                var showAll = $('<li><input type="checkbox" id="cat_showall"/><label for="cat_showall">Alle anzeigen</label></li>');
                showAll.change(function(event) {
                    var categories = $('input[id^="cat_"]').not(event.target);
                    $('input.search').val("");
                    if($(event.target).is(':checked')) {
                        categories.each(function() {
                            $(this).prop('checked', true);
                            $(this).trigger('change');
                        });
                        $(event.target).prop('checked', true);
                    } else {
                        categories.each(function() {
                            $(this).prop('checked', false);
                            $(this).trigger('change');
                        });
                        $(event.target).prop('checked', false);
                    }
                });
                cat_div.prepend(showAll);
            }
        }
    });
}