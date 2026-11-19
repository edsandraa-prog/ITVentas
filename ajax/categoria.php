<?php
require_once "../modelos/Categoria.php";
$categoria = new Categoria();
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$op = isset($_GET["op"]) ? $_GET["op"] : "";
switch ($op) {
 case "guardaryeditar":
    if ($nombre === "") { http_response_code(400); exit("Escribe el nombre de la categoría."); }
    $rspta = empty($idcategoria) ? $categoria->insertar($nombre,$descripcion) : $categoria->editar($idcategoria,$nombre,$descripcion);
    echo $rspta ? (empty($idcategoria) ? "Categoría registrada correctamente." : "Categoría actualizada correctamente.") : "No se pudo guardar la categoría. Revisa la base de datos.";
    break;
 case "desactivar":
    echo $categoria->desactivar($idcategoria) ? "Categoría desactivada." : "No se pudo desactivar.";
    break;
 case "activar":
    echo $categoria->activar($idcategoria) ? "Categoría activada." : "No se pudo activar.";
    break;
 case "mostrar":
    echo json_encode($categoria->mostrar($idcategoria));
    break;
 case "listar":
    $rspta=$categoria->listar(); $data=array();
    if ($rspta) while ($reg=$rspta->fetch_object()) {
      $opciones='<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button> ';
      $opciones .= $reg->condicion ? '<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>';
      $data[]=array("0"=>$opciones,"1"=>htmlspecialchars($reg->nombre),"2"=>htmlspecialchars($reg->descripcion),"3"=>$reg->condicion?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>');
    }
    echo json_encode(array("sEcho"=>1,"iTotalRecords"=>count($data),"iTotalDisplayRecords"=>count($data),"aaData"=>$data));
    break;
}
?>
