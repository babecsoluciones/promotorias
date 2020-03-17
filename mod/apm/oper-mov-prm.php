<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");

$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$bValidar = (($_SESSION['sessionAdmin']['eCodPerfil']==3) ? true : false);

$tCampo = ($_SESSION['sessionAdmin']['eCodPerfil']==4 ? 'eCodPromotor' : 'eCodSupervisor');

$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];

$select = "SELECT
	ct.eCodTienda,
	ct.tNombre 
FROM
	CatTiendas ct
	INNER JOIN RelPromotoriasPromotores rt ON rt.eCodTienda = ct.eCodTienda 
WHERE
	1 =1 ".
($bValidar ? " AND $tCampo = $eCodUsuario" : " rt.eCodPromotoria = ".($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1']));
$rsTiendas = mysql_query($select);

$select = "SELECT * FROM CatTiposImagenes WHERE tCodEstatus = 'AC'";
$rsTiposImagenes = mysql_query($select);

$select = "SELECT DISTINCT cp.eCodProducto, cp.tNombre tProducto FROM RelPromotoriasPresentaciones rp INNER JOIN CatProductos cp ON cp.eCodProducto=rp.eCodProducto WHERE rp.eCodPromotoria = ".($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1'])." ORDER BY cp.eCodProducto ASC";
$rsProductos = mysql_query($select);

?>



    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="eCodPromotoria" id="eCodPromotoria" value="<?=($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1']);?>">
        <input type="hidden" name="eAccion" id="eAccion">
                            <div class="col-lg-12">
								
                                <div class="card col-lg-12">
                                    
                                    <div class="card-body card-block">
                                        <!--campos-->
                                        <div class="form-group">
              
           </div>
           <div class="form-group">
              <label>Tienda</label>
              <? if(!$_SESSION['sesionPromotoria']){ ?>
              <select id="eCodTienda" name="eCodTienda" class="form-control" onchange="consultarImagenes()">
                  <option value="">Seleccione...</option>
                  <? while($rTienda = mysql_fetch_array($rsTiendas)){ ?>
                  <option value="<?=$rTienda{'eCodTienda'};?>"><?=$rTienda{'tNombre'};?></option>
                  <? } ?>
              </select>
              <? }else{ ?>
              <input type="hidden" name="eCodTienda" id="eCodTienda" value="<?=$_SESSION['sesionPromotoria']['eCodTienda'];?>"><?=$_SESSION['sesionPromotoria']['tTienda'];?>
              <? } ?>
           </div>
           <div class="form-group">
              <label>Producto</label>
              <select id="eCodProducto" name="eCodProducto" class="form-control" onchange="consultarPresentaciones()">
                  <option value="">Seleccione...</option>
                  <? while($rImagen = mysql_fetch_array($rsProductos)){ ?>
                  <option value="<?=$rImagen{'eCodProducto'};?>"><?=$rImagen{'tProducto'};?></option>
                  <? } ?>
              </select>
           </div>
           <div class="form-group">
              <label>Presentacion</label>
              <select id="eCodPresentacion" name="eCodPresentacion" class="form-control" onchange="consultarArrastres()">
                  <option value="">Seleccione...</option>
              </select>
           </div>
           
           <!--colocamos campos de arrastres-->
           <div class="form-group">
              <label>Inv. Inicial</label>
              <input type="text" class="form-control" id="eInicial" name="eInicial">
           </div>
           <div class="form-group">
              <label>Resurtido</label>
              <input type="text" class="form-control" id="eResurtido" name="eResurtido">
           </div>
           <div class="form-group">
              <label>Inv. Final</label>
              <input type="text" class="form-control" id="eFinal" name="eFinal">
           </div>
           <div class="form-group">
              <label>Precio Venta</label>
              <input type="text" class="form-control" id="dPrecioVenta" name="dPrecioVenta"><br>
              <small><i>Solo numeros con decimales. No se admiten signos de pesos</i></small>
           </div>
           <!--colocamos campos de arrastres-->
            
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
   
<script>
    
   function consultarArrastres()
      {
         var eCodTienda         = document.getElementById('eCodTienda');
         var eCodProducto       = document.getElementById('eCodProducto');
         var eCodPresentacion   = document.getElementById('eCodPresentacion');
          
          if(eCodTienda.value && eCodProducto.value && eCodPresentacion.value)
              {
                var obj = $('#datos').serializeJSON();
                var jsonString = JSON.stringify(obj);
                
                var eInicial = document.getElementById('eInicial');
                
                $.ajax({
                    type: "POST",
                    url: "/con/oper-mov-prm.php",
                    data: jsonString,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(data){
                        eInicial.value = data.inicial;
                        if(parseInt(eInicial.value)>0)
                            {
                                eInicial.readOnly = true;
                            }
                    },
                    failure: function(errMsg) {
                        alert('Error al enviar los datos.');
                    }
                });
              }
             
          
      }
    
    function consultarPresentaciones()
      {
         var eCodTienda         = document.getElementById('eCodTienda');
         var eCodProducto       = document.getElementById('eCodProducto');
          
          if(eCodTienda.value && eCodProducto.value)
              {
                var obj = $('#datos').serializeJSON();
                var jsonString = JSON.stringify(obj);
                
                var eCodPresentacion = document.getElementById('eCodPresentacion');
                
                $.ajax({
                    type: "POST",
                    url: "/con/prod-pres.php",
                    data: jsonString,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(data){
                        eCodPresentacion.innerHTML = data.tHTML;
                    },
                    failure: function(errMsg) {
                        alert('Error al enviar los datos.');
                    }
                });
              }
             
          
      }
 
   
    $(document).ready(function() {
              $('#fhFechaPromotoria').datepicker({
                  locale:'es',
                  dateFormat: "dd/mm/yy"
              });
          });
    
   
    

		</script>