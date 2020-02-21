function deleteMot(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            //document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "deleteMot.php?q=1" , true);
    xmlhttp.send();
}

window.addEventListener("load" , () => {
    let bttnDltWrd = document.querySelector("#bttnDltWrd");
    bttnDltWrd.addEventListener("click", deleteMot)
});