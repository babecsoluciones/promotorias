<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");




$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$select = "SELECT bp.*, cc.tNombre FROM BitPromotoria bp INNER JOIN CatClientes cc ON cc.eCodCliente=bp.eCodCliente WHERE bp.eCodPromotoria = ".$_GET['v1'];
$rsPromotoria = mysql_query($select);
$rPromotoria = mysql_fetch_array($rsPromotoria);


?>



    <form id="datos" name="datos" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="eCodPromotoria" id="eCodPromotoria" value="<?=$_GET['v1']?>">
        <input type="hidden" name="nvaFecha" id="nvaFecha">
        <input type="hidden" name="eAccion" id="eAccion">
        <!--tabs-->
        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
            <span>Datos Principales</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
            <span>Art&iacute;culos</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
            <span>Usuarios</span>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Datos Principales</h5>
                        <!--campos-->
                        <div class="position-relative form-group">
              <label> Cliente</label> 
               <input type="hidden" name="eCodCliente" id="eCodCliente" value="<?=$rPromotoria{'eCodCliente'};?>"> 
               <input type="text" class="form-control" id="tCliente" <?=(($_GET['v1']) ? 'readonly="readonly"' : '' )?> value="<?=(($rPromotoria{'eCodCliente'}) ? $rPromotoria{'tNombres'} . ' '.$rPromotoria{'tApellidos'} : '');?>" placeholder="Cliente" onkeyup="buscarClientes()" onkeypress="buscarClientes()"> 
               <small>Buscar y seleccionar el cliente de la lista</small>
               </div>
           
           
            
           <div class="position-relative form-group">    
              <label>Fecha de la Promotor&iacute;a</label>
              <input type="text" class="form-control" name="fhFechaPromotoria" id="fhFechaPromotoria" value="<?=$rPromotoria{'fhFechaPromotoria'} ? date('d/m/Y',strtotime($rPromotoria{'fhFechaPromotoria'})) : ""?>" >
            </div>
                                        
           <div class="position-relative form-group">
              <label>Tiendas</label>
              <table class="table table-hover" id="tiendas" width="100%">
                   <?
                    $i = 0;
                    ?>
                    <tr id="tie<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('tie<?=$i?>','tiendas')"></i></td>
                        <td>
                            <input type="hidden" id="eCodTienda<?=$i;?>" name="tiendas[<?=$i;?>][eCodTienda]">
                            <input type="text" class="form-control" id="tTienda<?=$i;?>" name="tiendas[<?=$i;?>][tTienda]" onkeyup="agregarTienda(<?=$i;?>)" onkeypress="agregarTienda(<?=$i;?>)" onblur="validarTienda(<?=$i;?>)">
                        </td>
                    </tr>
              </table>
           </div>
                        <!--campos-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>
                        <div class="table-responsive">
                            
                                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
        <div class="row">
            <div class="col-md-4">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Supervisores</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="supervisores" width="100%">
                                       <? $i = 0; ?>
                                        <tr id="sup<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('sup<?=$i?>','supervisores')"></i></td>
                        <td>
                            <input type="hidden" id="eCodSupervisor<?=$i;?>" name="supervisores[<?=$i;?>][eCodSupervisor]">
                            <input type="text" class="form-control" id="tSupervisor<?=$i;?>" name="supervisores[<?=$i;?>][tSupervisor]" onkeyup="agregarSupervisor(<?=$i;?>)" onkeypress="agregarSupervisor(<?=$i;?>)" onblur="validarSupervisor(<?=$i;?>)">
                        </td>
                    </tr>
                                    </table> 
                                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
               
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Promotores</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="promotores" width="100%">
                                       <? $i = 0; ?>
                                        <tr id="pro<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('pro<?=$i?>','promotores')"></i></td>
                        <td>
                            <input type="hidden" id="eCodPromotor<?=$i;?>" name="promotores[<?=$i;?>][eCodPromotor]">
                            <input type="text" class="form-control" id="tPromotor<?=$i;?>" name="promotores[<?=$i;?>][tPromotor]" onkeyup="agregarPromotor(<?=$i;?>)" onkeypress="agregarPromotor(<?=$i;?>)" onblur="validarPromotor(<?=$i;?>)">
                        </td>
                    </tr>
                                    </table> 
                                        
                        </div>
                    </div>
                </div>
               
                
            </div>
            <div class="col-md-4">
                
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios Marca</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="clientes" width="100%">
                                       <? $i = 0; ?>
                                        <tr id="cli<?=$i;?>">
                        <td><i class="far fa-trash-alt" onclick="deleteRow('cli<?=$i?>','clientes')"></i></td>
                        <td>
                            <input type="hidden" id="eCodCliente<?=$i;?>" name="clientes[<?=$i;?>][eCodCliente]">
                            <input type="text" class="form-control" id="tCliente<?=$i;?>" name="clientes[<?=$i;?>][tCliente]" onkeyup="agregarCliente(<?=$i;?>)" onkeypress="agregarCliente(<?=$i;?>)" onblur="validarCliente(<?=$i;?>)">
                        </td>
                    </tr>
                                    </table> 
                                        
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
        <!--tabs-->
        <input type="hidden" name="eFilas" id="eFilas" value="<?=$i?>">
        <input type="hidden" id="dTotalImportes" value="0">
    </form>
   
