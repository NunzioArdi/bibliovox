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

function printResultSearchAudio(e) {
    if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
            console.log(this.response);
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

function getSelectionsListe(id)
{
    var liste = document.getElementById(id);
    var lsSelections = "";
    for(var i=0; i<liste.options.length; i++)
    {
        if(liste.options[i].selected)
            lsSelections += liste.options[i].value + " ";
    }
    return lsSelections;
}