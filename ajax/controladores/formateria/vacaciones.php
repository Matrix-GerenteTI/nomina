<?php
error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT']."/intranet/lib/tcpdf/tcpdf.php";
require_once $_SERVER['DOCUMENT_ROOT']."/intranet/lib/tcpdf/tcpdi.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/intranet/modelos/nomina/trabajadores.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/intranet/controladores/nomina/trabajadores.php';


class SolicitudDePermiso    
{
    protected $documento;
    protected $controllerTrabajador;

    public function __construct()
    {
        $this->documento  = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->documento->SetCreator(PDF_CREATOR);
        $this->documento->SetAuthor('Olaf Lederer');
        $this->documento->SetTitle('Solicitud Vacaciones');
        $this->documento->SetSubject('TCPDF Tutorial');
        $this->documento->SetKeywords('TCPDF, PDF, Permisos, tutorial');

        $this->controllerTrabajador = new TrabajadorController;
    }

    public function generaSolicitud( $idEmpleado )
    {
        $this->documento->AddPage(); 
        $this->documento->setPrintHeader(false); 

        $detalleTrabajador = $this->controllerTrabajador->getContratacion( $idEmpleado );
        $solicitudesVacaciones = [];
        $periodoVacaciones = '';
        foreach ( $detalleTrabajador['listaVacaciones'] as $i => $diaSeleccionado) {
                $fecha = explode(" ", $diaSeleccionado['elaborado'] );

                $periodoVacaciones = $diaSeleccionado['periodo_vacacional'];
            if ( !isset( $solicitudesVacaciones[ $fecha[0] ] ) ) {
                $solicitudesVacaciones[ $fecha[0] ] = [ str_replace("-","/",$diaSeleccionado['fecha'] ) ] ;
            } else {
                array_push( $solicitudesVacaciones[ $fecha[0] ] ,  str_replace("-","/",$diaSeleccionado['fecha'] ));
            }
            
        }
        
      $header = '              <div style="margin-top:50px;">
                                                <table>
                                                    <tr>
                                                        <td><img src="http://servermatrixxxb.ddns.net:8181/intranet/assets/images/logo2.png" style="width:200px;height:auto"></td>
                                                        <td></td>
                                                        <td><img src="http://servermatrixxxb.ddns.net:8181/intranet/assets/images/logoleon.png" style="width:100px;height:auto"> </td>
                                                    </tr>
                                                    <tr>
                                                        <td><br></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align:center"> <b style="text-align:center;font-size:0.9em">CONSTANCIA DE GOCE DE VACACIONES</b> </td>
                                                    </tr>
                                                </table>
                                                
                                        </div>';        
        
        $this->documento->writeHTML( $header, true, false, false, false, '');

        $monto = '<br><br><div style="font-size:0.9em;margin-top:-120px;">
                                <table>
                                    <tr>   
                                        <th>
                                            <table>
                                                <tr>    
                                                    <td style="font-size:1.1em;background-color: #f44336;color: #fff; height:15;">Nombre del empleado</td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td >
                                            <table>
                                                <tr>
                                                    <td colspan="6" style="padding:3px;font-size:0.9em;background-color: #eceff1;color: #000; height:15;">'.$detalleTrabajador['nombre'].'</td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                           <table>
                                                <tr>    
                                                    <td style="font-size:1.1em;background-color: #f44336;color: #fff; height:15;">Puesto</td>
                                                    <td></td>
                                                </tr>
                                            </table>                                        
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <table>
                                                        <tr>
                                                            <td colspan="6" style="padding:3px;font-size:0.9em;background-color: #eceff1;color: #000; height:15;">'.$detalleTrabajador['puesto'].'</td>
                                                            <td></td>
                                                        </tr>
                                                </table>                                           
                                        </th>

                                    </tr>
                                    <tr>
                                        <th>
                                           <table>
                                                <tr>    
                                                    <td style="font-size:1.1em;background-color: #f44336;color: #fff; height:15;">Ubicación</td>
                                                    <td></td>
                                                </tr>
                                            </table>                                        
                                        </th>
                                    </tr>                                    
                                    <tr>
                                        <th>
                                            <table>
                                                        <tr>
                                                            <td colspan="6" style="padding:3px;font-size:0.9em;background-color: #eceff1;color: #000; height:15;">'.$detalleTrabajador['sucursal'].'</td>
                                                            <td></td>
                                                        </tr>
                                                </table>                                           
                                        </th>
                                    </tr>        
                                    <tr>
                                        <th>
                                           <table>
                                                <tr>    
                                                    <td style="font-size:1.1em;background-color: #f44336;color: #fff; height:15;">Fecha de Inicio Laboral</td>
                                                    <td></td>
                                                </tr>
                                            </table>                                        
                                        </th>
                                    </tr>                                    
                                    <tr>
                                        <th>
                                            <table>
                                                        <tr>
                                                            <td colspan="6" style="padding:3px;font-size:0.9em;background-color: #eceff1;color: #000; height:15;">'.str_replace("-",'/', $detalleTrabajador['fechainiciolab']).'</td>
                                                            <td></td>
                                                        </tr>
                                                </table>                                           
                                        </th>
                                    </tr>   
                                    <tr>
                                        <td>
                                            <br>
                                            Se hace constar que el colaborador(a) gozará su periodo vacacional como se detalla acontinuación:</td>
                                    </tr> 

                                    <tr>
                                        <td><br></td>
                                    </tr>
                                    <tr>    
                                        <table>
                                            <tr>
                                                <th style="font-size:0.8em;font-weight:bold;text-align:center;border: 1px solid black;">FECHA DE SOLICITUD</th>
                                                <th style="font-size:0.8em;font-weight:bold;text-align:center;border: 1px solid black;">DÍAS TOMADOS</th>
                                                <th style="font-size:0.8em;font-weight:bold;text-align:center;border: 1px solid black;">TOTAL DE DÍAS</th>
                                                <th style="font-size:0.8em;font-weight:bold;text-align:center;border: 1px solid black;">PERÍODO VACACIONAL</th>
                                            </tr>';
                                            foreach ($solicitudesVacaciones as $fechaSolicitud => $fechasSolicitadas) {
                                                $monto .= '<tr>
                                                                        <td style="padding:3%;font-size:0.8em;text-align:center;border: 1px solid black;">'.$fechaSolicitud.'</td>
                                                                        <td style="padding:3%;font-size:0.8em;text-align:center;border: 1px solid black;">'.join(",", $fechasSolicitadas).'</td>
                                                                        <td style="padding:3%;font-size:0.8em;text-align:center;font-weight:bold;border: 1px solid black;">'.sizeof( $fechasSolicitadas).'</td>
                                                                        <td style="padding:3%;font-size:0.8em;text-align:center;font-weight:bold;border: 1px solid black;">2019</td>
                                                                    </tr>';
                                            }
                                       $monto .=' </table>
                                    </tr>
                                    <tr>
                                            <td><br></td>
                                    </tr>
                                </table>
                            </div>';
        $this->documento->writeHTML( $monto, true, false, false, false, '');

        $this->documento->Ln(60);    

        $this->documento->writeHTML( '<table>
                                                            <tr>
                                                            <td colspan="3">Quedando completo el período vacacional del  '. $periodoVacaciones.'</td>
                                                            </tr>   
                                                        </table>', true, false, false, false, '');

        $this->documento->Ln(5);    
                                            
        $this->documento->writeHTML( '<table>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td style="padding:3%;font-size:0.7em;text-align:center;">FIRMAS DE CONFORMIDAD</td>
                                                                        <td></td>
                                                                    </tr>   
                                                                </table>', true, false, false, false, '');

        $this->documento->Ln(8);  
        $firmas = '
                            <div>
                                <table>                              
                                    <tr>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="8" style="border-top:1px solid black;text-align:center">Empleado</td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="8" style="border-top:1px solid black;text-align:center">Jefe inmediato</td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="8" style="border-top:1px solid black;text-align:center">Dirección Gral.</td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>'
                        ;
        $this->documento->writeHTML( $firmas, true, false, false, false, '');

        $name = strtotime( date("Y-m-d H:i:s") );
        $this->documento->Output($_SERVER['DOCUMENT_ROOT'].'/nomina/ajax/controladores/formateria/constancia_vacaciones.pdf', 'F');
        echo "http://servermatrixxxb.ddns.net:8181/nomina/ajax/controladores/formateria/constancia_vacaciones.pdf";
    }
}

$solicitud = new SolicitudDePermiso;
$solicitud->generaSolicitud( $_GET['empleado'] );
 