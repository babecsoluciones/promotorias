<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");


$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];


$select =   " SELECT cc.tNombre tCliente, bp.eCodPromotoria, ".
            " bp.fhFechaPromotoria, ce.tIcono estatus".
            " FROM BitPromotoria bp ".
            " INNER JOIN CatClientes cc ON cc.eCodCliente = bp.eCodCliente ".
            " INNER JOIN CatEstatus ce ON ce.eCodEstatus = bp.eCodEstatus ".
            " INNER JOIN RelPromotoriasClientes pc ON pc.eCodPromotoria = bp.eCodPromotoria ".
            " INNER JOIN RelPromotoriasPromotores pp ON pp.eCodPromotoria = bp.eCodPromotoria ".
            " INNER JOIN RelPromotoriasSupervisores ON ps.eCodPromotoria = bp.eCodPromotoria ".
            " WHERE DATE(bp.fhFechaPromotoria) >= '".date('Y-m-d H:i:s')."'".
            ($bAll ? "" : " AND ".$_SESSION['sessionAdmin']['eCodUsuario']." IN pc.eCodUsuario, pp.eCodPromotor, ps.eCodSupervisor ");
$rsConsulta = mysql_query($select);

?>

<div class="row">
<!--calendario-->
    <div class="col-lg-12">
                                <h2 class="title-1 m-b-25">Perfiles</h2>
                                <div class="table-responsive table--no-card m-b-40">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                            <th>C&oacute;digo</th>
		                                    <th>E</th>
                                            <th>Cliente</th>
                                            <th class="text-left">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?
											 if(mysql_num_rows($rsConsulta){
                                                 while($rConsulta=mysql_fetch_array($rsConsulta)){
												?>
											<tr>
                                            <td><?=menuEmergente($rConsulta{'eCodPromotoria'});?></td>
                                            <td><i class="<?=$rConsulta{'estatus'};?>"></i></td>
                                            <td><?=utf8_encode($rConsulta{'tCliente'});?>.'</td>
                                            <td><?=date('d/m/Y',strtotime($rConsulta{'fhFechaPromotoria'}));?></td>
                                            </tr>
											<?
											}
                                             }
                                                else
                                                { ?>
                                                   <tr>
                                                       <td colspan="4" align="center">
                                                           <i>
                                                           Sin promotor&iacute;as por el momento
                                                           </i>
                                                       </td>
                                                   </tr> 
                                                <? }
											?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
<!--calendario-->


</div>

