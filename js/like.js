function evento(id) {
    console.log(id);
    fetch(URL_PATH + "/obtenerlike/?valor=" + id)
    .then(response => response.json())
    .then(response => {
        if(response.lotiene) {
            this.childnode[0].setAttribute("class","fa fa-heart text-danger");
        }else {
            this.childnode[0].setAttribute("class","fa fa-heart");
        }
        this.childnode[1].innertHTML = response.numerolike;
                 
    })
}