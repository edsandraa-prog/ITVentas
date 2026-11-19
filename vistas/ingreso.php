<?php
$titulo='Ingresos / Compras';
$modulo='ingreso';
$campos=array(array('name'=>'idproveedor','label'=>'Proveedor (ID)','type'=>'number','required'=>true,'min'=>1),array('name'=>'idarticulo','label'=>'Artículo (ID)','type'=>'number','required'=>true,'min'=>1),array('name'=>'cantidad','label'=>'Cantidad','type'=>'number','required'=>true,'min'=>1),array('name'=>'precio','label'=>'Precio unitario','type'=>'number','required'=>true,'min'=>0,'step'=>'0.01'),array('name'=>'tipo_comprobante','label'=>'Comprobante','options'=>array('Boleta'=>'Boleta','Factura'=>'Factura','Ticket'=>'Ticket')),array('name'=>'num_comprobante','label'=>'N° comprobante','max'=>30));
require 'modulo.php';
?>