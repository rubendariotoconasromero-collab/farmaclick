-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bd_farmacia_demo
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ajuste`
--

DROP TABLE IF EXISTS `ajuste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ajuste` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock` int NOT NULL DEFAULT '0',
  `costo_compra` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_venta` decimal(11,2) DEFAULT NULL,
  `stock_anterior` int NOT NULL DEFAULT '0',
  `stock_actual` int NOT NULL DEFAULT '0',
  `costo_unitario` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_mayorista` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_preferencial` decimal(11,2) NOT NULL DEFAULT '0.00',
  `observacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_lote` bigint unsigned NOT NULL,
  `id_motivo_ajuste` bigint unsigned NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_venta` int DEFAULT NULL,
  `id_compra` int DEFAULT NULL,
  `id_transaccion` int DEFAULT NULL,
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `stock_general` decimal(11,2) NOT NULL DEFAULT '0.00',
  `stock_general_anterior` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `ajuste_id_lote_foreign` (`id_lote`),
  KEY `ajuste_id_motivo_ajuste_foreign` (`id_motivo_ajuste`),
  CONSTRAINT `ajuste_id_lote_foreign` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`),
  CONSTRAINT `ajuste_id_motivo_ajuste_foreign` FOREIGN KEY (`id_motivo_ajuste`) REFERENCES `motivo_ajuste` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ajuste`
--

