$(function() {
    Data.AccountId = $("#destiny-info-id").attr("data");
    Data.Platform = $("#destiny-info-pl").attr("data");
    Data.CanvasList = [];
    Data.SpasmList = [];
    $('.character-model').each(function() {
        var charId = $(this).attr('data');
        Data.CanvasList[charId] = $('#canvas-' + charId).get()[0];
        $('#button-' + charId).on('click', function(e) {
            fetchAccount(Data.Platform, Data.AccountId, function(complete) {
                Data.SpasmList[charId] = new Spasm.ItemPreview(Data.CanvasList[charId], Url+"/proxy/www.bungie.net");
                e.preventDefault();
                $('#button-' + charId).off('click');
                var text = $('#button-' + charId).text();
                $('#button-' + charId).html("<img src='"+Url+"/img/loading.gif'/>");
                loadCharacter(charId, function() {
                    $('#button-' + charId).hide();
                    $('#button-' + charId).text(text);
                    $('#character-' + charId).fadeOut();
                    $('#canvas-' + charId).fadeIn();
                });
            });
        });
    });
    console.log(Data);
});

function fetchGearAssets(itemHashes, callback) {
    if (Data.GearAssets == undefined)
        Data.GearAssets = [];
    var requestHashes = [];
    itemHashes.forEach(function(itemHash) {
        if (Data.GearAssets[itemHash] == null)
            requestHashes.push(itemHash);
    });
    if (requestHashes.length > 0) {
        var paths = requestHashes.map(function(item) {
            //return "/dinklebot/util/ApiProxy.php?s=destinytracker.com&p=/destiny/api/manifest/22/" + item + "/";
            return Url+"/proxy/www.bungie.net/platform/destiny/manifest/gearAsset/" + item + "/";
        });
        getItems(paths, function(responses) {
            for (var i = 0; i < responses.length; i++) {
                var response = responses[i];
                if (response.Response == null) {
                    Data.GearAssets[requestHashes[i]] = {};
                } else {
                    Data.GearAssets[response.Response.data.requestedId] = response.Response.data.gearAsset;
                }
            }
            callback(true);
        });
    } else
        callback(true);
}

function getItems(paths, callback, responses) {
    if (responses == undefined)
        responses = [];
    var req = new XMLHttpRequest();
    req.open("GET", paths.shift(), true);
    req.onload = function() {
        responses.push(JSON.parse(req.responseText));
        if (paths.length > 0) {
            getItems(paths, callback, responses);
        } else {
            callback(responses);
        }
    };
    req.send();
}

function fetchGearDetails(itemHashes, callback) {
    if (Data.GearDetails == undefined)
        Data.GearDetails = {};
    var requestHashes = [];
    itemHashes.forEach(function(itemHash) {
        if (Data.GearDetails[itemHash] == null)
            requestHashes.push(itemHash);
    });
    if (requestHashes.length > 0) {
        var req = new XMLHttpRequest();
        //req.open("GET", "/dinklebot/util/ApiProxy.php?s=db.destinytracker.com&p=/api/items/" + requestHashes.join(","), true);
        req.open("GET", Url+"/proxy/www.bungie.net/Platform/Destiny/Manifest/InventoryItem/" + requestHashes.join(","), true);
        console.log(req);
        req.onload = function() {
            var response = JSON.parse(req.response);
            response.forEach(function(resp) {
                Data.GearDetails[resp.Response.data.requestedId] = resp.Response.data.inventoryItem;
            });
            callback(true);
        };
        req.send();
    } else
        callback(true);
}

function getShaderDetails(itemHash, callback) {
    var req = new XMLHttpRequest();
    req.open("GET", Url+"/proxy/www.bungie.net/Platform/Destiny/Manifest/InventoryItem/" + itemHash, true);
    console.log(req);
    req.onload = function() {
        var response = JSON.parse(req.response);
        Data.Shader = response.Response.data.inventoryItem;
        callback(true);
    }
    req.send();
}

