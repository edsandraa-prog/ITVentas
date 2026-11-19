<?php
require_once 'header.php';
$titulo = isset($titulo) ? $titulo : 'Módulo';
$modulo = isset($modulo) ? $modulo : '';
$campos = isset($campos) ? $campos : array();
$transaccion = in_array($modulo, array('ingreso','venta'));
?>
<div class="content-wrapper">
<section class="content-header"><h1><?php echo htmlspecialchars($titulo); ?></h1></section>
<section class="content"><div class="box box-danger">
<div class="box-header with-border">
 <button class="btn btn-success" id="btnNuevo" onclick="nuevoRegistro()"><i class="fa fa-plus"></i> Agregar</button>
</div>
<div class="box-body" id="listado">
 <div class="table-responsive"><table id="tablaModulo" class="table table-bordered table-striped">
 <thead><tr>
 <?php if($transaccion): ?>
 <th>ID</th><th>Fecha</th><th>Proveedor / Cliente</th><th>Artículo</th><th>Cantidad</th><th>Comprobante</th><th>Total</th><th>Estado</th><th>Opciones</th>
 <?php else: foreach($campos as $c) echo '<th>'.htmlspecialchars($c['label']).'</th>'; ?><th>Estado / Opciones</th><?php endif; ?>
 </tr></thead>
 <tbody></tbody></table></div>
</div>
<div class="box-body" id="formularioBox" style="display:none">
 <form id="formModulo">
 <input type="hidden" name="id" id="id">
 <div class="row">
 <?php foreach($campos as $c): ?>
 <div class="form-group col-md-6">
 <label><?php echo htmlspecialchars($c['label']); ?></label>
 <?php if(isset($c['options'])): ?>
 <select class="form-control" name="<?php echo $c['name']; ?>" id="<?php echo $c['name']; ?>" <?php echo !empty($c['required'])?'required':''; ?>>
 <option value="">Seleccione</option>
 <?php foreach($c['options'] as $v=>$label) echo '<option value="'.htmlspecialchars((string)$v).'">'.htmlspecialchars($label).'</option>'; ?>
 </select>
 <?php else: ?>
 <input class="form-control" type="<?php echo isset($c['type'])?$c['type']:'text'; ?>" name="<?php echo $c['name']; ?>" id="<?php echo $c['name']; ?>" <?php echo isset($c['max'])?'maxlength="'.$c['max'].'"':''; ?> <?php echo !empty($c['required'])?'required':''; ?> <?php echo isset($c['min'])?'min="'.$c['min'].'"':''; ?> <?php echo isset($c['step'])?'step="'.$c['step'].'"':''; ?>>
 <?php endif; ?>
 </div>
 <?php endforeach; ?>
 </div>
 <button type="submit" class="btn btn-primary" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
 <button type="button" class="btn btn-danger" onclick="cancelarRegistro()"><i class="fa fa-times"></i> Cancelar</button>
 </form>
 <p class="help-block" id="notaModulo"></p>
</div>
</div></section></div>
<script>
window.MODULO_CONFIG = <?php echo json_encode(array('modulo'=>$modulo,'campos'=>$campos,'transaccion'=>$transaccion)); ?>;
</script>
<script src="scripts/modulo.js"></script>
<?php require 'footer.php'; ?>
