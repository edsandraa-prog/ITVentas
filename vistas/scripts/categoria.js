var tabla;
function init(){
 mostrarform(false); listar();
 $("#formulario").on("submit",function(e){guardaryeditar(e);});
}
function limpiar(){ $("#nombre,#descripcion,#idcategoria").val(""); }
function mostrarform(flag){
 limpiar();
 if(flag){$("#listadoregistros").hide();$("#formularioregistros").show();$("#btnGuardar").prop("disabled",false);$("#btnagregar").hide();}
 else{$("#listadoregistros").show();$("#formularioregistros").hide();$("#btnagregar").show();}
}
function cancelarform(){mostrarform(false);}
function listar(){
 tabla=$("#tbllistado").DataTable({processing:true,ajax:{url:"../ajax/categoria.php?op=listar",type:"GET",dataType:"json",error:function(x){console.error(x.responseText);}},
 destroy:true,pageLength:10,order:[[1,"asc"]],dom:"Bfrtip",buttons:["copyHtml5","excelHtml5","csvHtml5","pdf"]});
}
function guardaryeditar(e){
 e.preventDefault();$("#btnGuardar").prop("disabled",true);
 $.ajax({url:"../ajax/categoria.php?op=guardaryeditar",type:"POST",data:new FormData($("#formulario")[0]),contentType:false,processData:false,
 success:function(r){bootbox.alert(r,function(){mostrarform(false);tabla.ajax.reload(null,false);});},
 error:function(x){bootbox.alert("No se pudo guardar: "+x.responseText);$("#btnGuardar").prop("disabled",false);}});
}
function mostrar(id){
 $.post("../ajax/categoria.php?op=mostrar",{idcategoria:id},function(r){var d=JSON.parse(r);mostrarform(true);$("#idcategoria").val(d.idcategoria);$("#nombre").val(d.nombre);$("#descripcion").val(d.descripcion);});
}
function desactivar(id){bootbox.confirm("¿Está seguro de desactivar la categoría?",function(ok){if(ok)$.post("../ajax/categoria.php?op=desactivar",{idcategoria:id},function(r){bootbox.alert(r);tabla.ajax.reload(null,false);});});}
function activar(id){bootbox.confirm("¿Está seguro de activar la categoría?",function(ok){if(ok)$.post("../ajax/categoria.php?op=activar",{idcategoria:id},function(r){bootbox.alert(r);tabla.ajax.reload(null,false);});});}
$(document).ready(init);
