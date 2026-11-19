<?php
require_once "../config/conexion.php";
require 'header.php';
$desde=$_GET['desde']??date('Y-m-01'); $hasta=$_GET['hasta']??date('Y-m-d');
$st=$conexion->prepare("SELECT i.idingreso,i.fecha,p.nombre proveedor,i.tipo_comprobante,i.num_comprobante,a.nombre articulo,d.cantidad,d.precio,i.total FROM ingreso i JOIN proveedor p ON p.idproveedor=i.idproveedor JOIN detalle_ingreso d ON d.idingreso=i.idingreso JOIN articulo a ON a.idarticulo=d.idarticulo WHERE DATE(i.fecha) BETWEEN ? AND ? ORDER BY i.fecha DESC");
$st->bind_param("ss",$desde,$hasta);$st->execute();$rs=$st->get_result();
?>
<div class="content-wrapper"><section class="content-header"><h1>Consulta de Compras</h1></section><section class="content">
<div class="box"><div class="box-body">
<form class="form-inline" method="GET"><label>Desde</label> <input class="form-control" type="date" name="desde" value="<?php echo htmlspecialchars($desde); ?>"> <label>Hasta</label> <input class="form-control" type="date" name="hasta" value="<?php echo htmlspecialchars($hasta); ?>"> <button class="btn btn-primary">Consultar</button></form><hr>
<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>ID</th><th>Fecha</th><th>Proveedor</th><th>Artículo</th><th>Cantidad</th><th>Precio</th><th>Comprobante</th><th>Total</th></tr></thead><tbody>
<?php $sum=0;while($r=$rs->fetch_assoc()):$sum+=(float)$r['total']; ?>
<tr><td><?php echo $r['idingreso']; ?></td><td><?php echo htmlspecialchars($r['fecha']); ?></td><td><?php echo htmlspecialchars($r['proveedor']); ?></td><td><?php echo htmlspecialchars($r['articulo']); ?></td><td><?php echo $r['cantidad']; ?></td><td>S/ <?php echo number_format($r['precio'],2); ?></td><td><?php echo htmlspecialchars($r['tipo_comprobante'].' '.$r['num_comprobante']); ?></td><td>S/ <?php echo number_format($r['total'],2); ?></td></tr>
<?php endwhile; ?></tbody><tfoot><tr><th colspan="7" class="text-right">Total del periodo</th><th>S/ <?php echo number_format($sum,2); ?></th></tr></tfoot></table></div>
</div></div></section></div>
<?php $st->close(); require 'footer.php'; ?>
