/*
MySQL Backup
Source Server Version: 5.5.36
Source Database: e11_seguros
Date: 30/06/2015 16:14:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `acciones`
-- ----------------------------
DROP TABLE IF EXISTS `acciones`;
CREATE TABLE `acciones` (
  `id_acciones` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de acciones',
  `id_modulo` bigint(20) DEFAULT NULL COMMENT 'id de modulo',
  `nombre_acciones` varchar(100) DEFAULT NULL COMMENT 'nombre de la accion',
  PRIMARY KEY (`id_acciones`),
  KEY `FK_id_acciones_id_modulo` (`id_modulo`),
  CONSTRAINT `FK_id_acciones_id_modulo` FOREIGN KEY (`id_modulo`) REFERENCES `_modulos` (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `agenda`
-- ----------------------------
DROP TABLE IF EXISTS `agenda`;
CREATE TABLE `agenda` (
  `id_agenda` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de agenda',
  `id_subagente` bigint(20) DEFAULT NULL COMMENT 'id de subagente',
  `prefijo_agenda` varchar(255) DEFAULT '-' COMMENT 'prefijo de agenda',
  `nombre_agenda` varchar(255) DEFAULT '-' COMMENT 'nombre de agenda',
  `segundo_nombre_agenda` varchar(255) DEFAULT '-' COMMENT 'segundo nombre de agenda',
  `apellido_agenda` varchar(255) DEFAULT '-' COMMENT 'apellido del contacto',
  `sufijo_agenda` varchar(255) DEFAULT '-' COMMENT 'sufijo de agenda',
  `puesto_agenda` varchar(255) DEFAULT '-' COMMENT 'puesto agenda',
  `empresa_agenda` varchar(255) DEFAULT '-' COMMENT 'nombre de la empresa',
  `fecha_cumple_agenda` date DEFAULT '0000-00-00' COMMENT 'fecha de cumpleaños',
  `opcion_contacto` smallint(1) DEFAULT '1' COMMENT 'opcion de contacto 1 cliente, 2 proveedor, 3 otro',
  `otro_agenda` varchar(255) DEFAULT '-' COMMENT 'relacion a otro agenda',
  `descripcion_agenda` text COMMENT 'descripcion del contacto de agenda',
  `url_agenda` varchar(100) DEFAULT 'default.jpg' COMMENT 'url imagen agenda',
  `fecha_agenda` datetime DEFAULT NULL COMMENT 'fecha de agenda',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_agenda` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion de agenda',
  `estatus_agenda` smallint(1) DEFAULT '1' COMMENT 'estatus de agenda',
  PRIMARY KEY (`id_agenda`),
  KEY `FK_id_agenda_id_usuario` (`id_usuario`),
  KEY `FK_agenda_id_subagente` (`id_subagente`),
  CONSTRAINT `FK_agenda_id_subagente` FOREIGN KEY (`id_subagente`) REFERENCES `subagente` (`id_subagente`),
  CONSTRAINT `FK_agenda_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `agenda_cliente`
-- ----------------------------
DROP TABLE IF EXISTS `agenda_cliente`;
CREATE TABLE `agenda_cliente` (
  `id_agenda_cliente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de agenda cliente',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `id_cliente` bigint(20) DEFAULT NULL COMMENT 'id de cliente',
  PRIMARY KEY (`id_agenda_cliente`),
  KEY `id_agenda_cliente_id_agenda` (`id_agenda`),
  KEY `id_agenda_cliente_id_cliente` (`id_cliente`),
  CONSTRAINT `FK_agenda_cliente__id_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `FK_id_agenda_cliente_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `agenda_proveedor`
-- ----------------------------
DROP TABLE IF EXISTS `agenda_proveedor`;
CREATE TABLE `agenda_proveedor` (
  `id_agenda_proveedor` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de agenda proveedor',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `id_proveedor` bigint(20) DEFAULT NULL COMMENT 'id de proveedor',
  PRIMARY KEY (`id_agenda_proveedor`),
  KEY `FK_id_agenda_proveedor_id_agenda` (`id_agenda`),
  KEY `FK_id_agenda_proveedor_id_proveedor` (`id_proveedor`),
  CONSTRAINT `agenda_proveedor_ibfk_1` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `agenda_proveedor_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `agente`
-- ----------------------------
DROP TABLE IF EXISTS `agente`;
CREATE TABLE `agente` (
  `id_agente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de agente',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usario',
  `fecha_agente` datetime DEFAULT NULL COMMENT 'fecha de agente',
  `id_usuario_create` bigint(20) DEFAULT NULL COMMENT 'id de usuario create',
  `fecha_actualiza_agente` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza agente',
  `estatus_agente` smallint(1) DEFAULT '1' COMMENT 'estatus de agente',
  PRIMARY KEY (`id_agente`),
  KEY `FK_id_agente_id_usuario_create` (`id_usuario_create`),
  KEY `FK_id_agente_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_id_agente_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`),
  CONSTRAINT `FK_id_agente_id_usuario_create` FOREIGN KEY (`id_usuario_create`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `agente_subagente`
-- ----------------------------
DROP TABLE IF EXISTS `agente_subagente`;
CREATE TABLE `agente_subagente` (
  `id_agente_subagente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id agente subagente',
  `id_agente` bigint(20) DEFAULT NULL COMMENT 'id de agente',
  `id_subagente` bigint(20) DEFAULT NULL COMMENT 'id de subagente',
  PRIMARY KEY (`id_agente_subagente`),
  KEY `FK_id_agente_subagente_id_agente` (`id_agente`),
  KEY `FK_FK_id_agente_subagente_id_sub` (`id_subagente`),
  CONSTRAINT `FK_FK_id_agente_subagente_id_sub` FOREIGN KEY (`id_subagente`) REFERENCES `subagente` (`id_subagente`),
  CONSTRAINT `FK_id_agente_subagente_id_agente` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `archivos_cliente`
-- ----------------------------
DROP TABLE IF EXISTS `archivos_cliente`;
CREATE TABLE `archivos_cliente` (
  `id_archivos_cliente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id del archivo del cliente',
  `id_cliente` bigint(20) DEFAULT NULL COMMENT 'id del cliente',
  `nombre_archivos_cliente` varchar(100) DEFAULT NULL COMMENT 'nombre del archivo del cliente',
  `url_archivos_cliente` varchar(255) DEFAULT NULL COMMENT 'url del archivo del cliente',
  `fecha_archivos_cliente` datetime DEFAULT NULL,
  `id_usuario` bigint(20) DEFAULT NULL,
  `fecha_actualiza_archivos_cliente` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion de archivo del cliente',
  `estatus_archivos_cliente` smallint(1) DEFAULT '1' COMMENT 'estatus del archivo del cliente',
  PRIMARY KEY (`id_archivos_cliente`),
  KEY `id_archivo_cliente_cliente` (`id_cliente`),
  CONSTRAINT `archivos_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `calendario`
-- ----------------------------
DROP TABLE IF EXISTS `calendario`;
CREATE TABLE `calendario` (
  `id_calendario` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de calendario',
  `title_calendario` varchar(255) DEFAULT NULL COMMENT 'titulo de calendario',
  `start_calendario` varchar(100) DEFAULT NULL COMMENT 'fecha inicio calendario',
  `end_calendario` varchar(100) DEFAULT NULL COMMENT 'fecha fin calendario',
  `allDay_calendario` varchar(100) DEFAULT 'false' COMMENT 'todo el dia calendario',
  `url_calendario` varchar(255) DEFAULT NULL COMMENT 'url del calendario',
  `backgroundColor_calendario` varchar(7) DEFAULT NULL COMMENT 'fondo de calendario',
  `fecha_calendario` datetime DEFAULT NULL COMMENT 'fecha calendario',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_calendario` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza calendario',
  `estatus_calendario` smallint(1) DEFAULT '1' COMMENT 'estatus de calendario',
  PRIMARY KEY (`id_calendario`),
  KEY `FK_id_calendario_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_id_calendario_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `categoria_direcciones`
-- ----------------------------
DROP TABLE IF EXISTS `categoria_direcciones`;
CREATE TABLE `categoria_direcciones` (
  `id_categoria_direcciones` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de categoria de direcciones',
  `nombre_categoria_direcciones` varchar(255) DEFAULT NULL COMMENT 'nombre de categoria de direcciones',
  `fecha_categoria_direcciones` datetime DEFAULT NULL COMMENT 'fecha_categoria de direcciones',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_categoria_direcciones` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza categoria direcciones',
  `estatus_categoria_direcciones` smallint(1) DEFAULT '1' COMMENT 'estatus categoria direcciones',
  PRIMARY KEY (`id_categoria_direcciones`),
  KEY `FK_id_cate_direcciones_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_id_cate_direcciones_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `categoria_telefono`
-- ----------------------------
DROP TABLE IF EXISTS `categoria_telefono`;
CREATE TABLE `categoria_telefono` (
  `id_categoria_telefono` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de categoria de telefono',
  `nombre_categoria_telefono` varchar(255) DEFAULT NULL COMMENT 'nombre categoria telefono',
  `fecha_categoria_telefono` datetime DEFAULT NULL COMMENT 'fecha de categoria telefono',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_categoria_telefono` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza categoria telefono',
  `estatus_categoria_telefono` smallint(1) DEFAULT '1' COMMENT 'estatus de categoria de telefono',
  PRIMARY KEY (`id_categoria_telefono`),
  KEY `FK_id_categoria_empresa_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_id_categoria_empresa_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `categoria_url`
-- ----------------------------
DROP TABLE IF EXISTS `categoria_url`;
CREATE TABLE `categoria_url` (
  `id_categoria_url` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de categoria url',
  `nombre_categoria_url` varchar(255) DEFAULT NULL COMMENT 'nombre categoria url',
  `fecha_categoria_url` datetime DEFAULT NULL COMMENT 'fecha de categoria de url',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_categoria_url` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza categoria url',
  `estatus_categoria_url` smallint(1) DEFAULT '1' COMMENT 'estatus categoria url',
  PRIMARY KEY (`id_categoria_url`),
  KEY `FK_id_categoria_url_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_id_categoria_url_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ciudades`
-- ----------------------------
DROP TABLE IF EXISTS `ciudades`;
CREATE TABLE `ciudades` (
  `id_ciudades` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre_ciudades` varchar(100) DEFAULT NULL,
  `id_estados` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_ciudades`)
) ENGINE=InnoDB AUTO_INCREMENT=2595 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `cliente`
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id_cliente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id del cliente',
  `id_subagente` bigint(20) DEFAULT NULL COMMENT 'id de agente',
  `nombre_cliente` varchar(255) DEFAULT NULL COMMENT 'nombre del cliente',
  `razon_cliente` varchar(255) DEFAULT NULL,
  `calle_cliente` varchar(100) DEFAULT NULL COMMENT 'calle del cliente',
  `lada_cliente` int(3) DEFAULT NULL COMMENT 'lada del telefono del cliente',
  `telefono_cliente` int(7) DEFAULT NULL COMMENT 'telefono del cliente',
  `id_ciudad` bigint(20) DEFAULT '1' COMMENT 'id de la ciudad',
  `localidad_cliente` varchar(100) DEFAULT NULL COMMENT 'localidad cliente',
  `correo_cliente` varchar(255) DEFAULT NULL COMMENT 'correo del cliente',
  `contacto_cliente` varchar(100) DEFAULT NULL COMMENT 'nombre del contacto del cliente',
  `rfc_cliente` varchar(12) DEFAULT NULL COMMENT 'rfc del cliente',
  `numInt_cliente` varchar(100) DEFAULT NULL COMMENT 'numero int del cliente',
  `numExt_cliente` varchar(100) DEFAULT NULL COMMENT 'numero ext del cliente',
  `colonia_cliente` varchar(100) DEFAULT NULL COMMENT 'colonia del cliente',
  `cp_cliente` int(5) DEFAULT NULL COMMENT 'codigo postal del cliente',
  `id_usuario` bigint(20) DEFAULT '1' COMMENT 'id de usuario',
  `id_usuarioCreate` bigint(20) DEFAULT NULL COMMENT 'id de usuario creador',
  `estatus_cliente` smallint(1) NOT NULL DEFAULT '1' COMMENT 'estatus del cliente',
  PRIMARY KEY (`id_cliente`),
  KEY `FK_cliente_ciudades_id_ciudades` (`id_ciudad`),
  KEY `FK_id_usuario_id_cliente` (`id_usuario`),
  KEY `FK_id_cliente_id_subagente` (`id_subagente`),
  KEY `FK_id_usuarioCreate_id_usuario` (`id_usuarioCreate`),
  CONSTRAINT `FK_cliente_ciudades_id_ciudades` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudades`),
  CONSTRAINT `FK_id_cliente_id_subagente` FOREIGN KEY (`id_subagente`) REFERENCES `subagente` (`id_subagente`),
  CONSTRAINT `FK_id_usuarioCreate_id_usuario` FOREIGN KEY (`id_usuarioCreate`) REFERENCES `_usuarios` (`id_usuario`),
  CONSTRAINT `FK_id_usuario_id_cliente` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='catalogo de clientes';

-- ----------------------------
--  Table structure for `cliente_tipo_contacto`
-- ----------------------------
DROP TABLE IF EXISTS `cliente_tipo_contacto`;
CREATE TABLE `cliente_tipo_contacto` (
  `id_cliente_tipo_contacto` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de tipo de contacto en cliente',
  `id_cliente` bigint(20) DEFAULT NULL COMMENT 'id del cliente',
  `id_tipo_contacto` bigint(20) DEFAULT NULL COMMENT 'id del tipo de contacto',
  `nombre_cliente_tipo_contacto` varchar(100) DEFAULT NULL COMMENT 'nombre del contacto a cliente',
  `puesto_cliente_tipo_contacto` varchar(100) DEFAULT NULL COMMENT 'puesto de contacto a cliente',
  `valor_cliente_tipo_contacto` varchar(255) DEFAULT NULL COMMENT 'valor del tipo de contacto del cliente',
  `fecha_cliente_tipo_contacto` datetime DEFAULT NULL COMMENT 'fecha de tipo de contacto en cliente',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualizacion_cliente_tipo_contacto` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion de tipo de contacto del cliente',
  `estatus_cliente_tipo_contacto` smallint(1) DEFAULT '1' COMMENT 'estatus del tipo de contacto del cliente',
  PRIMARY KEY (`id_cliente_tipo_contacto`),
  KEY `FK_cliente_tipo_contacto__usuarios_id_usuario` (`id_usuario`),
  KEY `FK_cliente_tipo_contacto_cliente_id_cliente` (`id_cliente`),
  KEY `FK_cliente_tipo_contacto_tipo_contacto_id_tipo_contacto` (`id_tipo_contacto`),
  CONSTRAINT `FK_cliente_tipo_contacto_cliente_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `FK_cliente_tipo_contacto_tipo_contacto_id_tipo_contacto` FOREIGN KEY (`id_tipo_contacto`) REFERENCES `tipo_contacto` (`id_tipo_contacto`),
  CONSTRAINT `FK_cliente_tipo_contacto__usuarios_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de tipo de contacto en cliente';

-- ----------------------------
--  Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id_config` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de configuracion',
  `id_agente` bigint(20) DEFAULT NULL COMMENT 'id de agente',
  `navbar_config` varchar(100) DEFAULT NULL COMMENT 'color de barra superior',
  `panel_heading_config` varchar(100) DEFAULT NULL COMMENT 'color de  barra contenedor',
  `nombre_session_config` varchar(100) DEFAULT NULL COMMENT 'nombre de variable de sesion de usuario',
  `fecha_config` datetime DEFAULT NULL COMMENT 'fecha de config',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_config` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza config',
  `estatus_config` smallint(1) DEFAULT '1' COMMENT 'estatus de config',
  PRIMARY KEY (`id_config`),
  KEY `id_config_id_agente` (`id_agente`),
  CONSTRAINT `id_config_id_agente` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `contenido_tablero`
-- ----------------------------
DROP TABLE IF EXISTS `contenido_tablero`;
CREATE TABLE `contenido_tablero` (
  `id_contenido_tablero` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de contenido tablero',
  `nombre_contenido_tablero` varchar(100) DEFAULT NULL COMMENT 'nombre de contenido tablero',
  `estatus_contenido_tablero` smallint(1) DEFAULT '1' COMMENT 'estatus de contenido tablero',
  PRIMARY KEY (`id_contenido_tablero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de contenedores para dasboard';

-- ----------------------------
--  Table structure for `correo_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `correo_agenda`;
CREATE TABLE `correo_agenda` (
  `id_correo_agenda` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de correo de agenda',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `valor_correo_agenda` varchar(255) DEFAULT NULL COMMENT 'valor de correo de agenda',
  `fecha_correo_agenda` datetime DEFAULT NULL COMMENT 'fecha de correo de agenda',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_correo_agenda` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza correo agenda',
  `estatus_correo_agenda` smallint(1) DEFAULT '1' COMMENT 'estatus de correo de agenda',
  PRIMARY KEY (`id_correo_agenda`),
  KEY `FK_id_correo_agenda_id_agenda` (`id_agenda`),
  KEY `FK_id_correo_agenda_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_id_correo_agenda_id_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `FK_id_correo_agenda_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ctas_banco`
-- ----------------------------
DROP TABLE IF EXISTS `ctas_banco`;
CREATE TABLE `ctas_banco` (
  `id_ctas_banco` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de cueta de banco',
  `numero_ctas_banco` varchar(255) DEFAULT NULL COMMENT 'numero de cuenta de banco',
  `monto_ctas_banco` decimal(11,2) DEFAULT NULL COMMENT 'monto de la cuenta',
  `registradora_ctas_banco` smallint(1) DEFAULT '0' COMMENT 'maraca si es caja registradora',
  `banco_ctas_banco` varchar(100) DEFAULT NULL COMMENT 'nombre del banco',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_ctas_banco` datetime DEFAULT NULL COMMENT 'fecha de cuenta de bancos',
  `estatus_ctas_banco` smallint(1) DEFAULT '1' COMMENT 'estatus de cuenta ',
  `fecha_actualizacion_ctas_banco` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion de centas de banco',
  PRIMARY KEY (`id_ctas_banco`),
  KEY `FK_ctas_banco__usuarios_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_ctas_banco__usuarios_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='catalogo de cuenta de banco';

-- ----------------------------
--  Table structure for `datos_empresa`
-- ----------------------------
DROP TABLE IF EXISTS `datos_empresa`;
CREATE TABLE `datos_empresa` (
  `id_datos_empresa` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de los datos de empresa',
  `id_agente` bigint(20) DEFAULT NULL COMMENT 'id agente',
  `razon_datos_empresa` varchar(255) DEFAULT NULL COMMENT 'razon de la empresa',
  `rfc_datos_empresa` varchar(100) DEFAULT NULL COMMENT 'rfc de la empresa',
  `domicilio_datos_empresa` text COMMENT 'domicilio de la empresa',
  `logo_datos_empresa` varchar(100) DEFAULT NULL COMMENT 'logo de la empresa',
  `fecha_datos_empresa` datetime DEFAULT NULL COMMENT 'fecha datos empresa',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id usuario',
  `fecha_actualiza_datos_empresa` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza datos empresa',
  `estatus_datos_empresa` smallint(1) DEFAULT '1' COMMENT 'estatus datos empresa',
  PRIMARY KEY (`id_datos_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `datos_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `datos_usuario`;
CREATE TABLE `datos_usuario` (
  `id_datos_usuario` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de datos del usuario',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `imagen_datos_usuario` varchar(100) DEFAULT 'default.jpg' COMMENT 'imagen del usuario',
  `calle_datos_usuario` varchar(255) DEFAULT NULL COMMENT 'calle del usuario',
  `numExt_datos_usuario` varchar(100) DEFAULT NULL COMMENT 'num ext usuario',
  `numInt_datos_usuario` varchar(100) DEFAULT NULL COMMENT 'num int usuario',
  `colonia_datos_usuario` varchar(255) DEFAULT NULL COMMENT 'colonia usuario',
  `cp_datos_usuario` int(5) DEFAULT NULL,
  `id_ciudades` bigint(20) DEFAULT NULL COMMENT 'id de ciudad',
  `fecha_cumpleanios_datos_usuario` date DEFAULT NULL COMMENT 'fecha cumple usuario',
  `correo_datos_usuario` varchar(255) DEFAULT NULL COMMENT 'correo del usuario',
  `lada_datos_usuario` int(3) DEFAULT NULL COMMENT 'lada usuario',
  `telefono_datos_usuario` int(7) DEFAULT NULL COMMENT 'telefono usuario',
  `fecha_datos_usuario` datetime DEFAULT NULL COMMENT 'fecha de usuario',
  `estatus_datos_usuario` smallint(1) DEFAULT '1' COMMENT 'estatus del usuario',
  PRIMARY KEY (`id_datos_usuario`),
  KEY `FK_id_usuario_datos_usuario` (`id_usuario`),
  KEY `FK_id_ciudad_datos_usuario` (`id_ciudades`),
  CONSTRAINT `FK_id_ciudad_datos_usuario` FOREIGN KEY (`id_ciudades`) REFERENCES `ciudades` (`id_ciudades`),
  CONSTRAINT `FK_id_usuario_datos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `direccion_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `direccion_agenda`;
CREATE TABLE `direccion_agenda` (
  `id_direccion_agenda` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id direccion agenda',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `id_categoria_direcciones` bigint(20) DEFAULT NULL COMMENT 'id de categoria de direccion agenda',
  `calle_direccion_agenda` varchar(255) DEFAULT NULL COMMENT 'calle direccion agenda',
  `numInt_direccion_agenda` varchar(100) DEFAULT NULL COMMENT 'numero anterior de direccion agenda',
  `numExt_direccion_agenda` varchar(100) DEFAULT NULL COMMENT 'numero exterior direccion agenda',
  `id_ciudades` bigint(20) DEFAULT NULL COMMENT 'id de ciudad',
  `cp_direccion_agenda` int(5) DEFAULT NULL COMMENT 'codigo postal direccion agenda',
  `colonia_direccion_agenda` varchar(100) DEFAULT NULL COMMENT 'colonia direccion agenda',
  `fecha_direccion_agenda` datetime DEFAULT NULL COMMENT 'fecha direccion agenda',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_direccion_agenda` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza direccion agenda',
  `estatus_direccion_agenda` smallint(1) DEFAULT '1' COMMENT 'estatus direccion agenda',
  PRIMARY KEY (`id_direccion_agenda`),
  KEY `FK_direccion_agenda_id_agenda` (`id_agenda`),
  KEY `FK_direccion_agenda_id_cat_direccion` (`id_categoria_direcciones`),
  KEY `FK_direccion_agenda_id_usuario` (`id_usuario`),
  KEY `FK_direccion_agenda_id_ciudades` (`id_ciudades`),
  CONSTRAINT `FK_direccion_agenda_id_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `FK_direccion_agenda_id_cat_direccion` FOREIGN KEY (`id_categoria_direcciones`) REFERENCES `categoria_direcciones` (`id_categoria_direcciones`),
  CONSTRAINT `FK_direccion_agenda_id_ciudades` FOREIGN KEY (`id_ciudades`) REFERENCES `ciudades` (`id_ciudades`),
  CONSTRAINT `FK_direccion_agenda_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `estados`
-- ----------------------------
DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados` (
  `id_estados` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre_estados` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_estados`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `forma_pago`
-- ----------------------------
DROP TABLE IF EXISTS `forma_pago`;
CREATE TABLE `forma_pago` (
  `id_forma_pago` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de forma de pago',
  `nombre_forma_pago` varchar(100) DEFAULT NULL COMMENT 'nombre de forma de pago',
  `descripcion_forma_pago` varchar(255) DEFAULT NULL COMMENT 'descripcion de forma de pago',
  `estatus_forma_pago` smallint(1) DEFAULT '1' COMMENT 'estatus de forma de pago',
  PRIMARY KEY (`id_forma_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='catalogo de forma de pago';

-- ----------------------------
--  Table structure for `medio_contacto`
-- ----------------------------
DROP TABLE IF EXISTS `medio_contacto`;
CREATE TABLE `medio_contacto` (
  `id_medio_contacto` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de medio de contacto',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `id_tipo_contacto` bigint(20) DEFAULT NULL COMMENT 'id de tipo de contacto',
  `detalle_medio_contacto` varchar(255) DEFAULT NULL COMMENT 'detalle de medio de contacto',
  `fecha_medio_contacto` datetime DEFAULT NULL COMMENT 'fecha de medio de contacto',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_medio_contacto` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza medio de contacto',
  `estatus_medio_contacto` smallint(1) DEFAULT '1' COMMENT 'estatus de medio de contacto',
  PRIMARY KEY (`id_medio_contacto`),
  KEY `FK_medio_contacto_id_agenda` (`id_agenda`),
  KEY `FK_medio_contacto_id_tipo_contacto` (`id_tipo_contacto`),
  KEY `FK_medio_contacto_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_medio_contacto_id_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `FK_medio_contacto_id_tipo_contacto` FOREIGN KEY (`id_tipo_contacto`) REFERENCES `tipo_contacto` (`id_tipo_contacto`),
  CONSTRAINT `FK_medio_contacto_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `permisos_acciones`
-- ----------------------------
DROP TABLE IF EXISTS `permisos_acciones`;
CREATE TABLE `permisos_acciones` (
  `id_acciones` bigint(20) DEFAULT NULL COMMENT 'id de acciones',
  `id_perfil` bigint(20) DEFAULT NULL COMMENT 'id de perfil',
  KEY `FK_id_permisos_acciones_id_acciones` (`id_acciones`),
  KEY `FK_id_permisos_aciones_id_perfil` (`id_perfil`),
  CONSTRAINT `FK_id_permisos_acciones_id_acciones` FOREIGN KEY (`id_acciones`) REFERENCES `acciones` (`id_acciones`),
  CONSTRAINT `FK_id_permisos_aciones_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `_perfiles` (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `poliza`
-- ----------------------------
DROP TABLE IF EXISTS `poliza`;
CREATE TABLE `poliza` (
  `id_poliza` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de poliza',
  `id_cliente` bigint(20) DEFAULT NULL COMMENT 'id de cliente',
  `id_tipo_poliza` bigint(20) DEFAULT NULL COMMENT 'id de tipo de poliza',
  `prima_anual_poliza` decimal(10,2) DEFAULT NULL COMMENT 'prima anual de la poliza',
  `tipo_moneda_poliza` smallint(1) DEFAULT '1' COMMENT '1 pesos 2 dolares 3 udis',
  `id_tipo_pago` bigint(20) NOT NULL COMMENT 'id de tipo de pago',
  `fechaInicial_poliza` datetime DEFAULT NULL COMMENT 'fecha inicial',
  `fechaFin_poliza` datetime DEFAULT NULL COMMENT 'fecha final ',
  `observaciones_poliza` varchar(255) DEFAULT NULL COMMENT 'observaciones de la poliza',
  `archivo_poliza` varchar(255) DEFAULT NULL COMMENT 'archivo de la poliza',
  `num_poliza` varchar(100) DEFAULT NULL COMMENT 'nuero de poliza',
  `fecha_poliza` datetime DEFAULT NULL COMMENT 'fecha de la poliza',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario ',
  `fecha_actualiza_poliza` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion a la poliza',
  `estatus_poliza` smallint(1) DEFAULT '1' COMMENT 'estatus de la poliza',
  PRIMARY KEY (`id_poliza`),
  KEY `FK_poliza_cliente` (`id_cliente`),
  KEY `FK_poliza_tipo_pago` (`id_tipo_pago`),
  KEY `FK_poliza_id_usuario` (`id_usuario`),
  KEY `FK_poliza_tipo_poliza` (`id_tipo_poliza`),
  CONSTRAINT `poliza_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `poliza_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`),
  CONSTRAINT `poliza_ibfk_3` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`),
  CONSTRAINT `poliza_ibfk_4` FOREIGN KEY (`id_tipo_poliza`) REFERENCES `tipo_poliza` (`id_tipo_poliza`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de polizas';

-- ----------------------------
--  Table structure for `proveedor`
-- ----------------------------
DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE `proveedor` (
  `id_proveedor` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id del proveedor',
  `nombre_proveedor` varchar(255) DEFAULT NULL COMMENT 'nombre del proveedor',
  `razon_proveedor` varchar(255) DEFAULT NULL,
  `calle_proveedor` varchar(100) DEFAULT NULL COMMENT 'calle del proveedor',
  `lada_proveedor` int(3) DEFAULT NULL COMMENT 'lada del telefono del proveedor',
  `telefono_proveedor` int(7) DEFAULT NULL COMMENT 'telefono del proveedor',
  `id_ciudad` bigint(20) DEFAULT '1' COMMENT 'id de la ciudad',
  `correo_proveedor` varchar(255) DEFAULT NULL COMMENT 'correo del proveedor',
  `contacto_proveedor` varchar(100) DEFAULT NULL COMMENT 'nombre del contacto del proveedor',
  `rfc_proveedor` varchar(12) DEFAULT NULL COMMENT 'rfc del proveedor',
  `numInt_proveedor` varchar(100) DEFAULT NULL COMMENT 'numero int del proveedor',
  `numExt_proveedor` varchar(100) DEFAULT NULL COMMENT 'numero ext del proveedor',
  `colonia_proveedor` varchar(100) DEFAULT NULL COMMENT 'colonia del proveedor',
  `cp_proveedor` int(5) DEFAULT NULL COMMENT 'codigo postal del proveedor',
  `saldo_proveedor` decimal(11,2) DEFAULT '0.00' COMMENT 'saldo del porveedor',
  `fecha_proveedor` datetime DEFAULT '2014-10-16 11:18:44' COMMENT 'fecah del proveedor',
  `id_usuario` bigint(20) DEFAULT '1' COMMENT 'id de usuario',
  `fecha_actualiza_proveedor` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualiza proveedor',
  `estatus_proveedor` smallint(1) NOT NULL DEFAULT '1' COMMENT 'estatus del proveedor',
  PRIMARY KEY (`id_proveedor`),
  KEY `FK_proveedor_ciudades_id_ciudades` (`id_ciudad`),
  KEY `FK_proveedor_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_proveedor_ciudades_id_ciudades` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudades`),
  CONSTRAINT `FK_proveedor_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de proveedores';

-- ----------------------------
--  Table structure for `proveedor_tipo_contacto`
-- ----------------------------
DROP TABLE IF EXISTS `proveedor_tipo_contacto`;
CREATE TABLE `proveedor_tipo_contacto` (
  `id_proveedor_tipo_contacto` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de tipo de contacto en proveedor',
  `id_proveedor` bigint(20) DEFAULT NULL COMMENT 'id del proveedor',
  `id_tipo_contacto` bigint(20) DEFAULT NULL COMMENT 'id del tipo de contacto',
  `nombre_proveedor_tipo_contacto` varchar(100) DEFAULT NULL COMMENT 'nombre del contacto a proveedor',
  `puesto_proveedor_tipo_contacto` varchar(100) DEFAULT NULL COMMENT 'puesto de contacto a proveedor',
  `valor_proveedor_tipo_contacto` varchar(255) DEFAULT NULL COMMENT 'valor del tipo de contacto del proveedor',
  `fecha_proveedor_tipo_contacto` datetime DEFAULT NULL COMMENT 'fecha de tipo de contacto en proveedor',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualizacion_proveedor_tipo_contacto` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion de tipo de contacto del proveedor',
  `estatus_proveedor_tipo_contacto` smallint(1) DEFAULT '1' COMMENT 'estatus del tipo de contacto del proveedor',
  PRIMARY KEY (`id_proveedor_tipo_contacto`),
  KEY `FK_proveedor_tipo_contacto__usuarios_id_usuario` (`id_usuario`),
  KEY `FK_proveedor_tipo_contacto_proveedor_id_proveedor` (`id_proveedor`),
  KEY `FK_proveedor_tipo_contacto_tipo_contacto_id_tipo_contacto` (`id_tipo_contacto`),
  CONSTRAINT `FK_proveedor_tipo_contacto_proveedor_id_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  CONSTRAINT `FK_proveedor_tipo_contacto_tipo_contacto_id_tipo_contacto` FOREIGN KEY (`id_tipo_contacto`) REFERENCES `tipo_contacto` (`id_tipo_contacto`),
  CONSTRAINT `FK_proveedor_tipo_contacto__usuarios_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de tipo de contacto en proveedor';

-- ----------------------------
--  Table structure for `subagente`
-- ----------------------------
DROP TABLE IF EXISTS `subagente`;
CREATE TABLE `subagente` (
  `id_subagente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de subagente',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_subagente` datetime DEFAULT NULL COMMENT 'fecha de subagente',
  `id_usuario_create` bigint(20) DEFAULT NULL COMMENT 'id de usuario create',
  `fecha_actualiza_subagente` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza subagente',
  `estatus_subagente` smallint(1) DEFAULT '1' COMMENT 'estatus de subagente',
  PRIMARY KEY (`id_subagente`),
  KEY `FK_id_subagente_id_usuario` (`id_usuario`),
  KEY `FK_id_subagente_id_usuario_create` (`id_usuario_create`),
  CONSTRAINT `FK_id_subagente_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`),
  CONSTRAINT `FK_id_subagente_id_usuario_create` FOREIGN KEY (`id_usuario_create`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `telefono_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `telefono_agenda`;
CREATE TABLE `telefono_agenda` (
  `id_telefono_agenda` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de telefono de agenda',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `id_categoria_telefono` bigint(20) DEFAULT NULL,
  `lada_telefono_agenda` int(3) DEFAULT NULL COMMENT 'lada de telefono de agenda',
  `tel_telefono_agenda` int(7) DEFAULT NULL COMMENT 'lada de telefono de agenda',
  `fecha_telefono_agenda` datetime DEFAULT NULL COMMENT 'fecha de telefono agenda',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_telefono_agenda` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualiza telefono agenda',
  `estatus_telefono_agenda` smallint(1) DEFAULT '1' COMMENT 'estatus de telefono agenda',
  PRIMARY KEY (`id_telefono_agenda`),
  KEY `FK_telefono_agenda_id_agenda` (`id_agenda`),
  KEY `FK_telefono_agenda_id_categoria_tel` (`id_categoria_telefono`),
  KEY `FK_telefono_agenda_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_telefono_agenda_id_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `FK_telefono_agenda_id_categoria_tel` FOREIGN KEY (`id_categoria_telefono`) REFERENCES `categoria_telefono` (`id_categoria_telefono`),
  CONSTRAINT `FK_telefono_agenda_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `tipo_contacto`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_contacto`;
CREATE TABLE `tipo_contacto` (
  `id_tipo_contacto` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id del tipo de contacto',
  `id_subagente` bigint(20) DEFAULT NULL COMMENT 'id de subagente',
  `nombre_tipo_contacto` varchar(100) DEFAULT NULL COMMENT 'nombre del tipo de contacto',
  `descripcion_tipo_contacto` varchar(255) DEFAULT NULL COMMENT 'descripcion de tipo de contacto',
  `fecha_tipo_contacto` datetime DEFAULT NULL COMMENT 'fecha de tipo de contacto',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualizacion_tipo_contacto` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion de tipo de contacto',
  `estatus_tipo_contacto` smallint(1) DEFAULT '1' COMMENT 'estatus del tipo de contacto',
  PRIMARY KEY (`id_tipo_contacto`),
  KEY `FK_tipo_contacto__usuarios_id_usuario` (`id_usuario`),
  KEY `FK_tipo_contacto_id_subagente` (`id_subagente`),
  CONSTRAINT `FK_tipo_contacto_id_subagente` FOREIGN KEY (`id_subagente`) REFERENCES `subagente` (`id_subagente`),
  CONSTRAINT `FK_tipo_contacto__usuarios_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='catalogo de tipos de contacto';

-- ----------------------------
--  Table structure for `tipo_pago`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_pago`;
CREATE TABLE `tipo_pago` (
  `id_tipo_pago` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id del tipo de pago',
  `id_subagente` bigint(20) DEFAULT NULL COMMENT 'id de subagente',
  `nombre_tipo_pago` varchar(100) DEFAULT NULL COMMENT 'nombre del tipo de pago',
  `descripcion_tipo_pago` varchar(255) DEFAULT NULL COMMENT 'descripcion de forma de pago',
  `fecha_tipo_pago` datetime DEFAULT NULL COMMENT 'fecha tipo de pago',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_tipo_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza tipo pago',
  `estatus_tipo_pago` smallint(1) DEFAULT '1' COMMENT 'estatus del tipo de pago',
  PRIMARY KEY (`id_tipo_pago`),
  KEY `FK_tipo_pago_id_subagente` (`id_subagente`),
  KEY `FK_tipo_pago_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_tipo_pago_id_subagente` FOREIGN KEY (`id_subagente`) REFERENCES `subagente` (`id_subagente`),
  CONSTRAINT `FK_tipo_pago_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='catalogo de tipos de pago';

-- ----------------------------
--  Table structure for `tipo_poliza`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_poliza`;
CREATE TABLE `tipo_poliza` (
  `id_tipo_poliza` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de tipo de poliza',
  `id_subagente` bigint(20) DEFAULT NULL COMMENT 'id de subagente',
  `nombre_tipo_poliza` varchar(100) DEFAULT NULL COMMENT 'nombre de tipo de poliza',
  `descripcion_tipo_poliza` varchar(255) DEFAULT NULL COMMENT 'descripcion de tipo de poliza',
  `fecha_tipo_poliza` datetime DEFAULT NULL COMMENT 'fecha de tipo de poliza',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_tipo_poliza` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza tipo de poliza',
  `estatus_tipo_poliza` smallint(1) DEFAULT '1' COMMENT 'estatus de tipo de poliza',
  PRIMARY KEY (`id_tipo_poliza`),
  KEY `FK_tipo_poliza_id_subagente` (`id_subagente`),
  KEY `FK_tipo_poliza_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_tipo_poliza_id_subagente` FOREIGN KEY (`id_subagente`) REFERENCES `subagente` (`id_subagente`),
  CONSTRAINT `FK_tipo_poliza_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COMMENT='catalogo de tipos de poliza';

-- ----------------------------
--  Table structure for `url_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `url_agenda`;
CREATE TABLE `url_agenda` (
  `id_url_agenda` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id de url',
  `id_agenda` bigint(20) DEFAULT NULL COMMENT 'id de agenda',
  `id_categoria_url` bigint(20) DEFAULT NULL COMMENT 'id de categoria url',
  `nombre_url_agenda` text COMMENT 'url de la agenda',
  `fecha_url_agenda` datetime DEFAULT NULL COMMENT 'fecha de url de agenda',
  `id_usuario` bigint(20) DEFAULT NULL COMMENT 'id de usuario',
  `fecha_actualiza_url_agenda` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualiza url de agenda',
  `estatus_url_agenda` smallint(1) DEFAULT '1' COMMENT 'estatus de la url de la egenda',
  PRIMARY KEY (`id_url_agenda`),
  KEY `FK_url_agenda_id_agenda` (`id_agenda`),
  KEY `FK_url_agenda_id_cat_url` (`id_categoria_url`),
  KEY `FK_url_agenda_id_usuario` (`id_usuario`),
  CONSTRAINT `FK_url_agenda_id_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `FK_url_agenda_id_cat_url` FOREIGN KEY (`id_categoria_url`) REFERENCES `categoria_url` (`id_categoria_url`),
  CONSTRAINT `FK_url_agenda_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `_modulos`
-- ----------------------------
DROP TABLE IF EXISTS `_modulos`;
CREATE TABLE `_modulos` (
  `id_modulo` bigint(20) NOT NULL,
  `nombre_modulo` varchar(30) NOT NULL,
  `id_padre` int(11) NOT NULL,
  `orden_modulo` int(11) NOT NULL,
  `archivo_modulo` varchar(200) DEFAULT NULL,
  `icono_modulo` varchar(100) DEFAULT NULL COMMENT 'icono en el sistema',
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `_perfiles`
-- ----------------------------
DROP TABLE IF EXISTS `_perfiles`;
CREATE TABLE `_perfiles` (
  `id_perfil` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_agente` bigint(20) DEFAULT NULL COMMENT 'id de agente',
  `tipo_perfil` smallint(1) DEFAULT '0' COMMENT 'tipo de perfil 1 agencia 2 agente 3 subagente 4 ninguno',
  `nombre_perfil` varchar(100) NOT NULL,
  `estatus_perfiles` smallint(1) DEFAULT '1' COMMENT 'estatus del perfil',
  PRIMARY KEY (`id_perfil`),
  KEY `id_perfiles_id_agente` (`id_agente`),
  CONSTRAINT `id_perfiles_id_agente` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `_permisos`
-- ----------------------------
DROP TABLE IF EXISTS `_permisos`;
CREATE TABLE `_permisos` (
  `id_perfil` int(20) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  PRIMARY KEY (`id_perfil`,`id_modulo`),
  KEY `id_modulo` (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `_usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `_usuarios`;
CREATE TABLE `_usuarios` (
  `id_usuario` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_perfil` bigint(20) NOT NULL,
  `nombre_usuario` varchar(150) NOT NULL,
  `login_usuario` varchar(200) NOT NULL,
  `password_usuario` varchar(200) NOT NULL,
  `token_usuario` varchar(200) DEFAULT NULL,
  `fecha_creacion_usuario` datetime DEFAULT NULL,
  `id_usuarioCreate` bigint(20) DEFAULT NULL,
  `fecha_actualizacion_usuario` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado_usuario` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  KEY `FK__usuarios__perfiles_id_perfil` (`id_perfil`),
  KEY `FK__usuarios__usuarios_id_usuario` (`id_usuarioCreate`),
  CONSTRAINT `FK__usuarios__perfiles_id_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `_perfiles` (`id_perfil`),
  CONSTRAINT `FK__usuarios__usuarios_id_usuario` FOREIGN KEY (`id_usuarioCreate`) REFERENCES `_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_clientes`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_clientes`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_clientes`(IN idCliente BIGINT(20))
BEGIN
 UPDATE cliente
SET
  estatus_cliente = 0
WHERE
  id_cliente = idCliente;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_contacto_agenda`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_contacto_agenda`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_contacto_agenda`(IN idContacto BIGINT(20), IN idUser BIGINT(20))
BEGIN
UPDATE
	agenda
SET
	estatus_agenda = 0,
	id_usuario = idUser
WHERE
	id_agenda = idContacto;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_documentoCliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_documentoCliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_documentoCliente`(IN idArchivo BIGINT(20))
BEGIN
UPDATE archivos_cliente
SET
	estatus_archivos_cliente = 0
WHERE
	id_archivos_cliente = idArchivo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_formaContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_formaContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_formaContacto`(IN idContacto BIGINT(20))
BEGIN
  UPDATE cliente_tipo_contacto
  SET
    estatus_cliente_tipo_contacto = 0
  WHERE
    id_cliente_tipo_contacto = idContacto;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_perfil`(IN idPerfil BIGINT(20))
BEGIN
  UPDATE _perfiles
  SET
    estatus_perfiles = 0
  WHERE
    id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_permisos_acciones`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_permisos_acciones`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_permisos_acciones`(IN idPerfil BIGINT(20))
BEGIN
DELETE FROM permisos_acciones
WHERE
	id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_permisos_modulos`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_permisos_modulos`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_permisos_modulos`(IN idPerfil BIGINT(20))
BEGIN
DELETE FROM _permisos WHERE id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_permisos_tablero`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_permisos_tablero`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_permisos_tablero`(IN idPerfil bigint(20))
BEGIN
  DELETE FROM permisos_tablero
  WHERE
    id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_tipo_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_tipo_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_tipo_contacto`(IN idTipo BIGINT(20))
BEGIN
  UPDATE tipo_contacto
  SET
    estatus_tipo_contacto = 0
  WHERE
    id_tipo_contacto = idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_usuario`(IN idUser BIGINT(20))
BEGIN
  UPDATE _usuarios
  SET
    estado_usuario = 0
  WHERE
    id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_delete_usuarioId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_delete_usuarioId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_delete_usuarioId`(IN idUser BIGINT(20))
BEGIN
DELETE FROM _usuarios
WHERE
	id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_cliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_cliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_cliente`(IN idSubAgente BIGINT(20), IN nombre   VARCHAR(255),
												 IN razon VARCHAR(255),
                                                 IN rfc      VARCHAR(13),
                                                 IN calle    VARCHAR(100),
                                                 IN numInt   VARCHAR(100),
                                                 IN numExt   VARCHAR(100),
                                                 IN col      VARCHAR(100),
                                                 IN cp       INT(5),
       IN localidad VARCHAR(100),                                            IN city     BIGINT(20),
                                                 IN contacto VARCHAR(100),
                                                 IN correo   VARCHAR(255),
                                                 IN lada     INT(3),
                                                 IN tel      INT(7), IN idUser BIGINT(20), IN idUserCreate BIGINT(20))
BEGIN
  INSERT INTO cliente(id_subagente, nombre_cliente, razon_cliente, rfc_cliente, calle_cliente, numInt_cliente, numExt_cliente, 
colonia_cliente, cp_cliente, localidad_cliente, id_ciudad, contacto_cliente, correo_cliente, lada_cliente, telefono_cliente, id_usuario,
 id_usuarioCreate) 
  VALUES (idSubAgente, nombre, razon, rfc, calle, numInt, numExt, col, cp, localidad, city, contacto, correo, lada, tel, idUser, idUserCreate);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_config_empresa`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_config_empresa`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_config_empresa`(IN idAgente BIGINT(20), IN idUser BIGINT(20))
BEGIN
INSERT INTO config(id_agente, navbar_config, panel_heading_config, fecha_config, id_usuario)
VALUES(idAgente, "#2e2e2e", "#8e2f35", idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_contacto`(IN idCliente BIGINT(20),
                                            IN nombre VARCHAR(100),
                                            IN puesto VARCHAR(100),
                                            IN idTipo BIGINT(20),
                                            IN valor VARCHAR(255),
                                            IN idUser BIGINT(20))
BEGIN
  INSERT INTO cliente_tipo_contacto (id_cliente, nombre_cliente_tipo_contacto, puesto_cliente_tipo_contacto, id_tipo_contacto, valor_cliente_tipo_contacto, id_usuario, fecha_cliente_tipo_contacto) VALUES (idCliente, nombre, puesto, idTipo, valor, idUser, now());
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_correo_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_correo_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_correo_contacto`(IN idAgenda BIGINT(20), IN correo VARCHAR(255), IN idUser BIGINT(20))
BEGIN
INSERT INTO correo_agenda(id_agenda, valor_correo_agenda, fecha_correo_agenda, id_usuario)
VALUES(idAgenda, correo, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_datosEmpresa`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_datosEmpresa`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_datosEmpresa`(IN idAgente BIGINT(20), IN razon VARCHAR(255), IN rfc VARCHAR(100), IN domicilio TEXT, IN logo VARCHAR(100))
BEGIN
UPDATE
	datos_empresa
SET
	razon_datos_empresa = razon,
	rfc_datos_empresa = rfc,
	domicilio_datos_empresa = domicilio,
	logo_datos_empresa = logo
WHERE
	id_agente = idAgente ;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_datos_empresa`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_datos_empresa`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_datos_empresa`(IN idAgente BIGINT(20), IN idUser BIGINT(20))
BEGIN
INSERT INTO datos_empresa(id_agente, fecha_datos_empresa, id_usuario)
VALUES(idAgente, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_datos_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_datos_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_datos_usuario`(IN idUser BIGINT(20))
BEGIN
INSERT INTO datos_usuario(id_usuario, calle_datos_usuario, numExt_datos_usuario, numInt_datos_usuario, colonia_datos_usuario, cp_datos_usuario, id_ciudades, 
fecha_cumpleanios_datos_usuario, correo_datos_usuario, lada_datos_usuario, telefono_datos_usuario, fecha_datos_usuario)
VALUES (idUser, 'Calle', 'Num Ext', 'Num Int', 'Colonia', 37000, 462, '00-00-0000', 'Correo', 477, 7123456, NOW());
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_direccion_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_direccion_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_direccion_contacto`(IN idAgenda BIGINT(20), IN idCiudad BIGINT(20), IN idCat BIGINT(20), 
IN calle VARCHAR(255), IN numExt VARCHAR(100), IN numInt VARCHAR(100), IN colonia VARCHAR(100), IN cp INT(5), IN idUser BIGINT(20))
BEGIN
INSERT INTO direccion_agenda(id_agenda, id_categoria_direcciones, calle_direccion_agenda, numInt_direccion_agenda, numExt_direccion_agenda, id_ciudades, 
cp_direccion_agenda, colonia_direccion_agenda, fecha_direccion_agenda, id_usuario)
VALUES(idAgenda, idCat, calle, numInt, numExt, idCiudad, cp, colonia, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_documento_cliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_documento_cliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_documento_cliente`(IN idCliente BIGINT(20),
IN nombre VARCHAR(100), IN url VARCHAR(255), IN idUser BIGINT(20))
BEGIN
INSERT INTO archivos_cliente(id_cliente, nombre_archivos_cliente, url_archivos_cliente, fecha_archivos_cliente, id_usuario)
VALUES ( idCliente, nombre, url, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_evento_calendario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_evento_calendario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_evento_calendario`(IN titulo VARCHAR(255), IN fechaIni VARCHAR(100), IN fechaFin VARCHAR(100), IN url VARCHAR(255), IN background VARCHAR(7), IN idUser BIGINT(20))
BEGIN
INSERT INTO calendario (title_calendario, start_calendario, end_calendario, url_calendario, backgroundColor_calendario, fecha_calendario, id_usuario)
VALUES (titulo, fechaIni, fechaFin, url, background, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_nombre_agenda`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_nombre_agenda`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_nombre_agenda`(IN idSubAgente BIGINT(20), IN nombre VARCHAR(255), IN idUser BIGINT(20))
BEGIN
INSERT INTO agenda(id_subagente, nombre_agenda, fecha_agenda, fecha_cumple_agenda, id_usuario)
VALUES(idSubAgente, nombre, NOW(), '31-12-1969', idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_permisos_tablero`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_permisos_tablero`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_permisos_tablero`(IN idPerfil bigint(20), IN idCont bigint(20))
BEGIN
 INSERT INTO permisos_tablero( id_perfil, id_contenido_tablero)
 VALUES( idPerfil, idCont);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_permiso_accion`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_permiso_accion`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_permiso_accion`(IN idPerfil BIGINT(20), IN idAccion BIGINT(20))
BEGIN
INSERT INTO permisos_acciones (id_perfil, id_acciones)
VALUES (idPerfil, idAccion);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_permiso_modulo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_permiso_modulo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_permiso_modulo`(IN idPerfil BIGINT(20), IN idModulo BIGINT(20))
BEGIN
INSERT INTO _permisos (id_perfil,id_modulo) VALUES (idPerfil, idModulo);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_subAgente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_subAgente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_subAgente`(IN idUser BIGINT(20), IN idUserCreate BIGINT(20))
BEGIN
INSERT INTO subagente(id_usuario, fecha_subagente, id_usuario_create)
VALUES(idUser, NOW(), idUserCreate);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_telefono_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_telefono_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_telefono_contacto`(IN idAgenda BIGINT(20), IN idCat BIGINT(20), IN lada INT(3), IN tel INT(7), IN idUser BIGINT(20))
BEGIN
INSERT INTO telefono_agenda(id_agenda, id_categoria_telefono, lada_telefono_agenda, tel_telefono_agenda, fecha_telefono_agenda, id_usuario)
VALUES(idAgenda, idCat, lada, tel, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_tipoContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_tipoContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_tipoContacto`(IN idSubAgente BIGINT(20), IN nombre VARCHAR(100),
                                                IN txt VARCHAR(255),
                                                IN idUser BIGINT(20))
BEGIN
  INSERT INTO tipo_contacto (id_subagente, nombre_tipo_contacto, descripcion_tipo_contacto, id_usuario, fecha_tipo_contacto) 
	VALUES (idSubAgente, nombre, txt, idUser, now());
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_tipoPago`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_tipoPago`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_tipoPago`(IN idSubAgente BIGINT(20), IN nombre VARCHAR(100), IN txt VARCHAR(255))
BEGIN
INSERT INTO tipo_pago(id_subagente, nombre_tipo_pago, descripcion_tipo_pago)
VALUES(idSubAgente, nombre, txt);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_tipoPoliza`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_tipoPoliza`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_tipoPoliza`(IN idSubAgente BIGINT(20), IN nombre VARCHAR(100), IN descripcion VARCHAR(255))
BEGIN
	INSERT INTO tipo_poliza(id_subagente, nombre_tipo_poliza, descripcion_tipo_poliza)
	VALUES (idSubAgente, nombre, descripcion);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_insert_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_insert_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_insert_usuario`(IN login    VARCHAR(200),
                           IN nombre   VARCHAR(150),
                                              IN pass     VARCHAR(200),
                                              IN perfil   BIGINT(20), IN idUser BIGINT(20))
BEGIN
  INSERT INTO _usuarios (login_usuario, nombre_usuario, password_usuario, id_perfil, fecha_creacion_usuario, id_usuarioCreate) 
	VALUES (login, nombre, pass, perfil, NOW(), idUser);
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_ciudades_edoId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_ciudades_edoId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_ciudades_edoId`(IN idEdo BIGINT(20))
BEGIN
SELECT city.id_ciudades AS id
     , city.nombre_ciudades AS nombre
FROM
  ciudades city
WHERE
  city.id_estados = idEdo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_estados`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_estados`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_estados`()
BEGIN
  SELECT edos.id_estados AS id
       , edos.nombre_estados AS nombre
  FROM
    estados edos;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_perfiles`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_perfiles`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_perfiles`()
BEGIN
  SELECT perf.id_perfil AS id
       , perf.nombre_perfil AS nombre
  FROM
    _perfiles perf
    where perf.id_perfil <> 1
	AND perf.estatus_perfiles = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_perfiles_agente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_perfiles_agente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_perfiles_agente`(IN idAgente BIGINT(20))
BEGIN
	SELECT perf.id_perfil AS id
       , perf.nombre_perfil AS nombre
  FROM
			_perfiles perf
  WHERE 
			perf.id_perfil <> 1
  AND perf.id_agente = idAgente	
	AND perf.estatus_perfiles = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_tiposContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_tiposContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_tiposContacto`()
BEGIN
 SELECT tipo.id_tipo_contacto AS id
     , tipo.nombre_tipo_contacto AS nombre
FROM
  tipo_contacto tipo
WHERE
  tipo.estatus_tipo_contacto = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_tiposContacto_subAgente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_tiposContacto_subAgente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_tiposContacto_subAgente`(IN idSubAgente BIGINT(20))
BEGIN
SELECT
	tipCon.id_tipo_contacto AS id,
	tipCon.nombre_tipo_contacto AS nombre
FROM
	tipo_contacto AS tipCon
WHERE
	tipCon.id_subagente = idSubAgente
AND tipCon.estatus_tipo_contacto = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_tiposPago`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_tiposPago`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_tiposPago`()
BEGIN
	SELECT
		tipPag.id_tipo_pago AS id,
		tipPag.nombre_tipo_pago AS nombre
	FROM
		tipo_pago AS tipPag
	WHERE
		tipPag.estatus_tipo_pago = 1
ORDER BY
	nombre;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_lista_tiposPoliza`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_lista_tiposPoliza`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_lista_tiposPoliza`()
BEGIN
SELECT
tipPol.id_tipo_poliza AS id,
tipPol.nombre_tipo_poliza AS nombre
FROM
tipo_poliza AS tipPol
WHERE
tipPol.estatus_tipo_poliza = 1
ORDER BY
	nombre;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_agente_subagente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_agente_subagente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_agente_subagente`(IN idSubAgente BIGINT(20))
BEGIN
SELECT
	perf.id_agente AS idAgente
FROM
	subagente AS subAgen
INNER JOIN _usuarios AS usuar ON subAgen.id_usuario = usuar.id_usuario
INNER JOIN _perfiles AS perf ON usuar.id_perfil = perf.id_perfil
WHERE
	subAgen.id_subagente = idSubAgente;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_agente_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_agente_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_agente_usuario`(IN idUsuario BIGINT(20))
BEGIN
SELECT
	perf.id_agente AS idAgente
FROM
	_usuarios AS users
INNER JOIN _perfiles AS perf ON users.id_perfil = perf.id_perfil
WHERE
	users.id_usuario = idUsuario;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_clientes_nombre`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_clientes_nombre`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_clientes_nombre`(IN rfcCliente VARCHAR(255), IN idSubAgente BIGINT(20))
BEGIN
  SELECT clie.id_cliente AS id
FROM
  cliente clie
WHERE
  clie.rfc_cliente = rfcCliente
AND	
	clie.id_subagente = idSubAgente;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_clientes_nombreId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_clientes_nombreId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_clientes_nombreId`(IN idClie     BIGINT(20),
 IN idSubAgente BIGINT(20),                                                             IN rfcCliente VARCHAR(255))
BEGIN
  SELECT clie.id_cliente AS id
  FROM
    cliente clie
  WHERE
    clie.id_cliente <> idClie
	AND
		clie.id_subagente = idSubAgente
	AND
    clie.rfc_cliente = rfcCliente;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_cliente_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_cliente_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_cliente_contacto`(IN idContacto BIGINT(20))
BEGIN
SELECT
	clien.id_cliente AS id,
	clien.nombre_cliente AS nombre
FROM
	agenda_cliente AS agenCli
INNER JOIN cliente AS clien ON agenCli.id_cliente = clien.id_cliente
WHERE
	agenCli.id_agenda = idContacto;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_correos_agenda_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_correos_agenda_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_correos_agenda_id`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	correoAg.id_correo_agenda AS id,
	correoAg.valor_correo_agenda AS correo
FROM
	correo_agenda AS correoAg
WHERE
	correoAg.id_agenda = idAgenda
AND correoAg.estatus_correo_agenda = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_correo_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_correo_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_correo_contacto`(IN idCorreo BIGINT(20))
BEGIN
SELECT
	correoAg.id_correo_agenda AS id,
	correoAg.valor_correo_agenda AS correo
FROM
	correo_agenda AS correoAg
WHERE
	correoAg.id_correo_agenda = idCorreo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_calendario_id_user`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_calendario_id_user`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_calendario_id_user`(IN idUser BIGINT(20), IN fechaIni DATETIME, IN fechaFin DATETIME)
BEGIN
SELECT
	calen.id_calendario AS id,
	calen.title_calendario AS title,
	calen.start_calendario AS `start`,
	calen.end_calendario AS `end`,
	calen.backgroundColor_calendario AS backgroundColor	
FROM
	calendario AS calen
WHERE
	calen.id_usuario = idUser
AND calen.start_calendario >= fechaIni
AND calen.end_calendario <= fechaFin
AND calen.estatus_calendario = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_cliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_cliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_cliente`(IN idClie BIGINT(20))
BEGIN
  SELECT clien.id_cliente AS id
     , clien.nombre_cliente AS nombre
     , clien.razon_cliente AS razon
     , clien.lada_cliente AS lada
     , clien.telefono_cliente AS tel
		 , clien.localidad_cliente AS localidad
     , clien.id_ciudad AS idCity
     , edo.id_estados AS idEdo
		 , city.nombre_ciudades AS ciudad
     , clien.correo_cliente AS correo
     , clien.contacto_cliente AS contacto
     , clien.estatus_cliente AS estatus
     , clien.calle_cliente AS calle
     , clien.rfc_cliente AS rfc
     , clien.numInt_cliente AS numInt
     , clien.numExt_cliente AS numExt
     , clien.colonia_cliente AS col
     , clien.cp_cliente AS cp
     , clien.id_subagente AS subAgen
		 , clien.id_usuario AS idUser
FROM
  cliente clien
INNER JOIN ciudades city
ON clien.id_ciudad = city.id_ciudades
INNER JOIN estados edo
ON city.id_estados = edo.id_estados
WHERE
  clien.id_cliente = idClie;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_configuracion`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_configuracion`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_configuracion`(IN idAgente BIGINT(20))
BEGIN
SELECT
	conf.navbar_config AS colorTop,
	conf.panel_heading_config AS colorCont
FROM
	config AS conf
WHERE
	conf.id_agente = idAgente
AND
	conf.estatus_config = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_contacto`(IN idContacto BIGINT(20))
BEGIN
SELECT
	agenda.id_agenda AS id,
	agenda.prefijo_agenda AS prefijo,
	agenda.nombre_agenda AS nombre,
	agenda.segundo_nombre_agenda AS segNombre,
	agenda.apellido_agenda AS apellido,
	agenda.sufijo_agenda AS sufijo,
	agenda.opcion_contacto AS opcion,
	agenda.otro_agenda AS otro,
	agenda.descripcion_agenda AS txt,
	agenda.puesto_agenda AS puesto,
	agenda.empresa_agenda AS empresa,
	agenda.fecha_cumple_agenda AS cumple
FROM
	agenda
WHERE
	agenda.id_agenda = idContacto;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_documentoCliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_documentoCliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_documentoCliente`(IN idArchivo BIGINT(20))
BEGIN
SELECT
	archCli.id_archivos_cliente AS id,
	archCli.nombre_archivos_cliente AS nombre,
	archCli.url_archivos_cliente AS url
FROM
	archivos_cliente archCli
WHERE
archCli.id_archivos_cliente = idArchivo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_empresa`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_empresa`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_empresa`(IN idAgente BIGINT(20))
BEGIN
SELECT
	datEmpr.razon_datos_empresa AS razon,
	datEmpr.rfc_datos_empresa AS rfc,
	datEmpr.domicilio_datos_empresa AS domicilio,
	datEmpr.logo_datos_empresa AS logo
FROM
	datos_empresa AS datEmpr
WHERE
	datEmpr.id_agente = idAgente
AND
	datEmpr.estatus_datos_empresa = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_formaContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_formaContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_formaContacto`(IN idContacto BIGINT(20))
BEGIN
  SELECT cliTipo.id_cliente_tipo_contacto AS id
       , cliTipo.id_tipo_contacto AS idTipo
       , cliTipo.nombre_cliente_tipo_contacto AS nombre
       , cliTipo.puesto_cliente_tipo_contacto AS puesto
       , cliTipo.valor_cliente_tipo_contacto AS valor
  FROM
    cliente_tipo_contacto cliTipo
  WHERE
    id_cliente_tipo_contacto = idContacto;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_mi_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_mi_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_mi_perfil`(IN idUser BIGINT(20))
BEGIN
SELECT
	usuar.id_usuario AS id,
	perf.nombre_perfil AS perfil,
	usuar.nombre_usuario AS nombre,
	usuar.login_usuario AS login,
	usuar.password_usuario AS pass
FROM
	_usuarios AS usuar
INNER JOIN _perfiles AS perf ON usuar.id_perfil = perf.id_perfil
WHERE
	usuar.id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_modulo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_modulo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_modulo`(IN idMod BIGINT(20))
BEGIN
SELECT
	modu.nombre_modulo AS nombre
FROM
	_modulos AS modu
WHERE
	modu.id_modulo = idMod;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_moduloID`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_moduloID`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_moduloID`(IN idModulo BIGINT(20))
BEGIN
SELECT
	modu.nombre_modulo AS nombre,
	modu.id_padre AS idPadre,
	modu.orden_modulo AS orden,
	modu.archivo_modulo AS archivo,
	modu.icono_modulo AS icono
FROM
	_modulos AS modu
WHERE
	modu.id_modulo = idModulo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_perfil`(IN idPerfil BIGINT(20))
BEGIN
SELECT
	perf.tipo_perfil AS tipo,
	perf.nombre_perfil AS nombre
FROM
	_perfiles AS perf
WHERE
	perf.id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_tipoContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_tipoContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_tipoContacto`(IN idTipo BIGINT(20))
BEGIN
  SELECT tipContact.id_tipo_contacto AS id
     , tipContact.nombre_tipo_contacto AS nombre
     , tipContact.descripcion_tipo_contacto AS txt
		 , tipContact.id_subagente AS subAgen
FROM
  tipo_contacto tipContact
WHERE
  tipContact.id_tipo_contacto = idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_tipoPago`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_tipoPago`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_tipoPago`(IN idPago BIGINT(20))
BEGIN
	SELECT
		tipPag.id_tipo_pago AS id,
		tipPag.nombre_tipo_pago AS nombre,
		tipPag.descripcion_tipo_pago AS txt,
		tipPag.id_subagente AS subAgen
	FROM
		tipo_pago AS tipPag
	WHERE
		tipPag.id_tipo_pago = idPago;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_tipoPoliza`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_tipoPoliza`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_tipoPoliza`(IN idTipo BIGINT(20))
BEGIN
SELECT
	tipPol.id_tipo_poliza AS id,
	tipPol.id_subagente AS subAgen,
	tipPol.nombre_tipo_poliza AS nombre,
	tipPol.descripcion_tipo_poliza AS txt,
	tipPol.estatus_tipo_poliza AS estatus
FROM
	tipo_poliza AS tipPol
WHERE
	tipPol.id_tipo_poliza = idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_user_login`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_user_login`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_user_login`(IN login VARCHAR(200), IN pass VARCHAR(255))
BEGIN
SELECT
	u.id_usuario AS id,
	u.nombre_usuario AS usuario,
	u.login_usuario AS login,
	u.id_perfil AS idPerfil,
	p.nombre_perfil AS perfil,
  p.tipo_perfil AS tipo
FROM
	_usuarios u
INNER JOIN _perfiles p ON u.id_perfil = p.id_perfil
AND login_usuario = CONVERT(login using utf8) collate utf8_general_ci 
AND password_usuario = md5(pass)
AND estado_usuario = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_datos_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_datos_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_datos_usuario`(IN idUser BIGINT(20))
BEGIN
SELECT
	datUsu.id_datos_usuario AS id,
	datUsu.id_usuario AS idUser,
	datUsu.calle_datos_usuario AS calle,
	datUsu.numExt_datos_usuario AS numExt,
	datUsu.numInt_datos_usuario AS numInt,
	datUsu.colonia_datos_usuario AS colonia,
	datUsu.cp_datos_usuario AS cp,
	datUsu.id_ciudades AS idCity,
	datUsu.fecha_cumpleanios_datos_usuario AS cumple,
	datUsu.correo_datos_usuario AS correo,
	datUsu.lada_datos_usuario AS lada,
	datUsu.telefono_datos_usuario AS telefono,
	datUsu.imagen_datos_usuario AS imagen
FROM
	datos_usuario AS datUsu
WHERE
	datUsu.id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_direcciones_agenda_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_direcciones_agenda_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_direcciones_agenda_id`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	dirAgen.id_direccion_agenda AS id,
	dirAgen.calle_direccion_agenda AS calle,
	dirAgen.numInt_direccion_agenda AS numInt,
	dirAgen.numExt_direccion_agenda AS numExt,
	city.nombre_ciudades AS ciudad,
	dirAgen.cp_direccion_agenda AS cp,
	dirAgen.colonia_direccion_agenda AS colonia,
	catDir.nombre_categoria_direcciones AS categoria,
  edo.nombre_estados AS estado
FROM
	direccion_agenda AS dirAgen
INNER JOIN categoria_direcciones AS catDir ON dirAgen.id_categoria_direcciones = catDir.id_categoria_direcciones
INNER JOIN ciudades AS city ON dirAgen.id_ciudades = city.id_ciudades
INNER JOIN estados AS edo ON city.id_estados = edo.id_estados 
WHERE
	dirAgen.id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_direccion_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_direccion_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_direccion_contacto`(IN idDireccion BIGINT(20))
BEGIN
SELECT
	dirAgen.id_direccion_agenda AS id,
	dirAgen.id_categoria_direcciones AS idCat,
	catDir.nombre_categoria_direcciones AS categoria,
	dirAgen.calle_direccion_agenda AS calle,
	dirAgen.numInt_direccion_agenda AS numInt,
	dirAgen.numExt_direccion_agenda AS numExt,
	dirAgen.id_ciudades AS idCity,
	city.nombre_ciudades AS ciudad,
	dirAgen.cp_direccion_agenda AS cp,
	dirAgen.colonia_direccion_agenda AS colonia,
  city.id_estados AS idEdo,
	edos.nombre_estados AS estado
FROM
	direccion_agenda AS dirAgen
INNER JOIN categoria_direcciones AS catDir ON dirAgen.id_categoria_direcciones = catDir.id_categoria_direcciones
INNER JOIN ciudades AS city ON dirAgen.id_ciudades = city.id_ciudades
INNER JOIN estados AS edos ON city.id_estados = edos.id_estados
WHERE
	dirAgen.id_direccion_agenda = idDireccion;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_empresa_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_empresa_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_empresa_contacto`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	agen.empresa_agenda AS empresa
FROM
	agenda AS agen
WHERE
	agen.id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_fecha_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_fecha_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_fecha_contacto`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	agen.fecha_cumple_agenda AS fecha
FROM
	agenda AS agen
WHERE
	agen.id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_clientes`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_clientes`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_clientes`()
BEGIN
SELECT
	clien.id_cliente AS id,
	clien.nombre_cliente AS nombre
FROM
	cliente AS clien
WHERE
	clien.estatus_cliente = 1
AND	
	clien.id_cliente <> 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_correo_agenda_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_correo_agenda_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_correo_agenda_id`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	correoAg.valor_correo_agenda AS nombre
FROM
	correo_agenda AS correoAg
WHERE
	correoAg.id_agenda = idAgenda
AND correoAg.estatus_correo_agenda = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_direccion_agenda_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_direccion_agenda_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_direccion_agenda_id`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	CONCAT(
		dirAgen.calle_direccion_agenda,
		' ',
		dirAgen.numExt_direccion_agenda,
		' ',
		dirAgen.numInt_direccion_agenda,
		' ',
		dirAgen.colonia_direccion_agenda,
		' ',
		dirAgen.cp_direccion_agenda,
		' ',
		city.nombre_ciudades,
		', ',
		edo.nombre_estados
	) AS direccion
FROM
	direccion_agenda AS dirAgen
INNER JOIN ciudades AS city ON dirAgen.id_ciudades = city.id_ciudades
INNER JOIN estados AS edo ON city.id_estados = edo.id_estados
WHERE
	dirAgen.id_agenda = idAgenda
AND	
	dirAgen.estatus_direccion_agenda = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_modulos_padre`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_modulos_padre`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_modulos_padre`()
BEGIN
SELECT
	*
FROM
	_modulos
WHERE
	id_padre = 0
ORDER BY
	id_modulo ASC;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_subagentes_agenteId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_subagentes_agenteId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_subagentes_agenteId`(IN idAgente BIGINT(20))
BEGIN
SELECT
	subAgen.id_subagente AS id,
	usuar.nombre_usuario AS nombre
FROM
	subagente AS subAgen
INNER JOIN _usuarios AS usuar ON subAgen.id_usuario = usuar.id_usuario
INNER JOIN _perfiles AS perf ON usuar.id_perfil = perf.id_perfil
WHERE
	perf.id_agente = idAgente
AND subAgen.estatus_subagente = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_telefono_agenda_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_telefono_agenda_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_telefono_agenda_id`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	CONCAT(
		telAgen.lada_telefono_agenda,'-',telAgen.tel_telefono_agenda
	) AS telefono
FROM
	telefono_agenda telAgen
WHERE
	telAgen.id_agenda = idAgenda
AND telAgen.estatus_telefono_agenda = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_tipos_direcciones`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_tipos_direcciones`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_tipos_direcciones`()
BEGIN
SELECT
	catDir.id_categoria_direcciones AS id,
	catDir.nombre_categoria_direcciones AS nombre
FROM
	categoria_direcciones AS catDir
WHERE
	catDir.estatus_categoria_direcciones = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_lista_tipos_telefono`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_lista_tipos_telefono`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_lista_tipos_telefono`()
BEGIN
SELECT
	catTel.id_categoria_telefono AS id,
	catTel.nombre_categoria_telefono AS nombre
FROM
	categoria_telefono AS catTel
WHERE
	catTel.estatus_categoria_telefono = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_modulos_hijo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_modulos_hijo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_modulos_hijo`(IN idModu BIGINT(20))
BEGIN
SELECT
	*
FROM
	_modulos
WHERE
	id_padre = idModu
ORDER BY
	orden_modulo ASC;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_nombre_clienteId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_nombre_clienteId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_nombre_clienteId`(IN idCliente BIGINT(20))
BEGIN
SELECT
	clien.nombre_cliente AS nombre
FROM
	cliente AS clien
WHERE
	clien.id_cliente = idCliente;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_nombre_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_nombre_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_nombre_contacto`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	agen.prefijo_agenda AS prefijo,
	agen.nombre_agenda AS nombre,
	agen.segundo_nombre_agenda AS segNombre,
	agen.apellido_agenda AS apellido,
	agen.sufijo_agenda AS sufijo
FROM
	agenda AS agen
WHERE
	agen.id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_permisos_acciones`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_permisos_acciones`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_permisos_acciones`(IN idPerfil BIGINT(20))
BEGIN
SELECT
	perAcci.id_acciones,
	perAcci.id_perfil
FROM
	permisos_acciones perAcci
WHERE
perAcci.id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_permisos_acciones_modulo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_permisos_acciones_modulo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_permisos_acciones_modulo`(IN idPerfil BIGINT(20), IN idModulo BIGINT(20))
BEGIN
SELECT
	acci.id_acciones AS id,
	acci.id_modulo AS idMod,
	acci.nombre_acciones AS accion
FROM
	acciones AS acci
INNER JOIN permisos_acciones AS perAcci ON perAcci.id_acciones = acci.id_acciones
WHERE
	perAcci.id_perfil = idPerfil
AND acci.id_modulo = idModulo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_permisos_modulos`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_permisos_modulos`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_permisos_modulos`(IN idPerfil BIGINT(20))
BEGIN
SELECT
	*
FROM
	_permisos
WHERE
	id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_permisos_tablero`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_permisos_tablero`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_permisos_tablero`()
BEGIN
  SELECT
  contTab.id_contenido_tablero AS id,
  contTab.nombre_contenido_tablero AS nombre,
  contTab.estatus_contenido_tablero AS estatus
FROM contenido_tablero contTab
WHERE contTab.estatus_contenido_tablero = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_permisos_tablero_modulo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_permisos_tablero_modulo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_permisos_tablero_modulo`(IN idPerfil BIGINT(20))
BEGIN
  SELECT
		conTab.id_contenido_tablero AS id,
		conTab.nombre_contenido_tablero AS nombre
FROM
	permisos_tablero AS perTab
INNER JOIN contenido_tablero AS conTab ON perTab.id_contenido_tablero = conTab.id_contenido_tablero
WHERE perTab.id_perfil = idPerfil;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_permiso_modulo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_permiso_modulo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_permiso_modulo`(IN idPerfil BIGINT(20), IN idModulo BIGINT(20))
BEGIN
	SELECT 
		permi.id_perfil AS perfil,
		permi.id_modulo AS modulo
	FROM
		_permisos AS permi
	WHERE
		id_perfil = idPerfil
	AND
		id_modulo = idModulo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_proveedor_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_proveedor_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_proveedor_contacto`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	prov.id_proveedor AS id,
	prov.nombre_proveedor AS nombre
FROM
	agenda_proveedor AS agenProv
INNER JOIN proveedor AS prov ON agenProv.id_proveedor = prov.id_proveedor
WHERE
	agenProv.id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_puesto_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_puesto_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_puesto_contacto`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	agen.puesto_agenda AS puesto
FROM
	agenda AS agen
WHERE
	agen.id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_telefonos_agenda_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_telefonos_agenda_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_telefonos_agenda_id`(IN idAgenda BIGINT(20))
BEGIN
SELECT
	telAgen.id_telefono_agenda AS id,
	telAgen.lada_telefono_agenda AS lada,
	telAgen.tel_telefono_agenda AS telefono,
	cateTel.nombre_categoria_telefono AS categoria
FROM
	telefono_agenda AS telAgen
INNER JOIN categoria_telefono AS cateTel ON telAgen.id_categoria_telefono = cateTel.id_categoria_telefono
WHERE
	telAgen.id_agenda = idAgenda
AND telAgen.estatus_telefono_agenda = 1
ORDER BY id;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_telefono_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_telefono_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_telefono_contacto`(IN idTelefono BIGINT(20))
BEGIN
SELECT
	telAgen.id_telefono_agenda AS id,
	telAgen.id_categoria_telefono AS idTipo,
	telAgen.lada_telefono_agenda AS lada,
	telAgen.tel_telefono_agenda AS telefono,
	catTel.nombre_categoria_telefono AS categoria
FROM
	telefono_agenda AS telAgen
INNER JOIN categoria_telefono catTel ON catTel.id_categoria_telefono = telAgen.id_categoria_telefono
WHERE
	telAgen.id_telefono_agenda = idTelefono;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_tipoContacto_nombre`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_tipoContacto_nombre`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_tipoContacto_nombre`(IN nombre VARCHAR(100), IN idSubAgente BIGINT(20))
BEGIN
  SELECT id_tipo_contacto AS id
FROM
  tipo_contacto
WHERE
  nombre_tipo_contacto = nombre
AND
	id_subagente = idSubAgente
AND
  estatus_tipo_contacto <> 0;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_tipoContacto_nombreId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_tipoContacto_nombreId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_tipoContacto_nombreId`(IN idTipo BIGINT(20),
 IN idSubAgente BIGINT(20),                                                     IN nombre VARCHAR(100))
BEGIN
  SELECT id_tipo_contacto AS id
FROM
  tipo_contacto
WHERE
  nombre_tipo_contacto = nombre
AND
	id_subagente = idSubAgente
AND
  id_tipo_contacto <> idTipo
AND
  estatus_tipo_contacto <> 0;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_tipoPago_nombre`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_tipoPago_nombre`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_tipoPago_nombre`(IN nombre VARCHAR(255), IN idSubAgente BIGINT(20))
BEGIN
	SELECT
		tipPag.id_tipo_pago AS id
	FROM
		tipo_pago AS tipPag
	WHERE
		tipPag.nombre_tipo_pago = nombre
	AND
		tipPag.id_subagente = idSubAgente	
	AND 
		tipPag.estatus_tipo_pago <> 0;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_tipoPago_nombreId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_tipoPago_nombreId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_tipoPago_nombreId`(IN idTipo BIGINT (20), IN idSubAgente BIGINT(20), IN nombre VARCHAR (255))
BEGIN
	SELECT
		tipPag.id_tipo_pago AS id
	FROM
		tipo_pago AS tipPag
	WHERE
		tipPag.nombre_tipo_pago = nombre
	AND 
		tipPag.id_subagente = idSubAgente
	AND tipPag.estatus_tipo_pago <> 0
	AND tipPag.id_tipo_pago <> idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_tipoPoliza_nombre`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_tipoPoliza_nombre`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_tipoPoliza_nombre`(IN nombre VARCHAR(100), IN idSubAgente BIGINT(20))
BEGIN
SELECT
	tipPol.id_tipo_poliza AS id
FROM
	tipo_poliza AS tipPol
WHERE
	tipPol.nombre_tipo_poliza = nombre
AND	
	tipPol.id_subagente = idSubAgente
AND
	tipPol.estatus_tipo_poliza <> 0;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_tiposPoliza_nombreId`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_tiposPoliza_nombreId`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_tiposPoliza_nombreId`(IN idTipo BIGINT (20),
 IN idSubAgente BIGINT(20), IN nombre VARCHAR (100))
BEGIN
	SELECT
		tipPol.id_tipo_poliza AS id
	FROM
		tipo_poliza AS tipPol
	WHERE
		tipPol.nombre_tipo_poliza = nombre
  AND tipPol.id_subagente = idSubAgente
	AND tipPol.estatus_tipo_poliza <> 0
	AND tipPol.id_tipo_poliza <> idTipo;
	END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_ultimo_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_ultimo_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_ultimo_usuario`(IN idUser BIGINT(20))
BEGIN
SELECT
	Max(usuar.id_usuario) AS id
FROM
	_usuarios AS usuar
WHERE
	usuar.id_usuarioCreate = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_usuario_id`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_usuario_id`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_usuario_id`(IN idUser BIGINT(20))
BEGIN
  SELECT usuar.id_usuario AS id
       , usuar.login_usuario AS login
       , usuar.password_usuario AS pass
       , usuar.nombre_usuario AS nombre
       , perf.id_perfil AS idPerfil
       , perf.nombre_perfil AS perfil
       , usuar.estado_usuario AS estatus
  FROM
    _perfiles perf
  INNER JOIN _usuarios usuar
  ON perf.id_perfil = usuar.id_perfil
  WHERE
    usuar.id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_usuario_login`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_usuario_login`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_usuario_login`(IN login VARCHAR(200))
BEGIN
  SELECT id_usuario
  FROM
    _usuarios
  WHERE
    login_usuario = login;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_usuario_loginEditar`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_usuario_loginEditar`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_usuario_loginEditar`(IN login  VARCHAR(200), IN idUser BIGINT(20))
BEGIN
  SELECT id_usuario
  FROM
    _usuarios
  WHERE
    login_usuario = login
	AND id_usuario <> idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_usuario_logueado`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_usuario_logueado`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_usuario_logueado`(IN idUser BIGINT (20),
	IN token VARCHAR (200))
BEGIN
	SELECT
		usua.id_usuario AS id
	FROM
		_usuarios AS usua
	WHERE
		usua.id_usuario = idUser
	AND usua.token_usuario = CONVERT(token using utf8) collate utf8_general_ci
	AND usua.estado_usuario = 1;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_select_variable_id_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_select_variable_id_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_select_variable_id_usuario`()
BEGIN
SELECT
	conf.nombre_session_config AS nombre
FROM
	config AS conf;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_asignacion_cliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_asignacion_cliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_asignacion_cliente`(IN idCliente BIGINT(20), IN idSubAgente BIGINT(20), IN idUser BIGINT(20))
BEGIN
UPDATE cliente 
SET
	id_subagente = idSubAgente,
	id_usuario = idUser
WHERE
	id_cliente = idCliente;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_calle_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_calle_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_calle_perfil`(IN calle VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	calle_datos_usuario = calle
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_cliente`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_cliente`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_cliente`(IN idClie   BIGINT(20),
 IN idSubAgente BIGINT(20),  IN nombre   VARCHAR(255), IN razon   VARCHAR(255),
  IN rfc      VARCHAR(13),
                                                    IN calle    VARCHAR(100),
                                                    IN numInt   VARCHAR(100),
                                                    IN numExt   VARCHAR(100),
                                                    IN col      VARCHAR(100),
                                                    IN cp       INT(5),
      IN localidad VARCHAR(100),                                               IN city     BIGINT(20),
                                                    IN contacto VARCHAR(100),
                                                    IN correo   VARCHAR(255),
                                                    IN lada     INT(3),
                                                    IN tel      INT(7), IN idUser BIGINT(20))
BEGIN
  UPDATE cliente clie
  SET
		clie.id_subagente = idSubAgente,
    clie.nombre_cliente = nombre,
    clie.razon_cliente = razon,
    clie.rfc_cliente = rfc, clie.calle_cliente = calle, clie.numInt_cliente = numInt, clie.numExt_cliente = numExt, 
clie.colonia_cliente = col, clie.cp_cliente = cp, 
clie.localidad_cliente = localidad, clie.id_ciudad = city, clie.contacto_cliente = contacto, 
clie.correo_cliente = correo, clie.lada_cliente = lada, clie.telefono_cliente = tel, id_usuario = idUser
  WHERE
    clie.id_cliente = idClie;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_colonia_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_colonia_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_colonia_perfil`(IN colonia VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	colonia_datos_usuario = colonia
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_config_sistema`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_config_sistema`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_config_sistema`(IN idAgente BIGINT(20), IN colorTop VARCHAR(100), IN colorCont VARCHAR(100))
BEGIN
UPDATE config
SET 
	navbar_config = colorTop,
  panel_heading_config = colorCont
WHERE
	id_agente = idAgente;	
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_contrasenia_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_contrasenia_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_contrasenia_perfil`(IN pass VARCHAR(200), IN idUser BIGINT(20))
BEGIN
UPDATE _usuarios
SET
	password_usuario = pass
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_correo_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_correo_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_correo_contacto`(IN idCorreo BIGINT(20), IN correo VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE correo_agenda
SET
	valor_correo_agenda = correo,
	id_usuario = idUser
WHERE
	id_correo_agenda = idCorreo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_correo_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_correo_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_correo_perfil`(IN correo VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	correo_datos_usuario = correo
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_cp_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_cp_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_cp_perfil`(IN cp INT(5), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	cp_datos_usuario = cp
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_direccion_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_direccion_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_direccion_contacto`(IN idDireccion BIGINT(20), IN idCiudad BIGINT(20), IN idCat BIGINT(20), IN calle VARCHAR(255),
IN numExt VARCHAR(100), IN numInt VARCHAR(100), IN colonia VARCHAR(100), IN cp INT(5), IN idUser BIGINT(20))
BEGIN
UPDATE direccion_agenda
SET
	id_ciudades = idCiudad, 
	id_categoria_direcciones = idCat,
	calle_direccion_agenda = calle,
	numExt_direccion_agenda = numExt,
	numInt_direccion_agenda = numInt,
	colonia_direccion_agenda = colonia,
	cp_direccion_agenda = cp,	
	id_usuario = idUser
WHERE
	id_direccion_agenda = idDireccion;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_empresa_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_empresa_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_empresa_contacto`(IN idAgenda BIGINT(20), IN empresa VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE agenda
SET
	empresa_agenda = empresa,
	id_usuario = idUser
WHERE
	id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_fecha_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_fecha_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_fecha_contacto`(IN idAgenda BIGINT(20), IN fecha DATE, IN idUser BIGINT(20))
BEGIN
UPDATE agenda
SET
 fecha_cumple_agenda = fecha,
 id_usuario = idUser
WHERE
	id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_fecha_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_fecha_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_fecha_perfil`(IN fecha DATE, IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	fecha_cumpleanios_datos_usuario = fecha
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_formaContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_formaContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_formaContacto`(IN idContacto BIGINT(20),
                                            IN nombre     VARCHAR(100),
                                            IN puesto     VARCHAR(100),
                                            IN idTipo     BIGINT(20),
                                            IN valor      VARCHAR(255),
                                            IN idUser     BIGINT(20))
BEGIN
  UPDATE cliente_tipo_contacto
  SET
    nombre_cliente_tipo_contacto = nombre, 
		puesto_cliente_tipo_contacto = puesto, 
		id_tipo_contacto = idTipo, 
		valor_cliente_tipo_contacto = valor, 
		id_usuario = idUser
  WHERE
    id_cliente_tipo_contacto = idContacto;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_lada_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_lada_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_lada_perfil`(IN lada INT(3), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	lada_datos_usuario = lada
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_nombre_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_nombre_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_nombre_contacto`(IN idAgenda BIGINT(20), IN prefijo VARCHAR(255), IN nombre VARCHAR(255), IN segNombre VARCHAR(255), 
IN apellido VARCHAR(255), IN sufijo VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE agenda
SET
	prefijo_agenda = prefijo,
	nombre_agenda = nombre,
	segundo_nombre_agenda = segNombre,
	apellido_agenda = apellido,
	sufijo_agenda = sufijo,
	id_usuario = idUser
WHERE
	id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_nombre_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_nombre_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_nombre_perfil`(IN nombre VARCHAR(150), IN idUser BIGINT(20))
BEGIN
UPDATE _usuarios
SET
	nombre_usuario = nombre
WHERE
	id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_nota_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_nota_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_nota_contacto`(IN idAgenda BIGINT(20), IN nota TEXT, IN idUser BIGINT(20))
BEGIN
UPDATE agenda
SET
	descripcion_agenda = nota,
	id_usuario = idUser
WHERE
	id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_numExt_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_numExt_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_numExt_perfil`(IN numExt VARCHAR(100), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	numExt_datos_usuario = numExt
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_numInt_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_numInt_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_numInt_perfil`(IN numInt VARCHAR(100), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	numInt_datos_usuario = numInt
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_puesto_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_puesto_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_puesto_contacto`(IN idAgenda BIGINT(20), IN puesto VARCHAR(255), IN idUser BIGINT(20))
BEGIN
UPDATE agenda
SET
	puesto_agenda = puesto,
	id_usuario = idUser
WHERE
	id_agenda = idAgenda;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_telefono_contacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_telefono_contacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_telefono_contacto`(IN idTelefono BIGINT(20), IN tipTel BIGINT(20), IN lada INT(3), IN telefono INT(7), IN idUser BIGINT(20))
BEGIN
UPDATE telefono_agenda
SET
	id_categoria_telefono = tipTel,
	lada_telefono_agenda = lada,
	tel_telefono_agenda = telefono,
	id_usuario = idUser
WHERE
	id_telefono_agenda = idTelefono;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_telefono_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_telefono_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_telefono_perfil`(IN telefono INT(7), IN idUser BIGINT(20))
BEGIN
UPDATE datos_usuario
SET
	telefono_datos_usuario = telefono
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_tipoContacto`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_tipoContacto`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_tipoContacto`(IN idTipo BIGINT(20),
 IN idSubAgente BIGINT(20),                                                IN nombre VARCHAR(100),
                                                IN txt    VARCHAR(255),
                                                IN idUser BIGINT(20))
BEGIN
  UPDATE tipo_contacto
  SET
    nombre_tipo_contacto = nombre, 
		id_subagente = idSubAgente,
		descripcion_tipo_contacto = txt, 
		id_usuario = idUser
  WHERE
    id_tipo_contacto = idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_tipoPago`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_tipoPago`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_tipoPago`(IN idTipo BIGINT (20),
 IN idSubAgente BIGINT(20), 	IN nombre VARCHAR (100),
	IN txt VARCHAR (255))
BEGIN
	UPDATE 
		tipo_pago
	SET 
		nombre_tipo_pago = nombre,
		descripcion_tipo_pago = txt,
		id_subagente = idSubAgente
	WHERE
		id_tipo_pago = idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_tipoPoliza`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_tipoPoliza`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_tipoPoliza`(IN idTipo BIGINT(20), IN idSubAgente BIGINT(20), IN nombre VARCHAR(100), IN txt VARCHAR(255))
BEGIN
UPDATE tipo_poliza
SET
	id_subagente = idSubAgente,
	nombre_tipo_poliza = nombre,
	descripcion_tipo_poliza = txt
WHERE
	id_tipo_poliza = idTipo;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_token_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_token_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_token_usuario`(IN token VARCHAR(200), IN idUser BIGINT(20))
BEGIN
UPDATE 
	_usuarios
SET token_usuario = token
WHERE
	id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistema_update_usuario_perfil`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistema_update_usuario_perfil`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistema_update_usuario_perfil`(IN login VARCHAR(200), IN idUser BIGINT(20))
BEGIN
UPDATE _usuarios
SET
	login_usuario = login
WHERE
	id_usuario  =idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistems_select_agente_id_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistems_select_agente_id_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistems_select_agente_id_usuario`(IN idUser BIGINT(20))
BEGIN
SELECT
	agen.id_agente AS id
FROM
	agente AS agen
WHERE
	agen.id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistems_select_subagente_id_usuario`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistems_select_subagente_id_usuario`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistems_select_subagente_id_usuario`(IN idUser BIGINT(20))
BEGIN
SELECT
	subAg.id_subagente AS id
FROM
	subagente AS subAg
WHERE
	subAg.id_usuario = idUser;
END
;;
DELIMITER ;

-- ----------------------------
--  Procedure definition for `sp_sistem_select_acciones_modulo`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_sistem_select_acciones_modulo`;
DELIMITER ;;
CREATE   PROCEDURE `sp_sistem_select_acciones_modulo`(IN idModulo BIGINT(20))
BEGIN
	SELECT
		acci.id_acciones,
		acci.id_modulo,
		acci.nombre_acciones
	FROM
		acciones acci
	WHERE
	acci.id_modulo = idModulo;
END
;;
DELIMITER ;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `acciones` VALUES ('1','1001','Alta'), ('2','1001','Modificación'), ('3','1001','Eliminación'), ('13','3001','Asignar cliente'), ('18','3008','Alta'), ('20','1002','Alta'), ('21','1002','Modificación'), ('22','1002','Eliminación'), ('23','1002','Permisos de tablero'), ('24','3001','Alta'), ('25','3001','Modificación'), ('26','3001','Eliminación'), ('36','3008','Alta'), ('37','3008','Modificación'), ('38','3008','Eliminación'), ('45','3012','Alta'), ('46','3012','Modificación'), ('47','3012','Eliminación'), ('48','3012','Ver detalles'), ('49','3001','Ver detalles'), ('50','3012','Eliminar medio contacto'), ('51','3012','Editar medio contacto'), ('52','3013','Alta'), ('53','3013','Modificación'), ('54','3013','Eliminación'), ('55','3014','Alta'), ('56','3014','Modificación'), ('57','3014','Eliminación'), ('58','4001','Alta'), ('59','4001','Ver polizas'), ('60','3001','Medios de contacto'), ('61','3001','Nuevo medio de contacto'), ('62','3001','Editar medio de contacto'), ('63','3001','Eliminar medio de contacto'), ('64','3001','Documentos de cliente'), ('65','3001','Nuevo documento'), ('67','3001','Eliminar documento');
INSERT INTO `agenda` VALUES ('4','3','-','edgar rodriguez de la rosa','-','-','-','-','-','0000-00-00','1','-',NULL,'default.jpg','2015-05-21 10:47:10','2','2015-05-21 10:47:10','1'), ('5','3','-','jesus ramirez','-','-','-','-','-','0000-00-00','1','-',NULL,'default.jpg','2015-05-21 11:32:11','2','2015-05-21 11:32:11','1'), ('6','3','-','jorge reyna','-','-','-','-','-','0000-00-00','1','-',NULL,'default.jpg','2015-05-21 11:32:59','2','2015-05-21 11:32:59','1'), ('7','3','-','alberto chávez','-','-','-','-','-','0000-00-00','1','-',NULL,'default.jpg','2015-05-21 11:34:09','2','2015-05-21 11:34:09','1'), ('8','3','-','adriana chavez','-','-','-','-','-','0000-00-00','1','-',NULL,'default.jpg','2015-05-21 11:34:40','2','2015-05-21 11:34:40','1'), ('9','4','-','adriana chávez','-','-','-','-','-','0000-00-00','1','-',NULL,'default.jpg','2015-05-21 11:36:01','15','2015-05-21 11:36:01','1');
INSERT INTO `agente` VALUES ('1','2','2015-05-08 12:16:41','1','2015-05-08 12:16:47','1');
INSERT INTO `calendario` VALUES ('1','exis','2015-05-04 16:05:00','2015-05-04 17:05:00','false','','#85C744','2015-05-04 16:44:02','1','2015-05-04 16:44:02','1');
INSERT INTO `categoria_direcciones` VALUES ('1','Casa','2014-11-24 13:29:49','1','2014-11-24 13:43:14','1'), ('2','Trabajo','2014-11-24 13:29:49','1','2014-11-24 13:43:14','1');
INSERT INTO `categoria_telefono` VALUES ('1','Móvil','2014-11-24 13:29:49','1','2014-11-24 13:29:57','1'), ('2','Trabajo','2014-11-24 13:29:49','1','2014-11-24 13:31:16','1'), ('3','Principal','2014-11-24 13:29:49','1','2014-11-24 13:31:16','1'), ('4','Fax de trabajo','2014-11-24 13:29:49','1','2014-11-24 13:31:16','1'), ('5','Fax de casa','2014-11-24 13:29:49','1','2014-11-24 13:31:17','1');
INSERT INTO `categoria_url` VALUES ('1','Perfil','2014-11-24 13:29:49','1','2014-11-24 13:55:27','1'), ('2','Blog','2014-11-24 13:29:49','1','2014-11-24 13:55:27','1'), ('3','Página','2014-11-24 13:29:49','1','2014-11-24 13:55:27','1'), ('4','Principal','2014-11-24 13:29:49','1','2014-11-24 13:55:27','1'), ('5','Trabajo','2014-11-24 13:29:49','1','2014-11-24 13:55:27','1');
INSERT INTO `ciudades` VALUES ('1','Aguascalientes','1'), ('2','Asientos','1'), ('3','Calvillo','1'), ('4','Cosío','1'), ('5','Jesús maria','1'), ('6','Pabellón de arteaga','1'), ('7','Rincón de romos','1'), ('8','San josé de gracia','1'), ('9','Tepezalá','1'), ('10','San francisco de los romo','1'), ('11','El llano','1'), ('12','Ensenada','2'), ('13','Mexicali','2'), ('14','Tecate','2'), ('15','Tijuana','2'), ('16','Playas de rosarito','2'), ('17','Rosarito','2'), ('18','Ciudad guadalupe victoria','2'), ('19','San felipe','2'), ('20','San quintín','2'), ('21','Ciudad morelos','2'), ('22','Los algodones','2'), ('23','La rumorosa','2'), ('24','Cataviña','2'), ('25','Colonet','2'), ('26','Cedros','2'), ('27','Guadalupe','2'), ('28','Islas coronado','2'), ('29','Comondú','3'), ('30','Mulegé','3'), ('31','La paz','3'), ('32','Los cabos','3'), ('33','Loreto','3'), ('34','Calkiní','4'), ('35','Campeche','4'), ('36','Carmen','4'), ('37','Champotón','4'), ('38','Hecelchakán','4'), ('39','Hopelchén','4'), ('40','Palizada','4'), ('41','Tenabo','4'), ('42','Escárcega','4'), ('43','Calakmul','4'), ('44','Candelaria','4'), ('45','Acacoyagua ','5'), ('46','Acala ','5'), ('47','Acapetahua ','5'), ('48','Aldama ','5'), ('49','Altamirano ','5'), ('50','Amatán','5'), ('51','Amatenango de la frontera ','5'), ('52','Amatenango del valle ','5'), ('53','Angel albino corzo ','5'), ('54','Arriaga ','5'), ('55','Bejucal de ocampo ','5'), ('56','Bella vista ','5'), ('57','Benemérito de las américas ','5'), ('58','Berriozábal ','5'), ('59','Bochil ','5'), ('60','El bosque \r\n','5'), ('61','Cacahoatán ','5'), ('62','Catazajá ','5'), ('63','Cintalapa ','5'), ('64','Coapilla ','5'), ('65','Comitán de domínguez ','5'), ('66','La concordia ','5'), ('67','Copainalá ','5'), ('68','Chalchihuitán ','5'), ('69','Chamula ','5'), ('70','Chanal ','5'), ('71','Chapultenango ','5'), ('72','Chenalhó ','5'), ('73','Chiapa de corzo corzo ','5'), ('74','Chiapilla ','5'), ('75','Chicoasén ','5'), ('76','Chicomuselo ','5'), ('77','Chilón ','5'), ('78','Escuintla ','5'), ('79','Francisco león ','5'), ('80','Frontera comalapa ','5'), ('81','Frontera hidalgo ','5'), ('82','La grandeza ','5'), ('83','Huhuetán ','5'), ('84','Huixtán ','5'), ('85','Huitiupán ','5'), ('86','Huixtla ','5'), ('87','La independencia ','5'), ('88','Ixhuatán ','5'), ('89','Ixtacomitán ','5'), ('90','Ixtapa ','5'), ('91','Ixtapangajoya ','5'), ('92','Jiquipilas jiquipilas ','5'), ('93','Juárez ','5'), ('94','Larráinzar ','5'), ('95','La libertad ','5'), ('96','Mapastepec ','5'), ('97','Maravilla tenejapa ','5'), ('98','Marqués de comillas ','5'), ('99','Mazapa de madero ','5'), ('100','Mazatán ','5');
INSERT INTO `ciudades` VALUES ('101','Metapa ','5'), ('102','Mitontic ','5'), ('103','Montecristo de guerrero ','5'), ('104','Motozintla ','5'), ('105','Nicolás ruíz ','5'), ('106','Ocosingo \r\n','5'), ('107','Ocotepec ','5'), ('108','Ocozocoautla de espinosa ','5'), ('109','Ostuacán ','5'), ('110','Osumacinta ','5'), ('111','Oxchuc ','5'), ('112','Palenque ','5'), ('113','Pantelhó ','5'), ('114','Pantepec ','5'), ('115','Pichucalco ','5'), ('116','Pijijiapan ','5'), ('117','El porvenir ','5'), ('118','Pueblo nuevo solistahuacán ','5'), ('119','Rayón ','5'), ('120','Reforma ','5'), ('121','Las rosas ','5'), ('122','Las rosas ','5'), ('123','Sabanilla ','5'), ('124','Salto de agua ','5'), ('125','San andrés duraznal ','5'), ('126','San cristobal de las casas ','5'), ('127','San fernando ','5'), ('128','San juan cancuc ','5'), ('129','San lucas ','5'), ('130','Santiago el pinar ','5'), ('131','Siltepec ','5'), ('132','Simojovel ','5'), ('133','Sitalá ','5'), ('134','Socoltenango ','5'), ('135','Solosuchiapa ','5'), ('136','Soyaló ','5'), ('137','Suchiapa ','5'), ('138','Suchiate ','5'), ('139','Sunuapa ','5'), ('140','Tapachula ','5'), ('141','Tapalapa ','5'), ('142','Tapilula ','5'), ('143','Tecpatán ','5'), ('144','Tenejapa ','5'), ('145','Teopisca ','5'), ('146','Tila ','5'), ('147','Tonalá ','5'), ('148','Totolapa ','5'), ('149','La trinitaria ','5'), ('150','Tumbalá ','5'), ('151','Tuxtla gutiérrez ','5'), ('152','Tuxtla chico ','5'), ('153','Tuzantán ','5'), ('154','Tzimol ','5'), ('155','Unión juárez ','5'), ('156','Venustiano carranza ','5'), ('157','Villa comaltitlán ','5'), ('158','Villa corzo ','5'), ('159','Villaflores ','5'), ('160','Yajalón ','5'), ('161','Zinacantán ','5'), ('162','Ahumada','6'), ('163','Aldama','6'), ('164','Allende','6'), ('165','Aquiles serdánserdán','6'), ('166','Ascensión','6'), ('167','Bachíniva','6'), ('168','Balleza','6'), ('169','Batopilas','6'), ('170','Bocoyna','6'), ('171','Buenaventura','66'), ('172','Camargo','6'), ('173','Carichi','6'), ('174','Casas grandes','6'), ('175','Coronado','6'), ('176','Coyame del sotol','6'), ('177','La cruz','6'), ('178','Cuauhtémoc','6'), ('179','Cusihuiriáchi','6'), ('180','Chihuahua','6'), ('181','Chínipas','6'), ('182','Delicias','6'), ('183','Dr. belisario domínguez','6'), ('184','Galeana','6'), ('185','Santa isabel','6'), ('186','Gómez farías','6'), ('187','Gran morelos','6'), ('188','Guachochi','6'), ('189','Guadalupe d.b.','6'), ('190','Guadalupe y calvo','6'), ('191','Guazapares','6'), ('192','Guerrero','6'), ('193','Hidalgo del parral','6'), ('194','Huejotitán','6'), ('195','Ignacio zaragoza','6'), ('196','Janos','6'), ('197','Jiménez','6'), ('198','Juárez','6'), ('199','Julimes','6'), ('200','López','6');
INSERT INTO `ciudades` VALUES ('201','Madera','6'), ('202','Maguarichi','6'), ('203','Manuel benavides','6'), ('204','Matachi','6'), ('205','Matamoros','6'), ('206','Meoqui','6'), ('207','Morelos','6'), ('208','Moris','6'), ('209','Namiquipa','6'), ('210','Nonoava','6'), ('211','Nuevo casas grandes','6'), ('212','Ocampo','6'), ('213','Ojinaga','6'), ('214','Praxedis g. guerrero','6'), ('215','Riva palacio','6'), ('216','Rosales','6'), ('217','Rosario','6'), ('218','San francisco de borja','6'), ('219','San francisco de conchos','6'), ('220','San francisco del oro','6'), ('221','Santa bárbara','6'), ('222','Satevó','6'), ('223','Saucillo','6'), ('224','Temósachi','6'), ('225','El tule','6'), ('226','Urique','6'), ('227','Uruáchi','6'), ('228','Valle de zaragoza','6'), ('229','Abasolo','7'), ('230','Acuña','7'), ('231','Allende','7'), ('232','Arteaga','7'), ('233','Candela','7'), ('234','Castaños','7'), ('235','Cuatrocienegas','7'), ('236','Escobedo','7'), ('237','Francisco i. madero','7'), ('238','Frontera','7'), ('239','General cepeda','7'), ('240','Guerrero','7'), ('241','Hidalgo','7'), ('242','Jimenez','7'), ('243','Juarez','7'), ('244','Lamadrid','7'), ('245','Matamoros','7'), ('246','Monclova','7'), ('247','Morelos','7'), ('248','San buenaventura','7'), ('249','San juan de sabinas','7'), ('250','San pedro','7'), ('251','Sierra mojada','7'), ('252','Torreon','7'), ('253','Viesca','7'), ('254',' villa union','7'), ('255','Armería','8'), ('256','Colima','8'), ('257','Comala','8'), ('258','Coquimatlán','8'), ('259','Cuauhtémoc','8'), ('260','Ixtlahuacán','8'), ('261','Manzanillo','8'), ('262','Minatitlán','8'), ('263','Tecomán','8'), ('264','Villa de álvarez','8'), ('270','Canatlán','10'), ('271','Canelas','10'), ('272','Coneto de comonfort','10'), ('273','Cuencamé','10'), ('274','Durango','10'), ('275','General simón bolívar','10'), ('276','Gómez palacio','10'), ('277','Guadalupe victoria','10'), ('278','Guanaceví','10'), ('279','Hidalgo','10'), ('280','Indé','10'), ('281','Ciudad lerdo','10'), ('282','Mapimí','10'), ('283','Mezquital','10'), ('284','Nazas','10'), ('285','Nombre de dios','10'), ('286','Ocampo','10'), ('287','Oro, el','10'), ('288','Otáez','10'), ('289','Pánuco de coronado','10'), ('290','Peñón blanco','10'), ('291','Poanas','10'), ('292','Pueblo nuevo','10'), ('293','Rodeo','10'), ('294','San bernardo','10'), ('295','San dimas','10'), ('296','San juan de guadalupe','10'), ('297','San juan del río','10'), ('298','San luis del cordero','10'), ('299','San pedro del gallo','10'), ('300','Santiago papasquiaro','10'), ('301','Súchil','10'), ('302','Tamazula','10'), ('303','Tepehuanes','10'), ('304','Tlahualilo','10'), ('305','Topia','10');
INSERT INTO `ciudades` VALUES ('306','Vicente guerrero','10'), ('307','Nuevo ideal','10'), ('308','Santa clara','10'), ('309','Santiago papasquiaro','10'), ('310','Súchil','10'), ('311','Tamazula','10'), ('312','Tepehuanes','10'), ('313','Tlahualilo','10'), ('314','Topia','10'), ('315','Vicente guerrero','10'), ('316','Nuevo ideal','10'), ('320','Acambay','11'), ('321','Acolman','11'), ('322','Aculco','11'), ('323','Almoloya de alquisiras','11'), ('324','Almoloya de  juárez','11'), ('325','Almoloya del río','11'), ('326','Amanalco','11'), ('327','Amatepec','11'), ('328','Amecameca','11'), ('329','Apaxco','11'), ('330','Atenco','11'), ('331','Atizapán','11'), ('332','Atizapán de zaragoza','11'), ('333','Atlacomulco','11'), ('334','Atlautla','11'), ('335','Axapusco','11'), ('336','Ayapango','11'), ('337','Calimaya','11'), ('338','Capulhuac','11'), ('339','Coacalco de berriozábal','11'), ('340','Coatepec harinas','11'), ('341','Cocotitlán','11'), ('342','Coyotepec','11'), ('343','Cuautitlán','11'), ('344','Chalco','11'), ('345','Chapa de mota','11'), ('346','Chapultepec','11'), ('347','Chiautla','11'), ('348','Chicoloapan','11'), ('349','Chiconcuac','11'), ('350','Chimalhuacán','11'), ('351','Donato guerra','11'), ('352','Ecatepec','11'), ('353','Ecatzingo','11'), ('354','Hueypoxtla','11'), ('355','Huixquilucan','11'), ('356','Isidro fabela','11'), ('357','Ixtapaluca','11'), ('358','Ixtapan de la sal','11'), ('359','Ixtapan del oro','11'), ('360','Ixtlahuaca','11'), ('361','Xalatlaco','11'), ('362','Jaltenco','11'), ('363','Jilotepec','11'), ('364','Jilotzingo','11'), ('365','Jiquipilco','11'), ('366','Jocotitlán','11'), ('367','Joquicingo','11'), ('368','Juchitepec','11'), ('369','Lerma','11'), ('370','Malinalco','11'), ('371','Melchor ocampo','11'), ('372','Metepec','11'), ('373','Mexicaltzingo','11'), ('374','Morelos','11'), ('375','Naucalpan de juárez','11'), ('376','Nezahualcóyotl','11'), ('377','Nextlalpan','11'), ('378','Nicolás romero','11'), ('379','Nopaltepec','11'), ('380','Ocoyoacac','11'), ('381','Ocuilan','11'), ('382','El oro','11'), ('383','Otumba','11'), ('384','Otzoloapan','11'), ('385','Otzolotepec','11'), ('386','Ozumba','11'), ('387','Papalotla','11'), ('388','La paz','11'), ('389','Polotitlán','11'), ('390','Rayón','11'), ('391','San antonio la isla','11'), ('392','San felipe del progreso','11'), ('393','San martín de las pirámides','11'), ('394','San mateo atenco','11'), ('395','San simón de guerrero','11'), ('396','Santo tomás','11'), ('397','Soyaniquilpan de juárez','11'), ('398','Sultepec','11'), ('399','Tecámac','11'), ('400','Tejupilco','11'), ('401','Temamatla','11'), ('402','Temascalapa','11'), ('403','Temascalcingo','11'), ('404','Temascaltepec','11'), ('405','Temoaya','11'), ('406','Tenancingo','11'), ('407','Tenango del aire','11'), ('408','Tenango del valle','11');
INSERT INTO `ciudades` VALUES ('409','Teoloyucan','11'), ('410','Teotihuacán','11'), ('411','Tepetlaoxtoc','11'), ('412','Tepetlixpa','11'), ('413','Tepotzotlán','11'), ('414','Tequixquiac','11'), ('415','Texcaltitlán','11'), ('416','Texcalyacac','11'), ('417','Texcoco','11'), ('418','Tezoyuca','11'), ('419','Tianguistenco','11'), ('420','Timilpan','11'), ('421','Tlalmanalco','11'), ('422','Tlalnepantla de baz','11'), ('423','Tlatlaya','11'), ('424','Toluca','11'), ('425','Tonatico','11'), ('426','Tultepec','11'), ('427','Tultitlán','11'), ('428','Valle de bravo','11'), ('429','Villa de allende','11'), ('430','Villa del carbón','11'), ('431','Villa guerrero','11'), ('432','Villa victoria','11'), ('433','Xonacatlán','11'), ('434','Zacazonapan','11'), ('435','Zacualpan','11'), ('436','Zinacantepec','11'), ('437','Zumpahuacán','11'), ('438','Zumpango','11'), ('439','Cuautitlán izcalli','11'), ('440','Valle de chalco solidaridad','11'), ('441','Luvianos','11'), ('442','San josé del rincón','11'), ('443','Santa maría tonanitla','11'), ('444','Abasolo','12'), ('445','Acambaro','12'), ('446','Allende','12'), ('447','Apaseo el alto','12'), ('448','Atarjea','12'), ('449','Celaya','12'), ('450','Manuel doblado','12'), ('451','Comonfort','12'), ('452','Coroneo','12'), ('453','Cortazar','12'), ('454','Cueramaro','12'), ('455','Doctor mora','12'), ('456','Dolores hidalgo','12'), ('457','Guanajuato','12'), ('458','Huanimaro','12'), ('459','Irapuato','12'), ('460','Jaral del progreso','12'), ('461','Jerecuaro','12'), ('462','Leon','12'), ('463','Moroleon','12'), ('464','Ocampo','12'), ('465','Penjamo','12'), ('466','Pueblo nuevo','12'), ('467','Purisima del rincon','12'), ('468','Romita','12'), ('469','Salamanca','12'), ('470','Salvatierra','12'), ('471','San diego de la union','12'), ('472','San felipe','12'), ('473','San francisco del rincon','12'), ('474','San jose iturbide','12'), ('475','San luis de la paz','12'), ('476','Santa catarina','12'), ('477','Santa cruz de juventino rosas','12'), ('478','Santiago maravatio','12'), ('479','Silao','12'), ('480','Tarandacuao','12'), ('481','Tarimoro','12'), ('482','Tierra blanca','12'), ('483','Uriangato','12'), ('484','Valle de santiago','12'), ('485','Victoria','12'), ('486','Villagran','12'), ('487','Xichu','12'), ('488','Yuriria','12'), ('489','Acapulco de juárez  ','13'), ('490','Acatepec ','13'), ('491','Ahuacuotzingo ','13'), ('492','Ajuchitlan del progreso ','13'), ('493','Alcozauca de guerrero ','13'), ('494','Alpoyeca ','13'), ('495','Apaxtla ','13'), ('496','Arcelia ','13'), ('497','Atenango del río ','13'), ('498','Atlamajalcingo del monte ','13'), ('499','Atlixtac ','13'), ('500','Atoyac de álvarez ','13'), ('501','Ayutla ','13'), ('502','Azoyú ','13'), ('503','Benito juárez ','13'), ('504','Buenavista de cuéllar ','13'), ('505','Chilapa de álvarez ','13'), ('506','Chilpancingo de los bravo ','13'), ('507','Coahuayutla de josé maría izazaga ','13'), ('508','Cochoapa el grande','13');
INSERT INTO `ciudades` VALUES ('509','Cocula ','13'), ('510','Copala ','13'), ('511','Copalillo ','13'), ('512','Copanatoyac  ','13'), ('513','Coyuca de benítez ','13'), ('514','Coyuca de catalán ','13'), ('515','Cuajinicuilapa ','13'), ('516','Cualác ','13'), ('517','Cuautepec ','13'), ('518','Cuetzala del progreso ','13'), ('519','Cutzamala de pinzón ','13'), ('520','Eduardo neri ','13'), ('521','Florencio villarreal ','13'), ('522','General canuto a. neri ','13'), ('523','General heliodoro castillo ','13'), ('524','Huamuxtitlán ','13'), ('525','Huitzuco de los figueroa ','13'), ('526','Iguala ','13'), ('527','Igualapa ','13'), ('528','Iliatenco','13'), ('529','Ixcateopan de cuauhtémoc ','13'), ('530','José joaquín de herrera','13'), ('531','Juan r. escudero  ','13'), ('532','Juchitán','13'), ('533','La unión ','13'), ('534','Leonardo bravo ','13'), ('535','Malinaltepec ','13'), ('536','Marquelia','13'), ('537','Mártir de cuilapan ','13'), ('538','Metlatónoc ','13'), ('539','Mochitlán ','13'), ('540','Olinalá ','13'), ('541','Ometepec ','13'), ('542','Pedro ascencio alquisiras ','13'), ('543','Petatlán ','13'), ('544','Pilcaya ','13'), ('545','Pungarabato ','13'), ('546','Quechultenango ','13'), ('547','San luis acatlán ','13'), ('548','San marcos ','13'), ('549','San miguel totolapan ','13'), ('550','Taxco de alarcón ','13'), ('551','Tecoanapa ','13'), ('552','Tecpan de galeana ','13'), ('553','Teloloapan  ','13'), ('554','Teniente josé azueta ','13'), ('555','Tepecoacuilco de trujano ','13'), ('556','Tetipac ','13'), ('557','Tixtla de guerrero ','13'), ('558','Tlacoachistlahuaca ','13'), ('559','Tlacoapa ','13'), ('560','Tlalchapa ','13'), ('561','Tlalixtaquilla ','13'), ('562','Tlapa de comonfort ','13'), ('563','Tlapehuala ','13'), ('564','Xalpatláhuac ','13'), ('565','Xochihuehuetlán ','13'), ('566','Xochistlahuaca ','13'), ('567','Zapotitlán tablas ','13'), ('568','Zirándaro de los chávez ','13'), ('569','Zitlala ','13'), ('570','Acatlan','14'), ('571','Huichapan','14'), ('572','Singuilucan','14'), ('573','Acaxochitlan','14'), ('574','Ixmiquilpan','14'), ('575','Tasquillo','14'), ('576','Actopan','14'), ('577','Jacala de ledezma','14'), ('578','Tecozautla','14'), ('579','Agua blanca de iturbide','14'), ('580','Jaltocan','14'), ('581','Tenango de doria','14'), ('582','Ajacuba','14'), ('583','Juarez hidalgo','14'), ('584','Tepeapulco','14'), ('585','Alfajayucan','14'), ('586','Lolotla','14'), ('587','Tepehuacan de guerrero','14'), ('588','Almoloya','14'), ('589','Metepec','14'), ('590','Tepeji del rio de ocampo','14'), ('591','Apan','14'), ('592','San agustin metzquititlan','14'), ('593','Tepetitlan','14'), ('594','El arenal','14'), ('595','Metztitlan','14'), ('596','Tetepango','14'), ('597','Atitalaquia','14'), ('598','Mineral del chico','14'), ('599','Villa de tezontepec','14'), ('600','Atlapexco','14'), ('601','Mineral del monte','14'), ('602','Tezontepec de aldama','14'), ('603','Atotonilco el grande','14'), ('604','La mision','14'), ('605','Tianguistengo','14'), ('606','Atotonilco de tula','14'), ('607','Mixquiahuala de juarez','14'), ('608','Tizayuca','14');
INSERT INTO `ciudades` VALUES ('609','Calnali','14'), ('610','Molango de escamilla','14'), ('611','Tlahuelilpan','14'), ('612','Cardonal','14'), ('613','Nicolas flores','14'), ('614','Tlahuiltepa','14'), ('615','Cuautepec de hinojosa','14'), ('616','Nopala de villagran','14'), ('617','Tlanalapa','14'), ('618','Chapantongo','14'), ('619','Omitlan de juarez','14'), ('620','Tlanchinol','14'), ('621','Chapulhuacan','14'), ('622','San felipe orizatlan','14'), ('623','Tlaxcoapan','14'), ('624','Chilcuautla','14'), ('625','Pacula','14'), ('626','Tolcayuca','14'), ('627','Eloxochitlan','14'), ('628','Pachuca de soto','14'), ('629','Tula de allende','14'), ('630','Emiliano zapata','14'), ('631','Pisaflores','14'), ('632','Tulancingo de bravo','14'), ('633','Epazoyucan','14'), ('634','Progreso de obregon','14'), ('635','Xochiatipan','14'), ('636','Francisco i. madero','14'), ('637','Mineral de la reforma','14'), ('638','Xochicoatlan','14'), ('639','Huasca de ocampo','14'), ('640','San agustin tlaxiaca','14'), ('641','Yahualica','14'), ('642','Huautla','14'), ('643','San bartolo tutotepec','14'), ('644','Zacualtipan de angeles','14'), ('645','Huazalingo','14'), ('646','San salvador','14'), ('647','Zapotlan de juarez','14'), ('648','Huehuetla','14'), ('649','Santiago de anaya','14'), ('650','Zempoala','14'), ('651','Huejutla de reyes','14'), ('652','Santiago tulantepec de lugo guerrero','14'), ('653','Zimapan','14'), ('654','Acatic ','15'), ('655','Acatlán de juárez ','15'), ('656','Ahualulco de mercado ','15'), ('657','Amacueca ','15'), ('658','Amatitán ','15'), ('659','Ameca ','15'), ('660','San juanito de escobedo ','15'), ('661','Arandas ','15'), ('662','El arenal ','15'), ('663','Atemajac de brizuela ','15'), ('664','Atengo ','15'), ('665','Atenguillo ','15'), ('666','Atotonilco el alto ','15'), ('667','Atoyac ','15'), ('668','Autlán de navarro ','15'), ('669','Ayotlán ','15'), ('670','Ayutla ','15'), ('671','La barca ','15'), ('672','Bolaños ','15'), ('673','Cabo corrientes ','15'), ('674','Casimiro castillo ','15'), ('675','Cihuatlán ','15'), ('676','Zapotlán el grande ','15'), ('677','Cocula ','15'), ('678','Colotlán ','15'), ('679','Concepción de buenos aires ','15'), ('680','Cuautitlán de garcía barragán ','15'), ('681','Cuautla ','15'), ('682','Cuquío ','15'), ('683','Chapala ','15'), ('684','Chimaltitán ','15'), ('685','Chiquilistlán ','15'), ('686','Degollado ','15'), ('687','Ejutla ','15'), ('688','Encarnación de díaz ','15'), ('689','Etzatlán ','15'), ('690','El grullo ','15'), ('691','Guachinango ','15'), ('692','Guadalajara ','15'), ('693','Hostotipaquillo ','15'), ('694','Huejúcar ','15'), ('695','Huejuquilla el alto ','15'), ('696','La huerta ','15'), ('697','Ixtlahacán de los membrillos ','15'), ('698','Ixtlahuacán del río ','15'), ('699','Jalostotitlán ','15'), ('700','Jamay ','15'), ('701','Jesús maría ','15'), ('702','Jilotlán de los dolores ','15'), ('703','Jocotepec ','15'), ('704','Juanacatlán ','15'), ('705','Juchitlán ','15'), ('706','Lagos de moreno ','15'), ('707','El limón ','15'), ('708','Magdalena ','15');
INSERT INTO `ciudades` VALUES ('709','Santa maría del oro ','15'), ('710','La manzanilla de la paz ','15'), ('711','Mascota ','15'), ('712','Mazamitla ','15'), ('713','Mexticacán ','15'), ('714','Mezquitic ','15'), ('715','Mixtlán ','15'), ('716','Ocotlán','15'), ('717','Ojuelos de  jalisco','15'), ('718','Pihuamo','15'), ('719','Poncitlán','15'), ('720','Puerto vallarta','15'), ('721','Villa purificación','15'), ('722','Quitupan','15'), ('723','El salto','15'), ('724','San cristóbal de la barranca','15'), ('725','San diego de alejandría','15'), ('726','San juan de los lagos','15'), ('727','San julián','15'), ('728','San marcos','15'), ('729','San martín de bolaños','15'), ('730','San martín hidalgo','15'), ('731','San miguel el alto','15'), ('732','Gómez farías','15'), ('733','San sebastián del oeste','15'), ('734','Santa maría de los ángeles','15'), ('735','Sayula','15'), ('736','Tala','15'), ('737','Talpa de allende','15'), ('738','Tamazula de gordiano','15'), ('739','Tapalpa','15'), ('740','Tecalitlán','15'), ('741','Tecolotlán','15'), ('742','Techaluta de montenegro','15'), ('743','Tenamaxlán','15'), ('744','Teocaltiche','15'), ('745','Teocuitatlán de corona','15'), ('746','Tepatitlán de morelos','15'), ('747','Tequila','15'), ('748','Teuchitlán','15'), ('749','Tizapán el alto','15'), ('750','Tlajomulco de zúñiga','15'), ('751','Tlaquepaque','15'), ('752','Tolimán','15'), ('753','Tomatlán','15'), ('754','Tonalá','15'), ('755','Tonaya','15'), ('756','Tonila','15'), ('757','Totatiche','15'), ('758','Tototlán','15'), ('759','Tuxcacuesco','15'), ('760','Tuxcueca','15'), ('761','Tuxpan','15'), ('762','Unión de san antonio','15'), ('763','Unión de tula','15'), ('764','Valle de guadalupe','15'), ('765','Valle de juárez','15'), ('766','San gabriel','15'), ('767','Villa corona','15'), ('768','Villa guerrero','15'), ('769','Villa hidalgo','15'), ('770','Cañadas de obregón','15'), ('771','Yahualica de gonzález gallo','15'), ('772','Zacoalco de torres','15'), ('773','Zapopan','15'), ('774','Zapotiltic','15'), ('775','Zapotitlán de vadillo','15'), ('776','Zapotlán del rey','15'), ('777','Zapotlanejo','15'), ('778','San ignacio cerro gordo','15'), ('779','Acuitzio','0'), ('780','Huiramba','0'), ('781','San lucas','0'), ('782','Aguililla','0'), ('783','Indaparapeo','0'), ('784','Santa ana maya','0'), ('785','Álvaro obregón','16'), ('786','Irimbo','16'), ('787','Salvador escalante','16'), ('788','Angamacutiro','16'), ('789','Ixtlán','16'), ('790','Senguio','16'), ('791','Angangueo','16'), ('792','Jacona','16'), ('793','Susupuato','16'), ('794','Apatzingán','16'), ('795','Jiménez','16'), ('796','Tacámbaro','16'), ('797','Aporo','16'), ('798','Jiquilpan','16'), ('799','Tancítaro','16'), ('800','Aquila','16'), ('801','Juárez','16'), ('802','Tangamandapio','16'), ('803','Ario','16'), ('804','Jungapeo','16'), ('805','Tangancícuaro','16'), ('806','Arteaga','16'), ('807','Lagunillas','16'), ('808','Tanhuato','16');
INSERT INTO `ciudades` VALUES ('809','Briseñas','16'), ('810','Madero','16'), ('811','Taretan','16'), ('812','Buenavista','16'), ('813','Maravatío','16'), ('814','Tarímbaro','16'), ('815','Carácuaro','16'), ('816','Marcos castellanos','16'), ('817','Tepalcatepec','16'), ('818','Coahuayana','16'), ('819','Lázaro cárdenas','16'), ('820','Tingambato','16'), ('821','Coalcomán de vázquez pallares','16'), ('822','Morelia','16'), ('823','Tinguindín','16'), ('824','Coeneo','16'), ('825','Morelos','16'), ('826','Tiquicheo de nicolás romero','16'), ('827','Contepec','16'), ('828','Múgica','16'), ('829','Tlalpujahua','16'), ('830','Copándaro','16'), ('831','Nahuatzen','16'), ('832','Tlazazalca','16'), ('833','Cotija','16'), ('834','Nocupétaro','16'), ('835','Tocumbo','16'), ('836','Cuitzeo','16'), ('837','Nuevo parangaricutiro','16'), ('838','Tumbiscatío','16'), ('839','Charapan','16'), ('840','Nuevo urecho','16'), ('841','Turicato','16'), ('842','Charo','16'), ('843','Numarán','16'), ('844','Tuxpan','16'), ('845','Chavinda','16'), ('846','Ocampo','16'), ('847','Tuzantla','16'), ('848','Cherán','16'), ('849','Pajacuarán','16'), ('850','Tzintzuntzan','16'), ('851','Chilchota','16'), ('852','Panindícuaro','16'), ('853','Tzitzio','16'), ('854','Chinicuila','16'), ('855','Parácuaro','16'), ('856','Uruapan','16'), ('857','Chucándiro','16'), ('858','Paracho','16'), ('859','Venustiano carranza','16'), ('860','Churintzio','16'), ('861','Pátzcuaro','16'), ('862','Villamar','16'), ('863','Churumuco','16'), ('864','Penjamillo','16'), ('865','Vista hermosa','16'), ('866','Ecuandureo','16'), ('867','Peribán','16'), ('868','Yurécuaro','16'), ('869','Epitacio huerta','16'), ('870','Piedad, la','16'), ('871','Zacapu','16'), ('872','Erongarícuaro','16'), ('873','Purépero','16'), ('874','Zamora','16'), ('875','Gabriel zamora','16'), ('876','Puruándiro','16'), ('877','Zináparo','16'), ('878','Hidalgo','16'), ('879','Queréndaro','16'), ('880','Zinapécuaro','16'), ('881','Huacana, la','16'), ('882','Quiroga','16'), ('883','Ziracuaretiro','16'), ('884','Huandacareo','16'), ('885','Régules, cojumatlán de','16'), ('886','Zitácuaro','16'), ('887','Huaniqueo','16'), ('888','Reyes, los','16'), ('889','José sixto verduzco','16'), ('890','Huetamo','16'), ('891','Sahuayo','16'), ('892','Villa guerrero','16'), ('893','Villa hidalgo','16'), ('894','Cañadas de obregón','16'), ('895','Yahualica de gonzález gallo','16'), ('896','Zacoalco de torres','16'), ('897','Zapopan','16'), ('898','Zapotiltic','16'), ('899','Zapotitlán de vadillo','16'), ('900','Zapotlán del rey','16'), ('901','Zapotlanejo','16'), ('902','San ignacio cerro gordo','16'), ('903','Amacuzac ','17'), ('904','Atlatlahucan ','17'), ('905','Axochiapan ','17'), ('906','Ayala ','17'), ('907','Coatlan del río ','17'), ('908','Cuautla ','17');
INSERT INTO `ciudades` VALUES ('909','Cuernavaca ','17'), ('910','Emiliano zapata ','17'), ('911','Huitzilac ','17'), ('912','Jantetelco ','17'), ('913','Jiutepec ','17'), ('914','Jojutla ','17'), ('915','Jonacatepec ','17'), ('916','Mazatepec ','17'), ('917','Miacatlán ','17'), ('918','Ocuituco ','17'), ('919','Puente de ixtla ','17'), ('920','Temixco ','17'), ('921','Temoac','17'), ('922','Tepalcingo ','17'), ('923','Tepoztlan ','17'), ('924','Tetecala ','17'), ('925','Tetela del volcán ','17'), ('926','Tlalnepantla','17'), ('927','Tlaltizapan','17'), ('928','Tlaquiltenango','17'), ('929','Tlayacapan','17'), ('930','Totolapan','17'), ('931','Xochitepec','17'), ('932','Yautepec','17'), ('933','Yecapixtla','17'), ('934','Zacatepec','17'), ('935','Zacualpan de amilpas','17'), ('936','Acaponeta ','18'), ('937','Ahuacatlán ','18'), ('938','Amatlán de cañas ','18'), ('939','Bahía de banderas','18'), ('940','Compostela ','18'), ('941','Huajicori ','18'), ('942','Ixtlán del río ','18'), ('943','Jala ','18'), ('944','Nayar, el ','18'), ('945','Rosamorada ','18'), ('946','Ruíz','18'), ('947','San blas','18'), ('948','San pedro lagunillas','18'), ('949','Santa maría del oro','18'), ('950','Santiago ixcuintla','18'), ('951','Tecuala','18'), ('952','Tepic','18'), ('953','Tuxpan','18'), ('954','Xalisco ','18'), ('955','Yesca, la','18'), ('956','Abasolo  ','19'), ('957','Agualeguas ','19'), ('958','Aldamas, los ','19'), ('959','Allende ','19'), ('960','Anáhuac ','19'), ('961','Apodaca ','19'), ('962','Aramberri ','19'), ('963','Bustamante ','19'), ('964','Cadereyta jiménez ','19'), ('965','Carmen, el ','19'), ('966','Cerralvo ','19'), ('967','China ','19'), ('968','Ciénega de flores ','19'), ('969','Doctor arroyo ','19'), ('970','Doctor coss ','19'), ('971','Doctor gonzález ','19'), ('972','Galeana ','19'), ('973','García ','19'), ('974','General bravo ','19'), ('975','General escobedo ','19'), ('976','General terán ','19'), ('977','General treviño ','19'), ('978','General zaragoza ','19'), ('979','General zuazua ','19'), ('980','Guadalupe ','19'), ('981','Herreras, los ','19'), ('982','Hidalgo','19'), ('983','Higueras','19'), ('984','Hualahuises','19'), ('985','Iturbide','19'), ('986','Juárez','19'), ('987','Lampazos de naranjo','19'), ('988','Linares','19'), ('989','Marín','19'), ('990','Melchor ocampo','19'), ('991','Mier y noriega','19'), ('992','Mina','19'), ('993','Montemorelos','19'), ('994','Monterrey','19'), ('995','Parás','19'), ('996','Pesquería','19'), ('997','Ramones, los','19'), ('998','Rayones','19'), ('999','Sabinas hidalgo','19'), ('1000','Salinas victoria','19'), ('1001','San nicolás de los garza','19'), ('1002','San pedro garza garcía','19'), ('1003','Santa catarina','19'), ('1004','Santiago','19'), ('1005','Vallecillo','19'), ('1006','Villaldama','19'), ('1007','Abejones','20'), ('1008','Acatlan de perez figueroa','20');
INSERT INTO `ciudades` VALUES ('1009','Animas trujano','20'), ('1010','Asuncion cacalotepec','20'), ('1011','Asuncion cuyotepeji','20'), ('1012','Asuncion ixtaltepec','20'), ('1013','Asuncion nochixtlan','20'), ('1014','Asuncion ocotlan','20'), ('1015','Asuncion tlacolulita','20'), ('1016','Ayoquezco de aldama','20'), ('1017','Ayotzintepec','20'), ('1018','Calihuala','20'), ('1019','Candelaria loxicha','20'), ('1020','Capulalpam de mendez','20'), ('1021','Cienega de zimatlan','20'), ('1022','Ciudad ixtepec','20'), ('1023','Coatecas altas','20'), ('1024','Coicoyan de las flores','20'), ('1025','Concepcion buenavista','20'), ('1026','Concepcion papalo','20'), ('1027','Constancia del rosario','20'), ('1028','Cosolapa','20'), ('1029','Cosoltepec','20'), ('1030','Cuilapam de guerrero','20'), ('1031','Cuyamecalco villa de zaragoza','20'), ('1032','Chahuites','20'), ('1033','Chalcatongo de hidalgo','20'), ('1034','Chiquihuitlan de benito juarez','20'), ('1035','El barrio de la soledad','20'), ('1036','El espinal','20'), ('1037','Eloxochitlan de flores magon','20'), ('1038','Fresnillo de trujano','20'), ('1039','Guadalupe de ramirez','20'), ('1040','Guadalupe etla','20'), ('1041','Guelatao de juarez','20'), ('1042','Guevea de humboldt','20'), ('1043','Heroica ciudad de ejutla de crespo','20'), ('1044','Heroica ciudad de huajuapan de leon','20'), ('1045','Heroica ciudad de tlaxiaco','20'), ('1046','Huautepec','20'), ('1047','Huautla de jimenez','20'), ('1048','Ixpantepec nieves','20'), ('1049','Ixtlan de juarez','20'), ('1050','Juchitan de zaragoza','20'), ('1051','La compañia','20'), ('1052','La pe','20'), ('1053','La reforma','20'), ('1054','La trinidad vista hermosa','20'), ('1055','Loma bonita','20'), ('1056','Magdalena apasco','20'), ('1057','Magdalena jaltepec','20'), ('1058','Magdalena mixtepec','20'), ('1059','Magdalena ocotlan','20'), ('1060','Magdalena peñasco','20'), ('1061','Magdalena teitipac','20'), ('1062','Magdalena tequisistlan','20'), ('1063','Magdalena tlacotepec','20'), ('1064','Magdalena yodocono de porfirio diaz','20'), ('1065','Magdalena zahuatlan','20'), ('1066','Mariscala de juarez','20'), ('1067','Martires de tacubaya','20'), ('1068','Matias romero','20'), ('1069','Mazatlan villa de flores','20'), ('1070','Mesones hidalgo','20'), ('1071','Miahuatlan de porfirio diaz','20'), ('1072','Mixistlan de la reforma','20'), ('1073','Monjas','20'), ('1074','Natividad','20'), ('1075','Nazareno etla','20'), ('1076','Nejapa de madero','20'), ('1077','Nuevo zoquiapam','20'), ('1078','Oaxaca de juarez','20'), ('1079','Ocotlan de morelos','20'), ('1080','Pinotepa de don luis','20'), ('1081','Pluma hidalgo','20'), ('1082','Putla villa de guerrero','20'), ('1083','Reforma de pineda','20'), ('1084','Reyes etla','20'), ('1085','Rojas de cuauhtemoc','20'), ('1086','Salina cruz','20'), ('1087','San agustin amatengo','20'), ('1088','San agustin atenango','20'), ('1089','San agustin chayuco','20'), ('1090','San agustin de las juntas','20'), ('1091','San agustin etla','20'), ('1092','San agustin loxicha','20'), ('1093','San agustin tlacotepec','20'), ('1094','San agustin yatareni','20'), ('1095','San andres cabecera nueva','20'), ('1096','San andres dinicuiti','20'), ('1097','San andres huaxpaltepec','20'), ('1098','San andres huayapam','20'), ('1099','San andres ixtlahuaca','20'), ('1100','San andres lagunas','20'), ('1101','San andres nuxiño','20'), ('1102','San andres paxtlan','20'), ('1103','San andres sinaxtla','20'), ('1104','San andres solaga','20'), ('1105','San andres teotilalpam','20'), ('1106','San andres tepetlapa','20'), ('1107','San andres yaa','20'), ('1108','San andres zabache','20');
INSERT INTO `ciudades` VALUES ('1109','San andres zautla','20'), ('1110','San antonino castillo velasco','20'), ('1111','San antonino el alto','20'), ('1112','San antonino monteverde','20'), ('1113','San antonio acutla','20'), ('1114','San antonio de la cal','20'), ('1115','San antonio huitepec','20'), ('1116','San antonio nanahuatipam','20'), ('1117','San antonio sinicahua','20'), ('1118','San antonio tepetlapa','20'), ('1119','San baltazar chichicapam','20'), ('1120','San baltazar loxicha','20'), ('1121','San baltazar yatzachi el bajo','20'), ('1122','San bartolo coyotepec','20'), ('1123','San bartolo soyaltepec','20'), ('1124','San bartolo yautepec','20'), ('1125','San bartolome ayautla','20'), ('1126','San bartolome loxicha','20'), ('1127','San bartolome quialana','20'), ('1128','San bartolome yucuañe','20'), ('1129','San bartolome zoogocho','20'), ('1130','San bernardo mixtepec','20'), ('1131','San blas atempa','20'), ('1132','San carlos yautepec','20'), ('1133','San cristobal amatlan','20'), ('1134','San cristobal amoltepec','20'), ('1135','San cristobal lachirioag','20'), ('1136','San cristobal suchixtlahuaca','20'), ('1137','San dionisio del mar','20'), ('1138','San dionisio ocotepec','20'), ('1139','San dionisio ocotlan','20'), ('1140','San esteban atatlahuca','20'), ('1141','San felipe jalapa de diaz','20'), ('1142','San felipe tejalapam','20'), ('1143','San felipe usila','20'), ('1144','San francisco cahuacua','20'), ('1145','San francisco cajonos','20'), ('1146','San francisco chapulapa','20'), ('1147','San francisco chindua','20'), ('1148','San francisco del mar','20'), ('1149','San francisco huehuetlan','20'), ('1150','San francisco ixhuatan','20'), ('1151','San francisco jaltepetongo','20'), ('1152','San francisco lachigolo','20'), ('1153','San francisco logueche','20'), ('1154','San francisco nuxaño','20'), ('1155','San francisco ozolotepec','20'), ('1156','San francisco sola','20'), ('1157','San francisco telixtlahuaca','20'), ('1158','San francisco teopan','20'), ('1159','San francisco tlapancingo','20'), ('1160','San gabriel mixtepec','20'), ('1161','San ildefonso amatlan','20'), ('1162','San ildefonso sola','20'), ('1163','San ildefonso villa alta','20'), ('1164','San jacinto amilpas','20'), ('1165','San jacinto tlacotepec','20'), ('1166','San jeronimo coatlan','20'), ('1167','San jeronimo silacayoapilla','20'), ('1168','San jeronimo sosola','20'), ('1169','San jeronimo taviche','20'), ('1170','San jeronimo tecoatl','20'), ('1171','San jeronimo tlacochahuaya','20'), ('1172','San jorge nuchita','20'), ('1173','San jose ayuquila','20'), ('1174','San jose chiltepec','20'), ('1175','San jose del peñasco','20'), ('1176','San jose del progreso','20'), ('1177','San jose estancia grande','20'), ('1178','San jose independencia','20'), ('1179','San jose lachiguiri','20'), ('1180','San jose tenango','20'), ('1181','San juan achiutla','20'), ('1182','San juan atepec','20'), ('1183','San juan bautista atatlahuca','20'), ('1184','San juan bautista coixtlahuaca','20'), ('1185','San juan bautista cuicatlan','20'), ('1186','San juan bautista guelache','20'), ('1187','San juan bautista jayacatlan','20'), ('1188','San juan bautista lo de soto','20'), ('1189','San juan bautista suchitepec','20'), ('1190','San juan bautista tlacoatzintepec','20'), ('1191','San juan bautista tlachichilco','20'), ('1192','San juan bautista tuxtepec','20'), ('1193','San juan bautista valle nacional','20'), ('1194','San juan cacahuatepec','20'), ('1195','San juan cieneguilla','20'), ('1196','San juan coatzospam','20'), ('1197','San juan colorado','20'), ('1198','San juan comaltepec','20'), ('1199','San juan cotzocon','20'), ('1200','San juan chicomezuchil','20'), ('1201','San juan chilateca','20'), ('1202','San juan de los cues','20'), ('1203','San juan del estado','20'), ('1204','San juan del rio','20'), ('1205','San juan diuxi','20'), ('1206','San juan evangelista analco','20'), ('1207','San juan guelavia','20'), ('1208','San juan guichicovi','20');
INSERT INTO `ciudades` VALUES ('1209','San juan ihualtepec','20'), ('1210','San juan juquila mixes','20'), ('1211','San juan juquila vijanos','20'), ('1212','San juan lachao','20'), ('1213','San juan lachigalla','20'), ('1214','San juan lajarcia','20'), ('1215','San juan lalana','20'), ('1216','San juan mazatlan','20'), ('1217','San juan mixtepec - distr. 08','20'), ('1218','San juan mixtepec - distr. 26','20'), ('1219','San juan ñumi','20'), ('1220','San juan ozolotepec','20'), ('1221','San juan petlapa','20'), ('1222','San juan quiahije','20'), ('1223','San juan quiotepec','20'), ('1224','San juan sayultepec','20'), ('1225','San juan tabaa','20'), ('1226','San juan tamazola','20'), ('1227','San juan teita','20'), ('1228','San juan teitipac','20'), ('1229','San juan tepeuxila','20'), ('1230','San juan teposcolula','20'), ('1231','San juan yaee','20'), ('1232','San juan yatzona','20'), ('1233','San juan yucuita','20'), ('1234','San lorenzo','20'), ('1235','San lorenzo albarradas','20'), ('1236','San lorenzo cacaotepec','20'), ('1237','San lorenzo cuaunecuiltitla','20'), ('1238','San lorenzo texmelucan','20'), ('1239','San lorenzo victoria','20'), ('1240','San lucas camotlan','20'), ('1241','San lucas ojitlan','20'), ('1242','San lucas quiavini','20'), ('1243','San lucas zoquiapam','20'), ('1244','San luis amatlan','20'), ('1245','San marcial ozolotepec','20'), ('1246','San marcos arteaga','20'), ('1247','San martin de los cansecos','20'), ('1248','San martin huamelulpam','20'), ('1249','San martin itunyoso','20'), ('1250','San martin lachila','20'), ('1251','San martin peras','20'), ('1252','San martin tilcajete','20'), ('1253','San martin toxpalan','20'), ('1254','San martin zacatepec','20'), ('1255','San mateo cajonos','20'), ('1256','San mateo del mar','20'), ('1257','San mateo etlatongo','20'), ('1258','San mateo nejapam','20'), ('1259','San mateo peñasco','20'), ('1260','San mateo piñas','20'), ('1261','San mateo rio hondo','20'), ('1262','San mateo sindihui','20'), ('1263','San mateo tlapiltepec','20'), ('1264','San mateo yoloxochitlan','20'), ('1265','San melchor betaza','20'), ('1266','San miguel achiutla','20'), ('1267','San miguel ahuehuetitlan','20'), ('1268','San miguel aloapam','20'), ('1269','San miguel amatitlan','20'), ('1270','San miguel amatlan','20'), ('1271','San miguel coatlan','20'), ('1272','San miguel chicahua','20'), ('1273','San miguel chimalapa','20'), ('1274','San miguel del puerto','20'), ('1275','San miguel del rio','20'), ('1276','San miguel ejutla','20'), ('1277','San miguel el grande','20'), ('1278','San miguel huautla','20'), ('1279','San miguel mixtepec','20'), ('1280','San miguel panixtlahuaca','20'), ('1281','San miguel peras','20'), ('1282','San miguel piedras','20'), ('1283','San miguel quetzaltepec','20'), ('1284','San miguel santa flor','20'), ('1285','San miguel soyaltepec','20'), ('1286','San miguel suchixtepec','20'), ('1287','San miguel tecomatlan','20'), ('1288','San miguel tenango','20'), ('1289','San miguel tequixtepec','20'), ('1290','San miguel tilquiapam','20'), ('1291','San miguel tlacamama','20'), ('1292','San miguel tlacotepec','20'), ('1293','San miguel tulancingo','20'), ('1294','San miguel yotao','20'), ('1295','San nicolas','20'), ('1296','San nicolas hidalgo','20'), ('1297','San pablo coatlan','20'), ('1298','San pablo cuatro venados','20'), ('1299','San pablo etla','20'), ('1300','San pablo huitzo','20'), ('1301','San pablo huixtepec','20'), ('1302','San pablo macuiltianguis','20'), ('1303','San pablo tijaltepec','20'), ('1304','San pablo villa de mitla','20'), ('1305','San pablo yaganiza','20'), ('1306','San pedro amuzgos','20'), ('1307','San pedro apostol','20'), ('1308','San pedro atoyac','20');
INSERT INTO `ciudades` VALUES ('1309','San pedro cajonos','20'), ('1310','San pedro comitancillo','20'), ('1311','San pedro coxcaltepec cantaros','20'), ('1312','San pedro el alto','20'), ('1313','San pedro huamelula','20'), ('1314','San pedro huilotepec','20'), ('1315','San pedro ixcatlan','20'), ('1316','San pedro ixtlahuaca','20'), ('1317','San pedro jaltepetongo','20'), ('1318','San pedro jicayan','20'), ('1319','San pedro jocotipac','20'), ('1320','San pedro juchatengo','20'), ('1321','San pedro martir','20'), ('1322','San pedro martir quiechapa','20'), ('1323','San pedro martir yucuxaco','20'), ('1324','San pedro mixtepec - distr. 22 -','20'), ('1325','San pedro mixtepec - distr. 26 -','20'), ('1326','San pedro molinos','20'), ('1327','San pedro nopala','20'), ('1328','San pedro ocopetatillo','20'), ('1329','San pedro ocotepec','20'), ('1330','San pedro pochutla','20'), ('1331','San pedro quiatoni','20'), ('1332','San pedro sochiapam','20'), ('1333','San pedro tapanatepec','20'), ('1334','San pedro taviche','20'), ('1335','San pedro teozacoalco','20'), ('1336','San pedro teutila','20'), ('1337','San pedro tidaa','20'), ('1338','San pedro topiltepec','20'), ('1339','San pedro totolapa','20'), ('1340','San pedro y san pablo ayutla','20'), ('1341','San pedro y san pablo teposcolula','20'), ('1342','San pedro y san pablo tequixtepec','20'), ('1343','San pedro yaneri','20'), ('1344','San pedro yolox','20'), ('1345','San pedro yucunama','20'), ('1346','San raymundo jalpan','20'), ('1347','San sebastian abasolo','20'), ('1348','San sebastian coatlan','20'), ('1349','San sebastian ixcapa','20'), ('1350','San sebastian nicananduta','20'), ('1351','San sebastian rio hondo','20'), ('1352','San sebastian tecomaxtlahuaca','20'), ('1353','San sebastian teitipac','20'), ('1354','San sebastian tutla','20'), ('1355','San simon almolongas','20'), ('1356','San simon zahuatlan','20'), ('1357','San vicente coatlan','20'), ('1358','San vicente lachixio','20'), ('1359','San vicente nuñu','20'), ('1360','Santa ana','20'), ('1361','Santa ana ateixtlahuaca','20'), ('1362','Santa ana cuauhtemoc','20'), ('1363','Santa ana del valle','20'), ('1364','Santa ana tavela','20'), ('1365','Santa ana tlapacoyan','20'), ('1366','Santa ana yareni','20'), ('1367','Santa ana zegache','20'), ('1368','Santa catalina quieri','20'), ('1369','Santa catarina cuixtla','20'), ('1370','Santa catarina ixtepeji','20'), ('1371','Santa catarina juquila','20'), ('1372','Santa catarina lachatao','20'), ('1373','Santa catarina loxicha','20'), ('1374','Santa catarina mechoacan','20'), ('1375','Santa catarina minas','20'), ('1376','Santa catarina quiane','20'), ('1377','Santa catarina quioquitani','20'), ('1378','Santa catarina tayata','20'), ('1379','Santa catarina ticua','20'), ('1380','Santa catarina yosonotu','20'), ('1381','Santa catarina zapoquila','20'), ('1382','Santa cruz acatepec','20'), ('1383','Santa cruz amilpas','20'), ('1384','Santa cruz de bravo','20'), ('1385','Santa cruz itundujia','20'), ('1386','Santa cruz mixtepec','20'), ('1387','Santa cruz nundaco','20'), ('1388','Santa cruz papalutla','20'), ('1389','Santa cruz tacache de mina','20'), ('1390','Santa cruz tacahua','20'), ('1391','Santa cruz tayata','20'), ('1392','Santa cruz xitla','20'), ('1393','Santa cruz xoxocotlan','20'), ('1394','Santa cruz zenzontepec','20'), ('1395','Santa gertrudis','20'), ('1396','Santa ines de zaragoza','20'), ('1397','Santa ines del monte','20'), ('1398','Santa ines yatzeche','20'), ('1399','Santa lucia del camino','20'), ('1400','Santa lucia miahuatlan','20'), ('1401','Santa lucia monteverde','20'), ('1402','Santa lucia ocotlan','20'), ('1403','Santa magdalena jicotlan','20'), ('1404','Santa maria alotepec','20'), ('1405','Santa maria apazco','20'), ('1406','Santa maria atzompa','20'), ('1407','Santa maria camotlan','20'), ('1408','Santa maria colotepec','20');
INSERT INTO `ciudades` VALUES ('1409','Santa maria cortijo','20'), ('1410','Santa maria coyotepec','20'), ('1411','Santa maria chachoapam','20'), ('1412','Santa maria chilchotla','20'), ('1413','Santa maria chimalapa','20'), ('1414','Santa maria del rosario','20'), ('1415','Santa maria del tule','20'), ('1416','Santa maria ecatepec','20'), ('1417','Santa maria guelace','20'), ('1418','Santa maria guienagati','20'), ('1419','Santa maria huatulco','20'), ('1420','Santa maria huazolotitlan','20'), ('1421','Santa maria ipalapa','20'), ('1422','Santa maria ixcatlan','20'), ('1423','Santa maria jacatepec','20'), ('1424','Santa maria jalapa del marques','20'), ('1425','Santa maria jaltianguis','20'), ('1426','Santa maria la asuncion','20'), ('1427','Santa maria lachixio','20'), ('1428','Santa maria mixtequilla','20'), ('1429','Santa maria nativitas','20'), ('1430','Santa maria nduayaco','20'), ('1431','Santa maria ozolotepec','20'), ('1432','Santa maria papalo','20'), ('1433','Santa maria peñoles','20'), ('1434','Santa maria petapa','20'), ('1435','Santa maria quiegolani','20'), ('1436','Santa maria sola','20'), ('1437','Santa maria tataltepec','20'), ('1438','Santa maria tecomavaca','20'), ('1439','Santa maria temaxcalapa','20'), ('1440','Santa maria temaxcaltepec','20'), ('1441','Santa maria teopoxco','20'), ('1442','Santa maria tepantlali','20'), ('1443','Santa maria texcatitlan','20'), ('1444','Santa maria tlahuitoltepec','20'), ('1445','Santa maria tlalixtac','20'), ('1446','Santa maria tonameca','20'), ('1447','Santa maria totolapilla','20'), ('1448','Santa maria xadani','20'), ('1449','Santa maria yalina','20'), ('1450','Santa maria yavesia','20'), ('1451','Santa maria yolotepec','20'), ('1452','Santa maria yosoyua','20'), ('1453','Santa maria yucuhiti','20'), ('1454','Santa maria zacatepec','20'), ('1455','Santa maria zaniza','20'), ('1456','Santa maria zoquitlan','20'), ('1457','Santiago amoltepec','20'), ('1458','Santiago apoala','20'), ('1459','Santiago apostol','20'), ('1460','Santiago astata','20'), ('1461','Santiago atitlan','20'), ('1462','Santiago ayuquililla','20'), ('1463','Santiago cacaloxtepec','20'), ('1464','Santiago camotlan','20'), ('1465','Santiago comaltepec','20'), ('1466','Santiago chazumba','20'), ('1467','Santiago choapam','20'), ('1468','Santiago del rio','20'), ('1469','Santiago huajolotitlan','20'), ('1470','Santiago huauclilla','20'), ('1471','Santiago ihuitlan plumas','20'), ('1472','Santiago ixcuintepec','20'), ('1473','Santiago ixtayutla','20'), ('1474','Santiago jamiltepec','20'), ('1475','Santiago jocotepec','20'), ('1476','Santiago juxtlahuaca','20'), ('1477','Santiago lachiguiri','20'), ('1478','Santiago lalopa','20'), ('1479','Santiago laollaga','20'), ('1480','Santiago laxopa','20'), ('1481','Santiago llano grande','20'), ('1482','Santiago matatlan','20'), ('1483','Santiago miltepec','20'), ('1484','Santiago minas','20'), ('1485','Santiago nacaltepec','20'), ('1486','Santiago nejapilla','20'), ('1487','Santiago niltepec','20'), ('1488','Santiago nundiche','20'), ('1489','Santiago nuyoo','20'), ('1490','Santiago pinotepa nacional','20'), ('1491','Santiago suchilquitongo','20'), ('1492','Santiago tamazola','20'), ('1493','Santiago tapextla','20'), ('1494','Santiago tenango','20'), ('1495','Santiago tepetlapa','20'), ('1496','Santiago tetepec','20'), ('1497','Santiago texcalcingo','20'), ('1498','Santiago textitlan','20'), ('1499','Santiago tilantongo','20'), ('1500','Santiago tillo','20'), ('1501','Santiago tlazoyaltepec','20'), ('1502','Santiago xanica','20'), ('1503','Santiago xiacui','20'), ('1504','Santiago yaitepec','20'), ('1505','Santiago yaveo','20'), ('1506','Santiago yolomecatl','20'), ('1507','Santiago yosondua','20'), ('1508','Santiago yucuyachi','20');
INSERT INTO `ciudades` VALUES ('1509','Santiago zacatepec','20'), ('1510','Santiago zoochila','20'), ('1511','Santo domingo albarradas','20'), ('1512','Santo domingo armenta','20'), ('1513','Santo domingo chihuitan','20'), ('1514','Santo domingo de morelos','20'), ('1515','Santo domingo ingenio','20'), ('1516','Santo domingo ixcatlan','20'), ('1517','Santo domingo nuxaa','20'), ('1518','Santo domingo ozolotepec','20'), ('1519','Santo domingo petapa','20'), ('1520','Santo domingo roayaga','20'), ('1521','Santo domingo tehuantepec','20'), ('1522','Santo domingo teojomulco','20'), ('1523','Santo domingo tepuxtepec','20'), ('1524','Santo domingo tlatayapam','20'), ('1525','Santo domingo tomaltepec','20'), ('1526','Santo domingo tonala','20'), ('1527','Santo domingo tonaltepec','20'), ('1528','Santo domingo xagacia','20'), ('1529','Santo domingo yanhuitlan','20'), ('1530','Santo domingo yodohino','20'), ('1531','Santo domingo zanatepec','20'), ('1532','Santo tomas jalieza','20'), ('1533','Santo tomas mazaltepec','20'), ('1534','Santo tomas ocotepec','20'), ('1535','Santo tomas tamazulapan','20'), ('1536','Santos reyes nopala','20'), ('1537','Santos reyes papalo','20'), ('1538','Santos reyes tepejillo','20'), ('1539','Santos reyes yucuna','20'), ('1540','Silacayoapam','20'), ('1541','Sitio de xitlapehua','20'), ('1542','Soledad etla','20'), ('1543','Tamazulapam del espiritu santo','20'), ('1544','Tanetze de zaragoza','20'), ('1545','Taniche','20'), ('1546','Tataltepec de valdes','20'), ('1547','Teococuilco de marcos perez','20'), ('1548','Teotitlan de flores magon','20'), ('1549','Teotitlan del valle','20'), ('1550','Teotongo','20'), ('1551','Tepelmeme villa de morelos','20'), ('1552','Tezoatlan de segura y luna','20'), ('1553','Tlacolula de matamoros','20'), ('1554','Tlacotepec plumas','20'), ('1555','Tlalixtac de cabrera','20'), ('1556','Totontepec villa de morelos','20'), ('1557','Trinidad zaachila','20'), ('1558','Union hidalgo hidalgo','20'), ('1559','Valerio trujano','20'), ('1560','Villa de chilapa de diaz','20'), ('1561','Villa de etla','20'), ('1562','Villa de tamazulapam del progreso','20'), ('1563','Villa de tututepec de melchor ocampo','20'), ('1564','Villa de zaachila','20'), ('1565','Villa diaz ordaz','20'), ('1566','Villa hidalgo','20'), ('1567','Villa sola de vega','20'), ('1568','Villa talea de castro','20'), ('1569','Villa tejupam de la union','20'), ('1570','Yaxe','20'), ('1571','Yogana','20'), ('1572','Yutanduchi de guerrero','20'), ('1573','Zapotitlan del rio','20'), ('1574','Zapotitlan lagunas','20'), ('1575','Zapotitlan palmas','20'), ('1576','Zimatlan de alvarez','20'), ('1577','Acajete ','21'), ('1578','Acateno ','21'), ('1579','Acatlán ','21'), ('1580','Acatzingo ','21'), ('1581','Acteopan ','21'), ('1582','Ahuacatlán ','21'), ('1583','Ahuatlán ','21'), ('1584','Ahuazotepec ','21'), ('1585','Ahuehuetitla ','21'), ('1586','Ajalpan ','21'), ('1587','Albino zertuche ','21'), ('1588','Aljojuca ','21'), ('1589','Altepexi ','21'), ('1590','Amixtlán ','21'), ('1591','Amozoc ','21'), ('1592','Aquixtla ','21'), ('1593','Atempan ','21'), ('1594','Atexcal ','21'), ('1595','Atlixco ','21'), ('1596','Atoyatempan ','21'), ('1597','Atzala ','21'), ('1598','Atzitzihuacán ','21'), ('1599','Atzitzintla ','21'), ('1600','Axutla ','21'), ('1601','Ayotoxco de guerrero ','21'), ('1602','Calpan ','21'), ('1603','Caltepec ','21'), ('1604','Camocuautla ','21'), ('1605','Caxhuacan ','21'), ('1606','Coatepec ','21'), ('1607','Coatzingo ','21'), ('1608','Cohetzala ','21');
INSERT INTO `ciudades` VALUES ('1609','Cohuecán ','21'), ('1610','Coronango ','21'), ('1611','Coxcatlán ','21'), ('1612','Coyomeapan ','21'), ('1613','Coyotepec ','21'), ('1614','Cuapiaxtla de madero ','21'), ('1615','Cuautempan ','21'), ('1616','Cuantinchán ','21'), ('1617','Cuautlancingo ','21'), ('1618','Coayuca de andrade ','21'), ('1619','Cuetzalan del progreso ','21'), ('1620','Cuyoaco ','21'), ('1621','Chalchicomula de sesma ','21'), ('1622','Chapulco ','21'), ('1623','Chiautla ','21'), ('1624','Chiautzingo ','21'), ('1625','Chiconcuautla ','21'), ('1626','Chichiquila ','21'), ('1627','Chietla ','21'), ('1628','Chigmecatitlan ','21'), ('1629','Chignahuapan ','21'), ('1630','Chignautla ','21'), ('1631','Chila ','21'), ('1632','Chila de la sal ','21'), ('1633','Honey ','21'), ('1634','Chilchotla ','21'), ('1635','Chinantla ','21'), ('1636','Domingo arenas ','21'), ('1637','Eloxochitlán ','21'), ('1638','Epatlán ','21'), ('1639','Esperanza ','21'), ('1640','Francisco z. mena ','21'), ('1641','General felipe angeles ','21'), ('1642','Guadalupe ','21'), ('1643','Guadalupe victoria ','21'), ('1644','Hermenegildo galeana ','21'), ('1645','Huaquechula ','21'), ('1646','Huatlatlauca ','21'), ('1647','Huauchinango ','21'), ('1648','Huehuetla ','21'), ('1649','Huehuetlán el chico ','21'), ('1650','Huejotzingo ','21'), ('1651','Hueyapan ','21'), ('1652','Hueytamalco ','21'), ('1653','Hueytlalpan ','21'), ('1654','Huitzilan de serdán ','21'), ('1655','Huitziltepec ','21'), ('1656','Atlequizayan ','21'), ('1657','Ixcamilpa de guerrero ','21'), ('1658','Ixcaquixtla ','21'), ('1659','Ixtacamaxtitlán ','21'), ('1660','Ixtepec ','21'), ('1661','Izúcar de matamoros ','21'), ('1662','Jalpan ','21'), ('1663','Jolalpan ','21'), ('1664','Jonotla ','21'), ('1665','Jopala ','21'), ('1666','Juan c. bonilla ','21'), ('1667','Juan galindo ','21'), ('1668','Juan n. méndez ','21'), ('1669','Lafragua ','21'), ('1670','Libres ','21'), ('1671','Magdalena tlatlauquitepec, la ','21'), ('1672','Mazapiltepec de juárez ','21'), ('1673','Mixtla ','21'), ('1674','Molcaxac ','21'), ('1675','Morelos cañada ','21'), ('1676','Naupan ','21'), ('1677','Nauzontla ','21'), ('1678','Nealtican ','21'), ('1679','Nicolás bravo ','21'), ('1680','Nopalucan ','21'), ('1681','Ocotepec ','21'), ('1682','Ocoyucan ','21'), ('1683','Olintla ','21'), ('1684','Oriental ','21'), ('1685','Pahuatlán ','21'), ('1686','Palmar de bravo ','21'), ('1687','Pantepec ','21'), ('1688','Petlalcingo ','21'), ('1689','Piaxtla ','21'), ('1690','Puebla ','21'), ('1691','Quecholac ','21'), ('1692','Quimixtlán ','21'), ('1693','Rafael lara grajales ','21'), ('1694','Reyes de juárez, los ','21'), ('1695','San andrés cholula ','21'), ('1696','San antonio cañada ','21'), ('1697','San diego la meza tochimiltzingo ','21'), ('1698','San felipe teotlalcingo ','21'), ('1699','San felipe tepatlán ','21'), ('1700','San gabriel chilac ','21'), ('1701','San gregorio atzompa ','21'), ('1702','San jerónimo tecuanipan ','21'), ('1703','San jerónimo xayacatlán ','21'), ('1704','San josé chiapa ','21'), ('1705','San josé miahuatlán ','21'), ('1706','San juan atenco ','21'), ('1707','San juan atzompa ','21'), ('1708','San martín texmelucan ','21');
INSERT INTO `ciudades` VALUES ('1709','San martín totoltepec ','21'), ('1710','San matías tlalancaleca ','21'), ('1711','San miguel ixitlán ','21'), ('1712','San miguel xoxtla ','21'), ('1713','San nicolás buenos aires ','21'), ('1714','San nicolás de los ranchos ','21'), ('1715','San pablo anicano ','21'), ('1716','San pedro cholula ','21'), ('1717','San pedro yeloixtlahuaca ','21'), ('1718','San salvador el seco ','21'), ('1719','San salvador el verde ','21'), ('1720','San salvador huixcolotla ','21'), ('1721','San sebastián tlacotepec ','21'), ('1722','Santa catarina tlaltempan ','21'), ('1723','Santa inés ahuatempan','21'), ('1724','Santa isabel cholula','21'), ('1725','Santiago miahuatlán','21'), ('1726','Huehuetlán el grande','21'), ('1727','Santo tomás hueyotlipan','21'), ('1728','Soltepec','21'), ('1729','Tecali de herrera','21'), ('1730','Tecamachalco','21'), ('1731','Tecomatlán','21'), ('1732','Tehuacán','21'), ('1733','Tehuitzingo','21'), ('1734','Tenampulco','21'), ('1735','Teopantlán','21'), ('1736','Teotlalco','21'), ('1737','Tepanco de lópez','21'), ('1738','Tepango de rodríguez','21'), ('1739','Tepatlaxco de hidalgo','21'), ('1740','Tepeaca','21'), ('1741','Tepemaxalco','21'), ('1742','Tepeojuma','21'), ('1743','Tepetzintla','21'), ('1744','Tepexco','21'), ('1745','Tepexi de rodríguez','21'), ('1746','Tepeyahualco','21'), ('1747','Tepeyahualco de cuauhtémoc','21'), ('1748','Tetela de ocampo','21'), ('1749','Teteles de ávila castillo','21'), ('1750','Teziutlán','21'), ('1751','Tianguismanalco','21'), ('1752','Tilapa','21'), ('1753','Tlacotepec de benito juárez','21'), ('1754','Tlacuilotepec','21'), ('1755','Tlachichuca','21'), ('1756','Tlahuapan','21'), ('1757','Tlaltenango','21'), ('1758','Tlanepantla','21'), ('1759','Tlaola','21'), ('1760','Tlapacoya','21'), ('1761','Tlapanalá','21'), ('1762','Tlatlauquitepec','21'), ('1763','Tlaxco','21'), ('1764','Tochimilco','21'), ('1765','Tochtepec','21'), ('1766','Totoltepec de guerrero','21'), ('1767','Tulcingo','21'), ('1768','Tuzamapan de galeana','21'), ('1769','Tzicatlacoyan','21'), ('1770','Venustiano carranza','21'), ('1771','Vicente guerrero','21'), ('1772','Xayacatlán de bravo','21'), ('1773','Xicotepec','21'), ('1774','Xicotlán','21'), ('1775','Xiutetelco','21'), ('1776','Xochiapulco','21'), ('1777','Xochiltepec','21'), ('1778','Xochitlán de vicente suárez','21'), ('1779','Xochitlán todos santos','21'), ('1780','Yaonáhuac','21'), ('1781','Yehualtepec','21'), ('1782','Zacapala','21'), ('1783','Zacapoaxtla','21'), ('1784','Zacatlán','21'), ('1786','Zapotitlán de méndez','21'), ('1787','Zaragoza','21'), ('1788','Zautla','21'), ('1789','Zihuateutla','21'), ('1790','Zinacatepec','21'), ('1791','Zongozotla','21'), ('1792','Zoquiapan','21'), ('1793','Zoquitlán','21'), ('1794','Amealco de bonfil ','22'), ('1795','Arroyo seco ','22'), ('1796','Cadereyta de montes ','22'), ('1797','Colón ','22'), ('1798','Corregidora ','22'), ('1799','El marqués','22'), ('1800','Ezequiel montes ','22'), ('1801','Huimilpan ','22'), ('1802','Jalpan de serra ','22'), ('1803','Landa de matamoros ','22'), ('1804','Pedro escobedo','22'), ('1805','Peñamiller','22'), ('1806','Pinal de amoles','22'), ('1807','Querétaro','22'), ('1808','San joaquín','22'), ('1809','San juan del río','22');
INSERT INTO `ciudades` VALUES ('1810','Tequisquiapan','22'), ('1811','Tolimán','22'), ('1812','Cozumel ','23'), ('1813','Felipe carrillo puerto ','23'), ('1814','Isla mujeres ','23'), ('1815','Othón p. blanco ','23'), ('1816','Benito juárez','23'), ('1817','José maría morelos','23'), ('1818','Lázaro cárdenas','23'), ('1819','Solidaridad','23'), ('1866','Ahualulco  ','24'), ('1867','Alaquines ','24'), ('1868','Aquismón ','24'), ('1869','Armadillo de los infante ','24'), ('1870','Axtla de terrazas ','24'), ('1871','Cárdenas ','24'), ('1872','Catorce ','24'), ('1873','Cedral ','24'), ('1874','Cerritos ','24'), ('1875','Cerro de san pedro ','24'), ('1876','Ciudad del maíz ','24'), ('1877','Ciudad fernández ','24'), ('1878','Ciudad valles ','24'), ('1879','Coxcatlán ','24'), ('1880','Charcas ','24'), ('1881','Ébano  ','24'), ('1882','Guadalcázar ','24'), ('1883','Huehuetlán ','24'), ('1884','Lagunillas ','24'), ('1885','Matehuala ','24'), ('1886','Matlapa ','24'), ('1887','Mexquitic de carmona ','24'), ('1888','Moctezuma ','24'), ('1889','El naranjo ','24'), ('1890','Rayón ','24'), ('1891','Rioverde ','24'), ('1892','Salinas ','24'), ('1893','San antonio ','24'), ('1894','San ciro de acosta ','24'), ('1895','San luis potosí ','24'), ('1896','San martín chalchicuautla  ','24'), ('1897','San nicolás tolentino ','24'), ('1898','San vicente tancuayalab ','24'), ('1899','Santa catarina ','24'), ('1900','Santa maría del río ','24'), ('1901','Santo domingo ','24'), ('1902','Soledad de graciano sánchez ','24'), ('1903','Tamasopo ','24'), ('1904','Tamazunchale ','24'), ('1905','Tampacán ','24'), ('1906','Tampamolón corona ','24'), ('1907','Tamuín ','24'), ('1908','Tancanhuitz de santos ','24'), ('1909','Tanlajás ','24'), ('1910','Tanquián de escobedo','24'), ('1911','Tierranueva','24'), ('1912','Vanegas','24'), ('1913','Venado','24'), ('1914','Villa de arista','24'), ('1915','Villa de arriaga','24'), ('1916','Villa de guadalupe','24'), ('1917','Villa de la paz','24'), ('1918','Villa de ramos','24'), ('1919','Villa de reyes','24'), ('1920','Villa hidalgo','24'), ('1921','Villa juárez','24'), ('1922','Xilitla','24'), ('1923','Zaragoza','24'), ('1938','Ahome ','25'), ('1939','Angostura ','25'), ('1940','Badiraguato ','25'), ('1941','Concordia ','25'), ('1942','Cosalá ','25'), ('1943','Culiacán ','25'), ('1944','Choix ','25'), ('1945','Elota ','25'), ('1946','Escuinapa ','25'), ('1947','Fuerte, el','25'), ('1948','Guasave','25'), ('1949','Mazatlán','25'), ('1950','Mocorito','25'), ('1951','Navolato','25'), ('1952','Rosario','25'), ('1953','Salvador alvarado','25'), ('1954','San ignacio','25'), ('1955','Sinaloa','25'), ('2010','Aconchi ','26'), ('2011','Agua prieta ','26'), ('2012','Alamos ','26'), ('2013','Altar ','26'), ('2014','Arivechi ','26'), ('2015','Arizpe ','26'), ('2016','Atil ','26'), ('2017','Bacadéhuachi ','26'), ('2018','Bacanora ','26'), ('2019','Bacerac ','26'), ('2020','Bacoachi ','26'), ('2021','Bácum ','26'), ('2022','Banámichi ','26'), ('2023','Baviácora ','26');
INSERT INTO `ciudades` VALUES ('2024','Bavispe ','26'), ('2025','Benjamín hill ','26'), ('2026','Caborca ','26'), ('2027','Cajeme ','26'), ('2028','Cananea ','26'), ('2029','Carbó ','26'), ('2030','La colorada ','26'), ('2031','Cucurpe ','26'), ('2032','Cumpas ','26'), ('2033','Divisaderos ','26'), ('2034','Empalme ','26'), ('2035','Etchojoa ','26'), ('2036','Fronteras ','26'), ('2037','Granados ','26'), ('2038','Guaymas ','26'), ('2039','Hermosillo ','26'), ('2040','Huachinera ','26'), ('2041','Huásabas ','26'), ('2042','Huatabampo ','26'), ('2043','Huépac ','26'), ('2044','Imuris ','26'), ('2045','Magdalena ','26'), ('2046','Mazatán ','26'), ('2047','Moctezuma ','26'), ('2048','Naco ','26'), ('2049','Nácori chico ','26'), ('2050','Nacozari de garcía ','26'), ('2051','Navojoa ','26'), ('2052','Nogales ','26'), ('2053','Onavas ','26'), ('2054','Opodepe ','26'), ('2055','Oquitoa ','26'), ('2056','Pitiquito ','26'), ('2057','Puerto peñasco ','26'), ('2058','Quiriego','26'), ('2059','Rayón','26'), ('2060','Rosario','26'), ('2061','Sahuaripa','26'), ('2062','San felipe de jesús','26'), ('2063','San javier','26'), ('2064','San luis río colorado','26'), ('2065','San miguel de horcasitas','26'), ('2066','San pedro de la cueva','26'), ('2067','Santa ana','26'), ('2068','Santa cruz','26'), ('2069','Sáric','26'), ('2070','Soyopa','26'), ('2071','Suaqui grande','26'), ('2072','Tepachi','26'), ('2073','Trincheras','26'), ('2074','Tubutama','26'), ('2075','Ures','26'), ('2076','Villa hidalgo','26'), ('2077','Villa pesqueira','26'), ('2078','Yécora','26'), ('2079','Plutarco elías calles','26'), ('2080','Benito juárez','26'), ('2081','San ignacio río muerto','26'), ('2082','Balancán ','27'), ('2083','Cárdenas ','27'), ('2084','Centla ','27'), ('2085','Centro ','27'), ('2086','Comalcalco ','27'), ('2087','Cunduacán ','27'), ('2088','Emiliano zapata ','27'), ('2089','Huimanguillo ','27'), ('2090','Jalapa ','27'), ('2091','Jalpa de méndez','27'), ('2092','Jonuta','27'), ('2093','Macuspana','27'), ('2094','Nacajuca','27'), ('2095','Paraíso','27'), ('2096','Tacotalpa','27'), ('2097','Teapa','27'), ('2098','Tenosique','27'), ('2099','Abasolo ','28'), ('2100','Aldama ','28'), ('2101','Altamira ','28'), ('2102','Antiguo morelos ','28'), ('2103','Burgos ','28'), ('2104','Bustamante ','28'), ('2105','Camargo ','28'), ('2106','Casas ','28'), ('2107','Ciudad madero ','28'), ('2108','Cruillas ','28'), ('2109','Gómez farías ','28'), ('2110','González ','28'), ('2111','Guémez ','28'), ('2112','Guerrero ','28'), ('2113','Gustavo díaz ordaz ','28'), ('2114','Hidalgo ','28'), ('2115','Jaumave ','28'), ('2116','Jiménez ','28'), ('2117','Llera ','28'), ('2118','Mainero ','28'), ('2119','El mante ','28'), ('2120','Matamoros ','28'), ('2121','Méndez ','28'), ('2122','Mier ','28'), ('2123','Miguel alemán ','28');
INSERT INTO `ciudades` VALUES ('2124','Miquihuana ','28'), ('2125','Nuevo laredo ','28'), ('2126','Nuevo morelos ','28'), ('2127','Ocampo ','28'), ('2128','Padilla ','28'), ('2129','Palmillas ','28'), ('2130','Reynosa ','28'), ('2131','Río bravo ','28'), ('2132','San carlos','28'), ('2133','San fernando','28'), ('2134','San nicolás','28'), ('2135','Soto la marina','28'), ('2136','Tampico','28'), ('2137','Tula','28'), ('2138','Valle hermoso','28'), ('2139','Victoria','28'), ('2140','Villagrán','28'), ('2141','Xicoténcatl','28'), ('2142','Amaxac de guerrero','29'), ('2143','Tetla de la solidaridad','29'), ('2144','Apetatitlán de antonio carvajal','29'), ('2145','Tetlatlahuca','29'), ('2146','Atlangatepec','29'), ('2147','Tlaxcala','29'), ('2148','Altzayanca','29'), ('2149','Tlaxco','29'), ('2150','Apizaco','29'), ('2151','Tocatlán','29'), ('2152','Calpulalpan','29'), ('2153','Totolac','29'), ('2154','El carmen tequexquitla','29'), ('2155','Zitlaltepec de trinidad sánchez santos','29'), ('2156','Cuapiaxtla','29'), ('2157','Tzompantepec','29'), ('2158','Cuaxomulco','29'), ('2159','Xalostoc','29'), ('2160','Chiautempan','29'), ('2161','Xaltocan','29'), ('2162','Muñoz de domingo arenas','29'), ('2163','Papalotla de xicohténcatl','29'), ('2164','Españita','29'), ('2165','Xicohtzinco','29'), ('2166','Huamantla','29'), ('2167','Yauhquemecan','29'), ('2168','Hueyotlipan','29'), ('2169','Zacatelco','29'), ('2170','Ixtacuixtla de mariano matamoros','29'), ('2171','Benito juárez','29'), ('2172','Ixtenco','29'), ('2173','Emiliano zapata','29'), ('2174','Mazatecochco de josé maría morelos','29'), ('2175','Lázaro cárdenas','29'), ('2176','Contla de  juan cuamatzi','29'), ('2177','La magdalena tlaltelulco','29'), ('2178','Tepetitla de lardizábal','29'), ('2179','San damián texoloc','29'), ('2180','Sanctorum de lázaro cárdenas','29'), ('2181','San francisco tetlanohcan','29'), ('2182','Nanacamilpa de mariano arista','29'), ('2183','San jerónimo zacualpan','29'), ('2184','Acuamanala de miguel hidalgo','29'), ('2185','San josé  teacalco','29'), ('2186','Nativitas','29'), ('2187','San juan huactzinco','29'), ('2188','Panotla','29'), ('2189','San lorenzo axocomanitla','29'), ('2190','San pablo del monte','29'), ('2191','San lucas tecopilco','29'), ('2192','Santa cruz tlaxcala','29'), ('2193','Santa ana nopalucan','29'), ('2194','Tenancingo','29'), ('2195','Santa apolonia teacalco','29'), ('2196','Teolocholco','29'), ('2197','Santa catarina ayometla','29'), ('2198','Tepeyanco','29'), ('2199','Santa cruz quilehtla','29'), ('2200','Terrenate','29'), ('2201','Santa isabel xiloxoxtla','29'), ('2202','Acajete  ','30'), ('2203','Acatlán ','30'), ('2204','Acayucan ','30'), ('2205','Actopan ','30'), ('2206','Acula ','30'), ('2207','Acultzingo ','30'), ('2208','Agua dulce','30'), ('2209','Alpatláhuac ','30'), ('2210','Alto lucero de gutiérrez barrios ','30'), ('2211','Altotonga ','30'), ('2212','Alvarado ','30'), ('2213','Amatitlán ','30'), ('2214','Amatlán de los reyes ','30'), ('2215','Angel r. cabada ','30'), ('2216','Antigua, la ','30'), ('2217','Apazapan ','30'), ('2218','Aquila ','30'), ('2219','Astacinga ','30'), ('2220','Atlahuilco ','30'), ('2221','Atoyac ','30'), ('2222','Atzacan ','30'), ('2223','Atzalan ','30');
INSERT INTO `ciudades` VALUES ('2224','Ayahualulco ','30'), ('2225','Banderilla ','30'), ('2226','Benito juárez ','30'), ('2227','Boca del río ','30'), ('2228','Calcahualco ','30'), ('2229','Camarón de tejeda ','30'), ('2230','Camerino z. mendoza ','30'), ('2231','Carlos a. carrillo','30'), ('2232','Carrillo puerto ','30'), ('2233','Castillo de teayo','30'), ('2234','Catemaco ','30'), ('2235','Cazones de herrera ','30'), ('2236','Cerro azul ','30'), ('2237','Chacaltianguis ','30'), ('2238','Chalma ','30'), ('2239','Chiconamel ','30'), ('2240','Chiconquiaco ','30'), ('2241','Chicontepec ','30'), ('2242','Chinameca ','30'), ('2243','Chinampa de gorostiza ','30'), ('2244','Choapas, las ','30'), ('2245','Chocamán ','30'), ('2246','Chontla ','30'), ('2247','Chumatlán ','30'), ('2248','Citlaltépetl ','30'), ('2249','Coacoatzintla ','30'), ('2250','Coahuitlán ','30'), ('2251','Coatepec ','30'), ('2252','Coatzacoalcos ','30'), ('2253','Coatzintla ','30'), ('2254','Coetzala ','30'), ('2255','Colipa ','30'), ('2256','Comapa ','30'), ('2257','Córdoba ','30'), ('2258','Cosamaloapan ','30'), ('2259','Cosautlán de carvajal ','30'), ('2260','Coscomatepec ','30'), ('2261','Cosoleacaque ','30'), ('2262','Cotaxtla ','30'), ('2263','Coxquihui ','30'), ('2264','Coyutla ','30'), ('2265','Cuichapa ','30'), ('2266','Cuitláhuac ','30'), ('2267','Emiliano zapata ','30'), ('2268','Espinal ','30'), ('2269','Filomeno mata ','30'), ('2270','Fortín ','30'), ('2271','Gutiérrez zamora ','30'), ('2272','Hidalgotitlán ','30'), ('2273','Higo, el','30'), ('2274','Huatusco ','30'), ('2275','Huayacocotla ','30'), ('2276','Hueyapan de ocampo ','30'), ('2277','Huiloapan de cuauhtémoc ','30'), ('2278','Ignacio de la llave ','30'), ('2279','Ilamatlán ','30'), ('2280','Isla ','30'), ('2281','Ixcatepec ','30'), ('2282','Ixhuacán de los reyes ','30'), ('2283','Ixhuatlan de madero ','30'), ('2284','Ixhuatlán del cafe ','30'), ('2285','Ixhuatlán del sureste ','30'), ('2286','Ixhuatlancillo ','30'), ('2287','Ixmatlahuacan ','30'), ('2288','Ixtaczoquitlán ','30'), ('2289','Jalacingo ','30'), ('2290','Jalcomulco ','30'), ('2291','Jáltipan ','30'), ('2292','Jamapa ','30'), ('2293','Jesús carranza ','30'), ('2294','Jilotepec ','30'), ('2295','José azueta','30'), ('2296','Juan rodríguez clara ','30'), ('2297','Juchique de ferrer ','30'), ('2298','Landero y coss ','30'), ('2299','Lerdo de tejada ','30'), ('2300','Magdalena ','30'), ('2301','Maltrata ','30'), ('2302','Manlio fabio altamirano ','30'), ('2303','Mariano escobedo ','30'), ('2304','Martínez de la torre ','30'), ('2305','Mecatlán ','30'), ('2306','Mecayapan ','30'), ('2307','Medellín ','30'), ('2308','Miahuatlán','30'), ('2309','Minas, las','30'), ('2310','Minatitlán','30'), ('2311','Misantla','30'), ('2312','Mixtla de altamirano','30'), ('2313','Moloacán','30'), ('2314','Nanchital de lázaro cardenas del río','30'), ('2315','Naolinco','30'), ('2316','Naranjal','30'), ('2317','Naranjos-amatlán ','30'), ('2318','Nautla','30'), ('2319','Nogales','30'), ('2320','Oluta','30'), ('2321','Omealca','30'), ('2322','Orizaba','30'), ('2323','Otatitlán','30');
INSERT INTO `ciudades` VALUES ('2324','Oteapan','30'), ('2325','Ozuluama','30'), ('2326','Pajapan','30'), ('2327','Pánuco','30'), ('2328','Papantla','30'), ('2329','Paso de ovejas','30'), ('2330','Paso del macho','30'), ('2331','Perla, la','30'), ('2332','Perote','30'), ('2333','Platón sánchez','30'), ('2334','Playa vicente','30'), ('2335','Poza rica de hidalgo','30'), ('2336','Pueblo viejo','30'), ('2337','Puente nacional','30'), ('2338','Rafael delgado','30'), ('2339','Rafael lucio','30'), ('2340','Reyes, los','30'), ('2341','Río blanco','30'), ('2342','Saltabarranca','30'), ('2343','San andrés tenejapan','30'), ('2344','San andrés tuxtla','30'), ('2345','San juan evangelista','30'), ('2346','San rafael ','30'), ('2347','Santiago sochiapan','30'), ('2348','Santiago tuxtla','30'), ('2349','Sayula de alemán','30'), ('2350','Sochiapa','30'), ('2351','Soconusco','30'), ('2352','Soledad atzompa','30'), ('2353','Soledad de doblado','30'), ('2354','Soteapan','30'), ('2355','Tamalín','30'), ('2356','Tamiahua','30'), ('2357','Tampico alto','30'), ('2358','Tancoco','30'), ('2359','Tantima','30'), ('2360','Tantoyuca','30'), ('2361','Tatahuicapan de juárez','30'), ('2362','Tatatila','30'), ('2363','Tecolutla','30'), ('2364','Tehuipango','30'), ('2365','Temapache','30'), ('2366','Tempoal','30'), ('2367','Tenampa','30'), ('2368','Tenochtitlán','30'), ('2369','Teocelo','30'), ('2370','Tepatlaxco','30'), ('2371','Tepetlán','30'), ('2372','Tepetzintla','30'), ('2373','Tequila','30'), ('2374','Texcatepec','30'), ('2375','Texhuacán','30'), ('2376','Texistepec','30'), ('2377','Tezonapa','30'), ('2378','Tierra blanca','30'), ('2379','Tihuatlán','30'), ('2380','Tlachichilco','30'), ('2381','Tlacojalpan','30'), ('2382','Tlacolulan','30'), ('2383','Tlacotalpan','30'), ('2384','Tlacotepec de mejía','30'), ('2385','Tlalixcoyan','30'), ('2386','Tlalnelhuayocan','30'), ('2387','Tlaltetela ','30'), ('2388','Tlapacoyan','30'), ('2389','Tlaquilpa','30'), ('2390','Tlilapan','30'), ('2391','Tomatlán','30'), ('2392','Tonayán','30'), ('2393','Totutla','30'), ('2394','Tres valles','30'), ('2395','Tuxpan','30'), ('2396','Tuxtilla','30'), ('2397','Úrsulo galván','30'), ('2398','Uxpanapa','30'), ('2399','Vega de alatorre','30'), ('2400','Veracruz','30'), ('2401','Vigas de ramírez, las','30'), ('2402','Villa aldama','30'), ('2403','Xalapa ','30'), ('2404','Xico ','30'), ('2405','Xoxocotla','30'), ('2406','Yanga','30'), ('2407','Yecuatla','30'), ('2408','Zacualpan','30'), ('2409','Zaragoza','30'), ('2410','Zentla','30'), ('2411','Zongolica','30'), ('2412','Zontecomatlán','30'), ('2413','Zozocolco de hidalgo','30'), ('2414','Abalá','31'), ('2415','Acanceh','31'), ('2416','Akil','31'), ('2417','Baca','31'), ('2418','Bokobá','31'), ('2419','Buctzotz','31'), ('2420','Cacalchén','31'), ('2421','Calotmul','31'), ('2422','Cansahcab','31'), ('2423','Cantamayec','31');
INSERT INTO `ciudades` VALUES ('2424','Celestún','31'), ('2425','Cenotillo','31'), ('2426','Conkal','31'), ('2427','Cuncunul','31'), ('2428','Cuzamá','31'), ('2429','Chacsinkín','31'), ('2430','Chankom','31'), ('2431','Chapab','31'), ('2432','Chemax','31'), ('2433','Chicxulub pueblo','31'), ('2434','Chichimilá','31'), ('2435','Chikindzonot','31'), ('2436','Chocholá','31'), ('2437','Chumayel','31'), ('2438','Dzan','31'), ('2439','Dzemul','31'), ('2440','Dzidzantún','31'), ('2441','Dzilam de bravo','31'), ('2442','Dzilam gonzález','31'), ('2443','Dzitás','31'), ('2444','Dzoncauich','31'), ('2445','Espita','31'), ('2446','Halachó','31'), ('2447','Hocabá','31'), ('2448','Hoctún','31'), ('2449','Homún','31'), ('2450','Huhí','31'), ('2451','Hunucmá','31'), ('2452','Ixil','31'), ('2453','Izamal','31'), ('2454','Kanasín','31'), ('2455','Kantunil','31'), ('2456','Kaua','31'), ('2457','Kinchil','31'), ('2458','Kopomá','31'), ('2459','Mama','31'), ('2460','Maní','31'), ('2461','Maxcanú','31'), ('2462','Mayapán','31'), ('2463','Mérida','31'), ('2464','Mocochá','31'), ('2465','Motul','31'), ('2466','Muna','31'), ('2467','Muxupip','31'), ('2468','Opichén','31'), ('2469','Oxkutzcab','31'), ('2470','Panabá','31'), ('2471','Peto','31'), ('2472','Progreso','31'), ('2473','Quintana roo roo','31'), ('2474','Río lagartos','31'), ('2475','Sacalum','31'), ('2476','Samahil','31'), ('2477','Sanahcat','31'), ('2478','San felipe','31'), ('2479','Santa elena','31'), ('2480','Seyé','31'), ('2481','Sinanché','31'), ('2482','Sotuta','31'), ('2483','Sucilá','31'), ('2484','Sudzal','31'), ('2485','Suma','31'), ('2486','Tahdziú','31'), ('2487','Tahmek','31'), ('2488','Teabo','31'), ('2489','Tecoh','31'), ('2490','Tekal de venegas','31'), ('2491','Tekantó','31'), ('2492','Tekax','31'), ('2493','Tekit','31'), ('2494','Tekom','31'), ('2495','Telchac pueblo','31'), ('2496','Telchac puerto','31'), ('2497','Temax','31'), ('2498','Temozón','31'), ('2499','Tepakán','31'), ('2500','Tetiz','31'), ('2501','Teya','31'), ('2502','Ticul','31'), ('2503','Timucuy','31'), ('2504','Tinúm','31'), ('2505','Tixcacalcupul','31'), ('2506','Tixkokob','31'), ('2507','Tixméhuac','31'), ('2508','Tixpéhual','31'), ('2509','Tizimín','31'), ('2510','Tunkás','31'), ('2511','Tzucacab','31'), ('2512','Uayma','31'), ('2513','Ucú','31'), ('2514','Umán','31'), ('2515','Valladolid','31'), ('2516','Xocchel','31'), ('2517','Yaxcabá','31'), ('2518','Yaxkukul','31'), ('2519','Yobaín','31'), ('2520','Apozol ','32'), ('2521','Apulco ','32'), ('2522','Atolinga ','32'), ('2523','Benito juárez ','32');
INSERT INTO `ciudades` VALUES ('2524','Calera ','32'), ('2525','Cañitas de feilpe pescador ','32'), ('2526','Concepción del oro ','32'), ('2527','Cuauhtémoc ','32'), ('2528','Chalchihuites ','32'), ('2529','Fresnillo ','32'), ('2530','Genaro codina ','32'), ('2531','General enrique estrada ','32'), ('2532','General francisco r. murguía ','32'), ('2533','General pánfilo natera ','32'), ('2534','Guadalupe ','32'), ('2535','Huanusco ','32'), ('2536','Jalpa ','32'), ('2537','Jerez ','32'), ('2538','Jiménez del teul ','32'), ('2539','Santa maría de la paz ','32'), ('2540','Juan aldama ','32'), ('2541','Juchipila ','32'), ('2542','Loreto ','32'), ('2543','Luis moya ','32'), ('2544','Mazapil ','32'), ('2545','Melchor ocampo ','32'), ('2546','Mezquital del oro ','32'), ('2547','Miguel auza ','32'), ('2548','Momax ','32'), ('2549','Monte escobedo ','32'), ('2550','Morelos ','32'), ('2551','Moyahua de estrada ','32'), ('2552','Nochistlán de mejía ','32'), ('2553','Noria de ángeles ','32'), ('2554','Ojocaliente ','32'), ('2555','Pánuco ','32'), ('2556','Pinos ','32'), ('2557','Plateado de joaquín amaro, el ','32'), ('2558','Río grande ','32'), ('2559','Saín alto','32'), ('2560','Salvador, el','32'), ('2561','Sombrerete','32'), ('2562','Susticacán','32'), ('2563','Tabasco','32'), ('2564','Tepechitlán','32'), ('2565','Tepetongo','32'), ('2566','Teul de gonzález ortega','32'), ('2567','Tlaltenango de sánchez román','32'), ('2568','Trancoso','32'), ('2569','Trinidad garcía de la cadena','32'), ('2570','Valparaíso','32'), ('2571','Vetagrande','32'), ('2572','Villa de cos','32'), ('2573','Villa garcía','32'), ('2574','Villa gonzález ortega','32'), ('2575','Villa hidalgo','32'), ('2576','Villanueva','32'), ('2577','Zacatecas','32'), ('2578','Distrito federal','9'), ('2579','Álvaro Obregón','9'), ('2580','Azcapotzalco','9'), ('2581','Benito Juárez','9'), ('2582','Coyoacán','9'), ('2583','Cuajimalpa de Morelos','9'), ('2584','Cuauhtémoc','9'), ('2585','Gustavo A. Madero','9'), ('2586','Iztacalco','9'), ('2587','Iztapalapa','9'), ('2588','Magdalena Contreras','9'), ('2589','Miguel Hidalgo','9'), ('2590','Milpa Alta','9'), ('2591','Tláhuac','9'), ('2592','Tlalpan','9'), ('2593','Venustiano Carranza','9'), ('2594','Xochimilco','9');
INSERT INTO `cliente` VALUES ('1',NULL,'Cliente General','',NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,'1'), ('12','3','LUIS CAMARENA JIMENEZ','*','','0','0','462','','isccalbertochg@gmail.com','','','','','','0','2','2','1');
INSERT INTO `config` VALUES ('1','1','#2e2e2e','#8e2f35','idUserSeg1','2015-05-08 16:35:43','1','2015-05-08 17:19:35','1');
INSERT INTO `ctas_banco` VALUES ('1','','4000.00','1','Caja registradora','1','2014-07-29 17:16:14','1','2014-07-29 16:59:16');
INSERT INTO `datos_empresa` VALUES ('1','1','EMPRESA AGENTE1','RFCAGENTE1','DOMICILIO AGENTE1 #123 COL. AGENTE1','20150504112418.png','2015-05-08 16:35:43','1','2015-05-08 16:57:37','1'), ('2','2','EMPRESA AGENTE2','RFCAGENTE2','DOMICILIO AGENTE2 #987 COL. AGENTE2','20150508172228.png','2015-05-08 16:35:43','1','2015-05-08 17:22:28','1');
INSERT INTO `datos_usuario` VALUES ('1','1','default.jpg','NUEVA ORLEANS','219','0','LAS AMÉRICAS','37407','462','1987-05-05','isccalbertochg@gmail.com','477','1511166','2015-05-04 11:29:56','1');
INSERT INTO `estados` VALUES ('1','Aguascalientes'), ('2','Baja California'), ('3','Baja California Sur'), ('4','Campeche'), ('5','Chiapas'), ('6','Chihuahua'), ('7','Coahuila'), ('8','Colima'), ('9','Distrito Federal'), ('10','Durango'), ('11','Estado de México'), ('12','Guanajuato'), ('13','Guerrero'), ('14','Hidalgo'), ('15','Jalisco'), ('16','Michoacán'), ('17','Morelos'), ('18','Nayarit'), ('19','Nuevo León'), ('20','Oaxaca'), ('21','Puebla'), ('22','Querétaro'), ('23','Quintana Roo'), ('24','San Luis Potosí'), ('25','Sinaloa'), ('26','Sonora'), ('27','Tabasco'), ('28','Tamaulipas'), ('29','Tlaxcala'), ('30','Veracruz'), ('31','Yucatán'), ('32','Zacatecas');
INSERT INTO `forma_pago` VALUES ('1','Cheque',NULL,'1'), ('2','Tarjeta',NULL,'1'), ('3','Efectivo',NULL,'1'), ('4','Transferencia',NULL,'1');
INSERT INTO `permisos_acciones` VALUES ('1','1'), ('2','1'), ('3','1'), ('13','1'), ('18','1'), ('20','1'), ('21','1'), ('22','1'), ('23','1'), ('24','1'), ('25','1'), ('26','1'), ('36','1'), ('37','1'), ('38','1'), ('45','1'), ('46','1'), ('47','1'), ('48','1'), ('49','1'), ('50','1'), ('51','1'), ('52','1'), ('53','1'), ('54','1'), ('55','1'), ('56','1'), ('57','1'), ('1','2'), ('2','2'), ('3','2'), ('13','2'), ('18','2'), ('20','2'), ('21','2'), ('22','2'), ('23','2'), ('24','2'), ('25','2'), ('26','2'), ('36','2'), ('37','2'), ('38','2'), ('45','2'), ('46','2'), ('47','2'), ('48','2'), ('49','2'), ('50','2'), ('51','2'), ('52','2'), ('53','2'), ('54','2'), ('55','2'), ('56','2'), ('57','2'), ('58','1'), ('58','2'), ('59','1'), ('59','2'), ('24','5'), ('25','5'), ('26','5'), ('49','5'), ('18','5'), ('36','5'), ('37','5'), ('38','5'), ('45','5'), ('46','5'), ('47','5'), ('48','5'), ('50','5'), ('51','5'), ('52','5'), ('53','5'), ('54','5'), ('55','5'), ('56','5'), ('57','5'), ('58','5'), ('59','5'), ('60','1'), ('60','2'), ('60','5'), ('61','1'), ('61','2'), ('61','5'), ('62','1'), ('62','2'), ('62','5'), ('63','1'), ('63','2'), ('63','5'), ('64','1'), ('64','2'), ('64','5'), ('65','1'), ('65','2'), ('65','5');
INSERT INTO `permisos_acciones` VALUES ('67','1'), ('67','2'), ('67','5');
INSERT INTO `subagente` VALUES ('3','2','2015-05-08 12:16:41','1','2015-05-20 18:20:34','1'), ('4','15','2015-05-21 09:46:15','2','2015-05-21 09:46:15','1'), ('5','16','2015-05-21 09:46:43','2','2015-05-21 09:46:43','1');
INSERT INTO `tipo_contacto` VALUES ('5','3','forma contacto agente1','forma contacto agente1','2015-05-21 10:19:08','2','2015-05-21 10:19:08','1'), ('6','4','forma de contacto subagente1','forma de contacto subagente1','2015-05-21 10:25:39','2','2015-05-21 10:25:39','1'), ('7','3','forma2 contacto agente1','forma2 contacto agente1','2015-05-21 10:25:39','2','2015-05-21 16:25:24','1');
INSERT INTO `tipo_pago` VALUES ('8','4','forma de pago subagente1','forma de pago subagente1',NULL,NULL,'2015-05-21 12:07:35','1'), ('9','3','forma de pago agente1','forma de pago agente1',NULL,NULL,'2015-05-21 12:22:22','1');
INSERT INTO `tipo_poliza` VALUES ('15','4','tipo de poliza subagente1','tipo de poliza subagente1',NULL,NULL,'2015-05-21 11:53:12','1'), ('16','3','tipo de poliza agente1','tipo de poliza agente1',NULL,NULL,'2015-05-21 11:55:35','1');
INSERT INTO `_modulos` VALUES ('1000','SISTEMA','0','0',NULL,'fa fa-cog'), ('1001','Usuarios','1000','2','./modulos/sistema/usuarios.php',NULL), ('1002','Perfiles','1000','1','./modulos/sistema/perfiles.php',NULL), ('1003','Configuraciones','1000','3',NULL,'fa  fa-wrench'), ('1004','Datos de la empresa','1003','1','./modulos/config/datosEmpresa.php',''), ('1008','Mi perfil','1000','4','./modulos/sistema/perfil.php',NULL), ('3000','ADMINISTRATIVO','0','0',NULL,'fa fa-book'), ('3001','Clientes','3000','1','./modulos/catalogos/clientes.php',NULL), ('3007','Catálogos','3000','2',NULL,'fa fa-list-alt'), ('3008','Formas de contacto','3007','1','./modulos/catalogos/tipoContacto.php',NULL), ('3012','Agenda de contactos','3007','2','./modulos/admon/agenda.php',NULL), ('3013','Tipos de poliza','3007','3','./modulos/catalogos/tiposPoliza.php',NULL), ('3014','Formas de pago','3007','4','./modulos/catalogos/tiposPago.php',NULL), ('4000','POLIZAS','0','0','','fa fa-usd'), ('4001','Mis polizas','4000','1','./modulos/admon/polizas.php',NULL), ('5000','REPORTES','0','0',NULL,'fa fa-file-text-o'), ('6000','Calendario','0','0','./modulos/crm/calendario.php','fa fa-calendar');
INSERT INTO `_perfiles` VALUES ('1',NULL,'1','Super administrador del Sistema','1'), ('2','1','2','Agente','1'), ('3',NULL,'0','Cliente','1'), ('5','1','3','subagente1','1'), ('6','1','3','subagente2','1');
INSERT INTO `_permisos` VALUES ('1','1000'), ('2','1000'), ('1','1001'), ('2','1001'), ('1','1002'), ('2','1002'), ('1','1003'), ('2','1003'), ('1','1004'), ('2','1004'), ('1','1008'), ('2','1008'), ('1','3000'), ('2','3000'), ('5','3000'), ('1','3001'), ('2','3001'), ('5','3001'), ('1','3007'), ('2','3007'), ('5','3007'), ('1','3008'), ('2','3008'), ('5','3008'), ('1','3012'), ('2','3012'), ('5','3012'), ('1','3013'), ('2','3013'), ('5','3013'), ('1','3014'), ('2','3014'), ('5','3014'), ('1','4000'), ('2','4000'), ('5','4000'), ('1','4001'), ('2','4001'), ('5','4001'), ('1','5000'), ('2','5000'), ('1','6000'), ('2','6000');
INSERT INTO `_usuarios` VALUES ('1','1','CARLOS ALBERTO chávez guerrero','CODEMAN','7c9d014021d53709aad614fa40d161fd','eaf9e4ba4a33fa68623d32fbf2147783',NULL,NULL,'2015-06-26 09:38:19','1'), ('2','2','Agente 1','AGENTE1','152ea9e1a29b419842d0d94e222d54b2','9318dfa31d581c73ff03d7a61f6773e6','2015-05-08 12:15:37','1','2015-06-23 10:43:53','1'), ('15','5','subagente1','SUBAGENTE1','0ae4fd99a78d03cae4234913a97aac96','710fbb5d7f8ac1e2a481774cbad98baf','2015-05-21 09:46:15','2','2015-05-25 10:22:48','1'), ('16','6','subagente2','SUBAGENTE2','df345eedbc6c405a790038321e8a6167',NULL,'2015-05-21 09:46:43','2','2015-05-21 09:46:43','1'), ('18','3','LUIS CAMARENA JIMENEZ','LUIS','7fdf94f120c4f4ad8271a53de1d394a3',NULL,'2015-06-03 13:00:13','2','2015-06-03 13:00:13','1');
