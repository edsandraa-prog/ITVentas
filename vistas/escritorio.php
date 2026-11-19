<?php
require_once "../config/conexion.php";
function totalTabla($sql){ global $conexion; $r=$conexion->query($sql); if(!$r)return 0; $d=$r->fetch_row(); return $d[0]??0; }
$nArt=totalTabla("SELECT COUNT(*) FROM articulo WHERE condicion=1");
$nCat=totalTabla("SELECT COUNT(*) FROM categoria WHERE condicion=1");
$nProv=totalTabla("SELECT COUNT(*) FROM proveedor WHERE condicion=1");
$nCli=totalTabla("SELECT COUNT(*) FROM cliente WHERE condicion=1");
$nIng=totalTabla("SELECT COUNT(*) FROM ingreso");
$nVen=totalTabla("SELECT COUNT(*) FROM venta");
$stock=totalTabla("SELECT COALESCE(SUM(stock),0) FROM articulo WHERE condicion=1");
$sumVen=totalTabla("SELECT COALESCE(SUM(total),0) FROM venta WHERE estado='Aceptado'");
$sumComp=totalTabla("SELECT COALESCE(SUM(total),0) FROM ingreso WHERE estado='Aceptado'");
require 'header.php';
?>
<div class="content-wrapper">
<section class="content-header"><h1>Escritorio <small>Resumen general de ITVentas</small></h1></section>
<section class="content">
<div class="row">
<?php
$cards=array(
 array('Artículos',$nArt,'fa-cubes','bg-aqua','articulo.php'),
 array('Categorías',$nCat,'fa-tags','bg-green','categoria.php'),
 array('Proveedores',$nProv,'fa-truck','bg-yellow','proveedor.php'),
 array('Clientes',$nCli,'fa-users','bg-red','cliente.php'),
 array('Compras',$nIng,'fa-download','bg-purple','ingreso.php'),
 array('Ventas',$nVen,'fa-shopping-cart','bg-blue','venta.php')
);
foreach($cards as $c): ?>
<div class="col-lg-4 col-xs-6"><div class="small-box <?php echo $c[3]; ?>">
<div class="inner"><h3><?php echo number_format($c[1]); ?></h3><p><?php echo $c[0]; ?></p></div>
<div class="icon"><i class="fa <?php echo $c[2]; ?>"></i></div>
<a href="<?php echo $c[4]; ?>" class="small-box-footer">Abrir módulo <i class="fa fa-arrow-circle-right"></i></a>
</div></div>
<?php endforeach; ?>
</div>
<div class="row">
<div class="col-md-6"><div class="box box-success"><div class="box-header"><h3 class="box-title">Inventario</h3></div>
<div class="box-body"><h3><?php echo number_format($stock); ?> unidades</h3><p>Stock total de artículos activos.</p></div></div></div>
<div class="col-md-6"><div class="box box-primary"><div class="box-header"><h3 class="box-title">Resumen económico</h3></div>
<div class="box-body"><p><b>Total de compras:</b> S/ <?php echo number_format($sumComp,2); ?></p><p><b>Total de ventas:</b> S/ <?php echo number_format($sumVen,2); ?></p></div></div></div>
</div>
</section></div>
<?php require 'footer.php'; ?>
