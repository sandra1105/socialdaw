<?php
namespace controller;

class ErrorController {

    function gestionarNotFound() {
        http_response_code(404);
        echo "<h1>404 Not found</h1>";
        echo "<p>TO DO: Hacer una página de not found bonita y con MVC</p>";
    }

    function gestionarExcepcion($ex) {
        http_response_code(500);
        echo "<h1>500 Error interno (excepción descontrolada)</h1>";
        echo "<p>TO DO:Hacer página de excepción bonita y con MVC</p>";
        echo "<pre>$ex</pre>";
    }


}