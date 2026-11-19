<?php
require_once "../config/conexion.php";
class Articulo {
    public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen) {
        $sql="INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,condicion) VALUES ('$idcategoria','$codigo','$nombre','$stock','$descripcion','$imagen',1)";
        return ejecutarConsulta($sql);
    }
    public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen) {
        $sql="UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }
    public function desactivar($idarticulo) { return ejecutarConsulta("UPDATE articulo SET condicion=0 WHERE idarticulo='$idarticulo'"); }
    public function activar($idarticulo) { return ejecutarConsulta("UPDATE articulo SET condicion=1 WHERE idarticulo='$idarticulo'"); }
    public function mostrar($idarticulo) { return ejecutarConsultaSimpleFila("SELECT * FROM articulo WHERE idarticulo='$idarticulo'"); }
    public function listar() {
        return ejecutarConsulta("SELECT a.idarticulo,a.idcategoria,c.nombre AS categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria ORDER BY a.idarticulo DESC");
    }
    public function selectCategoria() {
        return ejecutarConsulta("SELECT idcategoria,nombre FROM categoria WHERE condicion=1 ORDER BY nombre");
    }
}
