function evento(id) {
    console.log(id);
    fetch(URL_PATH + "/obtenerlike/" + id)
    .then(response => response.json())
    .then(response => {
        let span = document.getElementById("numerolike" + id);
        let i = document.getElementById(id);
        if(response.lotiene) {
            i.setAttribute("class","fa fa-heart text-danger");
        }else {
            i.setAttribute("class","fa fa-heart");
        }
        console.log(response.numerolike);
        console.log(span);
        span.innerHTML = response.numerolike;
                 
    })
}