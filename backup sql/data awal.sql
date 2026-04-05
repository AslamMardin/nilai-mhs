-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: nilai
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `pertemuan_ke` tinyint NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('H','T','S','I','A') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H',
  `keterangan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_absensi` (`mahasiswa_id`,`mata_kuliah_id`,`pertemuan_ke`),
  KEY `absensi_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `absensi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi`
--

LOCK TABLES `absensi` WRITE;
/*!40000 ALTER TABLE `absensi` DISABLE KEYS */;
/*!40000 ALTER TABLE `absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kampus`
--

DROP TABLE IF EXISTS `kampus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kampus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kampus_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kampus`
--

LOCK TABLES `kampus` WRITE;
/*!40000 ALTER TABLE `kampus` DISABLE KEYS */;
INSERT INTO `kampus` VALUES (1,'Institut Teknologi dan Bisnis Muhammadiyah Majene','ITBM','Jl. Poros Majene, Sulawesi Barat','0422-12345','2026-04-02 08:42:38','2026-04-02 08:42:38'),(2,'Sekolah Tinggi Agama Islam Negeri Majene','STAIN','Jl. BPD, Majene, Sulawesi Barat','0422-67890','2026-04-02 08:42:38','2026-04-02 08:42:38');
/*!40000 ALTER TABLE `kampus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kampus_id` bigint unsigned NOT NULL,
  `nama` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('ganjil','genap') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` year NOT NULL,
  `wali_kelas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_kode_unique` (`kode`),
  KEY `kelas_kampus_id_foreign` (`kampus_id`),
  CONSTRAINT `kelas_kampus_id_foreign` FOREIGN KEY (`kampus_id`) REFERENCES `kampus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (1,2,'PAI 4','TP4','genap',2026,'Darwis','2026-04-02 05:12:46','2026-04-02 05:14:11'),(2,2,'PAI1','TP1','genap',2026,'Darwis','2026-04-02 05:13:12','2026-04-02 05:14:19'),(3,1,'K23-A6','K-A6','genap',2026,'Rahmi','2026-04-02 07:13:50','2026-04-02 07:13:50'),(4,1,'B23.B6','B-B6','genap',2026,'Rahmi','2026-04-02 07:14:29','2026-04-02 07:14:29'),(5,1,'B23.A6','B-A6','genap',2026,'Rahmi','2026-04-02 07:14:57','2026-04-02 07:14:57');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kampus_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `nim` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','cuti','lulus','dropout') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswa_nim_unique` (`nim`),
  UNIQUE KEY `mahasiswa_email_unique` (`email`),
  KEY `mahasiswa_kampus_id_foreign` (`kampus_id`),
  KEY `mahasiswa_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `mahasiswa_kampus_id_foreign` FOREIGN KEY (`kampus_id`) REFERENCES `kampus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mahasiswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa`
--

LOCK TABLES `mahasiswa` WRITE;
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` VALUES (9,2,1,'10156125104','ADE ADIATI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(10,2,1,'10156125101','AHMAD SYUKRAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(11,2,1,'10156125102','AKZAL AIMAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(12,2,1,'10156125106','ARBAINUL MOSLEM','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(13,2,1,'10156125113','ARINI NITHA MAFTUHA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(14,2,1,'10156125114','Faisal','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(15,2,1,'10156125109','IYAN DERMAWAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(16,2,1,'10156123139','M SYUKRI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(17,2,1,'10156125107','MASRI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(18,2,1,'10156125099','MUH. AIMAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(19,2,1,'10156125105','MUH. AS’AD','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(20,2,1,'10156125090','MUH. IDHAM','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(21,2,1,'10156125093','MUH. JAILI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(22,2,1,'10156125097','MUH. SAJIBANK','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(23,2,1,'10156125115','MUH.FAUZAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(24,2,1,'10156125088','MUHAMMAD RANDI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(25,2,1,'10156125029','NADIA ADILLAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(26,2,1,'10156125057','NAMIRA ZAHRA AINI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(27,2,1,'10156125108','NOVITHASARI DAHRI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(28,2,1,'10156125100','NUR ALIAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(29,2,1,'10156125116','NURSALWAH SUAIB','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(30,2,1,'10156125103','NURUL ZASKIA R','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(31,2,1,'10156125091','REZKY AMELIA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(32,2,1,'10156125092','Rahmat Ilahi','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(33,2,1,'10156125111','SARUDIN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(34,2,1,'10156125095','Selfi','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(35,2,1,'10156125110','WIWI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:58:41','2026-04-02 08:58:41'),(36,2,2,'10156125006','ABD. WAHAB','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(37,2,2,'10156125005','ADILA SYAHRA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(38,2,2,'10156125012','AGUSTINA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(39,2,2,'10156125010','CACA ANDIKA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(40,2,2,'10156125017','EZRA SETIA WIJAYA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(41,2,2,'10156125002','FATIMA AZZAHRAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(42,2,2,'10156125050','FILSA AMALIA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(43,2,2,'10156125014','FITRA NUR AVIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(44,2,2,'10156125076','FITRIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(45,2,2,'10156125031','HAJRAWATI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(46,2,2,'10156125007','HANIFAH NUR INAYAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(47,2,2,'10156125020','HUSNUL KHALIFAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(48,2,2,'10156125024','INDRI NURAENI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(49,2,2,'10156125001','IRIANI NUR','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(50,2,2,'10156125015','ISMA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(51,2,2,'10156125026','ISMAWATI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(52,2,2,'10156125008','M. HAIKAL','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(53,2,2,'10156125021','M. KAHFI. AR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(54,2,2,'10156125025','MUH. AGUNG SATRIO NEDAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(55,2,2,'10156125011','MUH. RASYIDIN R','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(56,2,2,'10156125016','MUHAMMAD IKHWAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(57,2,2,'10156125013','NURUL ILMI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(58,2,2,'10156125089','NUR HAFIDAH AZZAHRA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(59,2,2,'10156125022','RAHMADANI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(60,2,2,'10156125027','SUCI AMALIAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(61,2,2,'10156125003','SUDIRMAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(62,2,2,'10156125112','SATRIA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(63,2,2,'10156125019','ZADRAN T.','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(64,2,2,'10156125004','ZAHRATUNNISA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(65,2,2,'10156125009','ZULKIFLI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 08:59:07','2026-04-02 08:59:07'),(66,1,3,'202303001','NURPADILA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(67,1,3,'202303002','DIRA AMELIA PUTRI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(68,1,3,'202303003','DIAN OLEHFIA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(69,1,3,'202303004','ARIF MAULANA P. MBURA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(70,1,3,'202303005','NURMI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(71,1,3,'202303006','ABD. RAZAK','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(72,1,3,'202303007','RINA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(73,1,3,'202303008','RISMAYANTI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(74,1,3,'202303009','HASANUDDIN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(75,1,3,'202303010','PUTRIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(76,1,3,'202303011','FITRI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(77,1,3,'202303012','DIANA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(78,1,3,'202303013','MUH. WAHYU','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(79,1,3,'202303014','LISMAYANTI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(80,1,3,'202303015','NURPAIZAL','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(81,1,3,'202303016','PUTRI WAHYUNI ZAINAL','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(82,1,3,'202303017','RINA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(83,1,3,'202303018','VERA ANDRIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(84,1,3,'202303019','HIJRANA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(85,1,3,'202303020','SULBAR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(86,1,3,'202303021','RATNA SARI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(87,1,3,'202303023','NUR WILDA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(88,1,3,'202303024','ARJUNA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(89,1,3,'202303025','MUHAMMAD RIFDAL','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(90,1,3,'202303026','NURMAYA SARI DEWI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(91,1,3,'202303027','ANDI ALYA ANANTA ALWI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(92,1,3,'202303028','MEILAN PRAGESTI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(93,1,3,'202303029','NURFADILAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(94,1,3,'202303031','HARNISA BASRI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(95,1,3,'202303032','DIVA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(96,1,3,'202303033','EQI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(97,1,3,'202303034','UMARDIN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(98,1,3,'202303035','DIMAZ ARYAN ALDIANSYAH','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(99,1,3,'202303036','HERMAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(100,1,3,'202303038','NOVI MAWADDAH APRILIA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-02 20:04:44','2026-04-02 20:04:44'),(101,1,5,'202302001','ARIS MUNANDAR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(102,1,5,'202302005','MINHAJUDDIN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(103,1,5,'202302006','MUH.JABBAR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(104,1,5,'202302007','ANANDA UTARI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(105,1,5,'202302008','MUQARRAMA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(106,1,5,'202302009','RAEHANA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(107,1,5,'202302010','KARTINI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(108,1,5,'202302011','RINDIANI ARIF','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(109,1,5,'202302012','IZZATUL MUSARRIFA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(110,1,5,'202302013','RUSTINA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(111,1,5,'202302014','MUHAMMAD BASIT MUHLIS','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(112,1,5,'202302015','PUTRI NIRMALA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(113,1,5,'202302016','MUH. ZULHISYAM','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(114,1,5,'202302017','MUHAMMAD ASYRAF KHALID','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(115,1,5,'202302018','RISMA EKAYANTI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(116,1,5,'202302019','MUH. ICHSAN MAULANA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(117,1,5,'202302020','PRENGKI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(118,1,5,'202302021','ANUGRAH','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(119,1,5,'202302022','WINIARNI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(120,1,5,'202302023','NUR ATIKA ZAHRA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(121,1,5,'202302024','ANDI MUHAMMAD HIDAYAT','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(122,1,5,'202302025','TUTI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(123,1,5,'202302026','MUHAMMAD AFDAL HAMZAH','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(124,1,5,'202302027','NUR RAHMAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(125,1,5,'202302028','MARSION','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(126,1,5,'202302029','SAPUTRA JAYA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(127,1,5,'202302030','M. YOKIN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(128,1,5,'202302031','ALFAREZA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(129,1,5,'202302032','NUR AENA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(130,1,5,'202302034','SUTRIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(131,1,5,'202302035','AZIMAH AINIYYAH ZAHRANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(132,1,5,'202302036','RESKIADITYA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(133,1,5,'202302037','MASDAR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(134,1,5,'202302038','BUDIATMAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(135,1,5,'202302039','WINA AMELIA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(136,1,5,'202302040','WIDYA LESTARI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-03 04:53:19','2026-04-03 04:53:19'),(137,1,4,'202302041','AGUM ALQI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(138,1,4,'202302042','RISKY MUBARAQ','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(139,1,4,'202302043','ANANDA RHEYKA KODAO','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(140,1,4,'202302044','ADNAN DIDI ALFIAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(141,1,4,'202302045','ILMAN. B','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(142,1,4,'202302046','MASRIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(143,1,4,'202302047','ARMELIA PUTRI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(144,1,4,'202302048','ARPA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(145,1,4,'202302049','ALGI FARI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(146,1,4,'202302050','MUH. AIDIL RIFKY BUSMAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(147,1,4,'202302051','PUTRI RAMADANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(148,1,4,'202302052','SITI ASISYAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(149,1,4,'202302053','SALDI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(150,1,4,'202302054','FAHRI HUSAIN.D','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(151,1,4,'202302055','MUHAMMAD ALI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(152,1,4,'202302056','ARYA SAPUTRA','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(153,1,4,'202302057','ROSNIA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(154,1,4,'202302058','ALYA DWIYANTI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(155,1,4,'202302059','ALI IMRAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(156,1,4,'202302060','WAHYU MUHTAR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(157,1,4,'202302061','NUR FASILA','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(158,1,4,'202302062','FITRIANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(159,1,4,'202302063','RIDWAN AHMAD','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(160,1,4,'202302064','SITTI MARDIANAH NASIFAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(161,1,4,'202302065','MARAWIAH','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(162,1,4,'202302066','MUHAMMAD FADEL','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(163,1,4,'202302067','IRWANDI','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(164,1,4,'202302068','NOVITA SARI RIDWAN','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(165,1,4,'202302070','MAYA RAMADAHANI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(166,1,4,'202302071','MUHAMMAD IKHSAN MUNIR','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(167,1,4,'202302072','IRWAN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(168,1,4,'202302073','NUR ASRI AINUN AYU UTAMI','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(169,1,4,'202302074','PUTRI NAURI YUSUF','P',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07'),(170,1,4,'202302075','BAHARUDDIN','L',NULL,NULL,NULL,NULL,NULL,'aktif','2026-04-05 05:58:07','2026-04-05 05:58:07');
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_kuliah`
--

DROP TABLE IF EXISTS `mata_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kampus_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` tinyint NOT NULL DEFAULT '2',
  `jenis` enum('teori','praktikum','teori_praktikum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'teori',
  `dosen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_pertemuan` tinyint NOT NULL DEFAULT '16',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliah_kode_unique` (`kode`),
  KEY `mata_kuliah_kampus_id_foreign` (`kampus_id`),
  KEY `mata_kuliah_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `mata_kuliah_kampus_id_foreign` FOREIGN KEY (`kampus_id`) REFERENCES `kampus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mata_kuliah_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_kuliah`
--

LOCK TABLES `mata_kuliah` WRITE;
/*!40000 ALTER TABLE `mata_kuliah` DISABLE KEYS */;
INSERT INTO `mata_kuliah` VALUES (1,2,2,'TIK-1','Teknologi Informasi dan Komunikasi TP1',2,'teori_praktikum','Ust. Darwis Kaprodi',16,'2026-04-02 07:52:22','2026-04-02 07:53:00'),(2,2,1,'TIK-4','Teknologi Informasi dan Komunikasi TP4',2,'teori_praktikum','Ust. Darwis Kaprodi',16,'2026-04-02 07:52:53','2026-04-02 07:52:53'),(3,1,3,'BGI-K23-A6','BUSINESS GEOSPATIAL INFORMATION',3,'teori_praktikum','Bu Rahmi',16,'2026-04-02 08:02:47','2026-04-02 21:32:09'),(4,1,4,'BGI-B23-B6','BUSINESS GEOSPATIAL INFORMATION',3,'teori_praktikum','Bu Rahmi',16,'2026-04-02 08:04:45','2026-04-02 20:06:04'),(5,1,5,'BGI-B23-A6','BUSINESS GEOSPATIAL INFORMATION',3,'teori_praktikum','Bu Rahmi',16,'2026-04-02 08:05:27','2026-04-02 20:05:56');
/*!40000 ALTER TABLE `mata_kuliah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2024_01_01_000001_create_kampus_table',1),(4,'2024_01_01_000002_create_users_table',1),(5,'2024_01_01_000003_create_kelas_table',1),(6,'2024_01_01_000004_create_mata_kuliah_table',1),(7,'2024_01_01_000005_create_mahasiswa_table',1),(8,'2024_01_01_000006_create_akademik_tables',1),(9,'2026_04_01_140427_create_sessions_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai_akhir`
--

DROP TABLE IF EXISTS `nilai_akhir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_akhir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `nilai_teori` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_praktikum` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `huruf_mutu` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persentase_kehadiran` decimal(5,2) NOT NULL DEFAULT '0.00',
  `poin_kehadiran` smallint NOT NULL DEFAULT '0',
  `status_kelulusan` enum('lulus','tidak_lulus','belum_dinilai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dinilai',
  `keterangan_gagal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nilai_akhir` (`mahasiswa_id`,`mata_kuliah_id`),
  KEY `nilai_akhir_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `nilai_akhir_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_akhir_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_akhir`
--

LOCK TABLES `nilai_akhir` WRITE;
/*!40000 ALTER TABLE `nilai_akhir` DISABLE KEYS */;
/*!40000 ALTER TABLE `nilai_akhir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai_praktikum`
--

DROP TABLE IF EXISTS `nilai_praktikum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_praktikum` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `nilai_praktikum` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nilai_praktikum` (`mahasiswa_id`,`mata_kuliah_id`),
  KEY `nilai_praktikum_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `nilai_praktikum_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_praktikum_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_praktikum`
--

LOCK TABLES `nilai_praktikum` WRITE;
/*!40000 ALTER TABLE `nilai_praktikum` DISABLE KEYS */;
/*!40000 ALTER TABLE `nilai_praktikum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai_teori`
--

DROP TABLE IF EXISTS `nilai_teori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_teori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `keaktifan` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tugas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `uts` decimal(5,2) NOT NULL DEFAULT '0.00',
  `uas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir_teori` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nilai_teori` (`mahasiswa_id`,`mata_kuliah_id`),
  KEY `nilai_teori_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `nilai_teori_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_teori_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_teori`
--

LOCK TABLES `nilai_teori` WRITE;
/*!40000 ALTER TABLE `nilai_teori` DISABLE KEYS */;
/*!40000 ALTER TABLE `nilai_teori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendaftaran_mahasiswa`
--

DROP TABLE IF EXISTS `pendaftaran_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendaftaran_mahasiswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `tahun_ajaran` year NOT NULL,
  `semester` enum('ganjil','genap') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','mengulang','lulus','tidak_lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pendaftaran` (`mahasiswa_id`,`mata_kuliah_id`),
  KEY `pendaftaran_mahasiswa_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `pendaftaran_mahasiswa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pendaftaran_mahasiswa_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftaran_mahasiswa`
--

LOCK TABLES `pendaftaran_mahasiswa` WRITE;
/*!40000 ALTER TABLE `pendaftaran_mahasiswa` DISABLE KEYS */;
INSERT INTO `pendaftaran_mahasiswa` VALUES (14,9,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(15,10,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(16,11,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(17,12,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(18,13,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(19,14,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(20,15,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(21,16,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(22,17,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(23,18,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(24,19,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(25,20,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(26,21,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(27,22,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(28,23,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(29,24,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(30,25,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(31,26,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(32,27,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(33,28,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(34,29,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(35,30,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(36,31,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(37,32,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(38,33,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(39,34,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(40,35,2,2026,'genap','aktif','2026-04-02 09:00:07','2026-04-02 09:00:07'),(41,36,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(42,37,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(43,38,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(44,39,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(45,40,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(46,41,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(47,42,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(48,43,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(49,44,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(50,45,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(51,46,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(52,47,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(53,48,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(54,49,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(55,50,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(56,51,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(57,52,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(58,53,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(59,54,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(60,55,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(61,56,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(62,57,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(63,58,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(64,59,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(65,60,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(66,61,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(67,62,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(68,63,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(69,64,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(70,65,1,2026,'genap','aktif','2026-04-02 09:00:26','2026-04-02 09:00:26'),(71,66,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(72,67,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(73,68,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(74,69,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(75,70,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(76,71,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(77,72,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(78,73,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(79,74,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(80,75,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(81,76,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(82,77,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(83,78,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(84,79,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(85,80,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(86,81,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(87,82,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(88,83,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(89,84,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(90,85,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(91,86,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(92,87,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(93,88,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(94,89,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(95,90,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(96,91,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(97,92,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(98,93,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(99,94,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(100,95,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(101,96,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(102,97,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(103,98,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(104,99,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(105,100,3,2026,'genap','aktif','2026-04-02 20:08:57','2026-04-02 20:08:57'),(106,101,5,2026,'ganjil','aktif','2026-04-03 04:55:04','2026-04-03 04:55:54'),(107,102,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(108,103,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(109,104,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(110,105,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(111,106,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(112,107,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(113,108,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(114,109,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(115,110,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(116,111,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(117,112,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(118,113,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(119,114,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(120,115,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(121,116,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(122,117,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(123,118,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(124,119,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(125,120,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(126,121,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(127,122,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(128,123,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(129,124,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(130,125,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(131,126,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(132,127,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(133,128,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(134,129,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(135,130,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(136,131,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(137,132,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(138,133,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(139,134,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(140,135,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04'),(141,136,5,2026,'genap','aktif','2026-04-03 04:55:04','2026-04-03 04:55:04');
/*!40000 ALTER TABLE `pendaftaran_mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('superadmin','admin','dosen') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `kampus_id` bigint unsigned DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_kampus_id_foreign` (`kampus_id`),
  CONSTRAINT `users_kampus_id_foreign` FOREIGN KEY (`kampus_id`) REFERENCES `kampus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Aslam Mardin','aslam11mardin@gmail.com','$2y$12$b9nZNYEeYJ1LhLhMcjRCw.nrqMEJD..YausLEUYh6CQuuCRI/NbjW','superadmin',1,NULL,'2026-04-02 08:42:39','2026-04-05 05:52:55'),(2,'Admin','admin@gmail.com','$2y$12$dIZzF4pt0QL8MWgEJedgBe0y.mwFlTX74Sg/legJ7.zjWMgzZG4rq','admin',1,NULL,'2026-04-02 08:42:39','2026-04-02 08:42:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-05 21:58:30
