/**
 * Created by morbaes on 08.04.15.
 */

var currentIngredients;
var authToken = '62872019ec4cca2462fe';

function drawBlacklist()
{
    Blacklist.load(authToken).then(function(blacklist) {
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
            newIngredient = $('<li><input type="checkbox" id="' + currentIngredients[i].id + '" checked>' + currentIngredients[i].name + '</input></li>');
        } else {
            newIngredient = $('<li><input type="checkbox" id="' + currentIngredients[i].id + '">' + currentIngredients[i].name + '</input></li>');
        }
        newIngredient.change(function() {
            if($(event.target).is(':checked')) {
                searchInArray(currentIngredients, parseInt(event.target.id)).isBlacklisted = true;
            } else {
                searchInArray(currentIngredients, parseInt(event.target.id)).isBlacklisted = false;
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
        Ingredient.search(authToken, search_string).then(function (result) {
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
                for (i = 0; i < data.length; i++) {
                    var cat_div = $('ul.list_filter');
                    cat_div.empty();
                    cat_div.append('<li><input type="radio">'+data[i].name+'</input></li>');
                }
            }
        }
    });
}