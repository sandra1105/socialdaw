function eliminarUsuario(login) {
    if (confirm("¿Seguro que desea eliminar a " + login + "?")) {
        location.href=URL_PATH + "/eliminarusuario/" + login;
    }
}
function eliminarPost(id) {
    if (confirm("¿Seguro que desea eliminar este post?")) {
        location.href=URL_PATH + "/eliminarpost/" + id;
    }
}