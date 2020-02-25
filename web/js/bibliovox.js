var PATH = "/s3a_s20_bernard_claude_conte_sallerin_allard";

document.getElementById("searchButtn").onclick = function () {
    console.log(PATH);
    var words = document.getElementById("searchBar").value;
    makeRequest(PATH + '/searchAudio.php', words)
}

function printResultSearchAudio(e) {
    console.log("tt");
    console.log(this.response);
}

function makeRequest(url, data) {
    req = new XMLHttpRequest();
    req.onreadystatechange = printResultSearchAudio;
    req.open("POST", url);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("words=" + encodeURIComponent(data));
}