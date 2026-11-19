<?php
require_once "../config/conexion.php";
header('Content-Type: application/json; charset=utf-8');
$mod = isset($_REQUEST['modulo']) ? $_REQUEST['modulo'] : '';
$op = isset($_GET['op']) ? $_GET['op'] : '';
$allowed = array(
 'proveedor'=>array('table'=>'proveedor','id'=>'idproveedor','fields'=>array('tipo_documento','num_documento','nombre','telefono','email','direccion'),'active'=>'condicion'),
 'cliente'=>array('table'=>'cliente','id'=>'idcliente','fields'=>array('tipo_documento','num_documento','nombre','telefono','email','direccion'),'active'=>'condicion'),
 'usuario'=>array('table'=>'usuario','id'=>'idusuario','fields'=>array('nombre','login','clave','email','rol'),'active'=>'condicion'),
 'permiso'=>array('table'=>'permiso','id'=>'idpermiso','fields'=>array('rol','escritorio','almacen','compras','ventas','acceso','consultas'),'active'=>null),
 'ingreso'=>array('table'=>'ingreso','id'=>'idingreso','fields'=>array(),'active'=>'estado'),
 'venta'=>array('table'=>'venta','id'=>'idventa','fields'=>array(),'active'=>'estado')
);
if(!isset($allowed[$mod])) { http_response_code(400); echo json_encode(array('error'=>'Módulo no válido')); exit; }
$c=$allowed[$mod];
function escv($v){ return trim((string)$v); }
function bind_exec($conexion,$sql,$types,$vals){
 $st=$conexion->prepare($sql); if(!$st) return false;
 if($types!==''){
  $refs=array();
  foreach($vals as $k=>&$v){ $refs[$k]=&$v; }
  call_user_func_array(array($st,'bind_param'),array_merge(array($types),$refs));
 }
 $ok=$st->execute(); $st->close(); return $ok;
}
if($op==='listar'){
 $rows=array();
 if($mod==='ingreso'){
  $q=$conexion->query("SELECT i.idingreso,i.fecha,p.nombre AS tercero,i.tipo_comprobante,i.num_comprobante,i.total,i.estado,a.nombre AS articulo,d.cantidad FROM ingreso i JOIN proveedor p ON p.idproveedor=i.idproveedor JOIN detalle_ingreso d ON d.idingreso=i.idingreso JOIN articulo a ON a.idarticulo=d.idarticulo ORDER BY i.idingreso DESC");
  while($r=$q->fetch_assoc()) $rows[]=array('id'=>$r['idingreso'],'active'=>1,'values'=>array($r['idingreso'],$r['fecha'],$r['tercero'],$r['articulo'],$r['cantidad'],$r['tipo_comprobante'].' '.$r['num_comprobante'],$r['total'],$r['estado']),'data'=>array());
 } elseif($mod==='venta'){
  $q=$conexion->query("SELECT v.idventa,v.fecha,c.nombre AS tercero,v.tipo_comprobante,v.num_comprobante,v.total,v.estado,a.nombre AS articulo,d.cantidad FROM venta v JOIN cliente c ON c.idcliente=v.idcliente JOIN detalle_venta d ON d.idventa=v.idventa JOIN articulo a ON a.idarticulo=d.idarticulo ORDER BY v.idventa DESC");
  while($r=$q->fetch_assoc()) $rows[]=array('id'=>$r['idventa'],'active'=>1,'values'=>array($r['idventa'],$r['fecha'],$r['tercero'],$r['articulo'],$r['cantidad'],$r['tipo_comprobante'].' '.$r['num_comprobante'],$r['total'],$r['estado']),'data'=>array());
 } else {
  $q=$conexion->query("SELECT * FROM ".$c['table']." ORDER BY ".$c['id']." DESC");
  while($r=$q->fetch_assoc()){
   $vals=array(); foreach($c['fields'] as $f) $vals[]=($f==='clave'?'********':$r[$f]);
   $active=$c['active'] ? (isset($r[$c['active']]) && ($r[$c['active']]=='1'||$r[$c['active']]=='Aceptado')) : 1;
   $rows[]=array('id'=>$r[$c['id']],'active'=>$active,'values'=>$vals,'data'=>$r);
  }
 }
 echo json_encode($rows); exit;
}
if($op==='guardar'){
 if($mod==='ingreso'||$mod==='venta'){
  $third=$mod==='ingreso'?'idproveedor':'idcliente';
  $table=$mod; $idfield=$mod==='ingreso'?'idingreso':'idventa';
  $detail=$mod==='ingreso'?'detalle_ingreso':'detalle_venta';
  $thirdId=(int)($_POST[$third]??0);$art=(int)($_POST['idarticulo']??0);$qty=(int)($_POST['cantidad']??0);
  $price=(float)($_POST['precio']??0);$tipo=escv($_POST['tipo_comprobante']??'Boleta');$num=escv($_POST['num_comprobante']??'');
  if($thirdId<1||$art<1||$qty<1||$price<0){http_response_code(400);echo json_encode('Completa los datos correctamente.');exit;}
  $conexion->begin_transaction();
  try{
   $st=$conexion->prepare("SELECT stock FROM articulo WHERE idarticulo=? AND condicion=1 FOR UPDATE");$st->bind_param("i",$art);$st->execute();$res=$st->get_result();$ar=$res->fetch_assoc();$st->close();
   if(!$ar) throw new Exception("El artículo no existe o está inactivo.");
   if($mod==='venta' && (int)$ar['stock']<$qty) throw new Exception("Stock insuficiente. Disponible: ".$ar['stock']);
   $existsTable=$mod==='ingreso'?'proveedor':'cliente';
   $st=$conexion->prepare("SELECT 1 FROM ".$existsTable." WHERE ".($mod==='ingreso'?'idproveedor':'idcliente')."=? AND condicion=1");$st->bind_param("i",$thirdId);$st->execute();$ok=$st->get_result()->num_rows>0;$st->close();
   if(!$ok) throw new Exception("Proveedor/cliente no encontrado o inactivo.");
   $total=$qty*$price;
   $st=$conexion->prepare("INSERT INTO $table ($third, tipo_comprobante, num_comprobante,total,estado) VALUES (?,?,?,?, 'Aceptado')");
   $st->bind_param("issd",$thirdId,$tipo,$num,$total);$st->execute();$newid=$conexion->insert_id;$st->close();
   $st=$conexion->prepare("INSERT INTO $detail ($idfield,idarticulo,cantidad,precio) VALUES (?,?,?,?)");$st->bind_param("iiid",$newid,$art,$qty,$price);$st->execute();$st->close();
   $delta=$mod==='ingreso'?$qty:-$qty;
   $st=$conexion->prepare("UPDATE articulo SET stock=stock+? WHERE idarticulo=?");$st->bind_param("ii",$delta,$art);$st->execute();$st->close();
   $conexion->commit();echo json_encode($mod==='ingreso'?"Compra registrada. Stock aumentado en $qty unidades.":"Venta registrada. Stock descontado en $qty unidades.");exit;
  }catch(Exception $e){$conexion->rollback();http_response_code(400);echo json_encode($e->getMessage());exit;}
 }
 $id=(int)($_POST['id']??0);$vals=array();
 foreach($c['fields'] as $f) $vals[$f]=escv($_POST[$f]??'');
 if($mod==='usuario' && $id===0 && $vals['clave']===''){http_response_code(400);echo json_encode('La contraseña es obligatoria al crear un usuario.');exit;}
 if($mod==='usuario' && $vals['clave']!=='') $vals['clave']=password_hash($vals['clave'],PASSWORD_DEFAULT);
 if($mod==='usuario' && $id>0 && $vals['clave']==='') unset($vals['clave']);
 if($id>0){
  $sets=array();$types='';$args=array();
  foreach($vals as $f=>$v){$sets[]="$f=?";$types.='s';$args[]=$v;}
  $types.='i';$args[]=$id;
  $sql="UPDATE ".$c['table']." SET ".implode(',',$sets)." WHERE ".$c['id']."=?";
 } else {
  $fs=array_keys($vals);$sql="INSERT INTO ".$c['table']." (".implode(',',$fs).") VALUES (".implode(',',array_fill(0,count($fs),'?')).")";$types=str_repeat('s',count($vals));$args=array_values($vals);
 }
 $ok=bind_exec($conexion,$sql,$types,$args);
 if(!$ok){http_response_code(400);echo json_encode('No se pudo guardar. Verifica que el documento o usuario no estén repetidos.');}
 else echo json_encode($id>0?'Registro actualizado correctamente.':'Registro guardado correctamente.');
 exit;
}
if($op==='estado'){
 if(!$c['active'] || $mod==='ingreso'||$mod==='venta'){http_response_code(400);echo json_encode('No se puede cambiar el estado de una operación registrada.');exit;}
 $id=(int)($_POST['id']??0);$active=(int)($_POST['active']??0);
 $ok=bind_exec($conexion,"UPDATE ".$c['table']." SET ".$c['active']."=? WHERE ".$c['id']."=?","ii",array($active,$id));
 echo json_encode($ok?'Estado actualizado.':'No se pudo actualizar.');exit;
}
http_response_code(400);echo json_encode('Operación no válida.');
