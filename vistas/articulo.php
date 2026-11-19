<?php
require 'header.php';
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row"><div class="col-md-12"><div class="box">
      <div class="box-header with-border">
        <h1 class="box-title">Listado de Artículos
          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo
          </button>
        </h1>
      </div>
      <div class="panel-body table-responsive" id="listadoregistros">
        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
          <thead><tr>
            <th>Opciones</th><th>Nombre</th><th>Categoría</th><th>Código</th>
            <th>Stock</th><th>Imagen</th><th>Estado</th>
          </tr></thead>
          <tbody></tbody>
          <tfoot><tr>
            <th>Opciones</th><th>Nombre</th><th>Categoría</th><th>Código</th>
            <th>Stock</th><th>Imagen</th><th>Estado</th>
          </tr></tfoot>
        </table>
      </div>

      <div class="panel-body" id="formularioregistros" style="display:none;">
        <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-md-6">
              <label>Nombre:</label>
              <input type="hidden" name="idarticulo" id="idarticulo">
              <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" required>
            </div>
            <div class="form-group col-md-6">
              <label>Categoría:</label>
              <select class="form-control" name="idcategoria" id="idcategoria" required></select>
            </div>
            <div class="form-group col-md-6">
              <label>Código:</label>
              <input class="form-control" type="text" name="codigo" id="codigo" maxlength="50" required>
              <button type="button" class="btn btn-default btn-sm" onclick="generarCodigo()">
                <i class="fa fa-barcode"></i> Generar código
              </button>
              <svg id="barcode"></svg>
            </div>
            <div class="form-group col-md-6">
              <label>Stock:</label>
              <input class="form-control" type="number" name="stock" id="stock" min="0" step="1" value="0" required>
            </div>
            <div class="form-group col-md-6">
              <label>Descripción:</label>
              <textarea class="form-control" name="descripcion" id="descripcion" maxlength="500" rows="3"></textarea>
            </div>
            <div class="form-group col-md-6">
              <label>Imagen del artículo:</label>
              <input class="form-control" type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp">
              <input type="hidden" name="imagenactual" id="imagenactual">
              <br><img src="" id="imagenmuestra" style="max-width:150px;max-height:150px;display:none;" alt="Vista previa">
            </div>
          </div>
          <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
          <button class="btn btn-danger" type="button" onclick="cancelarform()"><i class="fa fa-arrow-left"></i> Cancelar</button>
        </form>
      </div>
    </div></div></div>
  </section>
</div>
<?php require 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script type="text/javascript" src="scripts/articulo.js"></script>
