
<?php
session_start();
require_once "../config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$login = trim($_POST["login"] ?? "");
$clave = $_POST["clave"] ?? "";

if ($login === "" || $clave === "") {
    header("Location: login.html?error=1");
    exit;
}

$sql = "SELECT idusuario, nombre, login, clave, rol, condicion
        FROM usuario
        WHERE login = ?
        LIMIT 1";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $conexion->error);
}

$stmt->bind_param("s", $login);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

if (
    !$usuario ||
    !password_verify($clave, $usuario["clave"]) ||
    $usuario["condicion"] != 1
) {
    header("Location: login.html?error=1");
    exit;
}

session_regenerate_id(true);

$_SESSION["idusuario"] = $usuario["idusuario"];
$_SESSION["nombre"] = $usuario["nombre"];
$_SESSION["login"] = $usuario["login"];
$_SESSION["rol"] = $usuario["rol"];

header("Location: escritorio.php");
exit;
