<?php 
class Sesion{
    
    var $id = null;
    var $id_usuario = null; // solo un alias
    var $nombre = null;
    var $esAdmin = false;
    var $email = null;
    var $permisos = null;
    var $tipoUsuario = null;
    
    public function __construct(){

    }

    public static function existe(){
            // session_destroy();
            $json = isset($_SESSION['id']) ? $_SESSION['id'] : null;
            if($json != null && $json != ""){
                    return true;
            }else{
                    return false;
            }
    }

    public static function iniciar(TblUsuario $usuario, $permisos = []){
            $_SESSION['id'] = $usuario->id_usuario;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['es_admin'] = $usuario->tipo_usuario == "admin";
            $_SESSION['tipo_usuario'] = $usuario->tipo_usuario;
            $_SESSION['email'] = $usuario->nombre;
            $_SESSION['permisos'] = implode(",", $permisos);
    }

    public function finalizar(){
            session_destroy();
    }

    public static function obtener(){
        if(self::existe()){
            $s = new Sesion();
            $s->id = $_SESSION['id'];
            $s->id_usuario = $_SESSION['id'];
            $s->nombre = $_SESSION['nombre'];
            $s->esAdmin = $_SESSION['es_admin'];
            $s->tipo_usuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario']:"";
            $s->email = $_SESSION['email'];
            $s->permisos = explode(",", $_SESSION['permisos']);
            return $s;
        }else{
            return null;
        }
    }

    public function obtenerPermisos(){
        return $this->permisos != null ? $this->permisos : [];
    }
    
    public function tienePermiso($str){
        return in_array($str, $this->obtenerPermisos());
    }
}