function comprobarnombre() {
var nombre = document.getElementById("login");
var error = document.getElementById("error");
fetch(URL_PATH + "/existeusuario/" + nombre.value)
    .then(response => response.json())
    .then(response => {
        if(response.respuesta){
            console.log(response.respuesta);
            error.textContent = "Un usuario ya tiene este nombre de login";
            //error.innerHTML = ` `;
            errornombre = true;
        }else {
            error.textContent = "";
            errornombre = false;
        }
    })
}