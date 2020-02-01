<?php
namespace model;
use dawfony\Klasto;
use model\post;
use model\usuario;
use model\comentario;
class OrmSocialDaw{
    function listado() {
        $conn=Klasto::getInstance();
        $params = [];
        $select = "select c.id,c.fecha,c.resumen,c.texto,foto,c.categoria_post_id,usuario_login,a.descripcion from post c,categoria_post a
        where c.categoria_post_id=a.id order by fecha DESC";
        $array = $conn->query($select,$params,"model\post");
        foreach( $array as $post) {
            $post->num_comentarios = $this->numerocomentario($post->id);
            $post->num_like = $this->numerolike($post->id);
        }
        return $array;
    }
    function quitaroponerlike($id,$login) {
        $conn = Klasto::getInstance();
        $num = $conn->execute(
            "DELETE FROM `like` WHERE post_id = ? AND usuario_login = ?",
            [$id, $login]
        );
        if ($num > 0) {
            return false; // Ya no tiene like
        }
        $conn->execute(
            "INSERT INTO `like`(post_id, usuario_login) VALUES(?,?)",
            [$id, $login]
        );
        return true; // Sí tiene like
    }
    function numerolike($id) {
        $conn=Klasto::getInstance();
        $params = [$id];
        $select = "select count(*) as 'num_like' from like where post_id = ?";
        //importante recuerdalo inutil
        return $conn->queryOne($select,$params)["num_like"];
    }
    function numerocomentario($id) {
        $conn=Klasto::getInstance();
        $params = [$id];
        $select = "select count(*) as 'num_comentarios' from comenta where post_id = ?";
        //importante recuerdalo inutil
        return $conn->queryOne($select,$params)["num_comentarios"];
    }
    //esta mal revisar
    function listadoseguidores($login) {
        $conn=Klasto::getInstance();
        $nombre = $this->numerocomentario($login);
        $params = [$login];
        $select = "select c.id,c.fecha,c.resumen,c.texto,foto,c.categoria_post_id,usuario_login,a.descripcion from post c,categoria_post a
        where c.categoria_post_id=a.id order by fecha DESC";
        $array = $conn->query($select,$params,"model\post");
        foreach( $array as $post) {
            $post->num_comentarios = $this->numerocomentario($post->id);
        }
        return $array;
    }
    function personassigo($login) {
        $conn=Klasto::getInstance();
        $params = [$login];
        $select = "select usuario_login_seguidor  from sigue where usuario_login_seguido = ? " ;
        return $conn->query($select,$params,"model/usuario");
    }

    function existe($login,$password) {
        $conn=Klasto::getInstance();
        $params = [$login,$password];
        $select = "select login,password,nombre from usuario where login=? and password=?";
        return $conn->queryOne($select,$params,"model\usuario");
    }
    function introducirusuario($login,$password,$nombre,$email) {
        $conn=Klasto::getInstance();
        $params = [$login,$password,0,$nombre,$email];
        $select = "insert into usuario(login,password,rol_id,nombre,email) values(?,?,?,?,?)";
        return $conn->execute($select,$params);
    }
    function conseguirusuario($login) {
        $conn=Klasto::getInstance();
        $params = [$login];
        $select = "select password,rol_id from usuario where login=?";
        return $conn->queryOne($select,$params);
    }
    function obtenerpost($id) {
        // si pongo queryone no me funciona si pongo query asecas si que funciona
        $conn=Klasto::getInstance();
        $params = [$id];
        $select = "select c.id,c.fecha,c.resumen,c.texto,foto,c.categoria_post_id,usuario_login,a.descripcion from post c,categoria_post a
        where c.categoria_post_id=a.id and c.id = ?";
        return $conn->queryOne($select,$params,"model\post");
    }
    function obtenercomentarios($id) {
        $conn=Klasto::getInstance();
        $params = [$id];
        $select = "select usuario_login,fecha,texto from comenta where post_id = ?";
        return $conn->query($select,$params,"model\comentario");
    }
    function obtenerpostpersona($login) {
        $conn=Klasto::getInstance();
        $params = [$login];
        $select = "select c.id,c.fecha,c.resumen,c.texto,foto,c.categoria_post_id,usuario_login,a.descripcion from post c,categoria_post a
        where c.categoria_post_id=a.id and usuario_login = ?";
        return $conn->query($select,$params,"model\post");
    }

    function anadirpost($post) {
        $conn=Klasto::getInstance();
        $params = [$post->fecha,$post->resumen,$post->texto,$post->foto,$post->categoria_post_id,$post->usuario_login];
        $select = "insert into post(fecha,resumen,texto,foto,categoria_post_id,usuario_login) values (?,?,?,?,?,?)";
        return $conn->execute($select,$params);
    }


    function obtenerseguidores($login) {
        $conn=Klasto::getInstance();
        $params = [$login];
        $select = "select count(usuario_login_seguido) as numero from sigue where usuario_login_seguidor = ?";
        return $conn->queryOne($select,$params);
    }
    function obtenersiguiendo($login) {
        $conn=Klasto::getInstance();
        $params = [$login];
        $select = "select count(usuario_login_seguidor) as numero from sigue where usuario_login_seguido = ? " ;
        return $conn->queryOne($select,$params);
    }

    function seguir($login,$loginpersona) {
        $conn=Klasto::getInstance();
        $params = [$loginpersona,$login];
        $select = "insert into sigue(usuario_login_seguido,usuario_login_seguidor) values (?,?)" ;
        return $conn->execute($select,$params);
    }
    function losigues($loginpersona,$login) {
        $conn=Klasto::getInstance();
        $params = [$login,$loginpersona];
        $select = "select usuario_login_seguido as numero from sigue where usuario_login_seguidor = ? and usuario_login_seguido = ?" ;
        return $conn->queryOne($select,$params);
    }
    function eliminarseguidor($login,$loginpersona) {
        $conn=Klasto::getInstance();
        $params = [$login,$loginpersona];
        $select = "delete from sigue where usuario_login_seguidor = ? and usuario_login_seguido = ?" ;
        return $conn->execute($select,$params);
    }
    function annadircomentario($valor,$comentario,$id) {
        $conn=Klasto::getInstance();
        $params = [$valor,$id,$comentario];
        $select = "insert into comenta(post_id,usuario_login,fecha,texto) values (?,?,NOW(),?)" ;
        return $conn->execute($select,$params);
    }
    
}