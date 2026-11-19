<?php
$titulo='Permisos por rol';
$modulo='permiso';
$campos=array(array('name'=>'rol','label'=>'Rol','required'=>true,'max'=>30),array('name'=>'escritorio','label'=>'Escritorio','options'=>array('1'=>'Permitido','0'=>'Denegado')),array('name'=>'almacen','label'=>'Almacén','options'=>array('1'=>'Permitido','0'=>'Denegado')),array('name'=>'compras','label'=>'Compras','options'=>array('1'=>'Permitido','0'=>'Denegado')),array('name'=>'ventas','label'=>'Ventas','options'=>array('1'=>'Permitido','0'=>'Denegado')),array('name'=>'acceso','label'=>'Acceso','options'=>array('1'=>'Permitido','0'=>'Denegado')),array('name'=>'consultas','label'=>'Consultas','options'=>array('1'=>'Permitido','0'=>'Denegado')));
require 'modulo.php';
?>