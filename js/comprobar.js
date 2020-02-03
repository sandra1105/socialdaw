
function comprobar() {
    var nombre = document.getElementById("login");
    var error = document.getElementById("error");

    if(nombre.value == "") {
        error.textContent = "Nombre vacio , por favor escriba un nombre";
        return false;
    }
    
    
    return false;
}