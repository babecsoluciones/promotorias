<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");

$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$bValidar = (($_SESSION['sessionAdmin']['eCodPerfil']==3 || $_SESSION['sessionAdmin']['eCodPerfil']==4) ? true : false);

$tCampo = ($_SESSION['sessionAdmin']['eCodPerfil']==4 ? 'eCodPromotor' : 'eCodSupervisor');

$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];

$select = "SELECT ct.eCodTienda, ct.tNombre FROM CatTiendas ct INNER JOIN RelPromotoriasPromotores rt ON rt.eCodTienda = ct.eCodTienda WHERE  rt.eCodPromotoria = ".($_SESSION['sesionPromotoria']['eCodPromotoria'] ? $_SESSION['sesionPromotoria']['eCodPromotoria'] : $_GET['v1']).
($bValidar ? " AND $tCampo = $eCodUsuario" : "");
$rsTiendas = mysql_query($select);

$select = "SELECT * FROM CatTiposImagenes WHERE tCodEstatus = 'AC'";
$rsTiposImagenes = mysql_query($select);

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
           <div class="form-group" id="divXHR">
              
           </div>
           
            
                                        <!--campos-->
                                    </div>
                                </div>
                            </div>
    </form>
   
<script>
    
    //autocompletes
   function consultarImagenes()
      {
         var eCodTienda         = document.getElementById('eCodTienda');
         
          
          if(eCodTienda.value)
              {
                var obj = $('#datos').serializeJSON();
                var jsonString = JSON.stringify(obj);
                
               
                
                $.ajax({
                    type: "POST",
                    url: "/con/oper-img-con.php",
                    data: jsonString,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(data){
                        document.getElementById('divXHR').innerHTML = data.tHTML;
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