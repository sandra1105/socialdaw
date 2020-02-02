<?php
namespace controller;
use dawfony\Ti;

class ErrorController {

    function gestionarNotFound() {
        http_response_code(404);
        echo Ti::render("view/Notfound.phtml");
    }

    function gestionarExcepcion($ex) {
        http_response_code(500);
        echo Ti::render("view/Exception.phtml",compact('ex'));
    }


}