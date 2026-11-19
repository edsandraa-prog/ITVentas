<?php
$titulo='Proveedores';
$modulo='proveedor';
$campos=array(array('name'=>'tipo_documento','label'=>'Tipo documento','options'=>array('RUC'=>'RUC','DNI'=>'DNI','CE'=>'Carné de extranjería')),array('name'=>'num_documento','label'=>'N° documento','required'=>true,'max'=>20),array('name'=>'nombre','label'=>'Razón social / Nombre','required'=>true,'max'=>120),array('name'=>'telefono','label'=>'Teléfono','max'=>30),array('name'=>'email','label'=>'Correo electrónico','type'=>'email','max'=>120),array('name'=>'direccion','label'=>'Dirección','max'=>180));
require 'modulo.php';
?>