LOCK TABLES `ajuste` WRITE;
/*!40000 ALTER TABLE `ajuste` DISABLE KEYS */;
/*!40000 ALTER TABLE `ajuste` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `animal`
--

DROP TABLE IF EXISTS `animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `animal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animal`
--

LOCK TABLES `animal` WRITE;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` VALUES (1,'Canino','',1),(2,'Felino','',1);
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antiparasitario`
--

DROP TABLE IF EXISTS `antiparasitario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antiparasitario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_paciente` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `prox_fecha` date NOT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `antiparasitario_id_paciente_foreign` (`id_paciente`),
  CONSTRAINT `antiparasitario_id_paciente_foreign` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antiparasitario`
--

LOCK TABLES `antiparasitario` WRITE;
/*!40000 ALTER TABLE `antiparasitario` DISABLE KEYS */;
/*!40000 ALTER TABLE `antiparasitario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arqueo_caja`
--

DROP TABLE IF EXISTS `arqueo_caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arqueo_caja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha_apertura` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `doscientos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cien` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cincuenta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `veinte` decimal(11,2) NOT NULL DEFAULT '0.00',
  `diez` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cinco` decimal(11,2) NOT NULL DEFAULT '0.00',
  `dos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `uno` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cerocinco` decimal(11,2) NOT NULL DEFAULT '0.00',
  `ceroveinte` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cien_dolar` decimal(11,2) NOT NULL DEFAULT '0.00',
  `registro_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `apertura` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `gastos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `registro_compra` decimal(11,2) NOT NULL DEFAULT '0.00',
  `saldo_sistema` decimal(11,2) NOT NULL DEFAULT '0.00',
  `saldo_efectivo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `diferencia` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_contado` decimal(11,2) DEFAULT NULL,
  `total_credito` decimal(11,2) DEFAULT NULL,
  `total_contado_compra` decimal(11,2) DEFAULT NULL,
  `total_credito_compra` decimal(11,2) DEFAULT NULL,
  `total_credito_deposito` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_contado_deposito` decimal(11,2) NOT NULL DEFAULT '0.00',
  `gastos_deposito` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_contado_deposito_compra` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_credito_deposito_compra` decimal(11,2) NOT NULL DEFAULT '0.00',
  `id_usuario` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `arqueo_caja_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `arqueo_caja_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arqueo_caja`
--

LOCK TABLES `arqueo_caja` WRITE;
/*!40000 ALTER TABLE `arqueo_caja` DISABLE KEYS */;
/*!40000 ALTER TABLE `arqueo_caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo`
--

DROP TABLE IF EXISTS `articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cod_sistema` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_proveedor` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_barra` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_comercial` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_generico` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `costo_compra` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_compra_caja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_unitario` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_mayorista` decimal(11,2) NOT NULL DEFAULT '0.00',
  `costo_preferencial` decimal(11,2) NOT NULL DEFAULT '0.00',
  `stock_minimo` int DEFAULT '0',
  `ubicacion` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `composicion` text COLLATE utf8mb4_unicode_ci,
  `venta_presentacion` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cantidad_caja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cantidad_blister` decimal(11,2) NOT NULL DEFAULT '0.00',
  `precio_caja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `precio_blister` decimal(11,2) NOT NULL DEFAULT '0.00',
  `psicotropico` tinyint(1) NOT NULL DEFAULT '1',
  `refrigerado` tinyint(1) NOT NULL DEFAULT '1',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_categoria` bigint unsigned DEFAULT NULL,
  `id_marca` bigint unsigned DEFAULT NULL,
  `id_unidad` bigint unsigned DEFAULT NULL,
  `id_proveedor` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `articulo_id_categoria_foreign` (`id_categoria`),
  KEY `articulo_id_unidad_foreign` (`id_unidad`),
  KEY `articulo_id_proveedor_foreign` (`id_proveedor`),
  KEY `articulo_id_marca_foreign` (`id_marca`),
  CONSTRAINT `articulo_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`),
  CONSTRAINT `articulo_id_marca_foreign` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id`),
  CONSTRAINT `articulo_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`),
  CONSTRAINT `articulo_id_unidad_foreign` FOREIGN KEY (`id_unidad`) REFERENCES `unidad_medida` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo`
--

LOCK TABLES `articulo` WRITE;
/*!40000 ALTER TABLE `articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auxiliar`
--

DROP TABLE IF EXISTS `auxiliar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auxiliar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `id_lote` bigint unsigned NOT NULL,
  `id_venta` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auxiliar_id_lote_foreign` (`id_lote`),
  KEY `auxiliar_id_venta_foreign` (`id_venta`),
  CONSTRAINT `auxiliar_id_lote_foreign` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`),
  CONSTRAINT `auxiliar_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auxiliar`
--

LOCK TABLES `auxiliar` WRITE;
/*!40000 ALTER TABLE `auxiliar` DISABLE KEYS */;
/*!40000 ALTER TABLE `auxiliar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `tabla` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_tabla` int NOT NULL,
  `transaccion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bitacora_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `bitacora_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `c_x_cobrar`
--

DROP TABLE IF EXISTS `c_x_cobrar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `c_x_cobrar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pago` int NOT NULL,
  `fecha` date NOT NULL,
  `monto_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `amortizacion` decimal(11,2) NOT NULL DEFAULT '0.00',
  `saldo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_forma_pago` int NOT NULL DEFAULT '0',
  `control` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `c_x_cobrar_id_pago_foreign` (`id_pago`),
  CONSTRAINT `c_x_cobrar_id_pago_foreign` FOREIGN KEY (`id_pago`) REFERENCES `pago` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `c_x_cobrar`
--

LOCK TABLES `c_x_cobrar` WRITE;
/*!40000 ALTER TABLE `c_x_cobrar` DISABLE KEYS */;
/*!40000 ALTER TABLE `c_x_cobrar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `c_x_pagar`
--

DROP TABLE IF EXISTS `c_x_pagar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `c_x_pagar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pago` int NOT NULL,
  `fecha` date NOT NULL,
  `monto_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `amortizacion` decimal(11,2) NOT NULL DEFAULT '0.00',
  `saldo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_forma_pago` int NOT NULL DEFAULT '0',
  `control` int NOT NULL DEFAULT '0',
  `id_usuario` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `c_x_pagar_id_pago_foreign` (`id_pago`),
  CONSTRAINT `c_x_pagar_id_pago_foreign` FOREIGN KEY (`id_pago`) REFERENCES `pago_compra` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `c_x_pagar`
--

LOCK TABLES `c_x_pagar` WRITE;
/*!40000 ALTER TABLE `c_x_pagar` DISABLE KEYS */;
/*!40000 ALTER TABLE `c_x_pagar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargo`
--

DROP TABLE IF EXISTS `cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cargo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargo`
--

LOCK TABLES `cargo` WRITE;
/*!40000 ALTER TABLE `cargo` DISABLE KEYS */;
INSERT INTO `cargo` VALUES (1,'Administrador','Administrador',1),(2,'Doctor(a)','Doctor(a)',1);
/*!40000 ALTER TABLE `cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricula` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descuento` int NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'S/N','0','0','SD','SD',1,1);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compra`
--

DROP TABLE IF EXISTS `compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compra` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proveedor` bigint unsigned NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  `id_tipo_pago` bigint unsigned NOT NULL,
  `id_forma_pago` bigint unsigned NOT NULL,
  `total_efectivo` decimal(11,2) DEFAULT NULL,
  `total_deposito` decimal(11,2) DEFAULT NULL,
  `control` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `compra_id_proveedor_foreign` (`id_proveedor`),
  KEY `compra_id_usuario_foreign` (`id_usuario`),
  KEY `compra_id_tipo_pago_foreign` (`id_tipo_pago`),
  KEY `compra_id_forma_pago_foreign` (`id_forma_pago`),
  KEY `compra_fecha_index` (`fecha`),
  KEY `compra_estado_index` (`estado`),
  CONSTRAINT `compra_id_forma_pago_foreign` FOREIGN KEY (`id_forma_pago`) REFERENCES `forma_pago` (`id`),
  CONSTRAINT `compra_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`),
  CONSTRAINT `compra_id_tipo_pago_foreign` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`),
  CONSTRAINT `compra_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra`
--

LOCK TABLES `compra` WRITE;
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control`
--

DROP TABLE IF EXISTS `control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `control` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabla` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tabla` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control`
--

LOCK TABLES `control` WRITE;
/*!40000 ALTER TABLE `control` DISABLE KEYS */;
/*!40000 ALTER TABLE `control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control_vacuna`
--

DROP TABLE IF EXISTS `control_vacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `control_vacuna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_paciente` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `prox_fecha` date NOT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `control_vacuna_id_paciente_foreign` (`id_paciente`),
  CONSTRAINT `control_vacuna_id_paciente_foreign` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_vacuna`
--

LOCK TABLES `control_vacuna` WRITE;
/*!40000 ALTER TABLE `control_vacuna` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_vacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizacion`
--

DROP TABLE IF EXISTS `cotizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `fecha_venci` date DEFAULT NULL,
  `dias_credito` int DEFAULT NULL,
  `tiempo_entrega` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar_entrega` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_venta` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nota` text COLLATE utf8mb4_unicode_ci,
  `con_factura` tinyint(1) DEFAULT NULL,
  `id_cliente` bigint unsigned DEFAULT NULL,
  `id_tipo_pago` bigint unsigned NOT NULL,
  `id_forma_pago` bigint unsigned DEFAULT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  `id_tienda` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_id_cliente_foreign` (`id_cliente`),
  KEY `cotizacion_id_tipo_pago_foreign` (`id_tipo_pago`),
  KEY `cotizacion_id_forma_pago_foreign` (`id_forma_pago`),
  KEY `cotizacion_id_usuario_foreign` (`id_usuario`),
  KEY `cotizacion_id_tienda_foreign` (`id_tienda`),
  CONSTRAINT `cotizacion_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  CONSTRAINT `cotizacion_id_forma_pago_foreign` FOREIGN KEY (`id_forma_pago`) REFERENCES `forma_pago` (`id`),
  CONSTRAINT `cotizacion_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tienda` (`id`),
  CONSTRAINT `cotizacion_id_tipo_pago_foreign` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`),
  CONSTRAINT `cotizacion_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizacion`
--

LOCK TABLES `cotizacion` WRITE;
/*!40000 ALTER TABLE `cotizacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `cotizacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_antiparasitario`
--

DROP TABLE IF EXISTS `detalle_antiparasitario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_antiparasitario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_antiparasitario` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `prox_fecha` date NOT NULL,
  `edad` int DEFAULT NULL,
  `peso` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cantidad` int NOT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `detalle_antiparasitario_id_antiparasitario_foreign` (`id_antiparasitario`),
  KEY `detalle_antiparasitario_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_antiparasitario_id_antiparasitario_foreign` FOREIGN KEY (`id_antiparasitario`) REFERENCES `antiparasitario` (`id`),
  CONSTRAINT `detalle_antiparasitario_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_antiparasitario`
--

LOCK TABLES `detalle_antiparasitario` WRITE;
/*!40000 ALTER TABLE `detalle_antiparasitario` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_antiparasitario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_compra` (
  `id_compra` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `costo_compra` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `eliminado` int NOT NULL DEFAULT '0',
  `id_lote` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_compra`,`id_producto`),
  KEY `detalle_compra_id_producto_foreign` (`id_producto`),
  KEY `detalle_compra_id_lote_foreign` (`id_lote`),
  CONSTRAINT `detalle_compra_id_compra_foreign` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id`),
  CONSTRAINT `detalle_compra_id_lote_foreign` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`),
  CONSTRAINT `detalle_compra_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_compra`
--

LOCK TABLES `detalle_compra` WRITE;
/*!40000 ALTER TABLE `detalle_compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_control_vacuna`
--

DROP TABLE IF EXISTS `detalle_control_vacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_control_vacuna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_control_vacuna` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `prox_fecha` date NOT NULL,
  `cantidad` int NOT NULL,
  `edad` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `detalle_control_vacuna_id_control_vacuna_foreign` (`id_control_vacuna`),
  KEY `detalle_control_vacuna_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_control_vacuna_id_control_vacuna_foreign` FOREIGN KEY (`id_control_vacuna`) REFERENCES `control_vacuna` (`id`),
  CONSTRAINT `detalle_control_vacuna_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_control_vacuna`
--

LOCK TABLES `detalle_control_vacuna` WRITE;
/*!40000 ALTER TABLE `detalle_control_vacuna` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_control_vacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_cotizacion`
--

DROP TABLE IF EXISTS `detalle_cotizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_cotizacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_cotizacion` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `tiempo_entrega` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_cotizacion_id_cotizacion_foreign` (`id_cotizacion`),
  KEY `detalle_cotizacion_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_cotizacion_id_cotizacion_foreign` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id`),
  CONSTRAINT `detalle_cotizacion_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_cotizacion`
--

LOCK TABLES `detalle_cotizacion` WRITE;
/*!40000 ALTER TABLE `detalle_cotizacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_cotizacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_historia`
--

DROP TABLE IF EXISTS `detalle_historia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_historia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_historia` bigint unsigned NOT NULL,
  `id_personal` bigint unsigned NOT NULL,
  `id_paciente` bigint unsigned NOT NULL,
  `peso` decimal(11,2) NOT NULL DEFAULT '0.00',
  `edad` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meses` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `motivo` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '0',
  `parvovirus` tinyint(1) NOT NULL DEFAULT '0',
  `hexavalente` tinyint(1) NOT NULL DEFAULT '0',
  `octavalente` tinyint(1) NOT NULL DEFAULT '0',
  `rabia_perro` tinyint(1) NOT NULL DEFAULT '0',
  `tos_perrera` tinyint(1) NOT NULL DEFAULT '0',
  `ninguna_perro` tinyint(1) NOT NULL DEFAULT '0',
  `obs_p` tinyint(1) NOT NULL DEFAULT '0',
  `obs_perro` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `triple_felina` tinyint(1) NOT NULL DEFAULT '0',
  `rabia_gato` tinyint(1) NOT NULL DEFAULT '0',
  `ninguna_gato` tinyint(1) NOT NULL DEFAULT '0',
  `obs_g` tinyint(1) NOT NULL DEFAULT '0',
  `obs_gato` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desparacitacion` tinyint(1) NOT NULL DEFAULT '0',
  `desparacitacion_cuando` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperatura` decimal(11,2) NOT NULL DEFAULT '0.00',
  `fc` decimal(11,2) NOT NULL DEFAULT '0.00',
  `taquicardia` tinyint(1) NOT NULL DEFAULT '0',
  `arritmia` tinyint(1) NOT NULL DEFAULT '0',
  `bradicardia` tinyint(1) NOT NULL DEFAULT '0',
  `sin_alteracion` tinyint(1) NOT NULL DEFAULT '0',
  `fr` decimal(11,2) NOT NULL DEFAULT '0.00',
  `bueno_fr` tinyint(1) NOT NULL DEFAULT '0',
  `disnea` tinyint(1) NOT NULL DEFAULT '0',
  `rosada` tinyint(1) NOT NULL DEFAULT '0',
  `palidas` tinyint(1) NOT NULL DEFAULT '0',
  `ictericas` tinyint(1) NOT NULL DEFAULT '0',
  `cianotica` tinyint(1) NOT NULL DEFAULT '0',
  `normal_apetito` tinyint(1) NOT NULL DEFAULT '0',
  `disminuido` tinyint(1) NOT NULL DEFAULT '0',
  `anorexico` tinyint(1) NOT NULL DEFAULT '0',
  `normal_mucosa` tinyint(1) NOT NULL DEFAULT '0',
  `leve` tinyint(1) NOT NULL DEFAULT '0',
  `moderada` tinyint(1) NOT NULL DEFAULT '0',
  `marcada` tinyint(1) NOT NULL DEFAULT '0',
  `bueno_estado` tinyint(1) NOT NULL DEFAULT '0',
  `regular` tinyint(1) NOT NULL DEFAULT '0',
  `malo` tinyint(1) NOT NULL DEFAULT '0',
  `enfermedades` tinyint(1) NOT NULL DEFAULT '0',
  `enfermedades_cuales` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enfermedades_cuando` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cirugia` tinyint(1) NOT NULL DEFAULT '0',
  `cirugia_cuales` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cirugia_cuando` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ocular` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nariz` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bucal` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piel_anexo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oidos` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vulvar` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prepucial` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `digestivo_sin_alteracion` tinyint(1) NOT NULL DEFAULT '0',
  `digestivo_obs` text COLLATE utf8mb4_unicode_ci,
  `respiratorio_sin_alteracion` tinyint(1) NOT NULL DEFAULT '0',
  `respiratorio_obs` text COLLATE utf8mb4_unicode_ci,
  `urinario_sin_alteracion` tinyint(1) NOT NULL DEFAULT '0',
  `urinario_obs` text COLLATE utf8mb4_unicode_ci,
  `nervioso_sin_alteracion` tinyint(1) NOT NULL DEFAULT '0',
  `nervioso_obs` text COLLATE utf8mb4_unicode_ci,
  `muestra` text COLLATE utf8mb4_unicode_ci,
  `examenes_solicitado` text COLLATE utf8mb4_unicode_ci,
  `fecha1` date NOT NULL,
  `hora1` time NOT NULL,
  `t1` decimal(11,2) NOT NULL DEFAULT '0.00',
  `dr1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `costo1` decimal(11,2) NOT NULL DEFAULT '0.00',
  `observaciones1` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_dia` text COLLATE utf8mb4_unicode_ci,
  `hidratacion` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `detalle_historia_id_historia_foreign` (`id_historia`),
  KEY `detalle_historia_id_personal_foreign` (`id_personal`),
  KEY `detalle_historia_id_paciente_foreign` (`id_paciente`),
  CONSTRAINT `detalle_historia_id_historia_foreign` FOREIGN KEY (`id_historia`) REFERENCES `historial_clinico` (`id`),
  CONSTRAINT `detalle_historia_id_paciente_foreign` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`),
  CONSTRAINT `detalle_historia_id_personal_foreign` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_historia`
--

LOCK TABLES `detalle_historia` WRITE;
/*!40000 ALTER TABLE `detalle_historia` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_historia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_orden_servicio`
--

DROP TABLE IF EXISTS `detalle_orden_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_orden_servicio` (
  `id_orden_servicio` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_orden_servicio`,`id_producto`),
  KEY `detalle_orden_servicio_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_orden_servicio_id_orden_servicio_foreign` FOREIGN KEY (`id_orden_servicio`) REFERENCES `orden_servicio` (`id`),
  CONSTRAINT `detalle_orden_servicio_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_orden_servicio`
--

LOCK TABLES `detalle_orden_servicio` WRITE;
/*!40000 ALTER TABLE `detalle_orden_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_orden_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_paquete`
--

DROP TABLE IF EXISTS `detalle_paquete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_paquete` (
  `id_paquete` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_paquete`,`id_producto`),
  KEY `detalle_paquete_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_paquete_id_paquete_foreign` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id`),
  CONSTRAINT `detalle_paquete_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_paquete`
--

LOCK TABLES `detalle_paquete` WRITE;
/*!40000 ALTER TABLE `detalle_paquete` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_paquete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_traspasos`
--

DROP TABLE IF EXISTS `detalle_traspasos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_traspasos` (
  `id_tienda_articulo` bigint unsigned NOT NULL,
  `id_traspaso` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tienda_articulo`,`id_traspaso`),
  KEY `detalle_traspasos_id_traspaso_foreign` (`id_traspaso`),
  CONSTRAINT `detalle_traspasos_id_tienda_articulo_foreign` FOREIGN KEY (`id_tienda_articulo`) REFERENCES `tienda_articulo` (`id`),
  CONSTRAINT `detalle_traspasos_id_traspaso_foreign` FOREIGN KEY (`id_traspaso`) REFERENCES `traspasos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_traspasos`
--

LOCK TABLES `detalle_traspasos` WRITE;
/*!40000 ALTER TABLE `detalle_traspasos` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_traspasos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_vacuna`
--

DROP TABLE IF EXISTS `detalle_vacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_vacuna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_animal` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `tipo_vacuna` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_vacuna_id_animal_foreign` (`id_animal`),
  KEY `detalle_vacuna_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_vacuna_id_animal_foreign` FOREIGN KEY (`id_animal`) REFERENCES `animal` (`id`),
  CONSTRAINT `detalle_vacuna_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_vacuna`
--

LOCK TABLES `detalle_vacuna` WRITE;
/*!40000 ALTER TABLE `detalle_vacuna` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_vacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `id_lote` bigint unsigned NOT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `presentacion` int DEFAULT NULL,
  `total_cantidad` int DEFAULT NULL,
  `id_eliminado` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_venta_id_venta_foreign` (`id_venta`),
  KEY `detalle_venta_id_producto_foreign` (`id_producto`),
  KEY `detalle_venta_id_lote_foreign` (`id_lote`),
  CONSTRAINT `detalle_venta_id_lote_foreign` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id`),
  CONSTRAINT `detalle_venta_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`),
  CONSTRAINT `detalle_venta_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta_paquete`
--

DROP TABLE IF EXISTS `detalle_venta_paquete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta_paquete` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` bigint unsigned NOT NULL,
  `id_paquete` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `costo_venta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `detalle_venta_paquete_id_venta_foreign` (`id_venta`),
  KEY `detalle_venta_paquete_id_paquete_foreign` (`id_paquete`),
  CONSTRAINT `detalle_venta_paquete_id_paquete_foreign` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id`),
  CONSTRAINT `detalle_venta_paquete_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta_paquete`
--

LOCK TABLES `detalle_venta_paquete` WRITE;
/*!40000 ALTER TABLE `detalle_venta_paquete` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_venta_paquete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forma_pago`
--

DROP TABLE IF EXISTS `forma_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forma_pago` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forma_pago`
--

LOCK TABLES `forma_pago` WRITE;
/*!40000 ALTER TABLE `forma_pago` DISABLE KEYS */;
INSERT INTO `forma_pago` VALUES (1,'Cuenta por Cobrar','Pago a Crédito'),(2,'Efectivo','Pago Efectivo'),(3,'Transferencia','Pago desde una Cuenta Bancaria'),(4,'Pago por QR','Pago por el código QR'),(5,'Depósito','Pago por Depósito');
/*!40000 ALTER TABLE `forma_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gasto`
--

DROP TABLE IF EXISTS `gasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gasto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_motivo_gasto` bigint unsigned NOT NULL,
  `id_forma_pago` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `efectivo` decimal(11,2) DEFAULT NULL,
  `deposito` decimal(11,2) DEFAULT NULL,
  `control` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gasto_id_motivo_gasto_foreign` (`id_motivo_gasto`),
  CONSTRAINT `gasto_id_motivo_gasto_foreign` FOREIGN KEY (`id_motivo_gasto`) REFERENCES `motivo_gasto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gasto`
--

LOCK TABLES `gasto` WRITE;
/*!40000 ALTER TABLE `gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `gasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_permission`
--

DROP TABLE IF EXISTS `group_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_permission` (
  `id_grupo` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_grupo`,`permission_id`),
  KEY `group_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `group_permission_id_grupo_foreign` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_permission`
--

LOCK TABLES `group_permission` WRITE;
/*!40000 ALTER TABLE `group_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo`
--

DROP TABLE IF EXISTS `grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupo_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo`
--

LOCK TABLES `grupo` WRITE;
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
INSERT INTO `grupo` VALUES (1,'Administrador','administrador','Control General',1,1);
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_clinico`
--

DROP TABLE IF EXISTS `historial_clinico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_clinico` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nro_historia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cliente` bigint unsigned NOT NULL,
  `id_paciente` bigint unsigned NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_clinico_id_cliente_foreign` (`id_cliente`),
  KEY `historial_clinico_id_paciente_foreign` (`id_paciente`),
  KEY `historial_clinico_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `historial_clinico_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  CONSTRAINT `historial_clinico_id_paciente_foreign` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`),
  CONSTRAINT `historial_clinico_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_clinico`
--

LOCK TABLES `historial_clinico` WRITE;
/*!40000 ALTER TABLE `historial_clinico` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_clinico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lote`
--

DROP TABLE IF EXISTS `lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lote` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha_vecimiento` date NOT NULL,
  `cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `lote` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_producto` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lote_id_producto_foreign` (`id_producto`),
  CONSTRAINT `lote_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `tienda_articulo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lote`
--

LOCK TABLES `lote` WRITE;
/*!40000 ALTER TABLE `lote` DISABLE KEYS */;
/*!40000 ALTER TABLE `lote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marca` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mi_empresa`
--

DROP TABLE IF EXISTS `mi_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mi_empresa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nit` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representante` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Correo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sitio_web` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_login` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_sistema` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_usuario` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fondo_login` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_login` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_menu` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mi_empresa`
--

LOCK TABLES `mi_empresa` WRITE;
/*!40000 ALTER TABLE `mi_empresa` DISABLE KEYS */;
INSERT INTO `mi_empresa` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'#7ad6e6','#000000',1);
/*!40000 ALTER TABLE `mi_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_resets_table',1),(2,'2019_08_19_000000_create_failed_jobs_table',1),(3,'2019_12_14_000001_create_personal_access_tokens_table',1),(4,'2022_01_07_024055_create_cliente_table',1),(5,'2022_01_09_142223_create_proveedor_table',1),(6,'2022_01_09_142337_create_cargo_table',1),(7,'2022_01_09_142414_create_personal_table',1),(8,'2022_01_09_142440_create_grupo_table',1),(9,'2022_01_09_142441_create_users_table',1),(10,'2022_01_09_213313_create_permiso_table',1),(11,'2022_01_09_213352_create_grupo_permiso_table',1),(12,'2022_01_10_151817_create_mi_empresas_table',1),(13,'2022_01_10_220331_create_categoria_table',1),(14,'2022_01_10_222227_create_marca_table',1),(15,'2022_01_10_222228_create_unidad_medida_table',1),(16,'2022_01_10_222229_create_articulo_table',1),(17,'2022_01_11_135814_create_tienda_table',1),(18,'2022_01_11_140001_create_tienda_articulo_table',1),(19,'2022_01_12_174900_create_motivo_gasto_table',1),(20,'2022_01_12_175008_create_gasto_table',1),(21,'2022_01_14_141047_create_forma_pago_table',1),(22,'2022_01_14_141218_create_tipo_pago_table',1),(23,'2022_01_14_141219_create_paquetes_table',1),(24,'2022_01_14_141220_create_detalle_paquete_table',1),(25,'2022_01_14_141343_create_lote_table',1),(26,'2022_01_14_141344_create_compra_table',1),(27,'2022_01_14_141357_create_detalle_compra_table',1),(28,'2022_01_21_095224_create_motivo_ajuste_table',1),(29,'2022_01_21_095639_create_ajuste_table',1),(30,'2022_01_25_135142_create_orden_servicio_table',1),(31,'2022_01_26_185830_create_venta_table',1),(32,'2022_01_26_200634_create_detalle_venta_table',1),(33,'2022_01_26_220155_create_pago_table',1),(34,'2022_01_27_220127_create_c_x_cobrar_table',1),(35,'2022_01_31_122614_create_bitacora_table',1),(36,'2022_02_02_104645_create_control_table',1),(37,'2022_02_04_151217_create_detalle_orden_servicio_table',1),(38,'2022_02_18_120034_create_permiso_forms_table',1),(39,'2022_02_18_133831_create_formularios_table',1),(40,'2022_02_18_180135_create_detalle_forms_table',1),(41,'2022_02_19_161936_create_usuario_permisos_table',1),(42,'2022_02_28_233247_create_traspasos_table',1),(43,'2022_03_01_001522_create_detalle_traspasos_table',1),(44,'2022_03_03_134530_create_arqueo_cajas_table',1),(45,'2022_06_03_143702_create_pago_compra_table',1),(46,'2022_06_03_143817_create_c_x_pagar_table',1),(47,'2022_06_09_140255_create_detalle_venta_paquete_table',1),(48,'2022_06_09_141340_create_animal_table',1),(49,'2022_06_09_141818_create_paciente_table',1),(50,'2022_06_13_151128_create_detalle_vacuna_table',1),(51,'2022_06_14_202256_create_control_vacuna_table',1),(52,'2022_06_14_203929_create_detalle_control_vacuna_table',1),(53,'2022_07_05_202422_create_antiparasitario_table',1),(54,'2022_07_05_202527_create_detalle_antiparasitario_table',1),(55,'2022_07_06_210732_create_historial_clinico_table',1),(56,'2022_07_07_160235_create_detalle_historia_table',1),(57,'2022_07_07_160237_create_auxiliar_table',1),(58,'2026_08_02_000001_add_purchase_history_filter_indexes',1),(59,'2026_08_04_000001_create_cotizacion_table',1),(60,'2026_08_04_000002_create_detalle_cotizacion_table',1),(61,'2026_08_04_100000_create_native_rbac_tables',1),(62,'2026_08_04_110000_remove_legacy_permission_system',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motivo_ajuste`
--

DROP TABLE IF EXISTS `motivo_ajuste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivo_ajuste` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivo_ajuste`
--

LOCK TABLES `motivo_ajuste` WRITE;
/*!40000 ALTER TABLE `motivo_ajuste` DISABLE KEYS */;
INSERT INTO `motivo_ajuste` VALUES (1,'INVENTARIO INICIAL','SD'),(2,'INGRESO','SD'),(3,'EGRESO','SD'),(4,'COSTO COMPRA','SD'),(5,'PRECIO VENTA','SD'),(6,'COMPRA PRODUCTO','SD'),(7,'VENTA 1','SD'),(8,'VENTA 2','SD'),(9,'VENTA 3','SD');
/*!40000 ALTER TABLE `motivo_ajuste` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motivo_gasto`
--

DROP TABLE IF EXISTS `motivo_gasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivo_gasto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivo_gasto`
--

LOCK TABLES `motivo_gasto` WRITE;
/*!40000 ALTER TABLE `motivo_gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `motivo_gasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden_servicio`
--

DROP TABLE IF EXISTS `orden_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_servicio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cliente` bigint unsigned NOT NULL,
  `id_personal` bigint unsigned NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  `id_tienda` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_servicio_id_cliente_foreign` (`id_cliente`),
  KEY `orden_servicio_id_personal_foreign` (`id_personal`),
  KEY `orden_servicio_id_usuario_foreign` (`id_usuario`),
  KEY `orden_servicio_id_tienda_foreign` (`id_tienda`),
  CONSTRAINT `orden_servicio_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  CONSTRAINT `orden_servicio_id_personal_foreign` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`),
  CONSTRAINT `orden_servicio_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tienda` (`id`),
  CONSTRAINT `orden_servicio_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden_servicio`
--

LOCK TABLES `orden_servicio` WRITE;
/*!40000 ALTER TABLE `orden_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `especie` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edad` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raza` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexo` tinyint(1) NOT NULL DEFAULT '1',
  `peso` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cirugias` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enfermedades` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vacunas` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_cliente` bigint unsigned NOT NULL,
  `id_animal` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paciente_id_cliente_foreign` (`id_cliente`),
  KEY `paciente_id_animal_foreign` (`id_animal`),
  CONSTRAINT `paciente_id_animal_foreign` FOREIGN KEY (`id_animal`) REFERENCES `animal` (`id`),
  CONSTRAINT `paciente_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `id` int NOT NULL,
  `fecha` date NOT NULL,
  `fecha_final` date NOT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `saldo` decimal(11,2) DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_tipo_pago` bigint unsigned NOT NULL DEFAULT '1',
  `id_venta` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pago_id_tipo_pago_foreign` (`id_tipo_pago`),
  KEY `pago_id_venta_foreign` (`id_venta`),
  CONSTRAINT `pago_id_tipo_pago_foreign` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`),
  CONSTRAINT `pago_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago_compra`
--

DROP TABLE IF EXISTS `pago_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago_compra` (
  `id` int NOT NULL,
  `fecha` date NOT NULL,
  `fecha_final` date NOT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `saldo` decimal(11,2) DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_tipo_pago` bigint unsigned NOT NULL DEFAULT '1',
  `id_compra` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pago_compra_id_tipo_pago_foreign` (`id_tipo_pago`),
  KEY `pago_compra_id_compra_foreign` (`id_compra`),
  CONSTRAINT `pago_compra_id_compra_foreign` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id`),
  CONSTRAINT `pago_compra_id_tipo_pago_foreign` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago_compra`
--

LOCK TABLES `pago_compra` WRITE;
/*!40000 ALTER TABLE `pago_compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `pago_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paquetes`
--

DROP TABLE IF EXISTS `paquetes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paquetes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `descripcion` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paquetes`
--

LOCK TABLES `paquetes` WRITE;
/*!40000 ALTER TABLE `paquetes` DISABLE KEYS */;
/*!40000 ALTER TABLE `paquetes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'dashboard.view','Ver datos gráficos','Inicio',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(2,'cash.manage','Gestionar arqueo de caja','Caja',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(3,'purchases.create','Registrar compras','Compras',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(4,'purchases.view','Consultar historial de compras','Compras',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(5,'purchases.update','Modificar compras','Compras',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(6,'purchases.cancel','Anular compras','Compras',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(7,'purchases.payments','Gestionar pagos de compras','Compras',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(8,'sales.create','Registrar ventas','Ventas',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(9,'sales.view','Consultar historial de ventas','Ventas',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(10,'sales.update','Modificar ventas','Ventas',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(11,'sales.cancel','Anular ventas','Ventas',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(12,'sales.return','Registrar devoluciones','Ventas',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(13,'sales.payments','Gestionar pagos de ventas','Ventas',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(14,'inventory.view','Consultar inventario','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(15,'inventory.products.manage','Gestionar productos','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(16,'inventory.categories.manage','Gestionar categorías','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(17,'inventory.adjustments.manage','Gestionar ajustes','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(18,'inventory.presentations.manage','Gestionar presentaciones','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(19,'inventory.lines.manage','Gestionar líneas','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(20,'inventory.lots.manage','Gestionar lotes','Almacén',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(21,'expenses.reasons.manage','Gestionar motivos de gasto','Gastos',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(22,'expenses.manage','Gestionar gastos','Gastos',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(23,'data.clients.manage','Gestionar clientes','Datos maestros',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(24,'data.laboratories.manage','Gestionar laboratorios','Datos maestros',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(25,'data.personal.manage','Gestionar personal','Datos maestros',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(26,'data.positions.manage','Gestionar cargos','Datos maestros',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(27,'data.company.manage','Gestionar empresa','Datos maestros',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(28,'users.roles.manage','Gestionar grupos de usuarios','Usuarios',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(29,'users.manage','Gestionar usuarios','Usuarios',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(30,'users.permissions.manage','Asignar permisos','Usuarios',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(31,'reports.view','Acceder al módulo de reportes','Reportes',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34'),(32,'reports.generate','Generar reportes','Reportes',NULL,'2026-08-06 23:03:34','2026-08-06 23:03:34');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_cargo` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personal_id_cargo_foreign` (`id_cargo`),
  CONSTRAINT `personal_id_cargo_foreign` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (1,'Administrador Principal','0','0','0',1,1);
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` int NOT NULL,
  `contacto` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rbac_migration_audits`
--

DROP TABLE IF EXISTS `rbac_migration_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rbac_migration_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rbac_migration_audits`
--

LOCK TABLES `rbac_migration_audits` WRITE;
/*!40000 ALTER TABLE `rbac_migration_audits` DISABLE KEYS */;
INSERT INTO `rbac_migration_audits` VALUES (1,'legacy-rbac-before-migration','{\"grupo\":[],\"formularios\":[],\"permiso_forms\":[],\"detalle_forms\":[],\"usuario_permisos\":[],\"permiso\":[],\"grupo_permiso\":[]}','2026-08-06 19:03:34');
/*!40000 ALTER TABLE `rbac_migration_audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tienda`
--

DROP TABLE IF EXISTS `tienda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tienda` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cod_tienda` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_almacen` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `foto` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_mi_empresa` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tienda_id_mi_empresa_foreign` (`id_mi_empresa`),
  CONSTRAINT `tienda_id_mi_empresa_foreign` FOREIGN KEY (`id_mi_empresa`) REFERENCES `mi_empresa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tienda`
--

LOCK TABLES `tienda` WRITE;
/*!40000 ALTER TABLE `tienda` DISABLE KEYS */;
INSERT INTO `tienda` VALUES (1,'TND000','Farmacia Suarez','Villa Verde',NULL,NULL,1,'logo.png',1);
/*!40000 ALTER TABLE `tienda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tienda_articulo`
--

DROP TABLE IF EXISTS `tienda_articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tienda_articulo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_articulo` bigint unsigned NOT NULL,
  `id_tienda` bigint unsigned NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tienda_articulo_id_articulo_foreign` (`id_articulo`),
  KEY `tienda_articulo_id_tienda_foreign` (`id_tienda`),
  CONSTRAINT `tienda_articulo_id_articulo_foreign` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id`),
  CONSTRAINT `tienda_articulo_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tienda` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tienda_articulo`
--

LOCK TABLES `tienda_articulo` WRITE;
/*!40000 ALTER TABLE `tienda_articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tienda_articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_pago`
--

DROP TABLE IF EXISTS `tipo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_pago` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_pago`
--

LOCK TABLES `tipo_pago` WRITE;
/*!40000 ALTER TABLE `tipo_pago` DISABLE KEYS */;
INSERT INTO `tipo_pago` VALUES (1,'Contado','Pago Contado'),(2,'Credito','Pago al Credito');
/*!40000 ALTER TABLE `tipo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traspasos`
--

DROP TABLE IF EXISTS `traspasos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `traspasos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `glosa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_tienda1` bigint unsigned NOT NULL,
  `id_tienda2` bigint unsigned NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `traspasos_id_tienda1_foreign` (`id_tienda1`),
  KEY `traspasos_id_tienda2_foreign` (`id_tienda2`),
  KEY `traspasos_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `traspasos_id_tienda1_foreign` FOREIGN KEY (`id_tienda1`) REFERENCES `tienda` (`id`),
  CONSTRAINT `traspasos_id_tienda2_foreign` FOREIGN KEY (`id_tienda2`) REFERENCES `tienda` (`id`),
  CONSTRAINT `traspasos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traspasos`
--

LOCK TABLES `traspasos` WRITE;
/*!40000 ALTER TABLE `traspasos` DISABLE KEYS */;
/*!40000 ALTER TABLE `traspasos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad_medida`
--

DROP TABLE IF EXISTS `unidad_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidad_medida` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abreviatura` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad_medida`
--

LOCK TABLES `unidad_medida` WRITE;
/*!40000 ALTER TABLE `unidad_medida` DISABLE KEYS */;
/*!40000 ALTER TABLE `unidad_medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricula` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_grupo` bigint unsigned NOT NULL,
  `id_personal` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id_grupo_foreign` (`id_grupo`),
  KEY `users_id_personal_foreign` (`id_personal`),
  CONSTRAINT `users_id_grupo_foreign` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id`),
  CONSTRAINT `users_id_personal_foreign` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','001','admin@admin.com','$2y$10$qYkPgkT9H769uzzOWCINp.1vlghyFOLzBCf/lrHF.GjAPUaBvVCve',1,1,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `sub_total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_venta` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_cliente` bigint unsigned DEFAULT NULL,
  `id_tipo_pago` bigint unsigned NOT NULL,
  `id_forma_pago` bigint unsigned NOT NULL,
  `id_usuario` bigint unsigned NOT NULL,
  `id_tienda` bigint unsigned NOT NULL,
  `id_orden_servicio` bigint unsigned DEFAULT NULL,
  `id_paquete` bigint unsigned DEFAULT NULL,
  `total_efectivo` decimal(11,2) DEFAULT NULL,
  `total_deposito` decimal(11,2) DEFAULT NULL,
  `efectivo` decimal(11,2) DEFAULT NULL,
  `cambio` decimal(11,2) DEFAULT NULL,
  `control` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `venta_id_cliente_foreign` (`id_cliente`),
  KEY `venta_id_tipo_pago_foreign` (`id_tipo_pago`),
  KEY `venta_id_forma_pago_foreign` (`id_forma_pago`),
  KEY `venta_id_usuario_foreign` (`id_usuario`),
  KEY `venta_id_tienda_foreign` (`id_tienda`),
  KEY `venta_id_orden_servicio_foreign` (`id_orden_servicio`),
  KEY `venta_id_paquete_foreign` (`id_paquete`),
  CONSTRAINT `venta_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  CONSTRAINT `venta_id_forma_pago_foreign` FOREIGN KEY (`id_forma_pago`) REFERENCES `forma_pago` (`id`),
  CONSTRAINT `venta_id_orden_servicio_foreign` FOREIGN KEY (`id_orden_servicio`) REFERENCES `orden_servicio` (`id`),
  CONSTRAINT `venta_id_paquete_foreign` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id`),
  CONSTRAINT `venta_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tienda` (`id`),
  CONSTRAINT `venta_id_tipo_pago_foreign` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id`),
  CONSTRAINT `venta_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bd_farmacia_demo'
--
/*!50003 DROP PROCEDURE IF EXISTS `stock` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `stock`(IN `id_producto` FLOAT)
begin
      UPDATE tienda_articulo
      SET tienda_articulo.stock = ( SELECT SUM(lote.cantidad) FROM lote WHERE(lote.id_producto = id_producto and lote.estado !=0))
      WHERE tienda_articulo.id = id_producto;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-06 15:06:21
