<? header('Access-Control-Allow-Origin: *');  ?>
<? header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"); ?>
<? header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE"); ?>
<? header("Allow: GET, POST, OPTIONS, PUT, DELETE"); ?>
<? header('Content-Type: application/json'); ?>
<?

if (isset($_SERVER{'HTTP_ORIGIN'})) {
        header("Access-Control-Allow-Origin: {$_SERVER{'HTTP_ORIGIN'}}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

require_once("../cnx/swgc-mysql.php");

session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );



    $eCodEvento = $data->eCodEventoTransaccion ? $data->eCodEventoTransaccion : false;
    $eCodTransaccion = $data->eCodTransaccion ? $data->eCodTransaccion : false;
    $dMonto = $data->dMonto;
    $fhFecha = "'".date('Y-m-d H:i:s')."'";
    $eCodTipoPago = $data->eCodTipoPago;
    $tCodEstatusTransaccion = $data->tCodEstatusTransaccion ? "'".$data->tCodEstatusTransaccion."'" : "'AC'";
    $tMotivoCancelacion = $data->tMotivoCancelacion ? "'".$data->tMotivoCancelacion."'" : false;
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];

    if(!$eCodEvento) { $errores[] = "No se recibió el código de la cotización"; }
    if($tCodEstatusTransaccion=="CA" && !$tmotivoCancelacion) { $errores[] = "El motivo de cancelación es obligatorio"; }

if(!sizeof($errores))
{
        if(!$eCodTransaccion)
        {
            
            $insert = "INSERT INTO BitTransacciones (eCodUsuario,eCodEvento,fhFecha,dMonto,eCodTipoPago) VALUES ($eCodUsuario,$eCodEvento,$fhFecha,$dMonto,$eCodTipoPago)";
            $tDescripcion = "Se ha registrado una transaccion por ".number_format($dMonto,2)." en el evento ".sprintf("%07d",$eCodEvento);
        }
        else
        {
            $insert = "UPDATE BitTransacciones SET
                            eCodUsuario=$eCodUsuario,
                            eCodEvento=$eCodEvento,
                            fhFecha=$fhFecha,
                            dMonto=$dMonto,
                            eCodTipoPago=$eCodTipoPago,
                            ".($tMotivoCancelacion ? " tMotivoCancelacion=$tMotivoCancelacion," : "")."
                            tCodEstatus = $tCodEstatusTransaccion
                        WHERE 
                            eCodTransaccion=$eCodTransaccion";
            
            $tDescripcion = "Se ha actualizado una transaccion por ".number_format($dMonto,2)." en el evento ".sprintf("%07d",$eCodEvento);
        }
        mysql_query($insert);
        
        //$pf = fopen("log.txt","w");
        //fwrite($pf,$insert);
        //fclose($pf);
        
        $tDescripcion = "Se ha registrado una transaccion por ".number_format($dMonto,2)." en el evento ".sprintf("%07d",$eCodEvento);
        $tDescripcion = "'".$tDescripcion."'";
        mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fhFecha, $tDescripcion)");
        
        mysql_query("UPDATE BitEventos SET eCodEstatus = 2 WHERE eCodEvento = ".$eCodEvento);
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores));

?>