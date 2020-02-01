function cerrarsesion() {
    if (confirm("¿Seguro que desea salir?")) {
        location.href=URL_PATH + "/borrar";
    }
}