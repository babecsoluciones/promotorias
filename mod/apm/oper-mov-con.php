<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");

$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$select = "SELECT
	rm.fhFecha,
	ct.tNombre tTienda,
	cp.tNombre tProducto,
	cr.tNombre tPresentacion,
	rm.eInicial,
	rm.eResurtido,
	rm.eFinal,
	rm.eTotalVenta,
	rm.dPrecioVenta 
FROM
	RelPromotoriasProductosPresentacionesMovimientos rm
	INNER JOIN CatTiendas ct ON ct.eCodTienda= rm.eCodTienda
	INNER JOIN CatProductos cp ON cp.eCodProducto = rm.eCodProducto
	INNER JOIN CatPresentaciones cr ON cr.eCodPresentacion = rm.ecodPresentacion 
WHERE
	rm.eCodPromotoria = ".$_GET['v1']." ORDER BY rm.eCodTienda ASC, rm.eCodProducto ASC";
$rsConsulta = mysql_query($select);

$tienda = "";
$producto = "";
$presentacion = "";

?>
<table class="table table-striped">

<tbody>
<? while($rConsulta = mysql_fetch_array($rsConsulta)){ ?>
    <? if($tienda!=$rConsulta{'tTienda'}){ ?>
       <? $tienda = $rConsulta{'tTienda'}; ?>
       <? $producto = ""; $presentacion = ""; ?>
        <tr>
            <td colspan="6" align="center"><b><?=$rConsulta{'tTienda'};?></b></td>
        </tr>
    <? } ?>
    <? if($producto!=$rConsulta{'tProducto'}){ ?>
       <? $producto = $rConsulta{'tProducto'}; ?>
        
    <? } ?>
    <? if($presentacion!=$rConsulta{'tPresentacion'}){ ?>
       <? $presentacion = $rConsulta{'tPresentacion'}; ?>
        <tr>
            <td colspan="6" align="center"><b><?=$producto;?> - <?=$rConsulta{'tPresentacion'};?></b></td>
        </tr>
       
    <tr>
        <th>Fecha</th>
        <th>Inv. Inicial</th>
        <th>Resurtido</th>
        <th>Inv. Final</th>
        <th>Venta</th>
        <th>Precio Venta</th>
    </tr>

    <? } ?>
    <tr>
        <td><?=date('d/m/Y H:i',strtotime($rConsulta{'fhFecha'}));?></td>
        <td><?=$rConsulta{'eInicial'};?></td>
        <td><?=$rConsulta{'eResurtido'};?></td>
        <td><?=$rConsulta{'eFinal'};?></td>
        <td><?=$rConsulta{'eTotalVenta'};?></td>
        <td>$<?=$rConsulta{'dPrecioVenta'};?></td>
    </tr>
<? } ?>
</tbody>
</table>


<script>
    function exportarXLS()
    {
        window.location="<?=obtenerURL();?>xls/<?=$_GET['tCodSeccion']?>/v1/"+<?=sprintf("%07d",$_GET['v1']);;?>+"/";
    }
</script>