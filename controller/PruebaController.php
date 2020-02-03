<?php
namespace controller;

use dawfony\Klasto;
use dawfony\Ti;
use Exception;
use model\OrmSocialDaw;
use model\post;
require("funciones.php");

class PruebaController extends Controller {
    public function listado($pagina = 1) {
        $title = "listado";
        global $URL_PATH;
        global $config;
        $Orm = new OrmSocialDaw;
        $listado = $Orm -> listado($pagina);
        $cuenta = $Orm ->contarUltimosPosts();
        $numpaginas = ceil($cuenta / $config["post_per_page"]);
        echo Ti::render("view/listado.phtml",compact('title','listado','pagina','numpaginas'));
    }
    function obtenerlike($id) {
        //importante si no lo pones no se convierte el json
        header('Content-type: application/json');
        if(isset($_SESSION["login"])) {
            $Orm = new OrmSocialDaw;
            $array["lotiene"] = $Orm -> quitaroponerlike($id,$_SESSION["login"]);
            $array["numerolike"] = $Orm -> numerolike($id);
            echo json_encode($array);
        }else {
            http_response_code(403);
            die (json_encode(["msg"=>"No logueado"]));
        }
    }
    public function registro() {
        $title = "registro";
        echo Ti::render("view/registro.phtml",compact('title'));
    }
    public function confirmacion() {
        $error = "";
        $login = sanitizar(strtolower($_POST["login"] ?? ""));
        $password = sanitizar($_POST["password"] ?? "");
        $passwordos = sanitizar($_POST["passwordos"] ?? "");
        $nombre = sanitizar($_POST["nombre"] ?? "");
        $email = sanitizar($_POST["email"] ?? "");
        if($login == "" || $nombre == "" || $email == "") {
            $error = "campos vacios";
            $title = "registro";
            echo Ti::render("view/registro.phtml",compact('title','error','login','password','passwordos','nombre','email'));
        }else if($password != $passwordos) {
            $error = "contraseñas diferentes";
            $title = "registro";
            echo Ti::render("view/registro.phtml",compact('title','error','login','password','passwordos','nombre','email'));
        }else {
            $Orm = new OrmSocialDaw;
            $existe = $Orm -> existe($login);
            if($existe) {
                $title = "registro";
                $error = "usuario ya existe";
                echo Ti::render("view/registro.phtml",compact('title','error','login','password','passwordos','nombre','email'));
            }else {
                $password= password_hash($password,PASSWORD_DEFAULT);
                $Orm -> introducirusuario($login,$password,$nombre,$email);
                $title = "login";
                echo Ti::render("view/login.phtml",compact('title'));
            
            }
        }
    }

    public function login() {
        $title = "login";
        $error = "";
        echo Ti::render("view/login.phtml",compact('title','error'));
    }
    //mirar
    public function confirmacionlogin() {
        global $URL_PATH;
        $login =  strtolower(sanitizar($_REQUEST["login"]));
        $password = $_REQUEST["password"];
        $error = "";
        $title = "login";
        if($login == "") {
            $error = "nombre vacio";
            echo Ti::render("view/login.phtml",compact('title','login','password','error'));
        }else {
            $Orm = new OrmSocialDaw;
            $listado = $Orm -> conseguirusuario($login);
            $valor = $listado["password"];
            $rol = $listado["rol_id"];
            //por aqui
            if(password_verify($password,$valor)) {
                session_start();
                $_SESSION["login"] = $login;
                $_SESSION["rol"] = $rol;
                header("Location: $URL_PATH");
            }else {
                $error = "login o contraseña incorrectos";
                echo Ti::render("view/login.phtml",compact('title','login','password','error'));
            }
        }
    }

    function borrar() {
        global $URL_PATH;
        session_start();
        session_destroy();
        //unset($_SESSION["login"]);
        header("Location: $URL_PATH");
    }


