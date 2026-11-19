<?php
$titulo='Usuarios';
$modulo='usuario';
$campos=array(array('name'=>'nombre','label'=>'Nombre completo','required'=>true,'max'=>120),array('name'=>'login','label'=>'Usuario de acceso','required'=>true,'max'=>60),array('name'=>'clave','label'=>'Contraseña (obligatoria al crear)','type'=>'password','max'=>100),array('name'=>'email','label'=>'Correo electrónico','type'=>'email','max'=>120),array('name'=>'rol','label'=>'Rol','options'=>array('Administrador'=>'Administrador','Vendedor'=>'Vendedor','Almacenero'=>'Almacenero')));
require 'modulo.php';
?>