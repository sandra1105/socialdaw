<?php
require 'vendor/autoload.php';
require 'cargarconfig.php';
session_start();
use NoahBuscher\Macaw\Macaw;
use controller\PruebaController;
use dawfony\KlastoException;

//listado para pantalla principal
Macaw::get($URL_PATH . "/","controller\PruebaController@listado");
Macaw::get($URL_PATH . "/pagina/(:any)","controller\PruebaController@listado");

Macaw::get($URL_PATH . '/registro', "controller\PruebaController@registro");
Macaw::post($URL_PATH . '/registro', "controller\PruebaController@confirmacion");

Macaw::get($URL_PATH . '/login', "controller\PruebaController@login");
Macaw::post($URL_PATH . '/login', "controller\PruebaController@confirmacionlogin");

Macaw::get($URL_PATH . '/borrar', "controller\PruebaController@borrar");

Macaw::get($URL_PATH . '/masinformacion/(:any)', "controller\PruebaController@masinformacion");

Macaw::get($URL_PATH . '/anadir', "controller\PruebaController@anadir");
Macaw::post($URL_PATH . '/anadir', "controller\PruebaController@confirmaranadir");

Macaw::get($URL_PATH . '/informacionpersonalalguien/(:any)', "controller\PruebaController@informacionpersonalalguien");

Macaw::get($URL_PATH . '/seguir/(:any)', "controller\PruebaController@seguir");
Macaw::get($URL_PATH . '/dejardeseguir/(:any)', "controller\PruebaController@dejardeseguir");

Macaw::get($URL_PATH . '/perfil', "controller\PruebaController@perfil");

Macaw::post($URL_PATH . '/annadircomentario/(:any)', "controller\PruebaController@annadircomentario");

Macaw::get($URL_PATH . '/seguidores', "controller\PruebaController@seguidores");
Macaw::get($URL_PATH . '/seguidores/pagina/(:any)', "controller\PruebaController@seguidores");

Macaw::get($URL_PATH . '/obtenerlike/(:any)', "controller\PruebaController@obtenerlike");

Macaw::get($URL_PATH . '/existeusuario/(:any)', "controller\PruebaController@existeusuario");

// Captura de URL no definidas.
Macaw::error(function() {
  (new \controller\ErrorController) -> gestionarNotFound();
});

//terminar esto
try {
  Macaw::dispatch();
}catch(Exception $ex) {
  (new \controller\ErrorController) -> gestionarExcepcion($ex);
}

