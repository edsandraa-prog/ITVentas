<?php require 'header.php'; ?>
<div class="content-wrapper"><section class="content-header"><h1>Ayuda del sistema</h1></section><section class="content">
<div class="box"><div class="box-body">
<h3>Guía rápida de ITVentas</h3>
<ol>
<li><b>Almacén:</b> registre primero las categorías y luego los artículos. Anote el ID del artículo para usarlo en compras y ventas.</li>
<li><b>Proveedores:</b> registre los datos del proveedor antes de realizar un ingreso.</li>
<li><b>Ingresos:</b> indique el ID del proveedor, ID del artículo, cantidad y precio unitario. Al guardar, el stock aumenta.</li>
<li><b>Clientes:</b> registre al cliente antes de realizar una venta.</li>
<li><b>Ventas:</b> indique el ID del cliente, ID del artículo, cantidad y precio unitario. El sistema valida el stock y lo descuenta.</li>
<li><b>Consultas:</b> filtre compras y ventas por fecha para revisar los movimientos.</li>
<li><b>Acceso:</b> cree usuarios y configure permisos por rol. En esta versión los permisos se registran como configuración; no son todavía un control de seguridad de sesión.</li>
</ol>
<p><b>Importante:</b> esta versión registra una línea de artículo por compra o venta. Para operaciones con varios productos, se requiere ampliar el detalle de la pantalla de transacción.</p>
</div></div></section></div>
<?php require 'footer.php'; ?>