function getGearDetails(itemHashes) {
    var result = [];
    itemHashes.forEach(function(hash) {
        result.push(Data.GearDetails[hash]);
    });
    return result;
}

function loadCharacter(characterId, callback) {
    var character = getCharacter(characterId);
    //Data.Spasm = Data.SpasmList[characterId];
    //Data.Canvas = Data.CanvasList[characterId];
    var equippedItemHashes = character.characterBase.peerView.equipment.map(function(item) {
        return item.itemHash;
    });
    fetchGearDetails(equippedItemHashes, function(completed) {
        var gearDetails = getGearDetails(equippedItemHashes);
        fetchGearAssets(equippedItemHashes, function(completed) {
            var armorHashes = getArmorDetails(equippedItemHashes).map(function(item) {
                return item.itemHash;
            });
            var armorIds = Array(5);
            for (var i = 0; i < armorHashes.length; i++)
                armorIds[i] = armorHashes[i].toString();
            var shaderHash = getEquippedShaderHash(equippedItemHashes);
            getShaderDetails(shaderHash, function(completed) {
                var shaderDefinition = Data.Shader;
                Data.CanvasList[characterId].setAttribute("data-character", characterId);
                Data.CanvasList[characterId].setAttribute("data-shader", shaderHash);
                updateSpasm(characterId, armorIds, character.characterBase.genderType, shaderDefinition, callback);
            });
        });
    });
}

function setGender(characterId, gender) {
    Data.CanvasList[characterId].setAttribute("data-gender", gender.toString());
    updateSpasm(characterId, Data.SpasmList[characterId].itemReferenceIds, gender, Data.SpasmList[characterId].shaderItemDefinition, null);
}

function setShader(characterId, shaderHash) {
    Data.Canvas.setAttribute("data-shader", shaderHash);
    fetchGearAssets([shaderHash], function(completed) {
        getShaderDetails([shaderHash], function(completed) {
            updateSpasm(Data.Spasm.itemReferenceIds, Data.Spasm.genderType, Data.Shader, null);
        });
    });
}

function updateSpasm(characterId, armorIds, gender, shader, callback) {
    Data.SpasmList[characterId].setGenderType(gender);
    try {
        Data.SpasmList[characterId].setItemReferenceIds(armorIds, null, shader, Data.GearAssets, function(a) {
            if (callback != null)
                callback(a);
        });
    } catch (error) {
    }
    Data.SpasmList[characterId].setFocusedItemReferenceId(null);
    Data.SpasmList[characterId].startAnimating();
    console.log(Data.SpasmList[characterId]);
}

function fetchAccount(platform, id, callback) {
    if (Data.Characters != undefined) {
        callback(true);
    }
    var req = new XMLHttpRequest();
    req.open("GET", Url+"/proxy/www.bungie.net/Platform/Destiny/" + platform + "/Account/" + id, true);
    req.onload = function() {
        try {
            var response = JSON.parse(req.responseText);
            if (response.ErrorCode == 1) {
                Data.AccountType = response.Response.data.membershipType;
                Data.Characters = response.Response.data.characters;
                callback(true);
                return;
            }
        } catch (ex) {
            callback(false);
        }
    };
    req.onerror = function() {
        callback(false);
    };
    console.log(req);
    req.send();
}

function getArmorDetails(itemHashes) {
    return getGearDetails(itemHashes).filter(function(item) {
        return item.bucketTypeHash == "3448274439" || item.bucketTypeHash == "3551918588" || item.bucketTypeHash == "14239492" || item.bucketTypeHash == "20886954" || item.bucketTypeHash == "1585787867";
    });
}

function getEquippedShaderHash(itemHashes) {
    return getGearDetails(itemHashes).filter(function(item) {
        return item.bucketTypeHash == "2973005342";
    })[0].itemHash;
}

function getCharacter(characterHash) {
    return Data.Characters.filter(function(character) {
        return character.characterBase.characterId == characterHash;
    })[0];
}
