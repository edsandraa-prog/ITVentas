var tabla;

function init() {
  mostrarform(false);
  listar();
  cargarCategorias();
  $("#formulario").on("submit", function(e) { guardaryeditar(e); });
  $("#imagen").on("change", function() {
    if (this.files && this.files[0]) {
      var lector = new FileReader();
      lector.onload = function(e) {
        $("#imagenmuestra").attr("src", e.target.result).show();
      };
      lector.readAsDataURL(this.files[0]);
    }
  });
}

function limpiar() {
  $("#idarticulo,#nombre,#codigo,#descripcion,#imagenactual").val("");
  $("#stock").val("0");
  $("#imagen").val("");
  $("#imagenmuestra").attr("src", "").hide();
  $("#barcode").empty();
  $("#formulario")[0].reset();
  $("#idarticulo").val("");
  $("#stock").val("0");
}

function mostrarform(flag) {
  limpiar();
  if (flag) {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnGuardar").prop("disabled", false);
    $("#btnagregar").hide();
    cargarCategorias();
  } else {
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();
  }
}

function cancelarform() { mostrarform(false); }

function cargarCategorias(idSeleccionado) {
  $.get("../ajax/articulo.php?op=selectCategoria", function(html) {
    $("#idcategoria").html(html);
    if (idSeleccionado) $("#idcategoria").val(idSeleccionado);
  }).fail(function(xhr) {
    console.error(xhr.responseText);
    bootbox.alert("No se pudieron cargar las categorías. Registra primero una categoría.");
  });
}

function listar() {
  tabla = $("#tbllistado").DataTable({
    processing: true,
    serverSide: false,
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf"],
    ajax: {
      url: "../ajax/articulo.php?op=listar",
      type: "GET",
      dataType: "json",
      error: function(xhr) { console.error(xhr.responseText); }
    },
    destroy: true,
    pageLength: 10,
    order: [[1, "asc"]]
  });
}

function guardaryeditar(e) {
  e.preventDefault();
  $("#btnGuardar").prop("disabled", true);
  var datos = new FormData($("#formulario")[0]);
  $.ajax({
    url: "../ajax/articulo.php?op=guardaryeditar",
    type: "POST",
    data: datos,
    contentType: false,
    processData: false,
    success: function(respuesta) {
      bootbox.alert(respuesta, function() {
        mostrarform(false);
        tabla.ajax.reload(null, false);
      });
    },
    error: function(xhr) {
      bootbox.alert("Error al guardar: " + xhr.responseText);
      $("#btnGuardar").prop("disabled", false);
    }
  });
}

function mostrar(idarticulo) {
  $.post("../ajax/articulo.php?op=mostrar", {idarticulo:idarticulo}, function(data) {
    var d = JSON.parse(data);
    mostrarform(true);
    $("#idarticulo").val(d.idarticulo);
    $("#nombre").val(d.nombre);
    $("#codigo").val(d.codigo);
    $("#stock").val(d.stock);
    $("#descripcion").val(d.descripcion);
    $("#imagenactual").val(d.imagen);
    if (d.imagen) $("#imagenmuestra").attr("src", "../files/articulos/" + encodeURIComponent(d.imagen)).show();
    cargarCategorias(d.idcategoria);
    if (d.codigo && window.JsBarcode) JsBarcode("#barcode", d.codigo, {format:"CODE128",height:55});
  }).fail(function(xhr) { bootbox.alert("No se pudo cargar el artículo: " + xhr.responseText); });
}

function generarCodigo() {
  var codigo = String(Date.now()).slice(-12);
  $("#codigo").val(codigo);
  if (window.JsBarcode) JsBarcode("#barcode", codigo, {format:"CODE128",height:55});
}

function desactivar(id) {
  bootbox.confirm("¿Está seguro de desactivar este artículo?", function(ok) {
    if (ok) $.post("../ajax/articulo.php?op=desactivar", {idarticulo:id}, function(r) {
      bootbox.alert(r); tabla.ajax.reload(null, false);
    });
  });
}
function activar(id) {
  bootbox.confirm("¿Está seguro de activar este artículo?", function(ok) {
    if (ok) $.post("../ajax/articulo.php?op=activar", {idarticulo:id}, function(r) {
      bootbox.alert(r); tabla.ajax.reload(null, false);
    });
  });
}
$(document).ready(init);
