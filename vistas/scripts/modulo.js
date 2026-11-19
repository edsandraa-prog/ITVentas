(function(){
 const cfg=window.MODULO_CONFIG, mod=cfg.modulo;
 let dt=null;
 function msg(t){ alert(t); }
 function showForm(show){
   document.getElementById('listado').style.display=show?'none':'block';
   document.getElementById('formularioBox').style.display=show?'block':'none';
   document.getElementById('btnNuevo').style.display=show?'none':'inline-block';
 }
 window.nuevoRegistro=function(){
   document.getElementById('formModulo').reset();
   document.getElementById('id').value='';
   document.getElementById('notaModulo').textContent =
     (mod==='ingreso'?'Registre una compra de un artículo. El stock aumentará automáticamente.':
      mod==='venta'?'Registre una venta de un artículo. El sistema verificará y descontará el stock.':'');
   showForm(true);
 };
 window.cancelarRegistro=function(){showForm(false);};
 function load(){
   fetch('../ajax/modulos.php?op=listar&modulo='+encodeURIComponent(mod))
    .then(r=>r.json()).then(rows=>{
      const thead=document.querySelector('#tablaModulo thead tr');
      const tbody=document.querySelector('#tablaModulo tbody');
      tbody.innerHTML='';
      rows.forEach(row=>{
        let tr=document.createElement('tr');
        row.values.forEach(v=>{let td=document.createElement('td');td.textContent=v??'';tr.appendChild(td);});
        let td=document.createElement('td');
        if(!cfg.transaccion){
          td.innerHTML='<button class="btn btn-warning btn-xs edit"><i class="fa fa-pencil"></i></button> '+
            (mod==='permiso'?'':'<button class="btn btn-danger btn-xs toggle"><i class="fa fa-refresh"></i></button>');
          td.querySelector('.edit').addEventListener('click',()=>edit(row));
          const toggleBtn=td.querySelector('.toggle');
          if(toggleBtn) toggleBtn.addEventListener('click',()=>toggle(row.id,row.active));
        } else { td.textContent='Registrado'; }
        tr.appendChild(td);tbody.appendChild(tr);
      });
    }).catch(e=>msg('No se pudo cargar el listado: '+e));
 }
 function edit(row){
   if(cfg.transaccion){msg('Las operaciones registradas se muestran en consultas. No se editan para proteger el stock.');return;}
   document.getElementById('id').value=row.id;
   cfg.campos.forEach(c=>{
     let el=document.getElementById(c.name);
     if(el) el.value=(c.name==='clave'?'':(row.data[c.name]??''));
   });
   showForm(true);
 }
 function toggle(id,active){
   if(!confirm('¿Desea cambiar el estado de este registro?'))return;
   const fd=new FormData();fd.append('modulo',mod);fd.append('id',id);fd.append('active',active?0:1);
   fetch('../ajax/modulos.php?op=estado',{method:'POST',body:fd}).then(r=>r.text()).then(t=>{try{msg(JSON.parse(t));}catch(e){msg(t);}load();});
 }
 document.getElementById('formModulo').addEventListener('submit',function(e){
   e.preventDefault();
   const fd=new FormData(this);fd.append('modulo',mod);
   fetch('../ajax/modulos.php?op=guardar',{method:'POST',body:fd})
    .then(async r=>({ok:r.ok,text:await r.text()}))
    .then(x=>{let message=x.text;try{message=JSON.parse(x.text);}catch(e){} msg(message);if(x.ok){this.reset();showForm(false);load();}})
    .catch(e=>msg('Error: '+e));
 });
 load();
})();
