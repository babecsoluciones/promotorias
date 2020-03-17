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
date_default_timezone_set('America/Mexico_City');

$fhFecha = "'".date('Y-m-d H:i:s')."'";
$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];


session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/
$eCodPromotoria     = $data->eCodPromotoria     ? $data->eCodPromotoria : false;
$eCodProducto       = $data->eCodProducto       ? $data->eCodProducto : false;
$eCodPresentacion   = $data->eCodPresentacion   ? $data->eCodPresentacion : false;
$dPrecioVenta       = $data->dPrecioVenta       ? $data->dPrecioVenta   : false;
$eCodTienda         = $data->eCodTienda         ? $data->eCodTienda     : false;

$eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];

$eInicial       = trim($data->eInicial)     ? $data->eInicial       : false;
$eResurtido     = trim($data->eResurtido)   ? $data->eResurtido     : 0;
$eFinal         = trim($data->eFinal)       ? $data->eFinal         : 0;
$dPrecioVenta   = trim($data->dPrecioVenta) ? $data->dPrecioVenta   : false;

if(!$eInicial){ $errores[] = "El inventario inicial es obligatorio"; }
if(!$dPrecioVenta){ $errores[] = "El precio de venta es obligatorio"; }
//if(!$eResurtido){ $errores[] = "La cantidad de resurtido es obligatoria"; }
//if(!$eFinal){ $errores[] = "El inventario final es obligatorio"; }

$eTotalVenta = ((int)$eInicial + (int)$eResurtido) - (int)$eFinal;

if(!sizeof($errores))
{
    $query = "INSERT INTO RelPromotoriasProductosPresentacionesMovimientos
                (
                    eCodPromotoria,
                    eCodTienda,
                    eCodUsuario,
                    eCodProducto,
                    eCodPresentacion,
                    eInicial,
                    eResurtido,
                    eFinal,
                    eTotalVenta,
                    dPrecioVenta,
                    fhFecha
                ) VALUES(
                    $eCodPromotoria,
                    $eCodTienda,
                    $eCodUsuario,
                    $eCodProducto,
                    $eCodPresentacion,
                    $eInicial,
                    $eResurtido,
                    $eFinal,
                    $eTotalVenta,
                    $dPrecioVenta,
                    $fhFecha
                )";
    $rs = mysql_query($query);
    if(!$rs){ $errores[] = "Error al insertar el arrastre"; }
}

if(!sizeof($errores))
{
    $tDescripcion = "Se ha insertado arrastre en la tienda $ecodTienda para el producto $eCodProducto con presentacion $eCodPresentacion de la promotoria ".sprintf("%07d",$eCodPromotoria);
    $tDescripcion = "'".$tDescripcion."'";
    $fecha = "'".date('Y-m-d H:i:s')."'";
    $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
    mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0),"inicial"=>(int)$rConsulta{'eFinal'}, 'errores'=>$errores));

?>