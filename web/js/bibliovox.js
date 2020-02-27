var PATH = "/s3a_s20_bernard_claude_conte_sallerin_allard";

if (document.getElementById("searchButtn") !== null)
    document.getElementById("searchButtn").onclick = function () {
        console.log(PATH);
        var words = document.getElementById("searchBar").value;
        makeRequest(PATH + '/searchAudio', "data=" + encodeURIComponent(words), printResultSearchAudio);
    }

if (document.getElementById("dicoButtn") !== null)
    document.getElementById("dicoButtn").onclick = function () {
        var dico = getSelectionsListe("selectedDico");
        var idM = document.getElementById("idM").value;
        makeRequest(PATH + '/changeDicoMot', "data=" + encodeURIComponent(dico) + "&idM=" + idM, nothing);
    }

if (document.getElementById("buttnChangeWord") !== null)
    document.getElementById("buttnChangeWord").onclick = function () {
        var word = document.getElementById("newWord").value;
        var idM = document.getElementById("idM").value;
        makeRequest(PATH + '/udpateWord', "word=" + word + "&idM=" + idM, nothing);
    }

if (document.getElementById("reload") !== null)
    document.getElementById("reload").onclick = function () {

    }

function printResultSearchAudio(e) {
    let txt;
    if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
            console.log(this.response);
            let resp = this.response.split('-');
            let board = document.querySelector("#results");
            while (board.firstChild) {
                board.removeChild(board.firstChild);
            }

            if (resp[0] !== "") {
                for (let i = 0; i < resp.length-1; i++) {
                    txt = "<audio controls src = '" + PATH + "/" + resp[i] + "'>Erreur</audio><br>";
                    $('#results').append(txt);
                }
            } else {
                board.append("Auncun Résultat");
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