function validarUsuario()
{
	var passwdOperaciones = document.getElementById('tPasswordOperaciones'),
		passwdVerificador = document.getElementById('tPasswordVerificador'),
		btnGuardar = document.getElementById('btnGuardar'),
		btnValidar = document.getElementById('btnValidar');
	
	if(passwdOperaciones.value == passwdVerificador.value)
		{
			btnGuardar.disabled = false;
            passwdOperaciones.style.display = 'none';
            btnValidar.style.display = 'none';
		}
}

function fnRedireccionar(seccion)
{
	window.location = seccion;
}

function cerrarSesion()
{
	if(confirm("Realmente deseas salir?"))
		{
			window.location="/logout/";
		}
}

function activarValidacion()
{
    document.getElementById('tPasswordOperaciones').style.display = 'inline';
    
    document.getElementById('tPasswordOperaciones').focus();
}

function consultarFecha()
{
	var formulario = document.getElementById('datos');
    
			formulario.submit();
	
}



function consultarDetalle(codigo)
      {
          document.getElementById('eCodEvento').value=codigo;
          
          var obj = $('#consDetalle').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
           $('#resDetalle').modal('show'); 
          
          $.ajax({
              type: "POST",
              url: "/cla/cons-deta.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  
                  
                 
                  document.getElementById('detalleEvento').innerHTML = data.detalle;
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });    
          
      }

      
      function cargarTransporte(codigo)
      {
          document.getElementById('eCodEvento').value=codigo;
          document.getElementById('eCodEventoCarga').value=codigo;
          
          document.getElementById('eCodCamioneta').value="";
          
          var obj = $('#consDetalle').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
           $('#detCarga').modal('show'); 
          
          $.ajax({
              type: "POST",
              url: "/cla/deta-reg.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  
                  
                 
                  document.getElementById('detalleCarga').innerHTML = data.detalle;
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
             
          
      }
    
      function nvaTran()
      {
          var obj = $('#nvaTran').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
          $.ajax({
              type: "POST",
              url: "/cla/nva-tran.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  if(data.exito==1)
                  {
                      $('#resExito').modal('show');
                      setTimeout(function(){ $('#resExito').modal('hide'); }, 3000);
                  }
                  else
                      {
                          var mensaje="";
                          for(var i=0;i<data.errores.length;i++)
                     {
                         mensaje += "-"+data.errores[i]+"\n";
                     }
                          alert("Error al procesar la solicitud.\n<-Valide la siguiente informacion->\n\n"+mensaje);
                         
                      }
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
          
      }
            
      function nvaOper()
      {
          var obj = $('#nvaOperador').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
          $.ajax({
              type: "POST",
              url: "/cla/nva-oper.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  if(data.exito==1)
                  {
                      $('#resExito').modal('show');
                      setTimeout(function(){ $('#resExito').modal('hide'); }, 3000);
                  }
                  else
                      {
                          var mensaje="";
                          for(var i=0;i<data.errores.length;i++)
                     {
                         mensaje += "-"+data.errores[i]+"\n";
                     }
                          alert("Error al procesar la solicitud.\n<-Valide la siguiente informacion->\n\n"+mensaje);
                         
                      }
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
          
      }

/*Asignaciones*/
      function asignarParametro(codigo,nombre)
      {
          document.getElementById('eCodCliente').value = codigo;
          document.getElementById('tNombreCliente').value = nombre;
          document.getElementById('tNombreCliente').style.display = 'inline';
          document.getElementById('asignarCliente').style.display = 'inline';
          document.getElementById('cot1').style.display = 'inline';
          document.getElementById('cot2').style.display = 'inline';
          document.getElementById('cot3').style.display = 'inline';
          var tblClientes = document.getElementById('mostrarTabla');
          if(tblClientes)
          {
          tblClientes.style.display='none';
          }
      }
      
      function verMisClientes()
      {
          $('#misClientes').modal({
                show: 'false'
            });
      }
      
      function agregarTransaccion(codigo)
      {
          document.getElementById('eCodEventoTransaccion').value = codigo;
      }
            
      function nuevaTransaccion(codigo)
      {
          document.getElementById('eCodEventoTransaccion').value = codigo;
          $('#myModal').modal('show');
      }
      
      function agregarOperador(codigo)
      {
          document.getElementById('eCodEventoOperador').value = codigo;
      }
            
    function asignarFecha(fecha,etiqueta)
      {
          document.getElementById('fhFechaConsulta').value=fecha;
          document.getElementById('tFechaConsulta').innerHTML = '<br><h2>'+etiqueta+'</h2>';
          consultarFecha();
      }
            
    function cambiarFechaEvento(mes,anio)
      {
          document.getElementById('nvaFecha').value=mes+'-'+anio;
          
          var obj = $('#datos').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
          $.ajax({
              type: "POST",
              url: "/inc/cal-cot.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  document.getElementById('calendario').innerHTML = data.calendario;
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });
          
      }
            
    function asignarFechaEvento(fecha,etiqueta,codigo)
      {
          document.getElementById('fhFechaEvento').value=fecha;
          document.getElementById('tFechaConsulta').innerHTML = '<br><h2>'+etiqueta+'</h2>';
      }
            
    function validarCarga()
      {
          var cmbTotal = document.querySelectorAll("[id^=eCodInventario]"),
              eCodCamioneta = document.getElementById('eCodCamioneta'),
              clickeado = 0;
          
          cmbTotal.forEach(function(nodo){
            if(nodo.checked==true)
                { clickeado++;}
        });
          
          if(clickeado==cmbTotal.length && eCodCamioneta.value>0)
              { document.getElementById('guardarCarga').style.display = 'inline'; }
          else
              { document.getElementById('guardarCarga').style.display = 'none'; }
      }
            
    function registrarCarga()
        {
            $('#detCarga').modal('hide');
            
            var obj = $('#carga').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
          $.ajax({
              type: "POST",
              url: "/cla/reg-carga-eve.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  if(data.exito==1)
                  {
                      
                      $('#resExito').modal('show');
                      setTimeout(function(){ $('#resExito').modal('hide'); consultarFecha(); }, 3000);
                      
                  }
                  else
                      {
                          $('#detCarga').modal('show');
                          var mensaje="";
                          for(var i=0;i<data.errores.length;i++)
                     {
                         mensaje += "-"+data.errores[i]+"\n";
                     }
                          alert("Error al procesar la solicitud.\n<-Valide la siguiente informacion->\n\n"+mensaje);
                         
                      }
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });    
        }
        
         function buscarSubclasificacion()
        {
          var obj = $('#datos').serializeJSON();
          var jsonString = JSON.stringify(obj);
          
          $.ajax({
              type: "POST",
              url: "/que/buscar-subclasificaciones.php",
              data: jsonString,
              contentType: "application/json; charset=utf-8",
              dataType: "json",
              success: function(data){
                  document.getElementById('eCodSubclasificacion').innerHTML = data.valores;
              },
              failure: function(errMsg) {
                  alert('Error al enviar los datos.');
              }
          });    
        }

//eliminaciones
function deleteRow(rowid,tabla)  {   
    var row = document.getElementById(rowid);
    row.parentNode.removeChild(row);
        
        if(tabla=="extras")
            {
               var x = document.getElementById("extras").rows.length;
                if(x<2)
                    {
                        agregarFilaExtra(0);
                    }
            }
        
        calcular();
}


// para cotizaciones
function validarPiezas(prefijo)
{
    var eMaxPiezas  =   document.getElementById(prefijo+'-eMaxPiezas'),
        ePiezas     =   document.getElementById(prefijo+'-ePiezas');
    
    if(parseInt(ePiezas.value)>parseInt(eMaxPiezas.value))
        { 
            alert("El máximo permitido es de "+eMaxPiezas.value+" unidades"); 
            ePiezas.value="";
        }
}

function validarExtra(indice)
    {
        var tDescripcion    =   document.getElementById('extra'+indice+'-tDescripcion'),
            dImporte        =   document.getElementById('extra'+indice+'-dImporte'),
            nIndice         =   parseInt(indice)+1;
        
        if(tDescripcion.value && dImporte.value)
            {
                agregarFilaExtra(nIndice);    
            }
    }
    
function agregarFilaExtra(indice)
    {
        var x = document.getElementById("extras").rows.length;
        
        var tExtra = document.getElementById('extra'+indice+'-tDescripcion');
        if(typeof tExtra != "undefined")  
        {
           
    var table = document.getElementById("extras");
    var row = table.insertRow(x);
    row.id="ext"+(indice);
    row.innerHTML = '<td><i class="far fa-trash-alt" onclick="deleteRow(\'ext'+indice+'\',\'extras\')"></i></td>';
    row.innerHTML += '<td><input type="checkbox" id="extra'+indice+'-bSuma" name="extra['+indice+'][bSuma]" value="1" onclick="calcular();"></td><td><input type="text" class="form-control" id="extra'+indice+'-tDescripcion" name="extra['+indice+'][tDescripcion]" onkeyup="validarExtra('+indice+')"></td>';
    row.innerHTML += '<td><input type="text" class="form-control" id="extra'+indice+'-dImporte" name="extra['+indice+'][dImporte]" onkeyup="validarExtra('+indice+')"></td>';
        }
        
    calcular();
        
    }

//tienda

function validarTienda(indice)
    {
        var eCodTienda    =   document.getElementById('eCodTienda'+indice),
            nIndice         =   parseInt(indice)+1;
        
        if(eCodTienda.value)
            {
                agregarFilaTienda(nIndice);    
            }
    }
    
function agregarFilaTienda(indice)
    {
        var x = document.getElementById("tiendas").rows.length;
        
        
        var eCodTienda = document.getElementById('eCodTienda'+indice);
        if(eCodTienda)
            {}
        else
        {
           
    var table = document.getElementById("tiendas");
    var row = table.insertRow(x);
    row.id="tie"+(indice);
    row.innerHTML = '<td><i class="far fa-trash-alt" onclick="deleteRow(\'tie'+indice+'\',\'tiendas\')"></i></td>';
    row.innerHTML += '<td><input type="hidden" id="eCodTienda'+indice+'" name="tiendas['+indice+'][eCodTienda]"><input type="text" class="form-control" id="tTienda'+indice+'" name="tiendas['+indice+'][tTienda]" onkeyup="agregarTienda('+indice+')" onkeypress="agregarTienda('+indice+')" onblur="validarTienda('+indice+')"></td>';
        }
        
    }