    function masinformacion($valor) {
        global $URL_PATH;
        $title = "masinformacion";
        $Orm = new OrmSocialDaw;
        $post = $Orm -> obtenerpost($valor);
        $comentarios = $Orm -> obtenercomentarios($valor);
        var_dump($post->foto);
        echo Ti::render("view/post.phtml",compact('title','post','comentarios','valor'));
    }
    function annadircomentario($valor) {
        if(isset($_SESSION["login"])) {
        global $URL_PATH;
        $comentario = $_POST["comentario"];
        $Orm = new OrmSocialDaw;
        $Orm -> annadircomentario($valor,$comentario,$_SESSION["login"]);
        header("Location: $URL_PATH/masinformacion/$valor");
        }else {
            throw new Exception("Intento añadir comentario sin estar logueado");
        }
    }

    
    function anadir() {
        if(isset($_SESSION["login"])) {
        $title = "anadir";
        echo Ti::render("view/anadir.phtml",compact('title'));
        }else {
            throw new Exception("Intento añadir sin estar logueado");
        }
    }
    public function confirmaranadir() {
        global $URL_PATH;
        if(isset($_SESSION["login"])) {
        if($_SESSION["rol"] == 0 || $_SESSION["rol"] == 1) {
            $post = new Post;
            $post ->fecha = date('Y-m-d H:i:s');
            $post->resumen = sanitizar($_REQUEST["resumen"]);
            //$post->texto = html_purify($_REQUEST["texto"]);
            $post->texto = sanitizar($_REQUEST["texto"]);
            $post->foto = $_FILES["foto"]["name"];
            $post->categoria_post_id = $_REQUEST["desplegable"];
            if(!($post->categoria_post_id == 0 || $post->categoria_post_id ==1 || $post->categoria_post_id == 2)) {
                die("Esa categoria de post no es correcta");
            }
            $post->usuario_login = $_SESSION["login"];
            var_dump($post);
            move_uploaded_file($_FILES["foto"]["tmp_name"], "assets/img/" . $post->foto);
            $Orm = new OrmSocialDaw;
            $Orm -> anadirpost($post);
            header("Location: $URL_PATH");
        }
        }else {
            throw new Exception("Intento añadir sin estar logueado");
        }   
    }


    function informacionpersonalalguien($login) {
        global $URL_PATH;
        $Orm = new OrmSocialDaw;
        $numeroseguidores = $Orm -> obtenerseguidores($login);
        $numeropersonassigue = $Orm -> obtenersiguiendo($login);
        $postpersona = $Orm -> obtenerpostpersona($login);
        $respuesta = false;
        if(isset($_SESSION["login"])) {
            $losigues = $Orm -> losigues($login,$_SESSION["login"]);
            if($losigues == false) {
                $respuesta = false;
            }else {
                $respuesta = true;
            }
        }
        $title = "informacionpersonal";
        echo Ti::render("view/perfilalguien.phtml",compact('title','login','postpersona','numeroseguidores','numeropersonassigue','respuesta'));

    }


    function seguir($loginpersona) {
        if(isset($_SESSION["login"])) {
            global $URL_PATH;
        $Orm = new OrmSocialDaw;
        $Orm -> seguir($_SESSION["login"],$loginpersona);
        header("Location: $URL_PATH/informacionpersonalalguien/$loginpersona");
        }else {
            throw new Exception("Intento seguir a alguien sin estar logueado");
        }

    }
    function dejardeseguir($loginpersona) {
        if(isset($_SESSION["login"])) {
        global $URL_PATH;
        $Orm = new OrmSocialDaw;
        var_dump($loginpersona);
        $Orm -> eliminarseguidor($_SESSION["login"],$loginpersona);
        header("Location: $URL_PATH/informacionpersonalalguien/$loginpersona");
        }else {
            throw new Exception("Intento dejar de seguir sin estar logueado");
        }
    }


    function perfil() {
        if(isset($_SESSION["login"])) {
            global $URL_PATH;
        $Orm = new OrmSocialDaw;
        $loginpersonal = $_SESSION["login"];
        $numeroseguidores = $Orm -> obtenerseguidores($loginpersonal);
        $numeropersonassigue = $Orm -> obtenersiguiendo($loginpersonal);
        $postpersona = $Orm -> obtenerpostpersona($loginpersonal);
        $title = "informacionpersonal";
        echo Ti::render("view/miperfil.phtml",compact('title','loginpersonal','postpersona','numeroseguidores','numeropersonassigue'));
        }else {
            throw new Exception("Intento ver el perfil sin estar logueado");
        }
    }

    function seguidores($pagina = 1) {
        if(isset($_SESSION["login"])) {
            $title = "listado";
            global $URL_PATH;
            global $config;
            $Orm = new OrmSocialDaw;
            $listado = $Orm -> listadoseguidores($_SESSION["login"],$pagina);
            $cuenta = $Orm ->contarultimospostseguidores($_SESSION["login"]);
            $numpaginas = ceil($cuenta / $config["post_per_page"]);
            echo Ti::render("view/listadomisseguidores.phtml",compact('title','listado','pagina','numpaginas'));
        }else {
            throw new Exception("Intento ver los seguidores sin estar logueado");
        }
    }

    function existeusuario($nombre) {
        header('Content-type: application/json');
            $Orm = new OrmSocialDaw;
            $existe = $Orm->existe($nombre);
            if($existe) {
                $array["respuesta"] = true;
                echo json_encode($array); 
            }else {
                $array["respuesta"] = false;
                echo json_encode($array); 
            }
    }
}