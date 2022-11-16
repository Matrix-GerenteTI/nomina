<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/nomina/ajax/clases/DB.php';
class Asistencia extends DB
{
    public function getAsistencia( $params)
    {
        extract( $params );

        $queryAsistencias = "SELECT 	e.nip as nip,
								e.nombre as nombre,
								d.descripcion as departamento,
								p.descripcion as puesto,
								c.id as idcontrato,
								r.timecheck,
								YEAR(r.timecheck) as anio,
								MONTH(r.timecheck) as mes,
								DAY(r.timecheck) as dia,
								HOUR(r.timecheck) as hora,
								MINUTE(r.timecheck) as minuto,
								CASE WHEN TIMEDIFF(TIME(r.timecheck),pa.entrada)>'00:00:59' THEN 
                                CASE WHEN(HOUR(TIMEDIFF(TIME(r.timecheck),pa.entrada))*60)+MINUTE(TIMEDIFF(TIME(r.timecheck),pa.entrada))<6 THEN 50 
                                ELSE IF(MINUTE(TIMEDIFF(TIME(r.timecheck),pa.entrada))<11,100,c.salariodiario) END ELSE 0 END AS RETARDO,
								pa.entrada,
								pa.entradai,
								pa.salidai,
								pa.salida,
								pa.tolerancia,
								pa.retardospfalta,
								pa.corrido,
								pa.faltaspdescuento ,
                                csucursal.descripcion as sucursal
					  FROM 		pregistros r  
					  INNER JOIN pempleado e ON r.idempleado=e.nip 
					  INNER JOIN pcontrato c ON e.nip=c.nip 
					  INNER JOIN cpuesto p ON c.idpuesto=p.id 
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  INNER JOIN cparametrosasistencia pa ON p.id=pa.idpuesto 
                      INNER JOIN csucursal on csucursal.id = e.idsucursal
					  WHERE 	( (e.nombre LIKE '%$trabajador%' 
					  AND 		p.id LIKE '%$puesto%' ) OR  (e.nip like '$nip' ) )
                      AND		r.timecheck>='$fechaInicio' AND r.timecheck<='$fechaFin  23:59:59' 
                      AND		e.status=1 
					  GROUP BY  d.descripcion,p.descripcion,e.nombre,anio,mes,dia
                      ORDER BY d.descripcion,p.descripcion,e.nombre,r.timecheck ASC";
                      
        return $this->select( $queryAsistencias );
    }
}
