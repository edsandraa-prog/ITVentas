<?php
require_once "../config/conexion.php";

class Categoria {
    public function insertar($nombre, $descripcion) {
        return ejecutarConsulta("INSERT INTO categoria (nombre, descripcion, condicion) VALUES ('$nombre', '$descripcion', 1)");
    }
    public function editar($idcategoria, $nombre, $descripcion) {
        return ejecutarConsulta("UPDATE categoria SET nombre='$nombre', descripcion='$descripcion' WHERE idcategoria='$idcategoria'");
    }
    public function desactivar($idcategoria) {
        return ejecutarConsulta("UPDATE categoria SET condicion=0 WHERE idcategoria='$idcategoria'");
    }
    public function activar($idcategoria) {
        return ejecutarConsulta("UPDATE categoria SET condicion=1 WHERE idcategoria='$idcategoria'");
    }
    public function mostrar($idcategoria) {
        return ejecutarConsultaSimpleFila("SELECT * FROM categoria WHERE idcategoria='$idcategoria'");
    }
    public function listar() {
        return ejecutarConsulta("SELECT * FROM categoria ORDER BY idcategoria DESC");
    }
    public function select() {
        return ejecutarConsulta("SELECT idcategoria, nombre FROM categoria WHERE condicion=1 ORDER BY nombre");
    }
}
