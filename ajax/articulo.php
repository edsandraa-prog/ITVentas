
<?php
require_once "../modelos/Articulo.php";
require_once "../config/conexion.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "0";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$imagen = isset($_POST["imagenactual"]) ? limpiarCadena($_POST["imagenactual"]) : "";

$op = isset($_GET["op"]) ? $_GET["op"] : "";

switch ($op) {

 case "guardaryeditar":

    if (!empty($_FILES["imagen"]["name"])) {

        $permitidos = array(
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        );

        $tipo = mime_content_type($_FILES["imagen"]["tmp_name"]);

        if (!isset($permitidos[$tipo])) {
            http_response_code(400);
            exit("Formato de imagen no permitido. Usa JPG, PNG o WEBP.");
        }

        if ($_FILES["imagen"]["size"] > 3*1024*1024) {
            http_response_code(400);
            exit("La imagen supera los 3 MB.");
        }

        $imagen = uniqid("art_", true) . "." . $permitidos[$tipo];

        // Crear automáticamente la carpeta si no existe
        $directorio = __DIR__ . "/../files/articulos";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        // Comprobar que la carpeta exista y permita guardar archivos
        if (!is_dir($directorio) || !is_writable($directorio)) {
            http_response_code(500);
            exit("No se puede acceder a la carpeta files/articulos.");
        }

        // Guardar imagen
        $destino = $directorio . DIRECTORY_SEPARATOR . $imagen;

        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino)) {
            http_response_code(500);
            exit("No se pudo subir la imagen.");
        }
    }

    if ($nombre === "" || $idcategoria === "" || $codigo === "") {
        http_response_code(400);
        exit("Completa nombre, categoría y código.");
    }

    $rspta = empty($idarticulo)
      ? $articulo->insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
      : $articulo->editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);

    echo $rspta
      ? (empty($idarticulo) ? "Artículo registrado correctamente." : "Artículo actualizado correctamente.")
      : "No se pudo guardar el artículo. Revisa la base de datos.";

    break;

 case "desactivar":

    echo $articulo->desactivar($idarticulo)
      ? "Artículo desactivado."
      : "No se pudo desactivar.";

    break;

 case "activar":

    echo $articulo->activar($idarticulo)
      ? "Artículo activado."
      : "No se pudo activar.";

    break;

 case "mostrar":

    echo json_encode($articulo->mostrar($idarticulo));

    break;

 case "listar":

    $rspta = $articulo->listar();
    $data = array();

    if ($rspta) {
        while ($reg = $rspta->fetch_object()) {

            $opciones = '<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button> ';

            $opciones .= $reg->condicion
              ? '<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>'
              : '<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>';

            $foto = $reg->imagen
              ? "../files/articulos/".rawurlencode($reg->imagen)
              : "";

            $img = $foto
              ? '<img src="'.$foto.'" style="width:55px;height:55px;object-fit:contain" alt="Artículo">'
              : '<span class="text-muted">Sin imagen</span>';

            $data[] = array(
                "0" => $opciones,
                "1" => htmlspecialchars($reg->nombre),
                "2" => htmlspecialchars($reg->categoria),
                "3" => htmlspecialchars($reg->codigo),
                "4" => (int)$reg->stock,
                "5" => $img,
                "6" => $reg->condicion
                    ? '<span class="label bg-green">Activo</span>'
                    : '<span class="label bg-red">Desactivado</span>'
            );
        }
    }

    echo json_encode(array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    ));

    break;

 case "selectCategoria":

    $rspta = $articulo->selectCategoria();

    echo '<option value="">Seleccione una categoría</option>';

    if ($rspta) {
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="'.$reg->idcategoria.'">'.htmlspecialchars($reg->nombre).'</option>';
        }
    }

    break;
}
