///boton.click() -> para que se clicken automaticamente
window.addEventListener("load",todo);
function todo() {
    let botones = document.getElementsByClassName("boton");
    for (let valor in botones) {
        let id = this.getAttribute("id");
        valor.addEventListener("click",evento);
        valor.click();
    }
}
function evento() {
    let id = this.getAttribute("id");
    fetch(URL_PATH + "/obtenerlike/?valor=" + id)
    .then(response => response.json())
    .then(json => {
        if(json[lotiene]) {
            this.childnode[0].setAttribute("class","fa fa-heart text-danger");
        }else {
            this.childnode[0].setAttribute("class","fa fa-heart");
        }
        this.childnode[1].innertHTML = json[numerolike];
                 
    })
}