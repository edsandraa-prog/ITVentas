<?php
$titulo='Clientes';
$modulo='cliente';
$campos=array(array('name'=>'tipo_documento','label'=>'Tipo documento','options'=>array('DNI'=>'DNI','RUC'=>'RUC','CE'=>'Carné de extranjería')),array('name'=>'num_documento','label'=>'N° documento','required'=>true,'max'=>20),array('name'=>'nombre','label'=>'Nombre completo / Razón social','required'=>true,'max'=>120),array('name'=>'telefono','label'=>'Teléfono','max'=>30),array('name'=>'email','label'=>'Correo electrónico','type'=>'email','max'=>120),array('name'=>'direccion','label'=>'Dirección','max'=>180));
require 'modulo.php';
?>