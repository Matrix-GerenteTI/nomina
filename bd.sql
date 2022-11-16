/*
SQLyog Ultimate v9.63 
MySQL - 5.5.24-log : Database - ruby2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cirugias` */

CREATE TABLE `cirugias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cirugia` varchar(500) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cirugias` */

/*Table structure for table `datosmedicos` */

CREATE TABLE `datosmedicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idexpediente` int(11) DEFAULT NULL,
  `ultimoexamenfisico` date DEFAULT NULL,
  `ultimaradiografia` date DEFAULT NULL,
  `ultimoelectrocardiograma` date DEFAULT NULL,
  `anestesiaraquia` varchar(5) DEFAULT NULL,
  `anestesiageneral` varchar(5) DEFAULT NULL,
  `anestesialocal` varchar(5) DEFAULT NULL,
  `anestesiareacciones` varchar(5) DEFAULT NULL,
  `anestesiafiebre` varchar(5) DEFAULT NULL,
  `usteddientespostizos` varchar(5) DEFAULT NULL,
  `usteddientesflojos` varchar(5) DEFAULT NULL,
  `ustedcubiertosporcelana` varchar(5) DEFAULT NULL,
  `ustedabrirboca` varchar(5) DEFAULT NULL,
  `ustedpestaniaspostizas` varchar(5) DEFAULT NULL,
  `ustedlentescontacto` varchar(5) DEFAULT NULL,
  `usteddefectosfisicos` varchar(5) DEFAULT NULL,
  `medantidepresivos` varchar(5) DEFAULT NULL,
  `medantidepresivoscual` varchar(500) DEFAULT NULL,
  `medantihipertensivos` varchar(5) DEFAULT NULL,
  `medantihipertensivoscual` varchar(500) DEFAULT NULL,
  `medanticuagulantes` varchar(5) DEFAULT NULL,
  `medanticuagulantescual` varchar(500) DEFAULT NULL,
  `medanticuagulantesdosis` varchar(500) DEFAULT NULL,
  `meddiabetes` varchar(5) DEFAULT NULL,
  `meddiabetescual` varchar(500) DEFAULT NULL,
  `medotro` varchar(5) DEFAULT NULL,
  `medotrocual1` varchar(500) DEFAULT NULL,
  `medotrodosis1` varchar(500) DEFAULT NULL,
  `medotrocual2` varchar(500) DEFAULT NULL,
  `medotrodosis2` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `datosmedicos` */

/*Table structure for table `expedientes` */

CREATE TABLE `expedientes` (
  `folio` int(11) NOT NULL AUTO_INCREMENT,
  `doctor` varchar(500) DEFAULT NULL,
  `cirugia` int(11) DEFAULT NULL,
  `pnombre` varchar(500) DEFAULT NULL,
  `pfecnac` date DEFAULT NULL,
  `pedad` int(11) DEFAULT NULL,
  `pedocivil` varchar(100) DEFAULT NULL,
  `psexo` varchar(100) DEFAULT NULL,
  `pdomicilio` varchar(600) DEFAULT NULL,
  `ptelparticular` varchar(100) DEFAULT NULL,
  `pteltrabajo` varchar(100) DEFAULT NULL,
  `pcelular` varchar(100) DEFAULT NULL,
  `pemail` varchar(200) DEFAULT NULL,
  `pfacebook` varchar(100) DEFAULT NULL,
  `ptwitter` varchar(100) DEFAULT NULL,
  `rnombre` varchar(500) DEFAULT NULL,
  `rtelefono` varchar(100) DEFAULT NULL,
  `avisara` varchar(500) DEFAULT NULL,
  `avisartelefonos` varchar(500) DEFAULT NULL,
  `peso` decimal(10,2) DEFAULT NULL,
  `talla` decimal(10,2) DEFAULT NULL,
  `imc` varchar(200) DEFAULT NULL,
  `operyproc` varchar(800) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `idusuario` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`folio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expedientes` */

/*Table structure for table `recibos` */

CREATE TABLE `recibos` (
  `folio` int(11) NOT NULL AUTO_INCREMENT,
  `idexpediente` int(11) DEFAULT NULL,
  `paciente` varchar(500) DEFAULT NULL,
  `concepto` text,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `idusuario` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`folio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `recibos` */

/*Table structure for table `usuarios` */

CREATE TABLE `usuarios` (
  `username` varchar(100) NOT NULL,
  `password` varbinary(100) DEFAULT NULL,
  `nombre` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT 'USUARIO',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `usuarios` */

insert  into `usuarios`(`username`,`password`,`nombre`,`email`,`tipo`,`status`) values ('admin','admin','Administrador General','serygra@gmail.com','ADMINISTRADOR',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
