$(function() {
    Data.CharacterSwitcher = $("#character-switcher");
    Data.Canvas = document.getElementById("guardian");
    Data.Spasm = new Spasm.ItemPreview(Data.Canvas, "/api/assets/?p=");
    Data.Name = Data.Canvas.getAttribute("data-name");
    Data.Platform = parseInt(Data.Canvas.getAttribute("data-platform"));
    var characterId = Data.Canvas.getAttribute("data-character");
    var shaderHash = Data.Canvas.getAttribute("data-shader");
    var gender = parseInt(Data.Canvas.getAttribute("data-gender"));
    fetchAccount(Data.Platform, Data.Name, function(completed) {
        console.log(Data.Characters);
        if (completed) {
            document.title = "Shader Previewer - " + Data.Name + " | Destiny Database";
            if (!(Data.Embed == true))
                document.getElementById("page-header").textContent = "Shader Previewer - " + Data.Name;
            for (var i = 0; i < Data.Characters.length; i++) {
                var character = Data.Characters[i];
                var ele = $('<div></div>');
                ele.addClass("account-character l" + (i + 1));
                ele.attr("data-character", character.characterBase.characterId);
                ele.attr("style", "background-image: url(https://www.bungie.net" + character.backgroundPath + ");");
                ele.html('<img src="https://www.bungie.net' + character.emblemPath + '">' + '<div class="account-info">' + '<h3>' + Data.Classes[character.characterBase.classHash].className + '</h3>' + '<h5>' + Data.Races[character.characterBase.raceHash].raceName + ' ' + Data.Genders[character.characterBase.genderHash].genderName + '</h5>' + '</div>' + '<div class="account-extras">' + '<h3>' + character.characterLevel + '</h3>' + '</div>');
                ele.on('click', onCharacterClick);
                Data.CharacterSwitcher.append(ele);
            }
            if (characterId == "")
                characterId = Data.Characters[0].characterBase.characterId;
            loadCharacter(characterId, function(completed) {
                document.getElementById("guardian-loader").style.display = "none";
                if (Data.Embed == true) {
                    window.parent.postMessage("loaded", "*");
                }
                if (shaderHash != "")
                    setShader(shaderHash);
                if (gender > 0)
                    setGender(gender);
            });
        } else {
            if (Data.Embed == true) {
                window.parent.postMessage("no-profile", "*");
                document.getElementById("guardian-loader").innerHTML = "No Model Available";
            } else {
                alert("Unable to load your profile");
                location.href = "/character";
            }
        }
    });
    $(".shader-item").on('click', onShaderClick);
});

function onCharacterClick(ev) {
    var args = [];
    for (var _i = 0; _i < (arguments.length - 1); _i++) {
        args[_i] = arguments[_i + 1];
    }
    loaderEnabled(true);
    var characterId = ev.currentTarget.getAttribute("data-character");
    loadCharacter(characterId, function(completed) {
        loaderEnabled(false);
    });
}

function setCharacterSwitcher(characterId) {
    var d = 2;
    Data.CharacterSwitcher.children().each(function(index, elem) {
        if (elem.getAttribute("data-character") == characterId)
            elem.setAttribute("class", "account-character l1");
        else {
            elem.setAttribute("class", "account-character l" + d);
            d++;
        }
    });
}

function onShaderClick(ev) {
    var args = [];
    for (var _i = 0; _i < (arguments.length - 1); _i++) {
        args[_i] = arguments[_i + 1];
    }
    ev.preventDefault();
    var element = $(ev.currentTarget);
    setShader(element.attr('data-shader'));
}

