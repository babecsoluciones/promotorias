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
include("../inc/fun-ini.php");

session_start();

$errores = array();

$data = json_decode( file_get_contents('php://input') );

/*Preparacion de variables*/

$codigo = $data->eCodAccion ? $data->eCodAccion : $data->eAccion;
$accion = $data->tCodAccion ? $data->tCodAccion : $data->tAccion;

$eCodInventario = $data->eCodInventario ? $data->eCodInventario : false;
$eCodTipoInventario = $data->eCodTipoInventario ? $data->eCodTipoInventario : false;

    $terms = explode(" ",$data->tNombre);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND ci.tNombre like '%".$terms[$i]."%' ";
    }


$eLimit = $data->eMaxRegistros;
$bOrden = $data->rOrden;
$rdOrden = $data->rdOrden ? $data->rdOrden : 'eCodInventario';


switch($accion)
{
    case 'D':
        $select = "SELECT COUNT(*) ePaquetes FROM RelServiciosInventario WHERE eCodInventario = $codigo";
        $rContador = mysql_fetch_array(mysql_query($select));
        if($rContador{'ePaquetes'}>=1)
        {
            $errores[] = 'El producto se encuentra en '.$rContador{'ePaquetes'}.' paquete(s). Imposible eliminar';
        }
        else
        {
        $insert = "DELETE FROM CatInventario WHERE eCodInventario = ".$codigo;
        }
        break;
    case 'F':
        $insert = "UPDATE CatInventario SET eCodEstatus = 8 WHERE eCodInventario = ".$codigo;
        break;
    case 'C':
        $tHTML = '<table class="table table-hover">'.
                 '<thead>'.
                 '    <tr>'.
                 '        <th>C&oacute;digo</th>'.
				 '		  <th>Tipo</th>'.
				 '		  <th>Nombre</th>'.
                 '        <th>Marca</th>'.
                 '        <th>Precio Interno</th>'.
                 '        <th>Precio P&uacute;blico</th>'.
                 '        <th>Existencia</th>'.
                 '        '.
                 '    </tr>'.
                 '</thead>'.
                 '<tbody>';
        $select = "	SELECT * FROM (SELECT 
					cti.tNombre as tipo, 
                    csi.tNombre subclasificacion,
					ci.*
					FROM
						CatInventario ci
					INNER JOIN CatTiposInventario cti ON cti.eCodTipoInventario = ci.eCodTipoInventario
                    LEFT JOIN CatSubClasificacionesInventarios csi ON csi.eCodSubclasificacion=ci.eCodSubclasificacion ".
					" WHERE 1=1".
            ($eCodInventario ? " AND ci.eCodInventario = $eCodInventario " : "").
            ($eCodTipoInventario ? " AND ci.eCodTipoInventario = $eCodTipoInventario " : "").
            ($data->tNombre ? $termino : "").
					")N0 ORDER BY $rdOrden $bOrden LIMIT 0, $eLimit";
        $rsConsulta = mysql_query($select);
        while($rConsulta=mysql_fetch_array($rsConsulta)){
         /* validamos si está cargado */
           
            
            //imprimimos
       $tHTML .=    '<tr>'.
                    '<td>'.menuEmergenteJSON($rConsulta{'eCodInventario'},'cata-inv-con').'</td>'.
			        '<td>'.utf8_encode($rConsulta{'tipo'}).'</td>'.
			        '<td>'.utf8_encode($rConsulta{'tNombre'}).'</td>'.
			        '<td>'.utf8_encode($rConsulta{'tMarca'}).'</td>'.
                    '<td>$'.number_format($rConsulta{'dPrecioInterno'},2).'</td>'.
					'<td>$'.number_format($rConsulta{'dPrecioVenta'},2).'</td>'.
					'<td>'.$rConsulta{'ePiezas'}.'</td>'.
                    '</tr>';
            //imprimimos
        }
        /* hacemos select */
        $tHTML .= '</tbody>'.
            '</table>';
        break;
}
        
if(!sizeof($errores) && ($accion=="D" || $accion=="F"))
{      
        $rs = mysql_query($insert);

        if(!$rs)
        {
            $errores[] = 'Error al efectuar la operacion '.mysql_error();
        }

    if(!sizeof($errores))
    {
        $tDescripcion = "Se ha ".(($accion=="D") ? 'Eliminado' : 'Finalizado')." el producto del inventario código ".sprintf("%07d",$codigo);
        $tDescripcion = "'".utf8_encode($tDescripcion)."'";
        $fecha = "'".date('Y-m-d H:i:s')."'";
        $eCodUsuario = $_SESSION['sessionAdmin']['eCodUsuario'];
        mysql_query("INSERT INTO SisLogs (eCodUsuario, fhFecha, tDescripcion) VALUES ($eCodUsuario, $fecha, $tDescripcion)");
    }
}

echo json_encode(array("exito"=>((!sizeof($errores)) ? 1 : 0), 'errores'=>$errores,'registros'=>(int)mysql_num_rows($rsConsulta),"consulta"=>$tHTML,"select"=>$select));

?>