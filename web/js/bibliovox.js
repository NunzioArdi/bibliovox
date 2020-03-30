var PATH = document.getElementById("path").value;

if (document.getElementById("searchButtn") !== null)
    document.getElementById("searchButtn").onclick = function () {
        var words = document.getElementById("searchBar").value;
        makeRequest(PATH + '/searchAudio', "data=" + encodeURIComponent(words), printResultSearchAudio);
    };

if (document.getElementById("dicoButtn") !== null)
    document.getElementById("dicoButtn").onclick = function () {
        var dico = getSelectionsListe("selectedDico");
        var idM = document.getElementById("idM").value;
        makeRequest(PATH + '/changeDicoMot', "data=" + encodeURIComponent(dico) + "&idM=" + idM, nothing);
    };

if (document.getElementById("buttnChangeWord") !== null)
    document.getElementById("buttnChangeWord").onclick = function () {
        var word = document.getElementById("newWord").value;
        var idM = document.getElementById("idM").value;
        makeRequest(PATH + '/udpateWord', "word=" + word + "&idM=" + idM, nothing);
    };

if (document.getElementById("bttnName") !== null)
    document.getElementById("bttnName").onclick = function () {
        let newName = document.getElementById("dicoName").value;
        let idD = document.getElementById("idD").value;
        makeRequest(PATH + "/updateDicoName", "dicoName=" + newName + "&idD=" + idD, nothing);
    };

if (document.getElementsByClassName("saveRec") !== null) {
    let all = document.getElementsByClassName("saveRec");
    for (let element of all) {
        element.onclick = function () {
            let id = this.value;
            var comm = document.getElementById("comm-" + id).value;
            var shared = 0;
            if (document.getElementById("shared-" + id).checked)
                shared = 1;

            makeRequest(PATH + '/updateAudioRec', "data=" + encodeURIComponent(comm) + "&shared=" + shared + "&id=" + id, nothing);
        }
    }
}

if (document.getElementsByClassName("saveMot") !== null) {
    let all = document.getElementsByClassName("saveMot");
    for (let element of all) {
        element.onclick = function () {
            let id = this.value;
            var comm = document.getElementById("comm-" + id).value;
            var shared = 0;
            if (document.getElementById("shared-" + id).checked)
                shared = 1;

            makeRequest(PATH + '/updateAudioMot', "data=" + encodeURIComponent(comm) + "&shared=" + shared + "&id=" + id, nothing);
        }
    }
}


if (document.getElementsByClassName("saveProd") !== null) {
    let all = document.getElementsByClassName("saveProd");
    for (let element of all) {
        element.onclick = function () {
            let id = this.value;
            var comm = document.getElementById("comm-" + id).value;

            makeRequest(PATH + '/updateAudioProd', "data=" + encodeURIComponent(comm) + "&id=" + id, nothing);
        }
    }
}

if (document.getElementById("connectProd") !== null)
    document.getElementById("connectProd").onclick = function () {
        var nomProd = document.getElementById("nomprod").value;
        var courriel = document.getElementById("mail").value;
        var mdp = document.getElementById("mdp").value;
        var stayConnected = 0;
        if (document.getElementById("stayConnected").checked)
            stayConnected = 1;
        makeRequest(PATH + '/createProdEleve', "nomProd=" + encodeURIComponent(nomProd) + "&courriel=" + encodeURIComponent(courriel) + "&mdp=" + encodeURIComponent(mdp) + "&stayConnected=" + stayConnected, createProd);
    };


function createProd() {

    if (this.readyState === XMLHttpRequest.DONE) {
        let txt = this.response;
        //Vérifications des erreurs
        if (txt === "err-login") {
            alert("Votre mot de passe ou votre identifiant est incorrect");
            return null;
        }
        if (txt === "err-right") {
            alert("Vous n\'avez pas le droit de créer une production.\nSeuls les enseignants ont ce droit.");
            return null;
        }
        if (txt === "err-data") {
            alert("Les informations envoyées sont incorrectes.\nVérifiez que vous avez bien indiqué un nom pour la production.");
            return null;
        }

        //Ici, il n'y a pas d'erreur normalement

        let board = document.querySelector("#prod");
        while (board.firstChild) {
            board.removeChild(board.firstChild);
        }
        $('#prod').append(txt);
    }
}

function printResultSearchAudio(e) {
    let txt;
    if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
            let resp = this.response.split(' ');
            let board = document.querySelector("#results");
            while (board.firstChild) {
                board.removeChild(board.firstChild);
            }

            if (resp[0] !== "") {

                txt = "<input type='text' name='cbnumber' hidden value='" + resp.length + "'>";

                txt += "<p>Sélectionnez les audio que vous souhaitez associer au mot.</p>"

                for (let i = 0; i < resp.length - 1; i++) {
                    txt += "<div>\n" +
                        "  <input type='checkbox' name='" + i + "' id='checkox' value='" + resp[i] + "'>" +
                        "  <label for='" + resp[i] + "'><audio controls src = '" + PATH + "/" + resp[i] + "'>Erreur</audio></label>" +
                        "</div><br>";
                }
                $('#results').append(txt);
            } else {
                board.append("Aucun résultat.Vérifiez l'orthographe.");
            }


        } else {
            alert('Il y a eu un problème avec la requête.');
        }
    }
}


function nothing(e) {
    if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
        } else {
            alert('Il y a eu un problème avec la requête.');
        }
    }
}

function makeRequest(url, data, then) {
    req = new XMLHttpRequest();
    req.onreadystatechange = then;
    req.open("POST", url);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send(data);
}

function getSelectionsListe(id) {
    var liste = document.getElementById(id);
    var lsSelections = "";
    for (var i = 0; i < liste.options.length; i++) {
        if (liste.options[i].selected)
            lsSelections += liste.options[i].value + " ";
    }
    return lsSelections;
}

function refuserToucheEntree(event) {
    // Compatibilité IE / Firefox
    if (!event && window.event) {
        event = window.event;
    }
    // IE
    if (event.keyCode == 13) {
        event.returnValue = false;
        event.cancelBubble = true;
    }
    // DOM
    if (event.which == 13) {
        event.preventDefault();
        event.stopPropagation();
    }
}