function loadCharacter(characterId, callback) {
    var character = getCharacter(characterId);
    var equippedItemHashes = character.characterBase.peerView.equipment.map(function(item) {
        return item.itemHash;
    });
    setCharacterSwitcher(characterId);
    fetchGearDetails(equippedItemHashes, function(completed) {
        var gearDetails = getGearDetails(equippedItemHashes);
        fetchGearAssets(equippedItemHashes, function(completed) {
            var armorHashes = getArmorDetails(equippedItemHashes).map(function(item) {
                return item.Hash;
            });
            var armorIds = Array(5);
            for (var i = 0; i < armorHashes.length; i++)
                armorIds[i] = armorHashes[i].toString();
            var shaderHash = getEquippedShaderHash(equippedItemHashes);
            switchShaderSelection(shaderHash);
            var shaderDefinition = Data.Shaders[shaderHash];
            Data.Canvas.setAttribute("data-character", characterId);
            Data.Canvas.setAttribute("data-shader", "");
            updateSpasm(armorIds, character.characterBase.genderType, shaderDefinition, callback);
        });
    });
}

function setGender(gender) {
    loaderEnabled(true);
    Data.Canvas.setAttribute("data-gender", gender.toString());
    updateSpasm(Data.Spasm.itemReferenceIds, gender, Data.Spasm.shaderItemDefinition, function(completed) {
        loaderEnabled(false);
    });
}

function setShader(shaderHash) {
    loaderEnabled(true);
    Data.Canvas.setAttribute("data-shader", shaderHash);
    fetchGearAssets([shaderHash], function(completed) {
        switchShaderSelection(shaderHash);
        updateSpasm(Data.Spasm.itemReferenceIds, Data.Spasm.genderType, Data.Shaders[shaderHash], function(completed) {
            loaderEnabled(false);
        });
    });
}

function loaderEnabled(enabled) {
    if (enabled)
        document.getElementById("guardian-loader").style.display = "block";
    else
        document.getElementById("guardian-loader").style.display = "none";
}

function switchShaderSelection(shaderHash) {
    $(".shader-item").removeClass("active");
    $(".shader-item[data-shader='" + shaderHash + "']").addClass("active");
}

function updateSpasm(armorIds, gender, shader, callback) {
    Data.Spasm.setGenderType(gender);
    try {
        Data.Spasm.setItemReferenceIds(armorIds, null, shader, Data.GearAssets, function(a) {
            loaderEnabled(false);
            if (callback != null)
                callback(a);
        });
    } catch (error) {
        if (Data.Embed == true) {
            window.parent.postMessage("render-failure", "*");
            document.getElementById("guardian-loader").innerHTML = "Render Failure";
            document.getElementById("guardian-loader").style.display = "block";
        } else
            alert('Error Rendering - Try another.');
    }
    Data.Spasm.setFocusedItemReferenceId(null);
    Data.Spasm.startAnimating();
    if (!(Data.Embed == true))
        updateUrl();
}

function updateUrl() {
    var platform = (Data.Platform == 1 ? "xbox" : "psn");
    var name = Data.Name.toLowerCase();
    if (platform == "xbox")
        name = name.replace(" ", "-");
    var character = Data.Canvas.getAttribute("data-character");
    var shader = Data.Canvas.getAttribute("data-shader");
    var searchQuery = "";
    if ($("#filter-query").val() != "")
        searchQuery = "?q=" + $("#filter-query").val().replace(" ", "+");
    history.replaceState({}, "", "/character/" + platform + "/" + name + "/shaders/" + character + "/" + shader + searchQuery);
}

function fetchAccount(platform, name, callback) {
    var req = new XMLHttpRequest();
    req.open("GET", "http://destinytracker.com/destiny/api/characters/" + platform + "/" + name, true);
    req.onload = function() {
        try {
            var response = JSON.parse(req.responseText);
            if (response.ErrorCode == 1) {
                Data.Name = response.displayName;
                Data.AccountId = response.Response.data.membershipId;
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
    req.send();
}

function getArmorDetails(itemHashes) {
    return getGearDetails(itemHashes).filter(function(item) {
        return item.BucketName == "Helmet" || item.BucketName == "Gauntlets" || item.BucketName == "Chest Armor" || item.BucketName == "Leg Armor" || item.BucketName == "Class Armor";
    });
}

function getEquippedShaderHash(itemHashes) {
    return getGearDetails(itemHashes).filter(function(item) {
        return item.BucketName == "Shaders";
    })[0].Hash;
}

function getCharacter(characterHash) {
    return Data.Characters.filter(function(character) {
        return character.characterBase.characterId == characterHash;
    })[0];
}