<?php require 'header.php'; ?>
<div class="content-wrapper"><section class="content"><div class="row"><div class="col-md-12"><div class="box">
<div class="box-header with-border"><h1 class="box-title">Categorías <button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1></div>
<div class="panel-body table-responsive" id="listadoregistros"><table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
<thead><tr><th>Opciones</th><th>Nombre</th><th>Descripción</th><th>Estado</th></tr></thead><tbody></tbody>
<tfoot><tr><th>Opciones</th><th>Nombre</th><th>Descripción</th><th>Estado</th></tr></tfoot></table></div>
<div class="panel-body" id="formularioregistros" style="display:none;height:400px"><form id="formulario" method="POST">
<div class="form-group col-md-6"><label>Nombre:</label><input type="hidden" id="idcategoria" name="idcategoria"><input class="form-control" type="text" id="nombre" name="nombre" maxlength="50" required placeholder="Nombre"></div>
<div class="form-group col-md-6"><label>Descripción:</label><input class="form-control" type="text" id="descripcion" name="descripcion" maxlength="256" placeholder="Descripción"></div>
<div class="form-group col-md-12"><button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button> <button class="btn btn-danger" type="button" onclick="cancelarform()"><i class="fa fa-arrow-left"></i> Cancelar</button></div>
</form></div></div></div></div></section></div>
<?php require 'footer.php'; ?>
<script type="text/javascript" src="scripts/categoria.js"></script>