<script>
    
    //autocompletes
   
    
    function agregarTienda(indice)
        {
            var tTienda = document.getElementById('tTienda'+indice),
                eCodTienda = document.getElementById('eCodTienda'+indice);
            
            if(tTienda.value=="" || !tTienda.value)
                {
                    eCodTienda.value="";
                }
            
            
             $( function() {
  
        $( "#tTienda"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-tiendas.php",
                    type: 'get',
                    dataType: "json",
                    data: {
                        search: request.term,
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tTienda'+indice).val(ui.item.label);
                $('#eCodTienda'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarSupervisor(indice)
        {
            var tSupervisor = document.getElementById('tSupervisor'+indice),
                eCodSupervisor = document.getElementById('eCodSupervisor'+indice);
            
            if(tSupervisor.value=="" || !tSupervisor.value)
                {
                    eCodSupervisor.value="";
                }
            
            
             $( function() {
  
        $( "#tSupervisor"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-usuarios.php",
                    type: 'get',
                    dataType: "json",
                    data: {
                        search: request.term,
                        eCodPerfil: 3
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tSupervisor'+indice).val(ui.item.label);
                $('#eCodSupervisor'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarPromotor(indice)
        {
            var tPromotor = document.getElementById('tPromotor'+indice),
                eCodPromotor = document.getElementById('eCodPromotor'+indice);
            
            if(tPromotor.value=="" || !tPromotor.value)
                {
                    eCodPromotor.value="";
                }
            
            
             $( function() {
  
        $( "#tPromotor"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-usuarios.php",
                    type: 'get',
                    dataType: "json",
                    data: {
                        search: request.term,
                        eCodPerfil: 4
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tPromotor'+indice).val(ui.item.label);
                $('#eCodPromotor'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }
    
    function agregarCliente(indice)
        {
            var tCliente = document.getElementById('tCliente'+indice),
                eCodCliente = document.getElementById('eCodCliente'+indice);
            
            if(tCliente.value=="" || !tCliente.value)
                {
                    eCodCliente.value="";
                }
            
            var eCodMarca = document.getElementById('eCodCliente');
            
             $( function() {
  
        $( "#tCliente"+indice ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "/que/json-usuarios.php",
                    type: 'get',
                    dataType: "json",
                    data: {
                        search: request.term,
                        eCodPerfil: 5,
                        eCodUsuarioCliente: eCodMarca.value
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#tCliente'+indice).val(ui.item.label);
                $('#eCodCliente'+indice).val(ui.item.value); 
                return false;
                
            }
        });

       
        }); 
        }

   
    $(document).ready(function() {
              $('#fhFechaPromotoria').datepicker({
                  locale:'es',
                  dateFormat: "dd/mm/yy"
              });
          });
    
   
    

		</script>