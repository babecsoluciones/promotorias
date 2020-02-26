<?php
require_once("cnx/swgc-mysql.php");
require_once("cls/cls-sistema.php");


$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];


$fhFechaInicio = $_POST['fhFechaConsulta'] ? date('Y-m-d',strtotime($_POST['fhFechaConsulta'])).' 00:00:00' : date('Y-m-d').' 00:00:00';
$fhFechaTermino = $_POST['fhFechaConsulta'] ? date('Y-m-d',strtotime($_POST['fhFechaConsulta'])).' 23:59:59' : date('Y-m-d').' 23:59:59';

$fhFechaConsulta = $_POST['fhFechaConsulta'] ? date('Y-m-d',strtotime($_POST['fhFechaConsulta'])).' 00:00:00' : date('Y-m-d').' 00:00:00';

$fhFechaInicio = "'".$fhFechaInicio."'";
$fhFechaTermino = "'".$fhFechaTermino."'";


?>

<div class="row">
<!--calendario-->
    <div class="col-lg-12   au-card--no-pad m-b-40" >
        <h1>Dashboard en proceso de instalaci&oacute;n</h1>
    </div>
<!--calendario-->


</div>

