
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.html");
    exit;
}

require_once "../config/conexion.php";

$nombreUsuario = $_SESSION["nombre"] ?? "Usuario";
$rolUsuario = $_SESSION["rol"] ?? "";

// Permisos iniciales
$permisos = array(
    "escritorio" => 0,
    "almacen" => 0,
    "compras" => 0,
    "ventas" => 0,
    "acceso" => 0,
    "consultas" => 0
);

// Consultar permisos del rol
$stmt = $conexion->prepare(
    "SELECT escritorio, almacen, compras, ventas, acceso, consultas
     FROM permiso
     WHERE rol = ?
     LIMIT 1"
);

if ($stmt) {
    $stmt->bind_param("s", $rolUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        foreach ($permisos as $campo => $valor) {
            $permisos[$campo] = (int)$fila[$campo];
        }
    }

    $stmt->close();
}

// Identificar página actual
$pagina = basename($_SERVER["PHP_SELF"]);

// Páginas que necesitan permisos
$paginasProtegidas = array(
    "escritorio.php" => "escritorio",
    "articulo.php" => "almacen",
    "categoria.php" => "almacen",
    "ingreso.php" => "compras",
    "proveedor.php" => "compras",
    "venta.php" => "ventas",
    "cliente.php" => "ventas",
    "usuario.php" => "acceso",
    "ususario.php" => "acceso",
    "permiso.php" => "acceso"
);

// Comprobar permiso de la página
if (isset($paginasProtegidas[$pagina])) {
    $permisoNecesario = $paginasProtegidas[$pagina];

    if ($permisos[$permisoNecesario] !== 1) {
        http_response_code(403);
        exit("Acceso denegado. Tu rol no tiene permiso para ingresar.");
    }
}

// Proteger consultas de compras
if ($pagina === "consultacompras.php") {
    if (
        $permisos["consultas"] !== 1 ||
        $permisos["compras"] !== 1
    ) {
        http_response_code(403);
        exit("No tienes permiso para consultar compras.");
    }
}

// Proteger consultas de ventas
if ($pagina === "consultaventas.php") {
    if (
        $permisos["consultas"] !== 1 ||
        $permisos["ventas"] !== 1
    ) {
        http_response_code(403);
        exit("No tienes permiso para consultar ventas.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ITVentas</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="../public/css/bootstrap-select.min.css">
</head>

<body class="hold-transition skin-red-light sidebar-mini">

<div class="wrapper">

<header class="main-header">

    <a href="escritorio.php" class="logo">
        <span class="logo-mini"><b>IT</b>Ventas</span>
        <span class="logo-lg"><b>ITVentas</b></span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="../public/dist/img/user2-160x160.jpg" class="user-image" alt="Usuario">

                        <span class="hidden-xs">
                            <?php echo htmlspecialchars($nombreUsuario); ?>
                            (<?php echo htmlspecialchars($rolUsuario); ?>)
                        </span>
                    </a>

                    <ul class="dropdown-menu">

                        <li class="user-header">
                            <img src="../public/dist/img/user2-160x160.jpg" class="img-circle" alt="Usuario">

                            <p>
                                <?php echo htmlspecialchars($nombreUsuario); ?>
                                <small><?php echo htmlspecialchars($rolUsuario); ?></small>
                            </p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="logout.php" class="btn btn-default btn-flat">
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>
                </li>

            </ul>
        </div>

    </nav>
</header>

<aside class="main-sidebar">

    <section class="sidebar">

        <ul class="sidebar-menu">

            <li class="header">MENÚ PRINCIPAL</li>

            <?php if ($permisos["escritorio"] === 1): ?>
            <li>
                <a href="escritorio.php">
                    <i class="fa fa-tasks"></i>
                    <span>Escritorio</span>
                </a>
            </li>
            <?php endif; ?>


            <?php if ($permisos["almacen"] === 1): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>Almacén</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li>
                        <a href="articulo.php">
                            <i class="fa fa-circle-o"></i> Artículos
                        </a>
                    </li>

                    <li>
                        <a href="categoria.php">
                            <i class="fa fa-circle-o"></i> Categorías
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if ($permisos["compras"] === 1): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-th"></i>
                    <span>Compras</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li>
                        <a href="ingreso.php">
                            <i class="fa fa-circle-o"></i> Ingresos
                        </a>
                    </li>

                    <li>
                        <a href="proveedor.php">
                            <i class="fa fa-circle-o"></i> Proveedores
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if ($permisos["ventas"] === 1): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Ventas</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li>
                        <a href="venta.php">
                            <i class="fa fa-circle-o"></i> Ventas
                        </a>
                    </li>

                    <li>
                        <a href="cliente.php">
                            <i class="fa fa-circle-o"></i> Clientes
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if ($permisos["acceso"] === 1): ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i>
                    <span>Acceso</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li>
                        <a href="usuario.php">
                            <i class="fa fa-circle-o"></i> Usuarios
                        </a>
                    </li>

                    <li>
                        <a href="permiso.php">
                            <i class="fa fa-circle-o"></i> Permisos
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if ($permisos["consultas"] === 1 && $permisos["compras"] === 1): ?>
            <li>
                <a href="consultacompras.php">
                    <i class="fa fa-bar-chart"></i>
                    <span>Consulta Compras</span>
                </a>
            </li>
            <?php endif; ?>


            <?php if ($permisos["consultas"] === 1 && $permisos["ventas"] === 1): ?>
            <li>
                <a href="consultaventas.php">
                    <i class="fa fa-bar-chart"></i>
                    <span>Consulta Ventas</span>
                </a>
            </li>
            <?php endif; ?>


            <li>
                <a href="ayuda.php">
                    <i class="fa fa-plus-square"></i>
                    <span>Ayuda</span>
                    <small class="label pull-right bg-red">PDF</small>
                </a>
            </li>

            <li>
                <a href="acerca.php">
                    <i class="fa fa-info-circle"></i>
                    <span>Acerca De...</span>
                    <small class="label pull-right bg-yellow">IT</small>
                </a>
            </li>

        </ul>

    </section>
</aside>
