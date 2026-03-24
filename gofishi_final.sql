-- MySQL dump 10.13  Distrib 9.6.0, for macos14.8 (x86_64)
--
-- Host: localhost    Database: rental_perahu
-- ------------------------------------------------------
-- Server version	9.6.0

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
-- GTID state at the beginning of the backup 
--


--
-- Table structure for table `about_us`
--

DROP TABLE IF EXISTS `about_us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `about_us` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_us`
--

LOCK TABLES `about_us` WRITE;
/*!40000 ALTER TABLE `about_us` DISABLE KEYS */;
/*!40000 ALTER TABLE `about_us` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `additional_service_contents`
--

DROP TABLE IF EXISTS `additional_service_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `additional_service_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `additional_service_id` bigint DEFAULT NULL,
  `language_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_service_contents`
--

LOCK TABLES `additional_service_contents` WRITE;
/*!40000 ALTER TABLE `additional_service_contents` DISABLE KEYS */;
INSERT INTO `additional_service_contents` VALUES (22,11,1,'Makan di Kapal','2026-03-17 23:30:46','2026-03-17 23:30:46'),(23,11,2,'In-Boat Dining','2026-03-17 23:30:46','2026-03-17 23:30:46'),(24,12,1,'Dekorasi Kapal','2026-03-17 23:30:46','2026-03-17 23:30:46'),(25,12,2,'Boat Decoration','2026-03-17 23:30:46','2026-03-17 23:30:46'),(26,13,1,'Antar Jemput Bandara','2026-03-17 23:30:47','2026-03-17 23:30:47'),(27,13,2,'Airport Pickup','2026-03-17 23:30:47','2026-03-17 23:30:47'),(28,14,1,'Penyewaan Alat Selam','2026-03-17 23:30:47','2026-03-17 23:30:47'),(29,14,2,'Diving Gear Rental','2026-03-17 23:30:47','2026-03-17 23:30:47');
/*!40000 ALTER TABLE `additional_service_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `additional_services`
--

DROP TABLE IF EXISTS `additional_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `additional_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` bigint DEFAULT NULL,
  `serial_number` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_services`
--

LOCK TABLES `additional_services` WRITE;
/*!40000 ALTER TABLE `additional_services` DISABLE KEYS */;
INSERT INTO `additional_services` VALUES (11,1,1,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(12,1,1,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(13,1,1,'2026-03-17 23:30:47','2026-03-17 23:30:47'),(14,1,1,'2026-03-17 23:30:47','2026-03-17 23:30:47');
/*!40000 ALTER TABLE `additional_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,1,'admin','admin@gofishi.com','$2y$12$VCY.lWWLXAkT9m9EaicQVOK8bnYUWwMPAjnCwwUXt0TTV/A5gnDqC',NULL,NULL,NULL,1,'2026-03-15 04:43:21','2026-03-19 09:19:13');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advertisements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ad_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolution_type` smallint NOT NULL COMMENT '1 => 300 x 250, 2 => 300 x 600, 3 => 728 x 90',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertisements`
--

LOCK TABLES `advertisements` WRITE;
/*!40000 ALTER TABLE `advertisements` DISABLE KEYS */;
/*!40000 ALTER TABLE `advertisements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amenities`
--

DROP TABLE IF EXISTS `amenities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amenities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amenities`
--

LOCK TABLES `amenities` WRITE;
/*!40000 ALTER TABLE `amenities` DISABLE KEYS */;
INSERT INTO `amenities` VALUES (25,1,'Wifi','fa fa-wifi',0,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(29,1,'Sarapan','fa fa-coffee',0,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(31,1,'Makan Siang','fa fa-utensils',0,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(33,1,'Makan Malam','fa fa-drumstick-bite',0,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(35,1,'Air Mineral','fa fa-tint',0,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(37,1,'Peralatan Mancing','fa fa-ship',0,'2026-03-17 23:30:46','2026-03-17 23:30:46'),(39,1,'Pelampung','fa fa-life-ring',0,'2026-03-17 23:30:46','2026-03-17 23:30:46');
/*!40000 ALTER TABLE `amenities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement_popups`
--

DROP TABLE IF EXISTS `announcement_popups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement_popups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement_popups`
--

LOCK TABLES `announcement_popups` WRITE;
/*!40000 ALTER TABLE `announcement_popups` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement_popups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `basic_settings`
--

DROP TABLE IF EXISTS `basic_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `basic_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uniqid` int NOT NULL DEFAULT '12345',
  `theme_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `website_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_two` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breadcrumb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disqus_status` tinyint unsigned NOT NULL DEFAULT '0',
  `disqus_short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_msg` text COLLATE utf8mb4_unicode_ci,
  `footer_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_background_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_status` int NOT NULL DEFAULT '0',
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_header_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_popup_status` int NOT NULL DEFAULT '0',
  `whatsapp_popup_message` text COLLATE utf8mb4_unicode_ci,
  `whatsapp_api_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tawkto_status` int NOT NULL DEFAULT '0',
  `tawkto_direct_chat_link` text COLLATE utf8mb4_unicode_ci,
  `base_currency_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `base_currency_text_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `base_currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Rp',
  `total_earning` decimal(20,2) NOT NULL DEFAULT '0.00',
  `hotel_tax_amount` decimal(10,3) DEFAULT '0.000',
  `base_currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `base_currency_rate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `hero_section_video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preloader_status` int NOT NULL DEFAULT '0',
  `preloader` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_map_api_key_status` int NOT NULL DEFAULT '0',
  `time_format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '24h',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_whatsapp_chat_active` tinyint NOT NULL DEFAULT '1' COMMENT '1: Active, 0: Deactive',
  `hotel_view` int DEFAULT '1',
  `room_view` int DEFAULT '1',
  `pixel_status` tinyint NOT NULL DEFAULT '0',
  `pixel_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_analytics_status` tinyint NOT NULL DEFAULT '0',
  `google_analytics_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_gemini_status` tinyint NOT NULL DEFAULT '0',
  `google_gemini_api_key` text COLLATE utf8mb4_unicode_ci,
  `google_gemini_prompt` text COLLATE utf8mb4_unicode_ci,
  `google_adsense_publisher_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_status` tinyint DEFAULT NULL,
  `smtp_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_port` int DEFAULT NULL,
  `encryption` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_theme_version` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `google_recaptcha_status` int NOT NULL DEFAULT '0',
  `google_recaptcha_site_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_recaptcha_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_login_status` tinyint unsigned NOT NULL DEFAULT '1',
  `facebook_app_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_app_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_login_status` tinyint unsigned NOT NULL DEFAULT '1',
  `google_client_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_client_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_map_api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_admin_approval` int NOT NULL DEFAULT '0',
  `vendor_email_verification` int NOT NULL DEFAULT '0',
  `admin_approval_notice` text COLLATE utf8mb4_unicode_ci,
  `expiration_reminder` int NOT NULL DEFAULT '3',
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `radius` int NOT NULL DEFAULT '10',
  `maintenance_status` tinyint DEFAULT NULL,
  `notification_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_checkout_status` tinyint unsigned NOT NULL DEFAULT '1',
  `bypass_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_subtile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_details` longtext COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feature_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `counter_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_section_inner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `self_pickup_status` tinyint unsigned DEFAULT NULL,
  `two_way_delivery_status` tinyint unsigned DEFAULT NULL,
  `equipment_tax_amount` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basic_settings`
--

LOCK TABLES `basic_settings` WRITE;
/*!40000 ALTER TABLE `basic_settings` DISABLE KEYS */;
INSERT INTO `basic_settings` VALUES (1,12345,'1','Gofishi','logo_v2.png','logo_v2.svg',NULL,NULL,0,NULL,NULL,NULL,'logo_v2.png',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,0,NULL,NULL,NULL,0,NULL,'IDR','left','Rp',0.00,0.000,'left','1',NULL,0,NULL,1,'24h','2026-03-15 15:48:57','2026-03-18 10:04:33',1,1,0,0,NULL,0,NULL,1,'AIzaSyDVtc0WKKzDAbzRVSMVgz1wMDEcO5DwOBU','kamu adalah asisten AI untuk bisanis gofishi.\r\nFokus bisnis adalah pada Marketplace penyewaan perahu dan Saltwater Angler.\r\n* Jasa Pemandu: Bermitra dengan lebih dari 30 kapten berlisensi untuk memandu perjalanan memancing (charter).\r\n* Produk: Menjual berbagai perlengkapan seperti joran, gulungan (reels), pakaian, topi, tas, dan aksesori dari merek-merek ternama.\r\n* Lokasi: Usaha Beroperasi di Jakarta Utara, Banten, dan Jawa Barat',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'light',0,NULL,NULL,1,NULL,NULL,1,NULL,NULL,NULL,1,0,NULL,3,NULL,10,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `basic_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `benifits`
--

DROP TABLE IF EXISTS `benifits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `benifits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `benifits`
--

LOCK TABLES `benifits` WRITE;
/*!40000 ALTER TABLE `benifits` DISABLE KEYS */;
/*!40000 ALTER TABLE `benifits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (53,1,'Tips & Wisata','tips-wisata',1,'2026-03-18 10:28:55','2026-03-18 10:28:55',1);
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_informations`
--

DROP TABLE IF EXISTS `blog_informations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_informations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint DEFAULT NULL,
  `blog_category_id` bigint DEFAULT NULL,
  `language_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_informations`
--

LOCK TABLES `blog_informations` WRITE;
/*!40000 ALTER TABLE `blog_informations` DISABLE KEYS */;
INSERT INTO `blog_informations` VALUES (1,2,53,1,'Cara Cerdas Menyewa Yacht Mewah di Jakarta untuk Acara Spesial','cara-cerdas-menyewa-yacht-mewah-di-jakarta-untuk-acara-spesial','<p class=\"lead\" style=\"font-size: 1.1rem; color: #555;\">Bosan dengan perayaan di hotel bintang lima? Menyewa yacht pribadi menyajikan level eksklusivitas yang tidak bisa didapatkan di tempat lain. Baik untuk ulang tahun, <i>anniversary</i>, maupun pesta perusahaan.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Keistimewaan Merayakan di Atas Yacht</h3>\n<p>Ketenangan laut dipasangkan dengan kemewahan fasilitas modern. Dari dek berjemur (sundeck) yang luas, interior ber-AC, hingga layanan katering kelas premium. Anda akan menikmati pemandangan matahari terbenam (sunset) terbaik di Teluk Jakarta secara privat.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">3 Tips Memilih Yacht yang Tepat</h3>\n<ol style=\"list-style-type: decimal; margin-left: 20px;\">\n<li style=\"margin-bottom: 8px;\"><strong>Sesuaikan dengan Jumlah Tamu:</strong> Pastikan ruang gerak nyaman. Yacht yang terlalu padat akan mengurangi kesan mewah.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Cek Fasilitas Entertainment:</strong> Apakah ada sound system premium? Bagaimana dengan dapur dan toiletnya?</li>\n<li style=\"margin-bottom: 8px;\"><strong>Pilih Kru Profesional:</strong> Hospitality dari kru akan sangat menentukan kesuksesan acara Anda.</li>\n</ol>\n<p>Di Gofishi, kami memastikan setiap yacht yang terdaftar telah melewati standar kelayakan dan pelayanan eksklusif.</p>\n<h3 class=\"mt-5 mb-2\" style=\"font-weight:bold;\">Wujudkan Momen Tak Terlupakan Anda</h3>\n<p>Amankan tanggal Anda sebelum kehabisan. Yacht mewah sangat diminati saat menjelang akhir pekan dan libur panjang.</p>\n<div style=\"margin-top: 25px;\"><a href=\"/perahu\" style=\"background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;\">Cek Harga Sewa Yacht</a></div>','Go Fishi Editor','2026-03-17 01:42:06','2026-03-20 09:14:49',NULL,NULL),(2,2,53,2,'Cara Cerdas Menyewa Yacht Mewah di Jakarta untuk Acara Spesial','cara-cerdas-menyewa-yacht-mewah-di-jakarta-untuk-acara-spesial','<p class=\"lead\" style=\"font-size: 1.1rem; color: #555;\">Bosan dengan perayaan di hotel bintang lima? Menyewa yacht pribadi menyajikan level eksklusivitas yang tidak bisa didapatkan di tempat lain. Baik untuk ulang tahun, <i>anniversary</i>, maupun pesta perusahaan.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Keistimewaan Merayakan di Atas Yacht</h3>\n<p>Ketenangan laut dipasangkan dengan kemewahan fasilitas modern. Dari dek berjemur (sundeck) yang luas, interior ber-AC, hingga layanan katering kelas premium. Anda akan menikmati pemandangan matahari terbenam (sunset) terbaik di Teluk Jakarta secara privat.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">3 Tips Memilih Yacht yang Tepat</h3>\n<ol style=\"list-style-type: decimal; margin-left: 20px;\">\n<li style=\"margin-bottom: 8px;\"><strong>Sesuaikan dengan Jumlah Tamu:</strong> Pastikan ruang gerak nyaman. Yacht yang terlalu padat akan mengurangi kesan mewah.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Cek Fasilitas Entertainment:</strong> Apakah ada sound system premium? Bagaimana dengan dapur dan toiletnya?</li>\n<li style=\"margin-bottom: 8px;\"><strong>Pilih Kru Profesional:</strong> Hospitality dari kru akan sangat menentukan kesuksesan acara Anda.</li>\n</ol>\n<p>Di Gofishi, kami memastikan setiap yacht yang terdaftar telah melewati standar kelayakan dan pelayanan eksklusif.</p>\n<h3 class=\"mt-5 mb-2\" style=\"font-weight:bold;\">Wujudkan Momen Tak Terlupakan Anda</h3>\n<p>Amankan tanggal Anda sebelum kehabisan. Yacht mewah sangat diminati saat menjelang akhir pekan dan libur panjang.</p>\n<div style=\"margin-top: 25px;\"><a href=\"/perahu\" style=\"background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;\">Cek Harga Sewa Yacht</a></div>','Go Fishi Editor','2026-03-17 01:42:06','2026-03-20 09:14:49',NULL,NULL),(3,3,53,1,'Sensasi Speedboat Manta Ray: Cepat, Aman, dan Mengguncang Adrenalin','sensasi-speedboat-manta-ray-cepat-aman-dan-mengguncang-adrenalin','<p class=\"lead\" style=\"font-size: 1.1rem; color: #555;\">Waktu liburan Anda terlalu berharga untuk dihabiskan dalam perjalanan yang lambat. Speedboat bermesin ganda kami siap memotong waktu tempuh Anda ke pulau impian hingga separuhnya.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Mengapa Memilih Speedboat Modern?</h3>\n<p>Dirancang untuk membelah ombak dengan mulus, speedboat kelas atas ini menggunakan mesin berkekuatan ganda yang stabil. Sangat cocok bagi Anda (dan keluarga) yang mudah mabuk laut, karena waktu tempuh yang super singkat.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Spesifikasi yang Akan Anda Cintai:</h3>\n<ul style=\"list-style-type: disc; margin-left: 20px;\">\n<li style=\"margin-bottom: 8px;\"><strong>Kecepatan Maksimal:</strong> Menghemat hingga 40% waktu perjalanan dibanding kapal reguler.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Kabin Nyaman:</strong> Dilengkapi tempat duduk ergonomis yang mengurangi benturan ombak.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Standar Keselamatan Internasional:</strong> Navigasi modern, radio komunikasi vhf, dan <i>life jacket</i> berkualitas untuk setiap penumpang.</li>\n</ul>\n<h3 class=\"mt-5 mb-2\" style=\"font-weight:bold;\">Jangan Mau Menunggu Lama di Dermaga</h3>\n<p>Rencanakan perjalanan <i>island hopping</i> Anda berikutnya dengan penuh gaya dan kecepatan maksimal. Hindari antrean panjang kapal feri umum.</p>\n<div style=\"margin-top: 25px;\"><a href=\"/perahu\" style=\"background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;\">Sewa Speedboat Sekarang</a></div>','Go Fishi Editor','2026-03-17 01:42:06','2026-03-20 09:14:49',NULL,NULL),(4,3,53,2,'Sensasi Speedboat Manta Ray: Cepat, Aman, dan Mengguncang Adrenalin','sensasi-speedboat-manta-ray-cepat-aman-dan-mengguncang-adrenalin','<p class=\"lead\" style=\"font-size: 1.1rem; color: #555;\">Waktu liburan Anda terlalu berharga untuk dihabiskan dalam perjalanan yang lambat. Speedboat bermesin ganda kami siap memotong waktu tempuh Anda ke pulau impian hingga separuhnya.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Mengapa Memilih Speedboat Modern?</h3>\n<p>Dirancang untuk membelah ombak dengan mulus, speedboat kelas atas ini menggunakan mesin berkekuatan ganda yang stabil. Sangat cocok bagi Anda (dan keluarga) yang mudah mabuk laut, karena waktu tempuh yang super singkat.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Spesifikasi yang Akan Anda Cintai:</h3>\n<ul style=\"list-style-type: disc; margin-left: 20px;\">\n<li style=\"margin-bottom: 8px;\"><strong>Kecepatan Maksimal:</strong> Menghemat hingga 40% waktu perjalanan dibanding kapal reguler.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Kabin Nyaman:</strong> Dilengkapi tempat duduk ergonomis yang mengurangi benturan ombak.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Standar Keselamatan Internasional:</strong> Navigasi modern, radio komunikasi vhf, dan <i>life jacket</i> berkualitas untuk setiap penumpang.</li>\n</ul>\n<h3 class=\"mt-5 mb-2\" style=\"font-weight:bold;\">Jangan Mau Menunggu Lama di Dermaga</h3>\n<p>Rencanakan perjalanan <i>island hopping</i> Anda berikutnya dengan penuh gaya dan kecepatan maksimal. Hindari antrean panjang kapal feri umum.</p>\n<div style=\"margin-top: 25px;\"><a href=\"/perahu\" style=\"background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;\">Sewa Speedboat Sekarang</a></div>','Go Fishi Editor','2026-03-17 01:42:06','2026-03-20 09:14:49',NULL,NULL),(5,4,53,1,'Sensasi Speedboat Manta Ray: Cepat, Aman, dan Mengguncang Adrenalin','sensasi-speedboat-manta-ray-cepat-aman-dan-mengguncang-adrenalin','<p class=\"lead\" style=\"font-size: 1.1rem; color: #555;\">Waktu liburan Anda terlalu berharga untuk dihabiskan dalam perjalanan yang lambat. Speedboat bermesin ganda kami siap memotong waktu tempuh Anda ke pulau impian hingga separuhnya.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Mengapa Memilih Speedboat Modern?</h3>\n<p>Dirancang untuk membelah ombak dengan mulus, speedboat kelas atas ini menggunakan mesin berkekuatan ganda yang stabil. Sangat cocok bagi Anda (dan keluarga) yang mudah mabuk laut, karena waktu tempuh yang super singkat.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Spesifikasi yang Akan Anda Cintai:</h3>\n<ul style=\"list-style-type: disc; margin-left: 20px;\">\n<li style=\"margin-bottom: 8px;\"><strong>Kecepatan Maksimal:</strong> Menghemat hingga 40% waktu perjalanan dibanding kapal reguler.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Kabin Nyaman:</strong> Dilengkapi tempat duduk ergonomis yang mengurangi benturan ombak.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Standar Keselamatan Internasional:</strong> Navigasi modern, radio komunikasi vhf, dan <i>life jacket</i> berkualitas untuk setiap penumpang.</li>\n</ul>\n<h3 class=\"mt-5 mb-2\" style=\"font-weight:bold;\">Jangan Mau Menunggu Lama di Dermaga</h3>\n<p>Rencanakan perjalanan <i>island hopping</i> Anda berikutnya dengan penuh gaya dan kecepatan maksimal. Hindari antrean panjang kapal feri umum.</p>\n<div style=\"margin-top: 25px;\"><a href=\"/perahu\" style=\"background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;\">Sewa Speedboat Sekarang</a></div>','Go Fishi Editor','2026-03-17 01:42:06','2026-03-20 09:10:24',NULL,NULL),(6,4,53,2,'Sensasi Speedboat Manta Ray: Cepat, Aman, dan Mengguncang Adrenalin','sensasi-speedboat-manta-ray-cepat-aman-dan-mengguncang-adrenalin','<p class=\"lead\" style=\"font-size: 1.1rem; color: #555;\">Waktu liburan Anda terlalu berharga untuk dihabiskan dalam perjalanan yang lambat. Speedboat bermesin ganda kami siap memotong waktu tempuh Anda ke pulau impian hingga separuhnya.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Mengapa Memilih Speedboat Modern?</h3>\n<p>Dirancang untuk membelah ombak dengan mulus, speedboat kelas atas ini menggunakan mesin berkekuatan ganda yang stabil. Sangat cocok bagi Anda (dan keluarga) yang mudah mabuk laut, karena waktu tempuh yang super singkat.</p>\n<h3 class=\"mt-4 mb-2\" style=\"font-weight:bold;\">Spesifikasi yang Akan Anda Cintai:</h3>\n<ul style=\"list-style-type: disc; margin-left: 20px;\">\n<li style=\"margin-bottom: 8px;\"><strong>Kecepatan Maksimal:</strong> Menghemat hingga 40% waktu perjalanan dibanding kapal reguler.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Kabin Nyaman:</strong> Dilengkapi tempat duduk ergonomis yang mengurangi benturan ombak.</li>\n<li style=\"margin-bottom: 8px;\"><strong>Standar Keselamatan Internasional:</strong> Navigasi modern, radio komunikasi vhf, dan <i>life jacket</i> berkualitas untuk setiap penumpang.</li>\n</ul>\n<h3 class=\"mt-5 mb-2\" style=\"font-weight:bold;\">Jangan Mau Menunggu Lama di Dermaga</h3>\n<p>Rencanakan perjalanan <i>island hopping</i> Anda berikutnya dengan penuh gaya dan kecepatan maksimal. Hindari antrean panjang kapal feri umum.</p>\n<div style=\"margin-top: 25px;\"><a href=\"/perahu\" style=\"background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;\">Sewa Speedboat Sekarang</a></div>','Go Fishi Editor','2026-03-17 01:42:06','2026-03-20 09:10:24',NULL,NULL);
/*!40000 ALTER TABLE `blog_informations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'fishing_spot.png',NULL,1,1,'2026-03-17 01:41:37','2026-03-20 09:10:24'),(2,'luxury_yacht.png',NULL,1,1,'2026-03-17 01:42:06','2026-03-20 09:14:46'),(3,'speedboat.png',NULL,1,2,'2026-03-17 01:42:06','2026-03-20 09:14:46'),(4,'speedboat.png',NULL,1,3,'2026-03-17 01:42:06','2026-03-20 09:10:24');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boat_packages`
--

DROP TABLE IF EXISTS `boat_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boat_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int NOT NULL DEFAULT '1',
  `meeting_time` time DEFAULT NULL,
  `return_time` time DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `boat_packages_room_id_foreign` (`room_id`),
  CONSTRAINT `boat_packages_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boat_packages`
--

LOCK TABLES `boat_packages` WRITE;
/*!40000 ALTER TABLE `boat_packages` DISABLE KEYS */;
INSERT INTO `boat_packages` VALUES (21,6,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',15000000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(22,6,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',16500000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(23,6,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',28500000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(24,6,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',40500000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(25,7,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',12500000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(26,7,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',13750000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(27,7,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',23750000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(28,7,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',33750000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(29,8,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',11000000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(30,8,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',12100000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(31,8,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',20900000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(32,8,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',29700000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(33,9,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',8000000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(34,9,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',8800000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(35,9,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',15200000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(36,9,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',21600000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(37,10,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',25000000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(38,10,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',27500000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(39,10,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',47500000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(40,10,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',67500000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(41,11,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',4500000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(42,11,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',4950000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(43,11,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',8550000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(44,11,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',12150000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(45,12,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',2800000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(46,12,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',3080000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(47,12,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',5320000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(48,12,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',7560000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(49,13,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',2200000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(50,13,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',2420000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(51,13,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',4180000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(52,13,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',5940000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(53,14,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',7000000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(54,14,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',7700000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(55,14,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',13300000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(56,14,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',18900000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(57,15,'Paket 1 Hari – Harian','Trip seharian penuh dari pagi hingga sore. Cocok untuk pemancing harian yang ingin mengeksplorasi spot sekitar Jakarta dan Kepulauan Seribu.',1800000.00,1,'06:00:00','16:00:00','Perairan Jakarta – Kepulauan Seribu',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(58,15,'Paket 1 Hari – Malam','Trip malam hari (night fishing) untuk memburu ikan pelagis aktif di malam hari. Berangkat sore, kembali dini hari.',1980000.00,1,'17:00:00','04:00:00','Karang Dalam – Pulau Pramuka',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(59,15,'Paket 2 Hari 1 Malam','Petualangan memancing 2 hari 1 malam. Bermalam di kapal atau di pulau, menjelajahi spot terbaik yang lebih jauh dari dermaga.',3420000.00,2,'06:00:00','16:00:00','Kepulauan Seribu – P. Tidung / P. Pari',1,'2026-03-17 23:42:54','2026-03-17 23:42:54'),(60,15,'Paket 3 Hari 2 Malam','Paket ekspedisi memancing eksklusif 3 hari 2 malam. Jangkauan lebih jauh, spot lebih matang, untuk ikan-ikan trophy. Termasuk konsumsi selama trip.',4860000.00,3,'05:00:00','17:00:00','Kepulauan Seribu – P. Pramuka / P. Harapan',1,'2026-03-17 23:42:54','2026-03-17 23:42:54');
/*!40000 ALTER TABLE `boat_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_hours`
--

DROP TABLE IF EXISTS `booking_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hour` bigint DEFAULT NULL,
  `serial_number` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_hours`
--

LOCK TABLES `booking_hours` WRITE;
/*!40000 ALTER TABLE `booking_hours` DISABLE KEYS */;
INSERT INTO `booking_hours` VALUES (6,2,1,'2026-03-17 22:13:46','2026-03-17 22:13:46'),(7,6,2,'2026-03-17 22:13:46','2026-03-17 22:13:46'),(8,9,3,'2026-03-17 22:13:46','2026-03-17 22:13:46'),(9,12,4,'2026-03-17 22:13:46','2026-03-17 22:13:46');
/*!40000 ALTER TABLE `booking_hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `room_id` bigint DEFAULT NULL,
  `hotel_id` bigint DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_in_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `check_out_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_date_time` datetime DEFAULT NULL,
  `check_out_date_time` datetime DEFAULT NULL,
  `preparation_time` int NOT NULL DEFAULT '0',
  `next_booking_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hour` int NOT NULL DEFAULT '0',
  `adult` int NOT NULL DEFAULT '0',
  `children` int NOT NULL DEFAULT '0',
  `additional_service` text COLLATE utf8mb4_unicode_ci,
  `service_details` text COLLATE utf8mb4_unicode_ci,
  `total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `roomPrice` decimal(16,2) NOT NULL DEFAULT '0.00',
  `serviceCharge` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(16,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `currency_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` int NOT NULL DEFAULT '0',
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_address` text COLLATE utf8mb4_unicode_ci,
  `age_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (2,'GF260318063817Y71F',13,1,2,1,'2026-03-21',NULL,'2026-03-22',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,4800000.00,4800000.00,0.00,0.00,0.00,4800000.00,'IDR',NULL,'Rp',NULL,1,'pending',NULL,NULL,NULL,'Bank Transfer','offline','Dimas Mancing','dimas_mancing@gofishi.id','081242025131',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(3,'GF260318063817JBLZ',14,1,3,1,'2026-03-30',NULL,'2026-03-31',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,5200000.00,5200000.00,0.00,0.00,0.00,5200000.00,'IDR',NULL,'Rp',NULL,1,'pending',NULL,NULL,NULL,'Bank Transfer','offline','Eko Bahari','eko_bahari@gofishi.id','081292392844',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(6,'GF260318063817EXT7',10,1,6,2,'2026-03-26',NULL,'2026-03-27',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,15000000.00,15000000.00,0.00,0.00,0.00,15000000.00,'IDR',NULL,'Rp',NULL,1,'completed',NULL,NULL,NULL,'Bank Transfer','offline','Andi Jakarta','andi_jakarta@gofishi.id','081249201556',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(9,'GF260318063817DJ0S',10,1,9,2,'2026-03-31',NULL,'2026-04-01',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,8000000.00,8000000.00,0.00,0.00,0.00,8000000.00,'IDR',NULL,'Rp',NULL,1,'completed',NULL,NULL,NULL,'Bank Transfer','offline','Andi Jakarta','andi_jakarta@gofishi.id','081291103137',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(13,'GF260318063817Z0SB',11,1,13,3,'2026-03-31',NULL,'2026-04-01',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,2200000.00,2200000.00,0.00,0.00,0.00,2200000.00,'IDR',NULL,'Rp',NULL,1,'pending',NULL,NULL,NULL,'Bank Transfer','offline','Budiono Trip','budiono_trip@gofishi.id','081220195187',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(14,'GF260318063817HL8G',11,1,14,3,'2026-03-20',NULL,'2026-03-21',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,7000000.00,7000000.00,0.00,0.00,0.00,7000000.00,'IDR',NULL,'Rp',NULL,1,'pending',NULL,NULL,NULL,'Bank Transfer','offline','Budiono Trip','budiono_trip@gofishi.id','081298815763',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(15,'GF260318063817POOQ',14,1,15,3,'2026-03-26',NULL,'2026-03-27',NULL,NULL,NULL,0,NULL,0,0,0,NULL,NULL,1800000.00,1800000.00,0.00,0.00,0.00,1800000.00,'IDR',NULL,'Rp',NULL,1,'pending',NULL,NULL,NULL,'Bank Transfer','offline','Eko Bahari','eko_bahari@gofishi.id','081261903361',NULL,1,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(16,'260318075037YUF0',NULL,1,1,1,'2026-03-18','06:00:00','2026-03-18','06:00:00','2026-03-18 06:00:00','2026-03-18 06:00:00',0,'06:00:00',24,1,0,'null','[]',5500000.00,5500000.00,0.00,0.00,550000.00,6050000.00,'IDR','left','Rp','left',1,'approved',NULL,NULL,NULL,'Midtrans','online','Doddy Tri Kapisha','doddykapisha@gmail.com','085780881368','Jl. Petojo Utara VI No. 44 Rt. 014 Rw. 003, Petojo Utara, Kec. Gambir',1,'2026-03-18 00:50:37','2026-03-18 00:50:37'),(17,'260318154629WJNV',NULL,1,2,1,'2026-03-18','06:00:00','2026-03-18','16:00:00','2026-03-18 06:00:00','2026-03-18 16:00:00',0,'16:00:00',24,1,0,'\"11\"','[{\"price\":400000,\"service_name\":\"Makan di Kapal\"}]',5200000.00,4800000.00,400000.00,0.00,520000.00,5720000.00,'IDR','left','Rp','left',1,'approved',NULL,NULL,NULL,'Midtrans','online','Depo Hiwater Pusat','info@hiwater.id','081388883798','Gading Serpong, Ruko, Jl. Fluorite Timur Jl. Raya Kelapa Gading Utara blok FR No.47, West Pakulonan, Kelapa Dua, Tangerang Regency, Banten 15810, Indonesia',1,'2026-03-18 08:46:30','2026-03-18 08:46:30'),(18,'2603201506183A8G',NULL,1,6,2,'2026-03-20','06:00:00','2026-03-20','16:00:00','2026-03-20 06:00:00','2026-03-20 16:00:00',0,'16:00:00',24,1,0,'null','[]',15000000.00,15000000.00,0.00,0.00,0.00,15000000.00,'IDR','left','Rp','left',1,'approved',NULL,NULL,NULL,'Midtrans','online','Doddy Tri Kapisha','doddykapisha@gmail.com','085780881368','Jl. Petojo Utara VI No. 44 Rt. 014 Rw. 003, Petojo Utara, Kec. Gambir',1,'2026-03-20 08:06:18','2026-03-20 08:06:18');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `captain_galleries`
--

DROP TABLE IF EXISTS `captain_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `captain_galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Fish Species Name',
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Weight of the catch',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `captain_galleries_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `captain_galleries_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `captain_galleries`
--

LOCK TABLES `captain_galleries` WRITE;
/*!40000 ALTER TABLE `captain_galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `captain_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` bigint unsigned NOT NULL,
  `sender_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_chat_id_foreign` (`chat_id`),
  CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chats_user_id_foreign` (`user_id`),
  KEY `chats_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `chats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chats_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chats`
--

LOCK TABLES `chats` WRITE;
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;
/*!40000 ALTER TABLE `chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `state_id` bigint unsigned DEFAULT NULL,
  `feature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (26,1,9,4,'1773934657.webp','Jakarta Utara',1,'2026-03-19 08:37:37','2026-03-19 08:37:37');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collaborators`
--

DROP TABLE IF EXISTS `collaborators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `collaborators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'co-host',
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collaborators_vendor_id_user_id_unique` (`vendor_id`,`user_id`),
  KEY `collaborators_user_id_foreign` (`user_id`),
  CONSTRAINT `collaborators_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collaborators_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborators`
--

LOCK TABLES `collaborators` WRITE;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
/*!40000 ALTER TABLE `collaborators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookie_alert_contents`
--

DROP TABLE IF EXISTS `cookie_alert_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cookie_alert_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookie_alert_contents`
--

LOCK TABLES `cookie_alert_contents` WRITE;
/*!40000 ALTER TABLE `cookie_alert_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `cookie_alert_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookie_alerts`
--

DROP TABLE IF EXISTS `cookie_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cookie_alerts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `cookie_alert_status` tinyint unsigned NOT NULL DEFAULT '0',
  `cookie_alert_btn_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cookie_alert_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookie_alerts`
--

LOCK TABLES `cookie_alerts` WRITE;
/*!40000 ALTER TABLE `cookie_alerts` DISABLE KEYS */;
INSERT INTO `cookie_alerts` VALUES (5,1,0,'I Agree','We use cookies to give you the best online experience.','2026-03-18 09:51:21','2026-03-18 09:51:21'),(6,2,0,'I Agree','We use cookies to give you the best online experience.','2026-03-18 09:51:21','2026-03-18 09:51:21');
/*!40000 ALTER TABLE `cookie_alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counter_informations`
--

DROP TABLE IF EXISTS `counter_informations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `counter_informations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counter_informations`
--

LOCK TABLES `counter_informations` WRITE;
/*!40000 ALTER TABLE `counter_informations` DISABLE KEYS */;
/*!40000 ALTER TABLE `counter_informations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (9,1,'Indonesia',1,'2026-03-19 08:07:56','2026-03-19 08:07:56');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_section_contents`
--

DROP TABLE IF EXISTS `custom_section_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_section_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint NOT NULL,
  `custom_section_id` bigint NOT NULL,
  `section_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_section_contents`
--

LOCK TABLES `custom_section_contents` WRITE;
/*!40000 ALTER TABLE `custom_section_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_section_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_sections`
--

DROP TABLE IF EXISTS `custom_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_sections`
--

LOCK TABLES `custom_sections` WRITE;
/*!40000 ALTER TABLE `custom_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_categories`
--

DROP TABLE IF EXISTS `faq_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_categories`
--

LOCK TABLES `faq_categories` WRITE;
/*!40000 ALTER TABLE `faq_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_contents`
--

DROP TABLE IF EXISTS `faq_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_contents`
--

LOCK TABLES `faq_contents` WRITE;
/*!40000 ALTER TABLE `faq_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_informations`
--

DROP TABLE IF EXISTS `faq_informations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_informations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_informations`
--

LOCK TABLES `faq_informations` WRITE;
/*!40000 ALTER TABLE `faq_informations` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq_informations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (21,1,'How do I book a perahu for a few hours?','Simply select the desired date and time for your booking on our website or app. Choose the duration of your stay and complete the payment process.','2026-03-20 08:24:23','2026-03-20 08:24:23',1),(22,1,'Is hourly booking available for all lokasi perahu?','Hourly booking availability depends on the lokasi and perahu type. Some lokasi may offer hourly bookings for specific perahu or during certain hours of the day.','2026-03-20 08:24:23','2026-03-20 08:24:23',2);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feature_contents`
--

DROP TABLE IF EXISTS `feature_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feature_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_contents`
--

LOCK TABLES `feature_contents` WRITE;
/*!40000 ALTER TABLE `feature_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `feature_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `featured_hotel_charges`
--

DROP TABLE IF EXISTS `featured_hotel_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_hotel_charges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `days` bigint DEFAULT NULL,
  `price` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `featured_hotel_charges`
--

LOCK TABLES `featured_hotel_charges` WRITE;
/*!40000 ALTER TABLE `featured_hotel_charges` DISABLE KEYS */;
INSERT INTO `featured_hotel_charges` VALUES (1,100,150,'2024-12-01 20:32:06','2024-12-01 20:32:06'),(2,500,699,'2024-12-01 20:32:20','2024-12-01 20:32:43'),(3,700,799,'2024-12-01 20:32:36','2024-12-01 20:32:36'),(4,900,999,'2024-12-01 20:33:08','2024-12-01 20:33:08');
/*!40000 ALTER TABLE `featured_hotel_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `featured_room_charges`
--

DROP TABLE IF EXISTS `featured_room_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_room_charges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `days` bigint DEFAULT NULL,
  `price` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `featured_room_charges`
--

LOCK TABLES `featured_room_charges` WRITE;
/*!40000 ALTER TABLE `featured_room_charges` DISABLE KEYS */;
INSERT INTO `featured_room_charges` VALUES (1,100,99,'2024-12-01 20:35:00','2024-12-01 20:35:00'),(2,300,249,'2024-12-01 20:35:09','2024-12-01 20:35:34'),(3,500,419,'2024-12-01 20:35:26','2024-12-01 20:35:45'),(4,1000,799,'2024-12-01 20:35:59','2024-12-01 20:35:59');
/*!40000 ALTER TABLE `featured_room_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_contents`
--

DROP TABLE IF EXISTS `footer_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `about_company` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `copyright_text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `footer_texts_language_id_foreign` (`language_id`),
  CONSTRAINT `footer_texts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_contents`
--

LOCK TABLES `footer_contents` WRITE;
/*!40000 ALTER TABLE `footer_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `footer_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_quick_link_contents`
--

DROP TABLE IF EXISTS `footer_quick_link_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_quick_link_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_quick_link_contents`
--

LOCK TABLES `footer_quick_link_contents` WRITE;
/*!40000 ALTER TABLE `footer_quick_link_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `footer_quick_link_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_quick_links`
--

DROP TABLE IF EXISTS `footer_quick_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_quick_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_quick_links`
--

LOCK TABLES `footer_quick_links` WRITE;
/*!40000 ALTER TABLE `footer_quick_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `footer_quick_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_texts`
--

DROP TABLE IF EXISTS `footer_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_texts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_texts`
--

LOCK TABLES `footer_texts` WRITE;
/*!40000 ALTER TABLE `footer_texts` DISABLE KEYS */;
/*!40000 ALTER TABLE `footer_texts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `endpoint` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guests`
--

LOCK TABLES `guests` WRITE;
/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_sections`
--

DROP TABLE IF EXISTS `hero_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_sections`
--

LOCK TABLES `hero_sections` WRITE;
/*!40000 ALTER TABLE `hero_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `hero_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holidays` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `hotel_id` bigint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
INSERT INTO `holidays` VALUES (1,0,7,'2025-03-12','2025-01-03 21:07:52','2025-01-03 21:07:52'),(2,0,12,'2025-01-09','2025-01-03 21:08:00','2025-01-03 21:08:00'),(3,1,8,'2025-01-21','2025-01-03 21:08:17','2025-01-03 21:08:17'),(4,1,13,'2025-01-23','2025-01-03 21:08:24','2025-01-03 21:08:24'),(5,1,22,'2025-01-29','2025-01-03 21:08:30','2025-01-03 21:08:30'),(6,2,5,'2025-01-28','2025-01-03 21:08:39','2025-01-03 21:08:39'),(7,2,6,'2025-01-18','2025-01-03 21:08:45','2025-01-03 21:08:45'),(8,3,3,'2025-01-22','2025-01-03 21:08:53','2025-01-03 21:08:53'),(9,3,4,'2025-01-31','2025-01-03 21:09:00','2025-01-03 21:09:00'),(10,3,10,'2025-02-10','2025-01-03 21:09:08','2025-01-03 21:09:08'),(12,4,2,'2025-02-06','2025-01-03 21:09:26','2025-01-03 21:09:26'),(13,0,7,'2025-02-20','2025-01-04 02:55:04','2025-01-04 02:55:04'),(14,0,7,'2025-03-19','2025-01-04 02:55:29','2025-01-04 02:55:29'),(15,0,7,'2025-03-20','2025-01-04 02:55:39','2025-01-04 02:55:39'),(16,0,7,'2025-04-30','2025-01-04 02:55:49','2025-01-04 02:55:49'),(17,1,8,'2025-02-20','2025-01-04 02:56:11','2025-01-04 02:56:11'),(18,1,8,'2025-03-27','2025-01-04 02:56:19','2025-01-04 02:56:19');
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_categories`
--

DROP TABLE IF EXISTS `hotel_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_categories`
--

LOCK TABLES `hotel_categories` WRITE;
/*!40000 ALTER TABLE `hotel_categories` DISABLE KEYS */;
INSERT INTO `hotel_categories` VALUES (13,1,'Lokasi','lokasi',1,1,'2026-03-17 02:04:03','2026-03-17 02:04:03'),(17,1,'Destinasi Wisata','destinasi-wisata',1,2,'2026-03-18 10:37:12','2026-03-18 10:37:12');
/*!40000 ALTER TABLE `hotel_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_contents`
--

DROP TABLE IF EXISTS `hotel_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint DEFAULT NULL,
  `language_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `amenities` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `state_id` bigint unsigned DEFAULT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_contents`
--

LOCK TABLES `hotel_contents` WRITE;
/*!40000 ALTER TABLE `hotel_contents` DISABLE KEYS */;
INSERT INTO `hotel_contents` VALUES (3,2,1,'Pantai Mutiara Hub','pantai-mutiara-hub','Jjln pantai mutiara blok x,marina dermaga kapal,pluit penjaringan, RT.20/RW.17, Pluit, Penjaringan, Jakarta Utara, Daerah Khusus Ibukota Jakarta, Indonesia','[\"29\",\"31\",\"33\",\"35\",\"37\",\"39\"]','<p>Pantai Mutiara Hub merujuk pada area komersial atau pusat aktivitas di kawasan perumahan elit Pantai Mutiara, Pluit, Jakarta Utara. Kawasan ini merupakan area reklamasi yang menawarkan suasana eksklusif dengan pemandangan langsung ke Teluk Jakarta</p>\n<h2>Daya Tarik &amp; Aktivitas</h2>\n<p>Pemandangan Sunset: Kawasan ini dikenal sebagai salah satu titik terbaik di Jakarta untuk menikmati matahari terbenam dengan latar belakang laut dan gedung-gedung tinggi.<br />Wisata Kuliner: Selain Jetski Cafe, terdapat berbagai kafe dan restoran dengan suasana tepi pantai yang memberikan kesan seperti berada di luar negeri.<br />Olahraga Air: Pengunjung dapat menyewa peralatan untuk bermain jetski atau sekadar menikmati angin laut di area terbuka.</p>',NULL,NULL,13,NULL,NULL,NULL,'2026-03-17 23:38:16','2026-03-19 07:03:00'),(5,3,1,'Muara Angke Hub','muara-angke-hub','Pelabuhan Kali Adem Muara Angke, Pluit, Jakarta Utara, Daerah Khusus Ibukota Jakarta, Indonesia','[\"29\",\"31\",\"33\",\"35\",\"39\"]','<p>Dermaga strategis dengan akses luas ke perairan Kepulauan Seribu. Pilihan terbaik untuk trip harian dan paket hemat. Pelabuhan Muara Angke (termasuk Pelabuhan Kali Adem) saat ini berfungsi sebagai pusat transportasi utama menuju Kepulauan Seribu dan pusat ekonomi perikanan Jakarta. Pelabuhan ini telah bertransformasi menjadi terminal modern dengan fasilitas setara bandara</p>\n<h2>Layanan Transportasi &amp; Tiket</h2>\n<p>Pelabuhan ini melayani dua jenis transportasi utama menuju Kepulauan Seribu: <br />Kapal Dinas Perhubungan (Dishub): Kapal modern bersubsidi dengan sistem E-Ticketing. Pendaftaran tiket biasanya dilakukan secara daring atau di lokasi mulai pukul 05.00 WIB.<br />Kapal Tradisional: Kapal kayu yang dikelola masyarakat dengan kapasitas lebih besar.</p>',NULL,NULL,13,NULL,NULL,NULL,'2026-03-17 23:38:16','2026-03-19 05:59:18'),(7,4,1,'Marina Ancol Hub','marina-ancol-hub','Dermaga 16 Marina Ancol, Jalan Taman Marina, Ancol, Jakarta Utara, Daerah Khusus Ibukota Jakarta, Indonesia','[\"29\",\"31\",\"33\",\"37\",\"39\"]','<p>Dermaga 17 Marina Ancol adalah salah satu titik keberangkatan utama untuk kapal cepat (speedboat) dari Jakarta menuju berbagai destinasi di Kepulauan Seribu. Lokasi ini berada di dalam kawasan Taman Impian Jaya Ancol dan sering menjadi pusat aktivitas wisatawan yang ingin menyeberang dengan waktu tempuh lebih singkat dibandingkan dari pelabuhan lain</p>',NULL,NULL,17,9,4,26,'2026-03-19 10:25:43','2026-03-19 10:25:43');
/*!40000 ALTER TABLE `hotel_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_counter_contents`
--

DROP TABLE IF EXISTS `hotel_counter_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_counter_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned DEFAULT NULL,
  `hotel_counter_id` bigint unsigned DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_counter_contents`
--

LOCK TABLES `hotel_counter_contents` WRITE;
/*!40000 ALTER TABLE `hotel_counter_contents` DISABLE KEYS */;
INSERT INTO `hotel_counter_contents` VALUES (1,1,1,'Pembatalan Gratis','100%','2026-03-17 23:38:16','2026-03-17 23:38:16'),(2,2,1,'Free Cancellation','100%','2026-03-17 23:38:16','2026-03-17 23:38:16'),(3,1,2,'Trip Selesai','2500+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(4,2,2,'Trips Done','2500+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(5,1,3,'Armada Kapal','15+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(6,2,3,'Boat Fleets','15+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(7,1,4,'Dukungan','24/7','2026-03-17 23:38:16','2026-03-17 23:38:16'),(8,2,4,'Support','24/7','2026-03-17 23:38:16','2026-03-17 23:38:16'),(9,1,5,'Pembatalan Gratis','100%','2026-03-17 23:38:16','2026-03-17 23:38:16'),(10,2,5,'Free Cancellation','100%','2026-03-17 23:38:16','2026-03-17 23:38:16'),(11,1,6,'Trip Selesai','2500+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(12,2,6,'Trips Done','2500+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(13,1,7,'Armada Kapal','15+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(14,2,7,'Boat Fleets','15+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(15,1,8,'Dukungan','24/7','2026-03-17 23:38:16','2026-03-17 23:38:16'),(16,2,8,'Support','24/7','2026-03-17 23:38:16','2026-03-17 23:38:16'),(17,1,9,'Pembatalan Gratis','100%','2026-03-17 23:38:16','2026-03-17 23:38:16'),(18,2,9,'Free Cancellation','100%','2026-03-17 23:38:16','2026-03-17 23:38:16'),(19,1,10,'Trip Selesai','2500+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(20,2,10,'Trips Done','2500+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(21,1,11,'Armada Kapal','15+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(22,2,11,'Boat Fleets','15+','2026-03-17 23:38:16','2026-03-17 23:38:16'),(23,1,12,'Dukungan','24/7','2026-03-17 23:38:16','2026-03-17 23:38:16'),(24,2,12,'Support','24/7','2026-03-17 23:38:16','2026-03-17 23:38:16');
/*!40000 ALTER TABLE `hotel_counter_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_counters`
--

DROP TABLE IF EXISTS `hotel_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint unsigned DEFAULT NULL,
  `key` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_counters`
--

LOCK TABLES `hotel_counters` WRITE;
/*!40000 ALTER TABLE `hotel_counters` DISABLE KEYS */;
INSERT INTO `hotel_counters` VALUES (1,1,0,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(2,1,1,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(3,1,2,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(4,1,3,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(5,2,0,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(6,2,1,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(7,2,2,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(8,2,3,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(9,3,0,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(10,3,1,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(11,3,2,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(12,3,3,'2026-03-17 23:38:16','2026-03-17 23:38:16');
/*!40000 ALTER TABLE `hotel_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_faqs`
--

DROP TABLE IF EXISTS `hotel_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint unsigned NOT NULL,
  `language_id` bigint unsigned NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_faqs_hotel_id_foreign` (`hotel_id`),
  KEY `hotel_faqs_language_id_foreign` (`language_id`),
  CONSTRAINT `hotel_faqs_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_faqs_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_faqs`
--

LOCK TABLES `hotel_faqs` WRITE;
/*!40000 ALTER TABLE `hotel_faqs` DISABLE KEYS */;
INSERT INTO `hotel_faqs` VALUES (1,2,1,'Apakah Pantai Mutiara terbuka untuk umum?','Ya, kawasan ini terbuka untuk umum, namun perlu diingat bahwa ini merupakan kawasan perumahan elit, jadi pengunjung diharapkan menjaga ketertiban.',0,'2026-03-19 07:08:56','2026-03-19 07:08:56'),(2,2,1,'Apa saja aktivitas yang bisa dilakukan?','Pengunjung bisa menikmati pemandangan sunset Teluk Jakarta, berolahraga sore, jogging, bersepeda, atau nongkrong di kafe-kafe sekitar.',1,'2026-03-19 07:08:56','2026-03-19 07:08:56'),(3,2,1,'Apakah bisa snorkeling?','Fasilitas snorkeling tidak tersedia di Pantai Mutiara Pluit, Jakarta, berbeda dengan Pantai Mutiara di Trenggalek.',2,'2026-03-19 07:08:56','2026-03-19 07:08:56');
/*!40000 ALTER TABLE `hotel_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_features`
--

DROP TABLE IF EXISTS `hotel_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `vendor_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_features`
--

LOCK TABLES `hotel_features` WRITE;
/*!40000 ALTER TABLE `hotel_features` DISABLE KEYS */;
INSERT INTO `hotel_features` VALUES (1,11,1,'hourlyhaven123@gmail.com','6753c56dec7f7',999.00,'$','right','Paypal','online','completed','apporved',NULL,'1.pdf','900','2024-12-07','2027-05-26',NULL,'2024-12-06 21:47:57','2024-12-06 21:49:41'),(3,12,0,'azimahmed11041@gmail.com','6753c5bba140d',999.00,NULL,NULL,'flutterwave','online','completed','apporved',NULL,NULL,'900','2024-12-07','2027-05-26',NULL,'2024-12-06 21:49:15','2024-12-06 21:49:15'),(5,5,2,'comfortzonemanager454@gmail.com','6753c700ce6e2',999.00,'$','right','Paypal','online','completed','apporved',NULL,'5.pdf','900','2024-12-07','2027-05-26',NULL,'2024-12-06 21:54:40','2024-12-06 21:57:52'),(6,10,3,'quickresthouse123@gmail.com','6753c7339e916',999.00,'$','right','Paypal','online','completed','apporved',NULL,'6.pdf','900','2024-12-07','2027-05-26',NULL,'2024-12-06 21:55:31','2024-12-06 21:57:50');
/*!40000 ALTER TABLE `hotel_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_images`
--

DROP TABLE IF EXISTS `hotel_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_images`
--

LOCK TABLES `hotel_images` WRITE;
/*!40000 ALTER TABLE `hotel_images` DISABLE KEYS */;
INSERT INTO `hotel_images` VALUES (1,NULL,'69bbeedb9d668.jpg','2026-03-19 05:40:59','2026-03-19 05:40:59'),(2,NULL,'69bbf0813ba10.jpg','2026-03-19 05:48:01','2026-03-19 05:48:01'),(3,NULL,'69bbf0814f84f.jpg','2026-03-19 05:48:01','2026-03-19 05:48:01'),(4,NULL,'69bbf0816308b.jpg','2026-03-19 05:48:01','2026-03-19 05:48:01'),(5,NULL,'69bbf081752fb.jpg','2026-03-19 05:48:01','2026-03-19 05:48:01'),(6,3,'69bbf137d2dd2.jpg','2026-03-19 05:51:03','2026-03-19 05:56:52'),(7,3,'69bbf137e9179.jpg','2026-03-19 05:51:03','2026-03-19 05:56:52'),(8,3,'69bbf1380881b.jpg','2026-03-19 05:51:04','2026-03-19 05:56:52'),(9,3,'69bbf1381b67c.jpg','2026-03-19 05:51:04','2026-03-19 05:56:52'),(12,2,'69bc01b86ce90.jpg','2026-03-19 07:01:28','2026-03-19 07:03:00'),(13,2,'69bc01b88076f.jpg','2026-03-19 07:01:28','2026-03-19 07:03:00'),(14,2,'69bc01b89327c.jpg','2026-03-19 07:01:28','2026-03-19 07:03:00'),(15,2,'69bc029483b21.jpg','2026-03-19 07:05:08','2026-03-19 07:05:11'),(16,NULL,'69bc0cd84c244.jpg','2026-03-19 07:48:56','2026-03-19 07:48:56'),(17,NULL,'69bc0cd863d17.jpg','2026-03-19 07:48:56','2026-03-19 07:48:56'),(18,NULL,'69bc0cd877c95.jpg','2026-03-19 07:48:56','2026-03-19 07:48:56'),(19,NULL,'69bc0cd88d0a9.jpg','2026-03-19 07:48:56','2026-03-19 07:48:56'),(20,NULL,'69bc0d688882c.jpg','2026-03-19 07:51:20','2026-03-19 07:51:20'),(21,NULL,'69bc0d689cad7.jpg','2026-03-19 07:51:20','2026-03-19 07:51:20'),(22,NULL,'69bc0d68b050e.jpg','2026-03-19 07:51:20','2026-03-19 07:51:20'),(23,NULL,'69bc0d68c80bc.jpg','2026-03-19 07:51:20','2026-03-19 07:51:20'),(24,NULL,'69bc0f5b1a656.jpg','2026-03-19 07:59:39','2026-03-19 07:59:39'),(25,NULL,'69bc0f5b3042e.jpg','2026-03-19 07:59:39','2026-03-19 07:59:39'),(26,NULL,'69bc0f5b47cab.jpg','2026-03-19 07:59:39','2026-03-19 07:59:39'),(27,NULL,'69bc0f5b7887e.jpg','2026-03-19 07:59:39','2026-03-19 07:59:39'),(28,NULL,'69bc0fe65d816.jpg','2026-03-19 08:01:58','2026-03-19 08:01:58'),(29,NULL,'69bc0fe670e19.jpg','2026-03-19 08:01:58','2026-03-19 08:01:58'),(30,NULL,'69bc0fe6834fb.jpg','2026-03-19 08:01:58','2026-03-19 08:01:58'),(31,NULL,'69bc0fe695a1a.jpg','2026-03-19 08:01:58','2026-03-19 08:01:58'),(32,NULL,'69bc10409ad41.jpg','2026-03-19 08:03:28','2026-03-19 08:03:28'),(33,NULL,'69bc1040b3531.jpg','2026-03-19 08:03:28','2026-03-19 08:03:28'),(34,NULL,'69bc1040cc4c4.jpg','2026-03-19 08:03:28','2026-03-19 08:03:28'),(35,NULL,'69bc1040ed389.jpg','2026-03-19 08:03:28','2026-03-19 08:03:28'),(36,NULL,'69bc152568320.jpg','2026-03-19 08:24:21','2026-03-19 08:24:21'),(37,NULL,'69bc15257caa2.jpg','2026-03-19 08:24:21','2026-03-19 08:24:21'),(38,NULL,'69bc15258f388.jpg','2026-03-19 08:24:21','2026-03-19 08:24:21'),(39,NULL,'69bc1525a1da8.jpg','2026-03-19 08:24:21','2026-03-19 08:24:21'),(40,NULL,'69bc1864f0e63.jpg','2026-03-19 08:38:12','2026-03-19 08:38:12'),(41,NULL,'69bc186512795.jpg','2026-03-19 08:38:13','2026-03-19 08:38:13'),(42,NULL,'69bc186524f5b.jpg','2026-03-19 08:38:13','2026-03-19 08:38:13'),(43,NULL,'69bc186537573.jpg','2026-03-19 08:38:13','2026-03-19 08:38:13'),(46,NULL,'69bc1a025965a.jpg','2026-03-19 08:45:06','2026-03-19 08:45:06'),(47,NULL,'69bc1a026e808.jpg','2026-03-19 08:45:06','2026-03-19 08:45:06'),(48,NULL,'69bc1a0281b8d.jpg','2026-03-19 08:45:06','2026-03-19 08:45:06'),(49,NULL,'69bc1a0294121.jpg','2026-03-19 08:45:06','2026-03-19 08:45:06'),(50,NULL,'69bc1c1e6a8a3.jpg','2026-03-19 08:54:06','2026-03-19 08:54:06'),(51,NULL,'69bc1c1e7e30d.jpg','2026-03-19 08:54:06','2026-03-19 08:54:06'),(52,NULL,'69bc1c1e90987.jpg','2026-03-19 08:54:06','2026-03-19 08:54:06'),(53,NULL,'69bc1c1ea3626.jpg','2026-03-19 08:54:06','2026-03-19 08:54:06'),(54,NULL,'69bc20d220f77.jpg','2026-03-19 09:14:10','2026-03-19 09:14:10'),(55,NULL,'69bc20d238286.jpg','2026-03-19 09:14:10','2026-03-19 09:14:10'),(56,NULL,'69bc20d24a06e.jpg','2026-03-19 09:14:10','2026-03-19 09:14:10'),(57,NULL,'69bc20d25c7c9.jpg','2026-03-19 09:14:10','2026-03-19 09:14:10'),(58,NULL,'69bc25e68a79e.jpg','2026-03-19 09:35:50','2026-03-19 09:35:50'),(59,NULL,'69bc25e69ec86.jpg','2026-03-19 09:35:50','2026-03-19 09:35:50'),(60,NULL,'69bc25e6b364b.jpg','2026-03-19 09:35:50','2026-03-19 09:35:50'),(61,NULL,'69bc25e6c5b83.jpg','2026-03-19 09:35:50','2026-03-19 09:35:50'),(62,NULL,'69bc27ff51568.jpg','2026-03-19 09:44:47','2026-03-19 09:44:47'),(63,NULL,'69bc27ff64ef1.jpg','2026-03-19 09:44:47','2026-03-19 09:44:47'),(64,NULL,'69bc27ff77ab0.jpg','2026-03-19 09:44:47','2026-03-19 09:44:47'),(65,NULL,'69bc27ff8a29c.jpg','2026-03-19 09:44:47','2026-03-19 09:44:47'),(66,NULL,'69bc28ae4640e.jpg','2026-03-19 09:47:42','2026-03-19 09:47:42'),(67,NULL,'69bc28ae5a625.jpg','2026-03-19 09:47:42','2026-03-19 09:47:42'),(68,NULL,'69bc28ae6d6ee.jpg','2026-03-19 09:47:42','2026-03-19 09:47:42'),(69,NULL,'69bc28ae807e2.jpg','2026-03-19 09:47:42','2026-03-19 09:47:42'),(70,NULL,'69bc2996ec304.jpg','2026-03-19 09:51:34','2026-03-19 09:51:34'),(71,NULL,'69bc29970de32.jpg','2026-03-19 09:51:35','2026-03-19 09:51:35'),(72,NULL,'69bc299721a16.jpg','2026-03-19 09:51:35','2026-03-19 09:51:35'),(73,NULL,'69bc299735ea5.jpg','2026-03-19 09:51:35','2026-03-19 09:51:35'),(74,4,'69bc3169e806f.jpg','2026-03-19 10:24:57','2026-03-19 10:25:43'),(75,4,'69bc316a094d2.jpg','2026-03-19 10:24:58','2026-03-19 10:25:43'),(76,4,'69bc316a1bc1a.jpg','2026-03-19 10:24:58','2026-03-19 10:25:43'),(77,4,'69bc316a2deca.jpg','2026-03-19 10:24:58','2026-03-19 10:25:43');
/*!40000 ALTER TABLE `hotel_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_wishlists`
--

DROP TABLE IF EXISTS `hotel_wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `hotel_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_wishlists`
--

LOCK TABLES `hotel_wishlists` WRITE;
/*!40000 ALTER TABLE `hotel_wishlists` DISABLE KEYS */;
INSERT INTO `hotel_wishlists` VALUES (6,1,22,'2025-01-04 00:02:32','2025-01-04 00:02:32'),(7,1,14,'2025-01-04 00:02:36','2025-01-04 00:02:36'),(8,1,10,'2025-01-04 00:02:39','2025-01-04 00:02:39'),(13,4,12,'2025-01-04 03:29:28','2025-01-04 03:29:28'),(14,4,21,'2025-01-04 03:29:31','2025-01-04 03:29:31'),(15,4,14,'2025-01-04 03:29:35','2025-01-04 03:29:35');
/*!40000 ALTER TABLE `hotel_wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `stars` int NOT NULL DEFAULT '0',
  `average_rating` double NOT NULL DEFAULT '0',
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_price` double DEFAULT '0',
  `min_price` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotels`
--

LOCK TABLES `hotels` WRITE;
/*!40000 ALTER TABLE `hotels` DISABLE KEYS */;
INSERT INTO `hotels` VALUES (2,1,1,5,5,'-6.0970828','106.7963999','2026-03-17 23:38:16','2026-03-19 07:08:56',0,'1773929336.webp',1000,0),(3,1,1,5,5,'-6.105106999999999','106.77171','2026-03-17 23:38:16','2026-03-19 05:56:52',0,'1773925012.jpg',1000,0),(4,1,1,5,0,'-6.120833699999999','106.829418','2026-03-19 10:25:43','2026-03-19 10:25:43',0,'1773941143.jpg',0,0);
/*!40000 ALTER TABLE `hotels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hourly_room_prices`
--

DROP TABLE IF EXISTS `hourly_room_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hourly_room_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `hotel_id` bigint DEFAULT NULL,
  `room_id` bigint DEFAULT NULL,
  `hour_id` bigint DEFAULT NULL,
  `hour` int DEFAULT NULL,
  `price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hourly_room_prices`
--

LOCK TABLES `hourly_room_prices` WRITE;
/*!40000 ALTER TABLE `hourly_room_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `hourly_room_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr',
  `is_default` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Indonesian','id','ltr',1,'2026-03-15 04:39:37','2026-03-15 04:39:37',0);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lokasi`
--

DROP TABLE IF EXISTS `lokasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `average_rating` double DEFAULT '0',
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` bigint DEFAULT NULL,
  `min_price` double DEFAULT '0',
  `max_price` double DEFAULT '0',
  `stars` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lokasi`
--

LOCK TABLES `lokasi` WRITE;
/*!40000 ALTER TABLE `lokasi` DISABLE KEYS */;
INSERT INTO `lokasi` VALUES (1,4,'1735365636.png',0,'23.8758547','90.3795438',1,30,150,4,'2024-12-01 00:40:30','2025-05-11 21:53:51'),(2,4,'1735365656.png',0,'23.8937796','90.4038963',1,100,1200,5,'2024-12-01 20:50:41','2025-05-11 21:54:05'),(3,3,'1735365699.png',4,'23.7936706','90.4066082',1,50,220,4,'2024-12-01 21:08:50','2025-05-11 21:54:33'),(4,3,'1735365713.png',0,'23.7374702','90.40879149999999',1,60,270,4,'2024-12-01 21:15:54','2025-05-11 21:54:49'),(5,2,'1735365724.png',0,'23.7461495','90.3742307',1,45,190,5,'2024-12-01 21:25:49','2025-05-11 21:55:09'),(6,2,'1735365733.png',0,'23.9905079','90.3877184',1,200,900,2,'2024-12-01 21:31:22','2025-05-11 21:55:24'),(7,0,'1735365742.png',5,'23.7339483','90.3929252',1,80,300,3,'2024-12-01 21:41:54','2025-05-11 21:55:39'),(8,1,'1735365754.png',0,'23.737588','90.40128999999999',1,55,225,5,'2024-12-01 22:02:34','2025-05-11 21:55:54'),(9,0,'1735365766.png',0,'23.7493571','90.4089838',1,90,350,5,'2024-12-01 22:24:27','2025-05-11 21:56:08'),(10,3,'1735365776.png',3.5,'23.697179','90.5073176',1,25,100,4,'2024-12-01 23:12:49','2025-05-11 21:56:24'),(11,1,'1735365786.png',0,'23.8518479','90.4080911',1,120,430,4,'2024-12-01 23:24:06','2025-05-11 21:56:38'),(12,0,'1735365797.png',0,'23.8311224','90.4243013',1,150,550,5,'2024-12-01 23:35:18','2025-05-11 21:56:53'),(13,1,'1735365809.png',0,'23.8773155','90.39013969999999',1,20,180,1,'2024-12-01 23:45:43','2025-05-11 21:57:15'),(14,1,'1735365819.png',2,'23.8712581','90.39960239999999',1,50,240,5,'2024-12-01 23:59:54','2025-05-11 21:57:31'),(15,0,'1735365831.png',0,'23.701278','90.3974862',1,300,1500,1,'2024-12-02 00:31:25','2025-05-11 21:57:43'),(21,0,'1735703717.png',0,'23.7329724','90.417231',1,50,220,3,'2024-12-31 21:55:17','2025-05-11 21:58:08'),(22,1,'1735704191.png',0,'23.7397263','90.394261',1,250,750,5,'2024-12-31 22:03:11','2025-05-11 21:58:22'),(23,3,'1735705103.png',0,'23.7345674','90.37601629999999',1,200,550,4,'2024-12-31 22:18:23','2025-05-11 21:58:35');
/*!40000 ALTER TABLE `lokasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_templates`
--

DROP TABLE IF EXISTS `mail_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mail_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mail_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_templates`
--

LOCK TABLES `mail_templates` WRITE;
/*!40000 ALTER TABLE `mail_templates` DISABLE KEYS */;
INSERT INTO `mail_templates` VALUES (1,'document_verification_approved','Your Onboarding Documents have been Approved','<p>Hello {username},</p><p>Great news! Your onboarding documents have been approved by our team. You can now start managing your perahu and locations.</p><p>Best regards,<br>{website_title}</p>',NULL,NULL),(2,'document_verification_rejected','Action Required: Your Onboarding Documents were Rejected','<p>Hello {username},</p><p>We regret to inform you that your onboarding documents were rejected for the following reason:</p><p><strong>{rejection_reason}</strong></p><p>Please log in to your dashboard and re-upload the corrected documents.</p><p>Best regards,<br>{website_title}</p>',NULL,NULL);
/*!40000 ALTER TABLE `mail_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `package_id` bigint DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `price` double DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `is_trial` tinyint NOT NULL DEFAULT '0',
  `trial_days` int NOT NULL DEFAULT '0',
  `receipt` longtext COLLATE utf8mb4_unicode_ci,
  `transaction_details` longtext COLLATE utf8mb4_unicode_ci,
  `settings` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberships`
--

LOCK TABLES `memberships` WRITE;
/*!40000 ALTER TABLE `memberships` DISABLE KEYS */;
INSERT INTO `memberships` VALUES (1,1,3,'2026-03-19','2126-03-19',29.99,'IDR','Rp','System Update','69bbdbe033995',1,0,0,NULL,NULL,'{\"id\":3,\"title\":\"Platinum\",\"slug\":\"platinum\",\"price\":29.99,\"icon\":\"fas fa-gift iconpicker-component\",\"term\":\"monthly\",\"number_of_hotel\":10,\"number_of_room\":10,\"recommended\":0,\"number_of_images_per_hotel\":10,\"number_of_images_per_room\":10,\"number_of_amenities_per_hotel\":10,\"number_of_amenities_per_room\":10,\"number_of_bookings\":999999,\"custom_features\":null,\"status\":1,\"features\":\"[\\\"Add Booking From Dashboard\\\",\\\"Edit Booking From Dashboard\\\",\\\"Support Tickets\\\"]\",\"created_at\":\"2024-12-01T04:53:20.000000Z\",\"updated_at\":\"2024-12-01T04:59:40.000000Z\",\"serial_number\":0}','2026-03-19 04:20:00','2026-03-19 04:20:00',0),(2,2,3,'2026-03-19','2126-03-19',29.99,'IDR','Rp','System Update','69bbdbe034e25',1,0,0,NULL,NULL,'{\"id\":3,\"title\":\"Platinum\",\"slug\":\"platinum\",\"price\":29.99,\"icon\":\"fas fa-gift iconpicker-component\",\"term\":\"monthly\",\"number_of_hotel\":10,\"number_of_room\":10,\"recommended\":0,\"number_of_images_per_hotel\":10,\"number_of_images_per_room\":10,\"number_of_amenities_per_hotel\":10,\"number_of_amenities_per_room\":10,\"number_of_bookings\":999999,\"custom_features\":null,\"status\":1,\"features\":\"[\\\"Add Booking From Dashboard\\\",\\\"Edit Booking From Dashboard\\\",\\\"Support Tickets\\\"]\",\"created_at\":\"2024-12-01T04:53:20.000000Z\",\"updated_at\":\"2024-12-01T04:59:40.000000Z\",\"serial_number\":0}','2026-03-19 04:20:00','2026-03-19 04:20:00',0),(3,3,3,'2026-03-19','2126-03-19',29.99,'IDR','Rp','System Update','69bbdbe0356b9',1,0,0,NULL,NULL,'{\"id\":3,\"title\":\"Platinum\",\"slug\":\"platinum\",\"price\":29.99,\"icon\":\"fas fa-gift iconpicker-component\",\"term\":\"monthly\",\"number_of_hotel\":10,\"number_of_room\":10,\"recommended\":0,\"number_of_images_per_hotel\":10,\"number_of_images_per_room\":10,\"number_of_amenities_per_hotel\":10,\"number_of_amenities_per_room\":10,\"number_of_bookings\":999999,\"custom_features\":null,\"status\":1,\"features\":\"[\\\"Add Booking From Dashboard\\\",\\\"Edit Booking From Dashboard\\\",\\\"Support Tickets\\\"]\",\"created_at\":\"2024-12-01T04:53:20.000000Z\",\"updated_at\":\"2024-12-01T04:59:40.000000Z\",\"serial_number\":0}','2026-03-19 04:20:00','2026-03-19 04:20:00',0),(4,4,3,'2026-03-19','2126-03-19',29.99,'IDR','Rp','System Update','69bbdbe035fa1',1,0,0,NULL,NULL,'{\"id\":3,\"title\":\"Platinum\",\"slug\":\"platinum\",\"price\":29.99,\"icon\":\"fas fa-gift iconpicker-component\",\"term\":\"monthly\",\"number_of_hotel\":10,\"number_of_room\":10,\"recommended\":0,\"number_of_images_per_hotel\":10,\"number_of_images_per_room\":10,\"number_of_amenities_per_hotel\":10,\"number_of_amenities_per_room\":10,\"number_of_bookings\":999999,\"custom_features\":null,\"status\":1,\"features\":\"[\\\"Add Booking From Dashboard\\\",\\\"Edit Booking From Dashboard\\\",\\\"Support Tickets\\\"]\",\"created_at\":\"2024-12-01T04:53:20.000000Z\",\"updated_at\":\"2024-12-01T04:59:40.000000Z\",\"serial_number\":0}','2026-03-19 04:20:00','2026-03-19 04:20:00',0);
/*!40000 ALTER TABLE `memberships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_builders`
--

DROP TABLE IF EXISTS `menu_builders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_builders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `menus` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_builders`
--

LOCK TABLES `menu_builders` WRITE;
/*!40000 ALTER TABLE `menu_builders` DISABLE KEYS */;
INSERT INTO `menu_builders` VALUES (7,20,'[{\"text\":\"Beranda\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"Lokasi\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"lokasi\"},{\"text\":\"Perahu\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"perahu\"},{\"type\":\"vendors\",\"text\":\"Kapten\",\"target\":\"_self\"},{\"text\":\"Pricing\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pricing\"},{\"text\":\"FAQ\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"Kontak\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"},{\"text\":\"Pages\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"Blog\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"About Us\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"about-us\"},{\"text\":\"Terms & Condition\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"terms-&-condition\"},{\"text\":\"Privacy Policy\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"privacy-policy\"}]}]','2023-08-17 03:19:12','2024-12-09 21:37:55'),(8,21,'[{\"text\":\"\\u0628\\u064a\\u062a\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"\\u0627\\u0644\\u0641\\u0646\\u0627\\u062f\\u0642\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"lokasi\"},{\"text\":\"\\u0627\\u0644\\u063a\\u0631\\u0641\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"perahu\"},{\"text\":\"\\u0627\\u0644\\u0628\\u0627\\u0639\\u0629\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendors\"},{\"text\":\"\\u0627\\u0644\\u062a\\u0633\\u0639\\u064a\\u0631\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pricing\"},{\"text\":\"\\u0627\\u0644\\u062a\\u0639\\u0644\\u064a\\u0645\\u0627\\u062a\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"\\u0627\\u062a\\u0635\\u0627\\u0644\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"},{\"type\":\"custom\",\"text\":\"\\u0627\\u0644\\u0635\\u0641\\u062d\\u0627\\u062a\",\"href\":\"\",\"target\":\"_self\",\"children\":[{\"type\":\"blog\",\"text\":\"\\u0645\\u062f\\u0648\\u0646\\u0629\",\"target\":\"_self\"},{\"type\":\"about-us\",\"text\":\"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0646\\u0627\",\"target\":\"_self\"},{\"type\":\"\\u0627\\u0644\\u0623\\u062d\\u0643\\u0627\\u0645-\\u0648\\u0627\\u0644\\u0634\\u0631\\u0648\\u0637\",\"text\":\"\\u0627\\u0644\\u0623\\u062d\\u0643\\u0627\\u0645 \\u0648\\u0627\\u0644\\u0634\\u0631\\u0648\\u0637\",\"target\":\"_self\"},{\"type\":\"\\u0633\\u064a\\u0627\\u0633\\u0629-\\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629\",\"text\":\"\\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629\",\"target\":\"_self\"}]}]','2023-08-17 03:19:32','2025-01-03 22:00:25'),(67,1,'[{\"text\":\"Beranda\",\"href\":\"https:\\/\\/gofishi.com\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"Lokasi\",\"href\":\"https:\\/\\/gofishi.com\\/lokasi\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"hotels\"},{\"text\":\"Perahu\",\"href\":\"https:\\/\\/gofishi.com\\/perahu\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"rooms\"},{\"text\":\"Kapten\",\"href\":\"https:\\/\\/gofishi.com\\/vendors\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendors\"},{\"type\":\"faq\",\"text\":\"FAQ\",\"target\":\"_self\"},{\"text\":\"Blog\",\"href\":\"https:\\/\\/gofishi.com\\/blogs\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"type\":\"contact\",\"text\":\"Kontak\",\"target\":\"_self\"}]',NULL,'2026-03-18 10:08:58'),(68,2,'[{\"text\":\"Beranda\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"Lokasi\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"hotels\"},{\"text\":\"Perahu\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"rooms\"},{\"text\":\"Kapten\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendors\"},{\"text\":\"Blog\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"FAQ\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"Kontak\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]',NULL,NULL);
/*!40000 ALTER TABLE `menu_builders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2020_01_01_000000_create_base_user_tables',1),(3,'2020_01_01_000000_create_languages_table',1),(4,'2020_01_01_000000_create_rooms_table',1),(5,'2020_01_01_000000_master_baseline_schema',1),(6,'2020_01_01_000001_create_withdraw_tables',1),(7,'2020_01_01_000002_create_bookings_table',1),(8,'2020_01_01_000003_create_gateways_table',1),(9,'2020_01_01_000004_create_mail_templates_table',1),(10,'2020_01_01_000005_create_vendor_infos_table',1),(11,'2020_01_01_000006_create_blogs_table',1),(12,'2020_01_01_000007_create_basic_settings_table',1),(13,'2020_01_01_000008_create_reviews_table',1),(14,'2020_01_01_000009_create_remaining_demo_tables',1),(15,'2020_01_01_000010_create_permission_tables',1),(16,'2020_01_01_000011_create_seo_table',1),(17,'2020_01_01_000012_create_custom_sections_table',1),(18,'2020_01_01_000013_create_more_demo_tables',1),(19,'2020_01_01_000014_create_location_tables',1),(20,'2020_01_01_000015_create_testimonial_tables',1),(21,'2021_02_01_030511_create_payment_invoices_table',1),(22,'2024_10_29_052622_section_contents',1),(23,'2026_02_27_000000_fix_rooms_missing_columns',1),(24,'2026_02_27_000001_fix_bookings_missing_columns',1),(25,'2026_02_27_000002_fix_vendors_missing_columns',1),(26,'2026_02_27_185642_add_perahu_facilities_text_to_rooms_table',1),(27,'2026_02_27_185642_create_perahu_daily_rates_table',1),(28,'2026_02_27_200730_add_daily_prices_and_remove_facilities_from_rooms_table',1),(29,'2026_02_27_200730_drop_perahu_daily_rates_table',1),(30,'2026_02_27_202557_add_daily_times_to_rooms_table',1),(31,'2026_02_27_235000_expand_withdraw_payment_limits',1),(32,'2026_02_27_235500_expand_booking_amounts',1),(33,'2026_03_10_232132_create_chats_table',1),(34,'2026_03_10_232138_create_chat_messages_table',1),(35,'2026_03_10_232142_create_user_reviews_table',1),(36,'2026_03_10_233750_add_boat_length_and_width_to_rooms_table',1),(37,'2026_03_11_024650_add_video_url_to_rooms_table',1),(38,'2026_03_12_205619_update_midtrans_label_in_online_gateways',1),(39,'2026_03_13_102747_add_rejection_reason_to_vendors_table',1),(40,'2026_03_13_102922_add_document_verification_mail_templates',1),(41,'2026_03_14_043938_add_fishing_fields_to_vendor_infos',1),(42,'2026_03_14_044111_create_captain_galleries_table',1),(43,'2026_03_14_044909_add_video_url_to_blogs_table',1),(44,'2026_03_14_125959_add_documents_to_vendors_table',1),(45,'2026_03_14_130000_rename_document_columns_in_vendors_table',1),(46,'2026_03_14_175824_add_whatsapp_fields_to_vendors_table',1),(47,'2026_03_14_175830_add_whatsapp_toggle_to_basic_settings_table',1),(48,'2026_03_14_192752_add_fishing_boat_specifications_to_rooms_table',1),(49,'2026_03_14_194234_add_reply_to_room_reviews_table',1),(50,'2026_03_14_213005_create_collaborators_table',1),(51,'2026_03_15_070435_create_cache_table',1),(52,'2026_03_15_070435_create_sessions_table',1),(53,'2026_03_15_094138_add_dob_to_users_and_vendors_tables',1),(54,'2026_03_15_095838_add_package_areas_to_rooms_table',1),(55,'2026_03_15_095853_add_booking_and_deposit_settings_to_rooms_table',1),(56,'2026_03_15_101900_add_age_confirmed_to_bookings_table',1),(57,'2026_03_15_105823_add_information_to_online_gateways',1),(58,'2026_03_15_134401_add_nama_km_to_rooms_table',2),(59,'2026_03_15_134402_add_bedroom_and_toilet_counts_to_rooms_table',2),(60,'2026_03_15_141429_create_wishlists_table',3),(61,'2026_03_15_144105_add_socialite_fields_to_users_table',4),(62,'2026_03_15_165058_add_boat_booking_fields_to_bookings_table',5),(63,'2026_03_15_165856_add_rejection_reason_to_bookings_table',6),(64,'2026_03_15_204327_add_total_earning_to_basic_settings_table',7),(65,'2026_03_15_204418_add_amount_to_vendors_table',8),(66,'2026_03_15_204722_fix_transactions_table_schema',9),(67,'2026_03_15_205303_rename_age_confirmation_in_bookings_table',10),(68,'2026_03_16_021152_add_columns_to_memberships_table',11),(69,'2026_03_16_061508_remove_google_map_api_key_from_basic_settings_table',12),(70,'2026_03_16_094024_create_boat_packages_table',13),(71,'2026_03_17_064259_add_hotel_view_to_basic_settings_table',14),(72,'2026_03_17_070547_add_analytics_to_basic_settings_table',15),(73,'2026_03_17_071628_add_ai_google_gemini_settings_to_basic_settings_table',16),(74,'2026_03_17_085333_add_gemini_prompt_to_basic_settings',17),(75,'2026_03_17_090325_add_slug_to_categories_tables',18),(76,'2026_03_17_103327_fix_location_tables_schema',19),(77,'2026_03_17_103849_fix_offline_gateways_table_schema',20),(78,'2026_03_17_122909_sync_location_and_boat_contents_schema',21),(79,'2026_03_17_130904_sync_hotel_counters_table',22),(80,'2026_03_17_131048_add_hotel_id_to_room_reviews',23),(81,'2026_03_17_155053_add_whatsapp_api_column_to_basic_settings_table',24),(82,'2026_03_17_155253_add_17_plus_validation_column_to_vendors_table',25),(83,'2026_03_18_034310_add_captain_name_to_rooms_table',26),(84,'2026_03_18_041621_fix_advertisements_table_schema',27),(85,'2026_03_18_042149_fix_basic_settings_missing_columns',28),(86,'2026_03_18_043547_fix_seos_table_missing_columns',29),(87,'2026_03_18_044433_complete_basic_settings_schema_fix',30),(88,'2026_03_18_045609_add_photo_to_vendors_table',31),(89,'2026_03_18_050000_fix_vendor_critical_tables',32),(90,'2026_03_18_060000_fix_remaining_missing_columns',33),(91,'2026_03_18_070000_fix_vendor_info_columns',34),(92,'2026_03_18_000001_add_missing_checkout_columns',35),(93,'2026_03_18_165110_repair_cookie_alerts_table',36),(94,'2026_03_18_165312_align_basic_settings_table',37),(95,'2026_03_18_165547_align_remaining_basic_settings_table',38),(96,'2026_03_18_172805_fix_blog_categories_table',39),(97,'2026_03_18_175025_add_meta_columns_to_blog_informations_table',40),(98,'2026_03_19_125455_add_missing_columns_to_hotels_table',41),(99,'2026_03_19_135245_create_hotel_faqs_table',42),(100,'2026_03_20_152329_add_columns_to_faqs_table',43),(101,'2026_03_24_063601_fix_custom_pages_table_schema',44);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offline_gateways`
--

DROP TABLE IF EXISTS `offline_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offline_gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `instructions` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 -> gateway is deactive, 1 -> gateway is active.',
  `has_attachment` tinyint NOT NULL DEFAULT '0' COMMENT '0 -> do not need attachment, 1 -> need attachment.',
  `serial_number` mediumint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offline_gateways`
--

LOCK TABLES `offline_gateways` WRITE;
/*!40000 ALTER TABLE `offline_gateways` DISABLE KEYS */;
INSERT INTO `offline_gateways` VALUES (3,'Bank Transfer','Transfer bank manual','Transfer ke Rekening XXXX',1,1,1,'2026-03-17 03:39:53','2026-03-18 09:44:17');
/*!40000 ALTER TABLE `offline_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `online_gateways`
--

DROP TABLE IF EXISTS `online_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `online_gateways` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_gateways`
--

LOCK TABLES `online_gateways` WRITE;
/*!40000 ALTER TABLE `online_gateways` DISABLE KEYS */;
INSERT INTO `online_gateways` VALUES (1,'Paypal','paypal','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(2,'Razorpay','razorpay','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(3,'Instamojo','instamojo','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(4,'Paystack','paystack','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(5,'Flutterwave','flutterwave','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(6,'Mollie','mollie','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(7,'Mercadopago','mercadopago','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(8,'Paytm','paytm','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(9,'Midtrans','midtrans','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"G308908288\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"SB-Mid-server-esXrj6dfGc6jD9vPaf9jhUO3\",\"client_key\":\"SB-Mid-client-1-AgZSBNWAoF0mqJ\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1,\"midtrans_mode\":1}',1,NULL,NULL),(10,'Stripe','stripe','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(11,'Iyzico','iyzico','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(12,'Authorize net','authorize.net','{\"client_id\":\"dummy\",\"client_secret\":\"dummy\",\"sandbox_status\":1,\"key\":\"dummy\",\"secret\":\"dummy\",\"token\":\"dummy\",\"public_key\":\"dummy\",\"secret_key\":\"dummy\",\"api_key\":\"dummy\",\"access_token\":\"dummy\",\"merchant_id\":\"dummy\",\"merchant_key\":\"dummy\",\"website\":\"dummy\",\"industry_type\":\"dummy\",\"channel\":\"dummy\",\"server_key\":\"dummy\",\"client_key\":\"dummy\",\"is_production\":0,\"login_id\":\"dummy\",\"transaction_key\":\"dummy\",\"sandbox_check\":1}',0,NULL,NULL),(31,'Paytabs','paytabs','{\"server_key\":\"1\",\"profile_id\":\"1\",\"country\":\"global\",\"api_endpoint\":\"1\"}',0,NULL,NULL),(32,'Toyyibpay','toyyibpay','{\"sandbox_status\":\"0\",\"secret_key\":\"1\",\"category_code\":\"1\"}',0,NULL,NULL),(33,'Phonepe','phonepe','{\"merchant_id\":\"1\",\"sandbox_status\":\"0\",\"salt_key\":\"1\",\"salt_index\":\"1\"}',0,NULL,NULL),(34,'Yoco','yoco','{\"secret_key\":\"1\"}',0,NULL,NULL),(35,'Myfatoorah','myfatoorah','{\"token\":\"1\",\"sandbox_status\":\"0\"}',0,NULL,NULL),(36,'Xendit','xendit','{\"secret_key\":\"1\"}',0,NULL,NULL),(37,'Perfect Money','perfect_money','{\"perfect_money_wallet_id\":\"1\"}',0,NULL,NULL);
/*!40000 ALTER TABLE `online_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `term` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `number_of_hotel` int DEFAULT '0',
  `number_of_room` int DEFAULT '0',
  `recommended` int DEFAULT NULL,
  `number_of_images_per_hotel` int DEFAULT '0',
  `number_of_images_per_room` int DEFAULT '0',
  `number_of_amenities_per_hotel` int NOT NULL DEFAULT '0',
  `number_of_amenities_per_room` int NOT NULL DEFAULT '0',
  `number_of_bookings` int NOT NULL DEFAULT '0',
  `custom_features` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `status` int NOT NULL DEFAULT '1',
  `features` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,'Silver','silver',9,'fas fa-gift iconpicker-component','monthly',3,3,0,3,3,3,3,100,NULL,1,'[\"Add Booking From Dashboard\"]','2024-11-30 21:51:04','2024-11-30 22:00:29',0),(2,'Gold','gold',19.99,'fas fa-gift iconpicker-component','monthly',5,5,1,5,5,5,5,500,NULL,1,'[\"Add Booking From Dashboard\",\"Edit Booking From Dashboard\"]','2024-11-30 21:52:19','2024-11-30 22:00:38',0),(3,'Platinum','platinum',29.99,'fas fa-gift iconpicker-component','monthly',999999,999999,0,999999,999999,999999,999999,999999,NULL,1,'[\"Add Booking From Dashboard\",\"Edit Booking From Dashboard\",\"Support Tickets\"]','2024-11-30 21:53:20','2026-03-19 05:16:55',0),(4,'Silver','silver',99,'fas fa-gift iconpicker-component','yearly',3,3,0,3,3,3,3,100,NULL,1,'[\"Add Booking From Dashboard\"]','2024-11-30 21:51:04','2024-11-30 22:04:34',0),(5,'Gold','gold',199,'fas fa-gift iconpicker-component','yearly',5,5,1,5,5,5,5,500,NULL,1,'[\"Add Booking From Dashboard\",\"Edit Booking From Dashboard\"]','2024-11-30 21:52:19','2024-11-30 22:00:48',0),(6,'Platinum','platinum',299,'fas fa-gift iconpicker-component','yearly',10,10,0,10,10,10,10,999999,NULL,1,'[\"Add Booking From Dashboard\",\"Edit Booking From Dashboard\",\"Support Tickets\"]','2024-11-30 21:53:20','2024-11-30 21:59:25',0),(7,'Silver','silver',399,'fas fa-gift iconpicker-component','lifetime',3,3,0,3,3,3,3,100,NULL,1,'[\"Add Booking From Dashboard\"]','2024-11-30 21:51:04','2024-11-30 22:00:09',0),(8,'Gold','gold',699,'fas fa-gift iconpicker-component','lifetime',5,5,1,5,5,5,5,500,NULL,1,'[\"Add Booking From Dashboard\",\"Edit Booking From Dashboard\"]','2024-11-30 21:52:19','2024-11-30 22:03:29',0),(9,'Platinum','platinum',999,'fas fa-gift iconpicker-component','lifetime',10,10,0,10,10,10,10,999999,NULL,1,'[\"Add Booking From Dashboard\",\"Edit Booking From Dashboard\",\"Support Tickets\"]','2024-11-30 21:53:20','2024-11-30 22:03:47',0);
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_contents`
--

DROP TABLE IF EXISTS `page_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint DEFAULT NULL,
  `language_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_contents`
--

LOCK TABLES `page_contents` WRITE;
/*!40000 ALTER TABLE `page_contents` DISABLE KEYS */;
INSERT INTO `page_contents` VALUES (1,1,1,'Pusat Bantuan','pusat-bantuan','<div class=\"p-6\"><h2>Pusat Bantuan</h2><p>Halaman Pusat Bantuan ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Pusat Bantuan, Gofishi','Informasi mengenai Pusat Bantuan di Gofishi.',NULL,NULL),(2,2,1,'Privacy Policy','privacy-policy','<div class=\"p-6\"><h2>Privacy Policy</h2><p>Halaman Privacy Policy ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Privacy Policy, Gofishi','Informasi mengenai Privacy Policy di Gofishi.',NULL,NULL),(3,3,1,'Dukungan & Bantuan','dukungan-bantuan','<div class=\"p-6\"><h2>Dukungan & Bantuan</h2><p>Halaman Dukungan & Bantuan ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Dukungan & Bantuan, Gofishi','Informasi mengenai Dukungan & Bantuan di Gofishi.',NULL,NULL),(4,4,1,'Kebijakan Pembatalan','kebijakan-pembatalan','<div class=\"p-6\"><h2>Kebijakan Pembatalan</h2><p>Halaman Kebijakan Pembatalan ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Kebijakan Pembatalan, Gofishi','Informasi mengenai Kebijakan Pembatalan di Gofishi.',NULL,NULL),(5,5,1,'Tips Wisata Gofishi','tips-wisata-gofishi','<div class=\"p-6\"><h2>Tips Wisata Gofishi</h2><p>Halaman Tips Wisata Gofishi ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Tips Wisata Gofishi, Gofishi','Informasi mengenai Tips Wisata Gofishi di Gofishi.',NULL,NULL),(6,6,1,'Fitur Baru Gofishi','fitur-baru-gofishi','<div class=\"p-6\"><h2>Fitur Baru Gofishi</h2><p>Halaman Fitur Baru Gofishi ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Fitur Baru Gofishi, Gofishi','Informasi mengenai Fitur Baru Gofishi di Gofishi.',NULL,NULL),(7,7,1,'Karier di Gofishi','karier-di-gofishi','<div class=\"p-6\"><h2>Karier di Gofishi</h2><p>Halaman Karier di Gofishi ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Karier di Gofishi, Gofishi','Informasi mengenai Karier di Gofishi di Gofishi.',NULL,NULL),(8,8,1,'Gift Cards','gift-cards','<div class=\"p-6\"><h2>Gift Cards</h2><p>Halaman Gift Cards ini sudah terintegrasi penuh dengan sistem Gofishi.</p></div>','Gift Cards, Gofishi','Informasi mengenai Gift Cards di Gofishi.',NULL,NULL);
/*!40000 ALTER TABLE `page_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_headings`
--

DROP TABLE IF EXISTS `page_headings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_headings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `hotel_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rooms_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `room_checkout_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blog_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contact_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `error_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pricing_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `faq_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `forget_password_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vendor_forget_password_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `login_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `signup_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vendor_login_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vendor_signup_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `checkout_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vendor_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `about_us_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `room_wishlist_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `hotel_wishlist_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dashboard_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `room_bookings_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `room_booking_details_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `support_ticket_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `support_ticket_create_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `change_password_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `edit_profile_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `custom_page_heading` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_headings_language_id_foreign` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_headings`
--

LOCK TABLES `page_headings` WRITE;
/*!40000 ALTER TABLE `page_headings` DISABLE KEYS */;
INSERT INTO `page_headings` VALUES (9,20,'Lokasi','Perahu','Perahu Checkout','Blog','Contact','404','Pricing','FAQ','Forget Password','Forget Password','Login','Signup','Vendor Login','Vendor Signup','Checkout','Vendors','About Us','Saved Perahu','Saved Lokasi','Dashboard','Bookings','Booking Details','Support Tickets','Create a Support Ticket','Change Password','Edit Profile','{\"21\":\"Terms & Condition\",\"22\":\"Privacy Policy\",\"28\":null}','2023-08-27 01:23:22','2025-01-03 21:55:04'),(10,21,NULL,NULL,NULL,'مدونة','اتصال','404','التسعير','التعليمات','نسيت كلمة المرور','نسيت كلمة المرور','تسجيل الدخول','اشتراك','تسجيل دخول البائع','تسجيل البائع','الدفع','الباعة','معلومات عنا','قوائم الامنيات',NULL,'لوحة القيادة','طلبات',NULL,'تذاكر الدعم الفني','إنشاء تذكرة دعم','تغيير كلمة المرور','تعديل الملف الشخصي','{\"21\":\"\\u0627\\u0644\\u0634\\u0631\\u0648\\u0637 \\u0648\\u0627\\u0644\\u0623\\u062d\\u0643\\u0627\\u0645\",\"22\":\"\\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629\",\"28\":null}','2024-02-06 02:49:35','2025-01-03 21:55:16');
/*!40000 ALTER TABLE `page_headings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(2,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(3,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(4,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(5,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(6,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(7,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(8,1,0,'2026-03-23 23:40:38','2026-03-23 23:40:38');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_invoices`
--

DROP TABLE IF EXISTS `payment_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `InvoiceId` bigint unsigned NOT NULL,
  `InvoiceStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoiceValue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoiceDisplayValue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TransactionId` bigint unsigned NOT NULL,
  `TransactionStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PaymentGateway` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PaymentId` bigint unsigned NOT NULL,
  `CardNumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_invoices`
--

LOCK TABLES `payment_invoices` WRITE;
/*!40000 ALTER TABLE `payment_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perahu`
--

DROP TABLE IF EXISTS `perahu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perahu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint DEFAULT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `feature_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `average_rating` double DEFAULT '0',
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` bigint DEFAULT NULL,
  `bed` bigint DEFAULT NULL,
  `min_price` decimal(10,0) DEFAULT '0',
  `max_price` decimal(10,0) NOT NULL DEFAULT '0',
  `adult` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `bathroom` bigint DEFAULT NULL,
  `number_of_rooms_of_this_same_type` bigint DEFAULT NULL,
  `preparation_time` int NOT NULL DEFAULT '0',
  `area` bigint DEFAULT NULL,
  `prices` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_service` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perahu`
--

LOCK TABLES `perahu` WRITE;
/*!40000 ALTER TABLE `perahu` DISABLE KEYS */;
INSERT INTO `perahu` VALUES (1,1,4,'674d75eee279f.png',0,NULL,NULL,1,1,30,150,2,0,1,10,20,180,'[\"30\",\"70\",\"120\",\"150\",\"100\"]','{\"1\":\"100\",\"3\":\"100\",\"4\":\"40\",\"6\":\"123\",\"9\":\"80\"}','2024-12-02 02:55:10','2025-01-03 23:58:44'),(2,2,4,'674d788fa3951.png',0,NULL,NULL,1,5,100,1200,6,4,3,15,30,270,'[\"100\",\"280\",\"590\",\"1200\",\"100\"]','{\"1\":\"120\",\"4\":\"40\",\"5\":\"125\",\"7\":\"99\",\"8\":\"69\"}','2024-12-02 03:06:23','2025-01-03 23:58:04'),(3,3,3,'1733194626.png',4,NULL,NULL,1,1,50,220,2,0,1,10,20,180,'[\"50\",\"130\",\"180\",\"220\",\"100\"]','{\"3\":\"100\",\"4\":\"199\",\"5\":\"40\",\"6\":\"123\",\"7\":\"99\"}','2024-12-02 02:55:10','2025-01-04 01:56:01'),(4,4,3,'1733195222.png',0,NULL,NULL,1,5,60,270,6,4,3,10,30,270,'[\"60\",\"150\",\"210\",\"270\",\"100\"]','{\"1\":\"70\",\"3\":\"90\",\"4\":\"130\",\"5\":\"120\",\"8\":\"150\"}','2024-12-02 03:06:23','2025-01-03 23:57:06'),(5,5,2,'1733195555.png',0,NULL,NULL,1,1,45,190,2,0,1,10,20,180,'[\"45\",\"110\",\"150\",\"190\",\"100\"]','{\"1\":\"100\",\"4\":\"40\",\"5\":\"166\",\"8\":\"59\",\"9\":\"120\"}','2024-12-02 02:55:10','2025-01-03 23:56:36'),(6,6,2,'1733196602.png',0,NULL,NULL,1,5,100,900,6,4,3,15,30,270,'[\"200\",\"500\",\"700\",\"900\",\"100\"]','{\"1\":\"100\",\"2\":\"30\",\"6\":\"123\",\"8\":\"69\",\"10\":\"177\"}','2024-12-02 03:06:23','2025-01-03 23:56:04'),(7,7,0,'1733196967.png',5,NULL,NULL,1,1,80,300,2,0,1,15,30,270,'[\"80\",\"160\",\"220\",\"300\",\"100\"]','{\"1\":\"100\",\"3\":\"45\",\"5\":\"40\",\"7\":\"99\",\"9\":\"67\"}','2024-12-02 03:06:23','2025-01-03 23:55:42'),(8,8,1,'1733200252.png',0,NULL,NULL,1,1,55,225,2,0,1,10,20,180,'[\"55\",\"135\",\"180\",\"225\",\"100\"]','{\"1\":\"100\",\"4\":\"199\",\"5\":\"100\",\"9\":\"189\",\"10\":\"177\"}','2024-12-02 02:55:10','2025-01-03 23:55:24'),(9,9,0,'1733200536.png',0,NULL,NULL,1,5,90,350,20,4,3,15,30,270,'[\"90\",\"200\",\"280\",\"350\",\"100\"]','{\"1\":\"100\",\"2\":\"30\",\"3\":\"100\",\"5\":\"40\",\"8\":\"65\"}','2024-12-02 03:06:23','2025-01-03 23:54:46'),(10,10,3,'1733202557.png',3.5,NULL,NULL,1,5,25,100,6,4,3,15,30,270,'[\"25\",\"60\",\"80\",\"100\",\"100\"]','{\"1\":\"100\",\"2\":\"100\",\"3\":\"100\",\"4\":\"100\",\"9\":\"189\"}','2024-12-02 03:06:23','2025-01-04 02:25:01'),(11,11,1,'1733203253.png',0,NULL,NULL,1,5,100,430,6,4,3,15,30,270,'[\"120\",\"250\",\"350\",\"430\",\"100\"]','{\"2\":\"100\",\"4\":\"56\",\"5\":\"100\",\"6\":\"100\",\"8\":\"120\"}','2024-12-02 03:06:23','2025-01-03 23:53:55'),(12,12,0,'1733203551.png',0,NULL,NULL,1,5,100,550,6,4,3,15,30,270,'[\"150\",\"350\",\"460\",\"550\",\"100\"]','{\"1\":\"100\",\"3\":\"45\",\"6\":\"100\",\"7\":\"99\",\"9\":\"189\"}','2024-12-02 03:06:23','2025-01-03 23:53:37'),(13,13,1,'1733540252.png',0,NULL,NULL,1,1,20,180,2,0,1,10,20,180,'[\"20\",\"100\",\"140\",\"180\",\"100\"]','{\"2\":\"48\",\"4\":\"56\",\"5\":\"40\",\"8\":\"120\",\"9\":\"80\"}','2024-12-02 02:55:10','2025-01-03 23:53:13'),(14,14,1,'1733541383.png',2,NULL,NULL,1,5,50,240,6,4,3,15,30,270,'[\"50\",\"120\",\"180\",\"240\",\"100\"]','{\"1\":\"100\",\"2\":\"100\",\"3\":\"100\",\"4\":\"100\",\"9\":\"67\"}','2024-12-02 03:06:23','2025-01-04 03:23:48'),(15,15,0,'1733542065.png',0,NULL,NULL,1,5,100,1500,6,4,3,15,30,270,'[\"300\",\"800\",\"1200\",\"1500\",\"100\"]','{\"1\":\"100\",\"2\":\"100\",\"3\":\"100\",\"4\":\"100\",\"5\":\"100\",\"6\":\"100\"}','2024-12-02 03:06:23','2024-12-23 22:45:09'),(17,21,0,'1735705662.png',0,NULL,NULL,1,4,50,220,5,4,2,5,45,670,'[\"50\",\"130\",\"180\",\"220\"]','{\"1\":\"100\",\"4\":\"199\",\"5\":\"100\",\"8\":\"65\",\"9\":\"189\"}','2024-12-31 22:26:56','2025-01-03 23:51:52'),(18,22,1,'6774c956a9616.png',0,NULL,NULL,1,1,250,750,2,0,1,6,30,NULL,'[\"250\",\"450\",\"600\",\"750\"]','{\"4\":\"67\",\"6\":\"123\",\"7\":\"99\",\"9\":\"189\",\"10\":\"177\"}','2024-12-31 22:49:26','2025-01-03 23:51:30'),(19,23,3,'6774caeb5b8a4.png',0,NULL,NULL,1,1,200,550,2,0,1,2,30,436,'[\"200\",\"350\",\"450\",\"550\"]','{\"1\":\"100\",\"2\":\"30\",\"3\":\"45\",\"4\":\"199\",\"8\":\"65\"}','2024-12-31 22:56:11','2025-01-03 23:51:06');
/*!40000 ALTER TABLE `perahu` ENABLE KEYS */;
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
  `expires_at` timestamp NULL DEFAULT NULL,
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
-- Table structure for table `popups`
--

DROP TABLE IF EXISTS `popups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `popups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `type` smallint unsigned NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `background_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `background_color_opacity` decimal(3,2) unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `button_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `delay` int unsigned NOT NULL COMMENT 'value will be in milliseconds',
  `serial_number` mediumint unsigned NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '0 => deactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `popups_language_id_foreign` (`language_id`),
  CONSTRAINT `popups_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `popups`
--

LOCK TABLES `popups` WRITE;
/*!40000 ALTER TABLE `popups` DISABLE KEYS */;
/*!40000 ALTER TABLE `popups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_subscriptions`
--

DROP TABLE IF EXISTS `push_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subscribable_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subscribable_id` bigint unsigned NOT NULL,
  `endpoint` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `public_key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `content_encoding` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  KEY `push_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_subscriptions`
--

LOCK TABLES `push_subscriptions` WRITE;
/*!40000 ALTER TABLE `push_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quick_links`
--

DROP TABLE IF EXISTS `quick_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quick_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `serial_number` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quick_links_language_id_foreign` (`language_id`),
  CONSTRAINT `quick_links_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quick_links`
--

LOCK TABLES `quick_links` WRITE;
/*!40000 ALTER TABLE `quick_links` DISABLE KEYS */;
INSERT INTO `quick_links` VALUES (1,1,'Menjadi Host Gofishi','/user/login',0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(2,1,'Host Resources','#',0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(3,1,'Forum Komunitas','#',0,'2026-03-23 23:40:38','2026-03-23 23:40:38'),(4,1,'Host Gofishi','#',0,'2026-03-23 23:40:38','2026-03-23 23:40:38');
/*!40000 ALTER TABLE `quick_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,'Admin 1 (Super Admin)','[\"Menu Builder\",\"Package Management\",\"Lokasi Management\",\"Perahu Management\",\"Perahu Bookings\",\"Payment Log\",\"Featured Lokasi\",\"Featured Perahu\",\"Lokasi Specifications\",\"Shop Management\",\"User Management\",\"Vendors Management\",\"Transaction\",\"Home Page\",\"Support Tickets\",\"Footer\",\"Custom Pages\",\"Blog Management\",\"FAQ Management\",\"Advertisements\",\"Announcement Popups\",\"Withdrawals Management\",\"Payment Gateways\",\"Basic Settings\",\"Admin Management\",\"Language Management\"]',NULL,'2026-03-15 04:54:19'),(2,'Admin 2 (Manager)','[\"Lokasi Management\",\"Perahu Management\",\"Perahu Bookings\",\"Payment Log\",\"User Management\",\"Vendors Management\",\"Transaction\",\"Support Tickets\",\"Blog Management\",\"FAQ Management\",\"Withdrawals Management\"]',NULL,'2026-03-15 04:54:19'),(3,'Admin 3 (Staff)','[\"Perahu Bookings\",\"Support Tickets\",\"FAQ Management\",\"User Management\"]',NULL,'2026-03-15 04:54:19'),(4,'Admin','[\"Support Tickets\"]','2021-08-06 22:42:38','2023-07-17 04:07:24'),(6,'Moderator','[\"Menu Builder\",\"Package Management\",\"Lokasi Management\",\"Perahu Management\",\"Perahu Bookings\",\"Payment Log\",\"Featured Lokasi\",\"Featured Perahu\",\"Lokasi Specifications\",\"Shop Management\",\"User Management\",\"Vendors Management\",\"Transaction\",\"Home Page\",\"Support Tickets\",\"Footer\",\"Custom Pages\",\"Blog Management\",\"FAQ Management\",\"Advertisements\",\"Announcement Popups\",\"Withdrawals Management\",\"Payment Gateways\",\"Basic Settings\",\"Admin Management\",\"Language Management\"]','2021-08-07 22:14:34','2024-08-01 00:32:56'),(14,'Supervisor','[\"Menu Builder\",\"Pages\",\"Transaction\",\"Withdrawals Management\",\"Package Management\",\"Lokasi Management\",\"Perahu Management\",\"Payment Log\",\"Perahu Bookings\",\"User Management\",\"Vendors Management\",\"Advertisements\",\"Announcement Popups\",\"Support Tickets\",\"Basic Settings\",\"Admin Management\"]','2021-11-24 22:48:53','2024-11-04 00:41:34');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_booking_items`
--

DROP TABLE IF EXISTS `room_booking_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_booking_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint DEFAULT NULL,
  `room_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_booking_items`
--

LOCK TABLES `room_booking_items` WRITE;
/*!40000 ALTER TABLE `room_booking_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_booking_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_bookings`
--

DROP TABLE IF EXISTS `room_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `hotel_id` bigint DEFAULT NULL,
  `room_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `booking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_days` int DEFAULT NULL,
  `number_of_children` int DEFAULT '0',
  `number_of_guests` int DEFAULT '1',
  `room_price` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `tax_charge` double DEFAULT NULL,
  `coupon_discount` double DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_commission` double DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `booking_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_info` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_bookings`
--

LOCK TABLES `room_bookings` WRITE;
/*!40000 ALTER TABLE `room_bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_categories`
--

DROP TABLE IF EXISTS `room_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_categories`
--

LOCK TABLES `room_categories` WRITE;
/*!40000 ALTER TABLE `room_categories` DISABLE KEYS */;
INSERT INTO `room_categories` VALUES (15,1,'Yacht',NULL,1,1,'2026-03-15 16:50:45','2026-03-15 16:50:45'),(16,1,'Speedboat',NULL,1,2,'2026-03-15 16:50:45','2026-03-15 16:50:45'),(17,1,'Traditional',NULL,1,3,'2026-03-15 16:50:45','2026-03-15 16:50:45'),(18,1,'Phinisi',NULL,1,48,'2026-03-17 01:38:41','2026-03-17 01:38:41'),(19,1,'Fishing',NULL,1,54,'2026-03-17 01:38:41','2026-03-17 01:38:41'),(20,1,'Catamaran',NULL,1,71,'2026-03-17 01:38:41','2026-03-17 01:38:41'),(21,1,'Party Boat',NULL,1,60,'2026-03-17 01:38:41','2026-03-17 01:38:41'),(22,1,'Wooden',NULL,1,72,'2026-03-17 01:38:41','2026-03-17 01:38:41'),(23,1,'Diving',NULL,1,71,'2026-03-17 01:38:41','2026-03-17 01:38:41'),(24,1,'Mancing','mancing',1,1,'2026-03-17 02:04:03','2026-03-17 02:04:03'),(25,1,'Mancing','mancing',1,1,'2026-03-17 02:04:03','2026-03-17 02:04:03'),(26,1,'Mancing','mancing',1,1,'2026-03-17 02:04:03','2026-03-17 02:04:03'),(27,1,'Mancing','mancing',1,1,'2026-03-17 02:04:03','2026-03-17 02:04:03');
/*!40000 ALTER TABLE `room_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_contents`
--

DROP TABLE IF EXISTS `room_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint DEFAULT NULL,
  `language_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `amenities` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `room_category` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_contents`
--

LOCK TABLES `room_contents` WRITE;
/*!40000 ALTER TABLE `room_contents` DISABLE KEYS */;
INSERT INTO `room_contents` VALUES (11,6,1,'Mutiara Luxury 88','mutiara-luxury-88-id','Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Mutiara Luxury 88. Dipimpin oleh Capt. Andre Wijaya yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 15 orang, bermesin Yanmar Diesel 400HP & Yanmar Diesel 400HP. Kru terlatih sebanyak 5 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(13,7,1,'Crystal Sea Yacht','crystal-sea-yacht-id','Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Crystal Sea Yacht. Dipimpin oleh Capt. Hendra Kurniawan yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 12 orang, bermesin Volvo Penta 350HP & Volvo Penta 350HP. Kru terlatih sebanyak 4 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(15,8,1,'Diamond Wave 05','diamond-wave-05-id','Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Diamond Wave 05. Dipimpin oleh Capt. Rudi Hartono yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 10 orang, bermesin Mercury Verado 300 & Mercury Verado 300. Kru terlatih sebanyak 3 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(17,9,1,'Sea Hawk Premium','sea-hawk-premium-id','Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Sea Hawk Premium. Dipimpin oleh Capt. Tony Halim yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 10 orang, bermesin Honda BF250HP & Honda BF250HP. Kru terlatih sebanyak 3 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(19,10,1,'Yacht Paradise JKT','yacht-paradise-jkt-id','Dermaga Pantai Mutiara, Penjaringan, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Yacht Paradise JKT. Dipimpin oleh Capt. Marco Santoso yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 20 orang, bermesin MTU V12 Diesel & MTU V12 Diesel. Kru terlatih sebanyak 6 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(21,11,1,'Angler King Express','angler-king-express-id','Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Angler King Express. Dipimpin oleh Capt. Yusuf Rahman yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 12 orang, bermesin Yamaha F200HP & Yamaha F200HP. Kru terlatih sebanyak 3 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(23,12,1,'Barakuda Trip','barakuda-trip-id','Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Barakuda Trip. Dipimpin oleh Capt. Deni Pratama yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 10 orang, bermesin Yamaha 85HP x2. Kru terlatih sebanyak 2 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(25,13,1,'Cakalang Explorer','cakalang-explorer-id','Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Cakalang Explorer. Dipimpin oleh Capt. Arif Budiman yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 8 orang, bermesin Dongfang 45HP. Kru terlatih sebanyak 2 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(27,14,1,'GT Predator 01','gt-predator-01-id','Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama GT Predator 01. Dipimpin oleh Capt. Guntur Wibowo yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 12 orang, bermesin Suzuki DF250HP & Suzuki DF250HP. Kru terlatih sebanyak 4 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(29,15,1,'Traditional Line','traditional-line-id','Pelabuhan Kali Adem, Muara Angke, Jakarta Utara',NULL,'Nikmati pengalaman memancing tak terlupakan bersama Traditional Line. Dipimpin oleh Capt. Salim Abdullah yang berpengalaman di perairan Jakarta dan Kepulauan Seribu. Kapasitas 10 orang, bermesin Inboard 30HP. Kru terlatih sebanyak 2 orang siap melayani Anda sepanjang perjalanan.',NULL,NULL,15,'2026-03-17 23:38:16','2026-03-17 23:38:16');
/*!40000 ALTER TABLE `room_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_coupons`
--

DROP TABLE IF EXISTS `room_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` double DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `perahu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_coupons`
--

LOCK TABLES `room_coupons` WRITE;
/*!40000 ALTER TABLE `room_coupons` DISABLE KEYS */;
INSERT INTO `room_coupons` VALUES (1,NULL,'Hourly Haven 10','hourlyhaven10','fixed',20,'2025-01-04','2024-12-31','[\"2\",\"17\",\"18\"]','2024-12-02 02:13:34','2025-01-03 21:14:05'),(3,NULL,'New Year','newyear2025','fixed',15,'2025-01-01','2025-01-02','[\"17\",\"19\"]','2024-12-31 23:26:12','2025-01-03 21:13:57'),(4,NULL,'Holiday','holiday','fixed',10,'2026-10-30','2026-10-31','[\"3\",\"5\",\"6\",\"17\"]','2025-01-03 21:13:38','2025-01-03 21:13:52'),(5,NULL,'BLACK FRIDAY','black99','percentage',12,'2029-12-28','2029-12-31','[\"1\",\"2\",\"6\",\"8\",\"18\",\"19\"]','2025-01-03 21:16:24','2025-01-03 21:16:24'),(6,NULL,'OPENING CEREMONY','open22','fixed',19,'2024-12-29','2029-12-31','[\"2\",\"4\",\"5\",\"6\",\"15\"]','2025-01-03 21:17:21','2025-01-03 21:17:34');
/*!40000 ALTER TABLE `room_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_features`
--

DROP TABLE IF EXISTS `room_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `vendor_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_features`
--

LOCK TABLES `room_features` WRITE;
/*!40000 ALTER TABLE `room_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_images`
--

DROP TABLE IF EXISTS `room_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_images`
--

LOCK TABLES `room_images` WRITE;
/*!40000 ALTER TABLE `room_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_reviews`
--

DROP TABLE IF EXISTS `room_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `hotel_id` bigint unsigned DEFAULT NULL,
  `room_id` bigint DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '0',
  `review` text COLLATE utf8mb4_unicode_ci,
  `reply` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_reviews`
--

LOCK TABLES `room_reviews` WRITE;
/*!40000 ALTER TABLE `room_reviews` DISABLE KEYS */;
INSERT INTO `room_reviews` VALUES (11,11,NULL,6,4,'Pelayanannya profesional. Harga sesuai fasilitas. Recommended!',NULL,'2026-03-13 23:38:17','2026-03-17 23:38:17'),(12,11,NULL,6,5,'Sudah sewa berkali-kali, selalu memuaskan. Kapten tahu spot rahasia!',NULL,'2026-02-21 23:38:17','2026-03-17 23:38:17'),(13,14,NULL,6,4,'Kapalnya bersih, krunya super ramah si Bang Kapten. Mantap!',NULL,'2026-02-11 23:38:17','2026-03-17 23:38:17'),(14,12,NULL,7,5,'Spot mancingnya jitu banget, ketemu GT dan Barakuda besar. Puas!',NULL,'2026-03-11 23:38:17','2026-03-17 23:38:17'),(15,12,NULL,7,4,'Spot mancingnya jitu banget, ketemu GT dan Barakuda besar. Puas!',NULL,'2026-03-07 23:38:17','2026-03-17 23:38:17'),(16,14,NULL,8,5,'Sudah sewa berkali-kali, selalu memuaskan. Kapten tahu spot rahasia!',NULL,'2026-02-02 23:38:17','2026-03-17 23:38:17'),(17,11,NULL,9,4,'Sudah sewa berkali-kali, selalu memuaskan. Kapten tahu spot rahasia!',NULL,'2026-02-11 23:38:17','2026-03-17 23:38:17'),(18,14,NULL,9,4,'Kapalnya bersih, krunya super ramah si Bang Kapten. Mantap!',NULL,'2026-02-18 23:38:17','2026-03-17 23:38:17'),(19,14,NULL,9,4,'Pemandangan lautnya indah, kapalnya nyaman dan bersih.',NULL,'2026-02-09 23:38:17','2026-03-17 23:38:17'),(20,14,NULL,10,4,'Pelayanannya profesional. Harga sesuai fasilitas. Recommended!',NULL,'2026-02-13 23:38:17','2026-03-17 23:38:17'),(21,10,NULL,10,4,'Pemandangan lautnya indah, kapalnya nyaman dan bersih.',NULL,'2026-01-23 23:38:17','2026-03-17 23:38:17'),(22,12,NULL,11,5,'Pelayanannya profesional. Harga sesuai fasilitas. Recommended!',NULL,'2026-03-03 23:38:17','2026-03-17 23:38:17'),(23,11,NULL,11,5,'Spot mancingnya jitu banget, ketemu GT dan Barakuda besar. Puas!',NULL,'2026-02-10 23:38:17','2026-03-17 23:38:17'),(24,13,NULL,11,4,'Fasilitas lengkap, ada freezer untuk ikan. Ikan tetap segar sampai rumah.',NULL,'2026-03-01 23:38:17','2026-03-17 23:38:17'),(25,13,NULL,12,4,'Kapalnya bersih, krunya super ramah si Bang Kapten. Mantap!',NULL,'2026-02-20 23:38:17','2026-03-17 23:38:17'),(26,14,NULL,12,5,'Fasilitas lengkap, ada freezer untuk ikan. Ikan tetap segar sampai rumah.',NULL,'2026-03-03 23:38:17','2026-03-17 23:38:17'),(27,13,NULL,12,5,'Pemandangan lautnya indah, kapalnya nyaman dan bersih.',NULL,'2026-02-03 23:38:17','2026-03-17 23:38:17'),(28,12,NULL,13,5,'Pemandangan lautnya indah, kapalnya nyaman dan bersih.',NULL,'2026-02-09 23:38:17','2026-03-17 23:38:17'),(29,14,NULL,13,5,'Berangkat dan pulang tepat waktu. Kapten berpengalaman banget.',NULL,'2026-02-13 23:38:17','2026-03-17 23:38:17'),(30,13,NULL,13,4,'Spot mancingnya jitu banget, ketemu GT dan Barakuda besar. Puas!',NULL,'2026-03-10 23:38:17','2026-03-17 23:38:17'),(31,14,NULL,14,4,'Kapalnya bersih, krunya super ramah si Bang Kapten. Mantap!',NULL,'2026-02-21 23:38:17','2026-03-17 23:38:17'),(32,13,NULL,15,5,'Kapalnya bersih, krunya super ramah si Bang Kapten. Mantap!',NULL,'2026-02-06 23:38:17','2026-03-17 23:38:17'),(33,13,NULL,15,5,'Pemandangan lautnya indah, kapalnya nyaman dan bersih.',NULL,'2026-03-01 23:38:17','2026-03-17 23:38:17');
/*!40000 ALTER TABLE `room_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_wishlists`
--

DROP TABLE IF EXISTS `room_wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `room_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_wishlists`
--

LOCK TABLES `room_wishlists` WRITE;
/*!40000 ALTER TABLE `room_wishlists` DISABLE KEYS */;
INSERT INTO `room_wishlists` VALUES (4,1,11,'2024-12-16 00:38:29','2024-12-16 00:38:29'),(6,1,17,'2025-01-04 00:03:07','2025-01-04 00:03:07'),(7,1,14,'2025-01-04 00:03:11','2025-01-04 00:03:11'),(8,1,9,'2025-01-04 00:03:16','2025-01-04 00:03:16'),(9,1,5,'2025-01-04 00:03:26','2025-01-04 00:03:26'),(11,1,10,'2025-01-04 00:09:45','2025-01-04 00:09:45'),(12,4,11,'2025-01-04 03:29:06','2025-01-04 03:29:06'),(13,4,18,'2025-01-04 03:29:10','2025-01-04 03:29:10'),(14,4,9,'2025-01-04 03:29:15','2025-01-04 03:29:15'),(15,4,2,'2025-01-04 03:29:22','2025-01-04 03:29:22');
/*!40000 ALTER TABLE `room_wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint DEFAULT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `feature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_km` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `captain_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `average_rating` double NOT NULL DEFAULT '0',
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` bigint DEFAULT NULL,
  `availability_mode` tinyint DEFAULT '1',
  `booking_type` enum('direct','approval') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct',
  `deposit_type` enum('full','percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bed` bigint DEFAULT NULL,
  `min_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `adult` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `bathroom` bigint DEFAULT NULL,
  `bedroom_count` int NOT NULL DEFAULT '0',
  `toilet_count` int NOT NULL DEFAULT '0',
  `number_of_rooms_of_this_same_type` bigint DEFAULT NULL,
  `preparation_time` int NOT NULL DEFAULT '0',
  `area` bigint DEFAULT NULL,
  `prices` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perahu_area_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `daily_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_day_1` bigint unsigned DEFAULT NULL,
  `price_day_2` bigint unsigned DEFAULT NULL,
  `price_day_3` bigint unsigned DEFAULT NULL,
  `meet_time_day_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_time_day_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_day_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meet_time_day_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_time_day_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_day_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meet_time_day_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_time_day_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_day_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_service` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `boat_length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boat_width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crew_count` int NOT NULL DEFAULT '0',
  `engine_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bait` tinyint(1) NOT NULL DEFAULT '0',
  `fishing_gear` tinyint(1) NOT NULL DEFAULT '0',
  `life_jacket` tinyint(1) NOT NULL DEFAULT '0',
  `breakfast` tinyint(1) NOT NULL DEFAULT '0',
  `lunch` tinyint(1) NOT NULL DEFAULT '0',
  `dinner` tinyint(1) NOT NULL DEFAULT '0',
  `mineral_water` tinyint(1) NOT NULL DEFAULT '0',
  `ac` tinyint(1) NOT NULL DEFAULT '0',
  `wifi` tinyint(1) NOT NULL DEFAULT '0',
  `electricity` tinyint(1) NOT NULL DEFAULT '0',
  `stove` tinyint(1) NOT NULL DEFAULT '0',
  `refrigerator` tinyint(1) NOT NULL DEFAULT '0',
  `other_features` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (6,2,1,NULL,'Mutiara Luxury 88','Capt. Andre Wijaya',5,'-6.1089','106.7917',1,1,'direct','full',4500000.00,NULL,0.00,0.00,15,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,15000000,28500000,40500000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":500000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','26','7',5,'Yanmar Diesel 400HP','Yanmar Diesel 400HP',1,1,1,1,1,1,1,1,1,1,1,1,NULL,NULL,0),(7,2,1,NULL,'Crystal Sea Yacht','Capt. Hendra Kurniawan',5,'-6.1089','106.7917',1,1,'direct','full',3750000.00,NULL,0.00,0.00,12,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,12500000,23750000,33750000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"12\\\":200000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','24','6',4,'Volvo Penta 350HP','Volvo Penta 350HP',1,1,1,1,1,1,1,1,1,1,1,1,NULL,NULL,0),(8,2,1,NULL,'Diamond Wave 05','Capt. Rudi Hartono',5,'-6.1089','106.7917',1,1,'direct','full',3300000.00,NULL,0.00,0.00,10,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,11000000,20900000,29700000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":500000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','22','6',3,'Mercury Verado 300','Mercury Verado 300',1,1,1,1,1,1,1,1,1,1,1,1,NULL,NULL,0),(9,2,1,NULL,'Sea Hawk Premium','Capt. Tony Halim',5,'-6.1089','106.7917',1,1,'direct','full',2400000.00,NULL,0.00,0.00,10,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,8000000,15200000,21600000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":300000,\\\"12\\\":300000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','20','5',3,'Honda BF250HP','Honda BF250HP',1,1,1,0,1,0,1,1,0,1,1,1,NULL,NULL,0),(10,2,1,NULL,'Yacht Paradise JKT','Capt. Marco Santoso',5,'-6.1089','106.7917',1,1,'direct','full',7500000.00,NULL,0.00,0.00,20,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,25000000,47500000,67500000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":100000,\\\"14\\\":300000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','35','8',6,'MTU V12 Diesel','MTU V12 Diesel',1,1,1,1,1,1,1,1,1,1,1,1,NULL,NULL,0),(11,3,1,NULL,'Angler King Express','Capt. Yusuf Rahman',5,'-6.1016','106.7736',1,1,'direct','full',1350000.00,NULL,0.00,0.00,12,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,4500000,8550000,12150000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":300000,\\\"12\\\":500000,\\\"14\\\":200000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','16','4',3,'Yamaha F200HP','Yamaha F200HP',1,1,1,1,1,0,1,1,0,1,1,1,NULL,NULL,0),(12,3,1,NULL,'Barakuda Trip','Capt. Deni Pratama',5,'-6.1016','106.7736',1,1,'direct','full',840000.00,NULL,0.00,0.00,10,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,2800000,5320000,7560000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":300000,\\\"12\\\":400000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','13','3',2,'Yamaha 85HP x2',NULL,1,1,1,1,1,0,1,0,0,1,1,1,NULL,NULL,0),(13,3,1,NULL,'Cakalang Explorer','Capt. Arif Budiman',5,'-6.1016','106.7736',1,1,'direct','full',660000.00,NULL,0.00,0.00,8,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,2200000,4180000,5940000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"12\\\":500000,\\\"13\\\":500000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','12','3',2,'Dongfang 45HP',NULL,1,1,1,1,1,0,1,0,0,1,1,1,NULL,NULL,0),(14,3,1,NULL,'GT Predator 01','Capt. Guntur Wibowo',5,'-6.1016','106.7736',1,1,'direct','full',2100000.00,NULL,0.00,0.00,12,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,7000000,13300000,18900000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"11\\\":500000,\\\"12\\\":500000,\\\"13\\\":300000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','20','5',4,'Suzuki DF250HP','Suzuki DF250HP',1,1,1,0,1,0,1,0,0,1,1,1,NULL,NULL,0),(15,3,1,NULL,'Traditional Line','Capt. Salim Abdullah',5,'-6.1016','106.7736',1,1,'direct','full',540000.00,NULL,0.00,0.00,10,NULL,NULL,0,0,1,0,NULL,NULL,NULL,0.00,1800000,3420000,4860000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'\"{\\\"13\\\":300000}\"','2026-03-17 23:38:16','2026-03-17 23:38:16','11','3',2,'Inboard 30HP',NULL,1,1,1,0,1,0,1,1,0,1,1,1,NULL,NULL,0);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_contents`
--

DROP TABLE IF EXISTS `section_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `section_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `category_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latest_service_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_service_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_section_background_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_section_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workprocess_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workprocess_section_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workprocess_section_btn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workprocess_section_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workprocess_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_process_background_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_section_inner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_section_btn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_to_action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_section_text` text COLLATE utf8mb4_unicode_ci,
  `testimonial_section_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_section_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_section_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_section_clients` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_contents`
--

LOCK TABLES `section_contents` WRITE;
/*!40000 ALTER TABLE `section_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `footer_section_status` int NOT NULL DEFAULT '1',
  `custom_section_status` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,1,1,NULL,'2026-03-15 15:48:57','2026-03-15 15:48:57'),(2,2,1,NULL,'2026-03-15 15:48:57','2026-03-15 15:48:57'),(3,20,1,NULL,'2026-03-15 15:48:57','2026-03-15 15:48:57'),(4,21,1,NULL,'2026-03-15 15:48:57','2026-03-15 15:48:57');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seos`
--

DROP TABLE IF EXISTS `seos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `meta_keyword_home` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_home` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meta_keyword_pricing` text COLLATE utf8mb4_unicode_ci,
  `meta_description_pricing` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_hotels` text COLLATE utf8mb4_unicode_ci,
  `meta_description_hotels` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_rooms` text COLLATE utf8mb4_unicode_ci,
  `meta_description_rooms` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_blog` text COLLATE utf8mb4_unicode_ci,
  `meta_description_blog` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_faq` text COLLATE utf8mb4_unicode_ci,
  `meta_description_faq` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_contact` text COLLATE utf8mb4_unicode_ci,
  `meta_description_contact` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_login` text COLLATE utf8mb4_unicode_ci,
  `meta_description_login` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_signup` text COLLATE utf8mb4_unicode_ci,
  `meta_description_signup` text COLLATE utf8mb4_unicode_ci,
  `meta_keyword_forget_password` text COLLATE utf8mb4_unicode_ci,
  `meta_description_forget_password` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords_vendor_login` text COLLATE utf8mb4_unicode_ci,
  `meta_description_vendor_login` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords_vendor_signup` text COLLATE utf8mb4_unicode_ci,
  `meta_description_vendor_signup` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords_vendor_forget_password` text COLLATE utf8mb4_unicode_ci,
  `meta_descriptions_vendor_forget_password` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords_vendor_page` text COLLATE utf8mb4_unicode_ci,
  `meta_description_vendor_page` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords_about_page` text COLLATE utf8mb4_unicode_ci,
  `meta_description_about_page` text COLLATE utf8mb4_unicode_ci,
  `custome_page_meta_keyword` text COLLATE utf8mb4_unicode_ci,
  `custome_page_meta_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seos`
--

LOCK TABLES `seos` WRITE;
/*!40000 ALTER TABLE `seos` DISABLE KEYS */;
INSERT INTO `seos` VALUES (7,1,NULL,NULL,'2026-03-17 21:37:40','2026-03-17 21:37:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Vendor Login','Vendor Login descriptions','Vendor Signup','Vendor Signup descriptions',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,2,NULL,NULL,'2026-03-17 21:37:40','2026-03-17 21:37:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Vendor Login','Vendor Login descriptions','Vendor Signup','Vendor Signup descriptions',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `seos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint unsigned NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,20,'6753b550ef9ab.jpg','Your Ultimate Hourly Lokasi Booking Solution','Nestled in the heart of the Pacific Islands resort, on the edge of a tranquil and beautiful Garden Island, CozyStay is a haven','2024-12-06 20:39:12','2024-12-06 20:39:12'),(6,21,'6778ad00a9dc3.jpg','الحل الأمثل لحجز الفنادق كل ساعة','يقع في قلب منتجع جزر المحيط الهادئ، على حافة جزيرة Garden Island الهادئة والجميلة، وهو ملاذ','2025-01-03 21:37:36','2025-01-03 21:37:36'),(7,20,'6778ad8f33e90.png','Your Perfect Stay, Your Perfect Time','Discover flexibility in luxury with TimeStay, where your perfect lokasi experience is just an hour away. Escape the ordinary and book by the hour in the most serene locations.','2025-01-03 21:39:59','2025-01-03 21:39:59'),(8,20,'6778adc42a534.png','Hourly Lokasi Booking, Redefined','At TimeStay, we offer an effortless way to book a perahu that fits your schedule. Whether you’re staying for business or leisure, enjoy ultimate comfort without the commitment','2025-01-03 21:40:52','2025-01-03 21:40:52'),(9,21,'6778ae028b20e.png','إعادة تعريف حجز الفنادق كل ساعة','في ، نقدم طريقة سهلة لحجز غرفة تناسب جدولك الزمني. سواء كنت تقيم للعمل أو الترفيه، استمتع بالراحة المطلقة دون أي التزام','2025-01-03 21:41:54','2025-01-03 21:41:54'),(10,21,'6778ae2bd8523.png','إقامتك المثالية، وقتك المثالي','اكتشف المرونة في الرفاهية مع ، حيث تقع تجربتك الفندقية المثالية على بعد ساعة واحدة فقط. اهرب من المألوف واحجز بالساعة في أكثر الأماكن هدوءًا.','2025-01-03 21:42:35','2025-01-03 21:42:35');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_media`
--

DROP TABLE IF EXISTS `social_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_media`
--

LOCK TABLES `social_media` WRITE;
/*!40000 ALTER TABLE `social_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_medias`
--

DROP TABLE IF EXISTS `social_medias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_medias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `serial_number` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_medias`
--

LOCK TABLES `social_medias` WRITE;
/*!40000 ALTER TABLE `social_medias` DISABLE KEYS */;
INSERT INTO `social_medias` VALUES (1,'fab fa-facebook-square','http://example.com/',1,'2024-12-03 02:47:42','2024-12-03 02:48:29'),(2,'fab fa-twitter','http://example.com/',2,'2024-12-03 02:48:45','2024-12-03 02:48:45'),(3,'fab fa-youtube','http://example.com/',3,'2024-12-03 02:49:03','2024-12-03 02:49:03'),(4,'fab fa-instagram','http://example.com/',4,'2024-12-03 02:49:23','2024-12-03 02:49:23');
/*!40000 ALTER TABLE `social_medias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `states` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint DEFAULT NULL,
  `country_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` VALUES (2,1,9,'Bali','2024-11-30 23:29:25','2026-03-19 08:22:46'),(4,1,9,'DKI Jakarta','2024-11-30 23:29:53','2026-03-19 08:25:45'),(5,1,9,'Jawa Timur','2024-11-30 23:29:11','2026-03-19 08:22:28'),(9,1,9,'Jawa Tengah','2024-12-01 23:19:26','2026-03-19 08:21:40'),(10,1,9,'Jawa Barat','2024-12-01 23:19:36','2026-03-19 08:21:29');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriber_infos`
--

DROP TABLE IF EXISTS `subscriber_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriber_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriber_infos`
--

LOCK TABLES `subscriber_infos` WRITE;
/*!40000 ALTER TABLE `subscriber_infos` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriber_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_id_unique` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_ticket_statuses`
--

DROP TABLE IF EXISTS `support_ticket_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_ticket_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `support_ticket_status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_ticket_statuses`
--

LOCK TABLES `support_ticket_statuses` WRITE;
/*!40000 ALTER TABLE `support_ticket_statuses` DISABLE KEYS */;
INSERT INTO `support_ticket_statuses` VALUES (1,'active','2022-06-25 03:52:18','2024-05-11 21:48:31');
/*!40000 ALTER TABLE `support_ticket_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` longtext,
  `attachment` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1-pending, 2-open, 3-closed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_message` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonial_contents`
--

DROP TABLE IF EXISTS `testimonial_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonial_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial_contents`
--

LOCK TABLES `testimonial_contents` WRITE;
/*!40000 ALTER TABLE `testimonial_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonial_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,20,'6753d623bf8e1.png','Sarah Johnson','Freelance Designer','Vintage Charm provided me a peaceful place to work and relax. Booking by the hour is so convenient for my schedule!',0,'2024-12-06 22:59:15','2024-12-06 22:59:15'),(2,20,'6753d648f2862.png','Emily Carter','Travel Blogger','The flexibility of hourly bookings is a lifesaver for business meetings. Great service and cozy perahu!',0,'2024-12-06 22:59:52','2024-12-06 22:59:52'),(3,20,'6753d66f5f6d7.png','Yusuf Al-Mansouri','Entrepreneur','A perfect retreat for study breaks or relaxing. The environment is quiet and welcoming!',0,'2024-12-06 23:00:31','2024-12-06 23:00:31'),(4,20,'6753d6a3a600a.png','Olivia Brown','College Student','Having an hourly option makes quick breaks during busy schedules easy. Highly recommended for professionals!',0,'2024-12-06 23:01:23','2024-12-06 23:01:23'),(5,20,'6753d6e350331.png','Jacob Harris','Photographer','A great place to recharge between photoshoots. The perahu are clean, and the staff is incredibly helpful',0,'2024-12-06 23:02:27','2024-12-06 23:02:27'),(6,20,'6753d705e9929.png','David Kim','Tech Consultant','Hourly bookings helped me make the most of my layover. Efficient service and great amenities!',0,'2024-12-06 23:03:01','2024-12-06 23:03:01'),(7,21,'6753da134516e.png','سارة جونسون','مصمم مستقل','لقد وفر لي  مكانًا هادئًا للعمل والاسترخاء. الحجز بالساعة مناسب جدًا لجدول أعمالي!',0,'2024-12-06 22:59:15','2025-01-03 21:44:04'),(8,21,'6753d86ca8ccb.png','إميلي كارتر','مدون السفر','تعد مرونة الحجوزات بالساعة بمثابة المنقذ لاجتماعات العمل. خدمة رائعة وغرف مريحة!',0,'2024-12-06 22:59:52','2024-12-06 23:09:00'),(9,21,'6753d846d0b1f.png','يوسف المنصوري','مُقَاوِل','ملاذ مثالي لفترات الدراسة أو الاسترخاء. البيئة هادئة ومرحبة!',0,'2024-12-06 23:00:31','2024-12-06 23:08:22'),(10,21,'6753d81fc0b56.png','أوليفيا براون','طالب جامعي','وجود خيار بالساعة يجعل فترات الراحة السريعة خلال الجداول الزمنية المزدحمة أمرًا سهلاً. موصى به للغاية للمحترفين!',0,'2024-12-06 23:01:23','2024-12-06 23:07:43'),(11,21,'6753d7f4c0991.png','جاكوب هاريس','مصور','مكان رائع لإعادة الشحن بين جلسات التصوير. الغرف نظيفة، فريق العمل متعاون بشكل لا يصدق',0,'2024-12-06 23:02:27','2024-12-06 23:07:00'),(12,21,'6753d7b4b6a0f.png','ديفيد كيم','مستشار تقني','ساعدتني الحجوزات بالساعة في تحقيق أقصى استفادة من توقفي. خدمة فعالة ووسائل راحة رائعة!',0,'2024-12-06 23:03:01','2024-12-06 23:05:56');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timezones` (
  `country_code` char(3) NOT NULL,
  `timezone` varchar(125) NOT NULL DEFAULT '',
  `gmt_offset` float(10,2) DEFAULT NULL,
  `dst_offset` float(10,2) DEFAULT NULL,
  `raw_offset` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`country_code`,`timezone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezones`
--

LOCK TABLES `timezones` WRITE;
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;
INSERT INTO `timezones` VALUES ('AD','Europe/Andorra',1.00,2.00,1.00),('AE','Asia/Dubai',4.00,4.00,4.00),('AF','Asia/Kabul',4.50,4.50,4.50),('AG','America/Antigua',-4.00,-4.00,-4.00),('AI','America/Anguilla',-4.00,-4.00,-4.00),('AL','Europe/Tirane',1.00,2.00,1.00),('AM','Asia/Yerevan',4.00,4.00,4.00),('AO','Africa/Luanda',1.00,1.00,1.00),('AQ','Antarctica/Casey',8.00,8.00,8.00),('AQ','Antarctica/Davis',7.00,7.00,7.00),('AQ','Antarctica/DumontDUrville',10.00,10.00,10.00),('AQ','Antarctica/Mawson',5.00,5.00,5.00),('AQ','Antarctica/McMurdo',13.00,12.00,12.00),('AQ','Antarctica/Palmer',-3.00,-4.00,-4.00),('AQ','Antarctica/Rothera',-3.00,-3.00,-3.00),('AQ','Antarctica/South_Pole',13.00,12.00,12.00),('AQ','Antarctica/Syowa',3.00,3.00,3.00),('AQ','Antarctica/Vostok',6.00,6.00,6.00),('AR','America/Argentina/Buenos_Aires',-3.00,-3.00,-3.00),('AR','America/Argentina/Catamarca',-3.00,-3.00,-3.00),('AR','America/Argentina/Cordoba',-3.00,-3.00,-3.00),('AR','America/Argentina/Jujuy',-3.00,-3.00,-3.00),('AR','America/Argentina/La_Rioja',-3.00,-3.00,-3.00),('AR','America/Argentina/Mendoza',-3.00,-3.00,-3.00),('AR','America/Argentina/Rio_Gallegos',-3.00,-3.00,-3.00),('AR','America/Argentina/Salta',-3.00,-3.00,-3.00),('AR','America/Argentina/San_Juan',-3.00,-3.00,-3.00),('AR','America/Argentina/San_Luis',-3.00,-3.00,-3.00),('AR','America/Argentina/Tucuman',-3.00,-3.00,-3.00),('AR','America/Argentina/Ushuaia',-3.00,-3.00,-3.00),('AS','Pacific/Pago_Pago',-11.00,-11.00,-11.00),('AT','Europe/Vienna',1.00,2.00,1.00),('AU','Antarctica/Macquarie',11.00,11.00,11.00),('AU','Australia/Adelaide',10.50,9.50,9.50),('AU','Australia/Brisbane',10.00,10.00,10.00),('AU','Australia/Broken_Hill',10.50,9.50,9.50),('AU','Australia/Currie',11.00,10.00,10.00),('AU','Australia/Darwin',9.50,9.50,9.50),('AU','Australia/Eucla',8.75,8.75,8.75),('AU','Australia/Hobart',11.00,10.00,10.00),('AU','Australia/Lindeman',10.00,10.00,10.00),('AU','Australia/Lord_Howe',11.00,10.50,10.50),('AU','Australia/Melbourne',11.00,10.00,10.00),('AU','Australia/Perth',8.00,8.00,8.00),('AU','Australia/Sydney',11.00,10.00,10.00),('AW','America/Aruba',-4.00,-4.00,-4.00),('AX','Europe/Mariehamn',2.00,3.00,2.00),('AZ','Asia/Baku',4.00,5.00,4.00),('BA','Europe/Sarajevo',1.00,2.00,1.00),('BB','America/Barbados',-4.00,-4.00,-4.00),('BD','Asia/Dhaka',6.00,6.00,6.00),('BE','Europe/Brussels',1.00,2.00,1.00),('BF','Africa/Ouagadougou',0.00,0.00,0.00),('BG','Europe/Sofia',2.00,3.00,2.00),('BH','Asia/Bahrain',3.00,3.00,3.00),('BI','Africa/Bujumbura',2.00,2.00,2.00),('BJ','Africa/Porto-Novo',1.00,1.00,1.00),('BL','America/St_Barthelemy',-4.00,-4.00,-4.00),('BM','Atlantic/Bermuda',-4.00,-3.00,-4.00),('BN','Asia/Brunei',8.00,8.00,8.00),('BO','America/La_Paz',-4.00,-4.00,-4.00),('BQ','America/Kralendijk',-4.00,-4.00,-4.00),('BR','America/Araguaina',-3.00,-3.00,-3.00),('BR','America/Bahia',-3.00,-3.00,-3.00),('BR','America/Belem',-3.00,-3.00,-3.00),('BR','America/Boa_Vista',-4.00,-4.00,-4.00),('BR','America/Campo_Grande',-3.00,-4.00,-4.00),('BR','America/Cuiaba',-3.00,-4.00,-4.00),('BR','America/Eirunepe',-5.00,-5.00,-5.00),('BR','America/Fortaleza',-3.00,-3.00,-3.00),('BR','America/Maceio',-3.00,-3.00,-3.00),('BR','America/Manaus',-4.00,-4.00,-4.00),('BR','America/Noronha',-2.00,-2.00,-2.00),('BR','America/Porto_Velho',-4.00,-4.00,-4.00),('BR','America/Recife',-3.00,-3.00,-3.00),('BR','America/Rio_Branco',-5.00,-5.00,-5.00),('BR','America/Santarem',-3.00,-3.00,-3.00),('BR','America/Sao_Paulo',-2.00,-3.00,-3.00),('BS','America/Nassau',-5.00,-4.00,-5.00),('BT','Asia/Thimphu',6.00,6.00,6.00),('BW','Africa/Gaborone',2.00,2.00,2.00),('BY','Europe/Minsk',3.00,3.00,3.00),('BZ','America/Belize',-6.00,-6.00,-6.00),('CA','America/Atikokan',-5.00,-5.00,-5.00),('CA','America/Blanc-Sablon',-4.00,-4.00,-4.00),('CA','America/Cambridge_Bay',-7.00,-6.00,-7.00),('CA','America/Creston',-7.00,-7.00,-7.00),('CA','America/Dawson',-8.00,-7.00,-8.00),('CA','America/Dawson_Creek',-7.00,-7.00,-7.00),('CA','America/Edmonton',-7.00,-6.00,-7.00),('CA','America/Glace_Bay',-4.00,-3.00,-4.00),('CA','America/Goose_Bay',-4.00,-3.00,-4.00),('CA','America/Halifax',-4.00,-3.00,-4.00),('CA','America/Inuvik',-7.00,-6.00,-7.00),('CA','America/Iqaluit',-5.00,-4.00,-5.00),('CA','America/Moncton',-4.00,-3.00,-4.00),('CA','America/Montreal',-5.00,-4.00,-5.00),('CA','America/Nipigon',-5.00,-4.00,-5.00),('CA','America/Pangnirtung',-5.00,-4.00,-5.00),('CA','America/Rainy_River',-6.00,-5.00,-6.00),('CA','America/Rankin_Inlet',-6.00,-5.00,-6.00),('CA','America/Regina',-6.00,-6.00,-6.00),('CA','America/Resolute',-6.00,-5.00,-6.00),('CA','America/St_Johns',-3.50,-2.50,-3.50),('CA','America/Swift_Current',-6.00,-6.00,-6.00),('CA','America/Thunder_Bay',-5.00,-4.00,-5.00),('CA','America/Toronto',-5.00,-4.00,-5.00),('CA','America/Vancouver',-8.00,-7.00,-8.00),('CA','America/Whitehorse',-8.00,-7.00,-8.00),('CA','America/Winnipeg',-6.00,-5.00,-6.00),('CA','America/Yellowknife',-7.00,-6.00,-7.00),('CC','Indian/Cocos',6.50,6.50,6.50),('CD','Africa/Kinshasa',1.00,1.00,1.00),('CD','Africa/Lubumbashi',2.00,2.00,2.00),('CF','Africa/Bangui',1.00,1.00,1.00),('CG','Africa/Brazzaville',1.00,1.00,1.00),('CH','Europe/Zurich',1.00,2.00,1.00),('CI','Africa/Abidjan',0.00,0.00,0.00),('CK','Pacific/Rarotonga',-10.00,-10.00,-10.00),('CL','America/Santiago',-3.00,-4.00,-4.00),('CL','Pacific/Easter',-5.00,-6.00,-6.00),('CM','Africa/Douala',1.00,1.00,1.00),('CN','Asia/Chongqing',8.00,8.00,8.00),('CN','Asia/Harbin',8.00,8.00,8.00),('CN','Asia/Kashgar',8.00,8.00,8.00),('CN','Asia/Shanghai',8.00,8.00,8.00),('CN','Asia/Urumqi',8.00,8.00,8.00),('CO','America/Bogota',-5.00,-5.00,-5.00),('CR','America/Costa_Rica',-6.00,-6.00,-6.00),('CU','America/Havana',-5.00,-4.00,-5.00),('CV','Atlantic/Cape_Verde',-1.00,-1.00,-1.00),('CW','America/Curacao',-4.00,-4.00,-4.00),('CX','Indian/Christmas',7.00,7.00,7.00),('CY','Asia/Nicosia',2.00,3.00,2.00),('CZ','Europe/Prague',1.00,2.00,1.00),('DE','Europe/Berlin',1.00,2.00,1.00),('DE','Europe/Busingen',1.00,2.00,1.00),('DJ','Africa/Djibouti',3.00,3.00,3.00),('DK','Europe/Copenhagen',1.00,2.00,1.00),('DM','America/Dominica',-4.00,-4.00,-4.00),('DO','America/Santo_Domingo',-4.00,-4.00,-4.00),('DZ','Africa/Algiers',1.00,1.00,1.00),('EC','America/Guayaquil',-5.00,-5.00,-5.00),('EC','Pacific/Galapagos',-6.00,-6.00,-6.00),('EE','Europe/Tallinn',2.00,3.00,2.00),('EG','Africa/Cairo',2.00,2.00,2.00),('EH','Africa/El_Aaiun',0.00,0.00,0.00),('ER','Africa/Asmara',3.00,3.00,3.00),('ES','Africa/Ceuta',1.00,2.00,1.00),('ES','Atlantic/Canary',0.00,1.00,0.00),('ES','Europe/Madrid',1.00,2.00,1.00),('ET','Africa/Addis_Ababa',3.00,3.00,3.00),('FI','Europe/Helsinki',2.00,3.00,2.00),('FJ','Pacific/Fiji',13.00,12.00,12.00),('FK','Atlantic/Stanley',-3.00,-3.00,-3.00),('FM','Pacific/Chuuk',10.00,10.00,10.00),('FM','Pacific/Kosrae',11.00,11.00,11.00),('FM','Pacific/Pohnpei',11.00,11.00,11.00),('FO','Atlantic/Faroe',0.00,1.00,0.00),('FR','Europe/Paris',1.00,2.00,1.00),('GA','Africa/Libreville',1.00,1.00,1.00),('GB','Europe/London',0.00,1.00,0.00),('GD','America/Grenada',-4.00,-4.00,-4.00),('GE','Asia/Tbilisi',4.00,4.00,4.00),('GF','America/Cayenne',-3.00,-3.00,-3.00),('GG','Europe/Guernsey',0.00,1.00,0.00),('GH','Africa/Accra',0.00,0.00,0.00),('GI','Europe/Gibraltar',1.00,2.00,1.00),('GL','America/Danmarkshavn',0.00,0.00,0.00),('GL','America/Godthab',-3.00,-2.00,-3.00),('GL','America/Scoresbysund',-1.00,0.00,-1.00),('GL','America/Thule',-4.00,-3.00,-4.00),('GM','Africa/Banjul',0.00,0.00,0.00),('GN','Africa/Conakry',0.00,0.00,0.00),('GP','America/Guadeloupe',-4.00,-4.00,-4.00),('GQ','Africa/Malabo',1.00,1.00,1.00),('GR','Europe/Athens',2.00,3.00,2.00),('GS','Atlantic/South_Georgia',-2.00,-2.00,-2.00),('GT','America/Guatemala',-6.00,-6.00,-6.00),('GU','Pacific/Guam',10.00,10.00,10.00),('GW','Africa/Bissau',0.00,0.00,0.00),('GY','America/Guyana',-4.00,-4.00,-4.00),('HK','Asia/Hong_Kong',8.00,8.00,8.00),('HN','America/Tegucigalpa',-6.00,-6.00,-6.00),('HR','Europe/Zagreb',1.00,2.00,1.00),('HT','America/Port-au-Prince',-5.00,-4.00,-5.00),('HU','Europe/Budapest',1.00,2.00,1.00),('ID','Asia/Jakarta',7.00,7.00,7.00),('ID','Asia/Jayapura',9.00,9.00,9.00),('ID','Asia/Makassar',8.00,8.00,8.00),('ID','Asia/Pontianak',7.00,7.00,7.00),('IE','Europe/Dublin',0.00,1.00,0.00),('IL','Asia/Jerusalem',2.00,3.00,2.00),('IM','Europe/Isle_of_Man',0.00,1.00,0.00),('IN','Asia/Kolkata',5.50,5.50,5.50),('IO','Indian/Chagos',6.00,6.00,6.00),('IQ','Asia/Baghdad',3.00,3.00,3.00),('IR','Asia/Tehran',3.50,4.50,3.50),('IS','Atlantic/Reykjavik',0.00,0.00,0.00),('IT','Europe/Rome',1.00,2.00,1.00),('JE','Europe/Jersey',0.00,1.00,0.00),('JM','America/Jamaica',-5.00,-5.00,-5.00),('JO','Asia/Amman',2.00,3.00,2.00),('JP','Asia/Tokyo',9.00,9.00,9.00),('KE','Africa/Nairobi',3.00,3.00,3.00),('KG','Asia/Bishkek',6.00,6.00,6.00),('KH','Asia/Phnom_Penh',7.00,7.00,7.00),('KI','Pacific/Enderbury',13.00,13.00,13.00),('KI','Pacific/Kiritimati',14.00,14.00,14.00),('KI','Pacific/Tarawa',12.00,12.00,12.00),('KM','Indian/Comoro',3.00,3.00,3.00),('KN','America/St_Kitts',-4.00,-4.00,-4.00),('KP','Asia/Pyongyang',9.00,9.00,9.00),('KR','Asia/Seoul',9.00,9.00,9.00),('KW','Asia/Kuwait',3.00,3.00,3.00),('KY','America/Cayman',-5.00,-5.00,-5.00),('KZ','Asia/Almaty',6.00,6.00,6.00),('KZ','Asia/Aqtau',5.00,5.00,5.00),('KZ','Asia/Aqtobe',5.00,5.00,5.00),('KZ','Asia/Oral',5.00,5.00,5.00),('KZ','Asia/Qyzylorda',6.00,6.00,6.00),('LA','Asia/Vientiane',7.00,7.00,7.00),('LB','Asia/Beirut',2.00,3.00,2.00),('LC','America/St_Lucia',-4.00,-4.00,-4.00),('LI','Europe/Vaduz',1.00,2.00,1.00),('LK','Asia/Colombo',5.50,5.50,5.50),('LR','Africa/Monrovia',0.00,0.00,0.00),('LS','Africa/Maseru',2.00,2.00,2.00),('LT','Europe/Vilnius',2.00,3.00,2.00),('LU','Europe/Luxembourg',1.00,2.00,1.00),('LV','Europe/Riga',2.00,3.00,2.00),('LY','Africa/Tripoli',2.00,2.00,2.00),('MA','Africa/Casablanca',0.00,0.00,0.00),('MC','Europe/Monaco',1.00,2.00,1.00),('MD','Europe/Chisinau',2.00,3.00,2.00),('ME','Europe/Podgorica',1.00,2.00,1.00),('MF','America/Marigot',-4.00,-4.00,-4.00),('MG','Indian/Antananarivo',3.00,3.00,3.00),('MH','Pacific/Kwajalein',12.00,12.00,12.00),('MH','Pacific/Majuro',12.00,12.00,12.00),('MK','Europe/Skopje',1.00,2.00,1.00),('ML','Africa/Bamako',0.00,0.00,0.00),('MM','Asia/Rangoon',6.50,6.50,6.50),('MN','Asia/Choibalsan',8.00,8.00,8.00),('MN','Asia/Hovd',7.00,7.00,7.00),('MN','Asia/Ulaanbaatar',8.00,8.00,8.00),('MO','Asia/Macau',8.00,8.00,8.00),('MP','Pacific/Saipan',10.00,10.00,10.00),('MQ','America/Martinique',-4.00,-4.00,-4.00),('MR','Africa/Nouakchott',0.00,0.00,0.00),('MS','America/Montserrat',-4.00,-4.00,-4.00),('MT','Europe/Malta',1.00,2.00,1.00),('MU','Indian/Mauritius',4.00,4.00,4.00),('MV','Indian/Maldives',5.00,5.00,5.00),('MW','Africa/Blantyre',2.00,2.00,2.00),('MX','America/Bahia_Banderas',-6.00,-5.00,-6.00),('MX','America/Cancun',-6.00,-5.00,-6.00),('MX','America/Chihuahua',-7.00,-6.00,-7.00),('MX','America/Hermosillo',-7.00,-7.00,-7.00),('MX','America/Matamoros',-6.00,-5.00,-6.00),('MX','America/Mazatlan',-7.00,-6.00,-7.00),('MX','America/Merida',-6.00,-5.00,-6.00),('MX','America/Mexico_City',-6.00,-5.00,-6.00),('MX','America/Monterrey',-6.00,-5.00,-6.00),('MX','America/Ojinaga',-7.00,-6.00,-7.00),('MX','America/Santa_Isabel',-8.00,-7.00,-8.00),('MX','America/Tijuana',-8.00,-7.00,-8.00),('MY','Asia/Kuala_Lumpur',8.00,8.00,8.00),('MY','Asia/Kuching',8.00,8.00,8.00),('MZ','Africa/Maputo',2.00,2.00,2.00),('NA','Africa/Windhoek',2.00,1.00,1.00),('NC','Pacific/Noumea',11.00,11.00,11.00),('NE','Africa/Niamey',1.00,1.00,1.00),('NF','Pacific/Norfolk',11.50,11.50,11.50),('NG','Africa/Lagos',1.00,1.00,1.00),('NI','America/Managua',-6.00,-6.00,-6.00),('NL','Europe/Amsterdam',1.00,2.00,1.00),('NO','Europe/Oslo',1.00,2.00,1.00),('NP','Asia/Kathmandu',5.75,5.75,5.75),('NR','Pacific/Nauru',12.00,12.00,12.00),('NU','Pacific/Niue',-11.00,-11.00,-11.00),('NZ','Pacific/Auckland',13.00,12.00,12.00),('NZ','Pacific/Chatham',13.75,12.75,12.75),('OM','Asia/Muscat',4.00,4.00,4.00),('PA','America/Panama',-5.00,-5.00,-5.00),('PE','America/Lima',-5.00,-5.00,-5.00),('PF','Pacific/Gambier',-9.00,-9.00,-9.00),('PF','Pacific/Marquesas',-9.50,-9.50,-9.50),('PF','Pacific/Tahiti',-10.00,-10.00,-10.00),('PG','Pacific/Port_Moresby',10.00,10.00,10.00),('PH','Asia/Manila',8.00,8.00,8.00),('PK','Asia/Karachi',5.00,5.00,5.00),('PL','Europe/Warsaw',1.00,2.00,1.00),('PM','America/Miquelon',-3.00,-2.00,-3.00),('PN','Pacific/Pitcairn',-8.00,-8.00,-8.00),('PR','America/Puerto_Rico',-4.00,-4.00,-4.00),('PS','Asia/Gaza',2.00,3.00,2.00),('PS','Asia/Hebron',2.00,3.00,2.00),('PT','Atlantic/Azores',-1.00,0.00,-1.00),('PT','Atlantic/Madeira',0.00,1.00,0.00),('PT','Europe/Lisbon',0.00,1.00,0.00),('PW','Pacific/Palau',9.00,9.00,9.00),('PY','America/Asuncion',-3.00,-4.00,-4.00),('QA','Asia/Qatar',3.00,3.00,3.00),('RE','Indian/Reunion',4.00,4.00,4.00),('RO','Europe/Bucharest',2.00,3.00,2.00),('RS','Europe/Belgrade',1.00,2.00,1.00),('RU','Asia/Anadyr',12.00,12.00,12.00),('RU','Asia/Irkutsk',9.00,9.00,9.00),('RU','Asia/Kamchatka',12.00,12.00,12.00),('RU','Asia/Khandyga',10.00,10.00,10.00),('RU','Asia/Krasnoyarsk',8.00,8.00,8.00),('RU','Asia/Magadan',12.00,12.00,12.00),('RU','Asia/Novokuznetsk',7.00,7.00,7.00),('RU','Asia/Novosibirsk',7.00,7.00,7.00),('RU','Asia/Omsk',7.00,7.00,7.00),('RU','Asia/Sakhalin',11.00,11.00,11.00),('RU','Asia/Ust-Nera',11.00,11.00,11.00),('RU','Asia/Vladivostok',11.00,11.00,11.00),('RU','Asia/Yakutsk',10.00,10.00,10.00),('RU','Asia/Yekaterinburg',6.00,6.00,6.00),('RU','Europe/Kaliningrad',3.00,3.00,3.00),('RU','Europe/Moscow',4.00,4.00,4.00),('RU','Europe/Samara',4.00,4.00,4.00),('RU','Europe/Volgograd',4.00,4.00,4.00),('RW','Africa/Kigali',2.00,2.00,2.00),('SA','Asia/Riyadh',3.00,3.00,3.00),('SB','Pacific/Guadalcanal',11.00,11.00,11.00),('SC','Indian/Mahe',4.00,4.00,4.00),('SD','Africa/Khartoum',3.00,3.00,3.00),('SE','Europe/Stockholm',1.00,2.00,1.00),('SG','Asia/Singapore',8.00,8.00,8.00),('SH','Atlantic/St_Helena',0.00,0.00,0.00),('SI','Europe/Ljubljana',1.00,2.00,1.00),('SJ','Arctic/Longyearbyen',1.00,2.00,1.00),('SK','Europe/Bratislava',1.00,2.00,1.00),('SL','Africa/Freetown',0.00,0.00,0.00),('SM','Europe/San_Marino',1.00,2.00,1.00),('SN','Africa/Dakar',0.00,0.00,0.00),('SO','Africa/Mogadishu',3.00,3.00,3.00),('SR','America/Paramaribo',-3.00,-3.00,-3.00),('SS','Africa/Juba',3.00,3.00,3.00),('ST','Africa/Sao_Tome',0.00,0.00,0.00),('SV','America/El_Salvador',-6.00,-6.00,-6.00),('SX','America/Lower_Princes',-4.00,-4.00,-4.00),('SY','Asia/Damascus',2.00,3.00,2.00),('SZ','Africa/Mbabane',2.00,2.00,2.00),('TC','America/Grand_Turk',-5.00,-4.00,-5.00),('TD','Africa/Ndjamena',1.00,1.00,1.00),('TF','Indian/Kerguelen',5.00,5.00,5.00),('TG','Africa/Lome',0.00,0.00,0.00),('TH','Asia/Bangkok',7.00,7.00,7.00),('TJ','Asia/Dushanbe',5.00,5.00,5.00),('TK','Pacific/Fakaofo',13.00,13.00,13.00),('TL','Asia/Dili',9.00,9.00,9.00),('TM','Asia/Ashgabat',5.00,5.00,5.00),('TN','Africa/Tunis',1.00,1.00,1.00),('TO','Pacific/Tongatapu',13.00,13.00,13.00),('TR','Europe/Istanbul',2.00,3.00,2.00),('TT','America/Port_of_Spain',-4.00,-4.00,-4.00),('TV','Pacific/Funafuti',12.00,12.00,12.00),('TW','Asia/Taipei',8.00,8.00,8.00),('TZ','Africa/Dar_es_Salaam',3.00,3.00,3.00),('UA','Europe/Kiev',2.00,3.00,2.00),('UA','Europe/Simferopol',2.00,4.00,4.00),('UA','Europe/Uzhgorod',2.00,3.00,2.00),('UA','Europe/Zaporozhye',2.00,3.00,2.00),('UG','Africa/Kampala',3.00,3.00,3.00),('UM','Pacific/Johnston',-10.00,-10.00,-10.00),('UM','Pacific/Midway',-11.00,-11.00,-11.00),('UM','Pacific/Wake',12.00,12.00,12.00),('US','America/Adak',-10.00,-9.00,-10.00),('US','America/Anchorage',-9.00,-8.00,-9.00),('US','America/Boise',-7.00,-6.00,-7.00),('US','America/Chicago',-6.00,-5.00,-6.00),('US','America/Denver',-7.00,-6.00,-7.00),('US','America/Detroit',-5.00,-4.00,-5.00),('US','America/Indiana/Indianapolis',-5.00,-4.00,-5.00),('US','America/Indiana/Knox',-6.00,-5.00,-6.00),('US','America/Indiana/Marengo',-5.00,-4.00,-5.00),('US','America/Indiana/Petersburg',-5.00,-4.00,-5.00),('US','America/Indiana/Tell_City',-6.00,-5.00,-6.00),('US','America/Indiana/Vevay',-5.00,-4.00,-5.00),('US','America/Indiana/Vincennes',-5.00,-4.00,-5.00),('US','America/Indiana/Winamac',-5.00,-4.00,-5.00),('US','America/Juneau',-9.00,-8.00,-9.00),('US','America/Kentucky/Louisville',-5.00,-4.00,-5.00),('US','America/Kentucky/Monticello',-5.00,-4.00,-5.00),('US','America/Los_Angeles',-8.00,-7.00,-8.00),('US','America/Menominee',-6.00,-5.00,-6.00),('US','America/Metlakatla',-8.00,-8.00,-8.00),('US','America/New_York',-5.00,-4.00,-5.00),('US','America/Nome',-9.00,-8.00,-9.00),('US','America/North_Dakota/Beulah',-6.00,-5.00,-6.00),('US','America/North_Dakota/Center',-6.00,-5.00,-6.00),('US','America/North_Dakota/New_Salem',-6.00,-5.00,-6.00),('US','America/Phoenix',-7.00,-7.00,-7.00),('US','America/Shiprock',-7.00,-6.00,-7.00),('US','America/Sitka',-9.00,-8.00,-9.00),('US','America/Yakutat',-9.00,-8.00,-9.00),('US','Pacific/Honolulu',-10.00,-10.00,-10.00),('UY','America/Montevideo',-2.00,-3.00,-3.00),('UZ','Asia/Samarkand',5.00,5.00,5.00),('UZ','Asia/Tashkent',5.00,5.00,5.00),('VA','Europe/Vatican',1.00,2.00,1.00),('VC','America/St_Vincent',-4.00,-4.00,-4.00),('VE','America/Caracas',-4.50,-4.50,-4.50),('VG','America/Tortola',-4.00,-4.00,-4.00),('VI','America/St_Thomas',-4.00,-4.00,-4.00),('VN','Asia/Ho_Chi_Minh',7.00,7.00,7.00),('VU','Pacific/Efate',11.00,11.00,11.00),('WF','Pacific/Wallis',12.00,12.00,12.00),('WS','Pacific/Apia',14.00,13.00,13.00),('YE','Asia/Aden',3.00,3.00,3.00),('YT','Indian/Mayotte',3.00,3.00,3.00),('ZA','Africa/Johannesburg',2.00,2.00,2.00),('ZM','Africa/Lusaka',2.00,2.00,2.00),('ZW','Africa/Harare',2.00,2.00,2.00);
/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transcation_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int DEFAULT NULL,
  `transcation_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `payment_status` tinyint NOT NULL DEFAULT '0',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grand_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `commission` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pre_balance` decimal(20,2) DEFAULT NULL,
  `after_balance` decimal(20,2) DEFAULT NULL,
  `gateway_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'1773923192',1,'withdraw',NULL,1,0,'1',100000.00,0.00,10000000.00,9900000.00,NULL,'Rp','left','2026-03-19 05:26:32','2026-03-19 05:26:32');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_reviews`
--

DROP TABLE IF EXISTS `user_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_reviews_user_id_foreign` (`user_id`),
  KEY `user_reviews_vendor_id_foreign` (`vendor_id`),
  KEY `user_reviews_booking_id_foreign` (`booking_id`),
  CONSTRAINT `user_reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_reviews_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_reviews`
--

LOCK TABLES `user_reviews` WRITE;
/*!40000 ALTER TABLE `user_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (9,'gofishi_user','user@gofishi.com','1990-01-01','$2y$12$WcYg78dPvBd9kls49.bkSewGbvGimYdTpciF9Xyzzl.Zq8lT9yLhO',NULL,NULL,NULL,'2026-03-15 18:10:57','2026-03-15 18:10:57'),(10,'andi_jakarta','andi_jakarta@gofishi.id',NULL,'$2y$12$fbK1gpteSL9f69A5/.Eg8.BsmOr4qbIiBdCWobMrLjrEre2hwk.su',NULL,NULL,NULL,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(11,'budiono_trip','budiono_trip@gofishi.id',NULL,'$2y$12$mbiBBU6SvE1HXngm8K5Oq.cK4SG5vAos9uK.Aovzm0UKz3Kqks7dO',NULL,NULL,NULL,'2026-03-17 23:38:16','2026-03-17 23:38:16'),(12,'citra_angler','citra_angler@gofishi.id',NULL,'$2y$12$.Cy0h3zzViH1VldK/hAFI.As7dFbPE91w/1Rh2n2qPh/L0Ba83CVC',NULL,NULL,NULL,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(13,'dimas_mancing','dimas_mancing@gofishi.id',NULL,'$2y$12$hrqMu0nB2Ealrk7eQw9lg.ZPiZMvfu264doogMMR3xYAaEysNSNZO',NULL,NULL,NULL,'2026-03-17 23:38:17','2026-03-17 23:38:17'),(14,'eko_bahari','eko_bahari@gofishi.id',NULL,'$2y$12$6ucaEUoGhu.u.T0z5QQuSOFBbRqahmhROLqvslSywDrySPYBtMJLO',NULL,NULL,NULL,'2026-03-17 23:38:17','2026-03-17 23:38:17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_infos`
--

DROP TABLE IF EXISTS `vendor_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_infos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `language_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'U.S. Coast Guard or Local License Number',
  `specializations` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array of fishing techniques like Fly Fishing, Saltwater, etc.',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_infos`
--

LOCK TABLES `vendor_infos` WRITE;
/*!40000 ALTER TABLE `vendor_infos` DISABLE KEYS */;
INSERT INTO `vendor_infos` VALUES (1,1,1,'Gofishi Jakarta Charter',NULL,NULL,'Indonesia','Jakarta Utara','Kawasan Pesisir Jakarta',NULL,NULL,'Penyedia jasa sewa perahu pancing (Saltwater Angling) dan wisata bahari profesional di Jakarta.','2026-03-15 15:48:57','2026-03-15 15:48:57'),(2,1,2,'Gofishi Jakarta Charter',NULL,NULL,'Indonesia','Jakarta Utara','Kawasan Pesisir Jakarta',NULL,NULL,'Penyedia jasa sewa perahu pancing (Saltwater Angling) dan wisata bahari profesional di Jakarta.','2026-03-15 15:48:57','2026-03-15 15:48:57'),(3,1,20,'Gofishi Jakarta Charter',NULL,NULL,'Indonesia','Jakarta Utara','Kawasan Pesisir Jakarta',NULL,NULL,'Penyedia jasa sewa perahu pancing (Saltwater Angling) dan wisata bahari profesional di Jakarta.','2026-03-15 15:48:57','2026-03-15 15:48:57'),(4,1,21,'Gofishi Jakarta Charter',NULL,NULL,'Indonesia','Jakarta Utara','Kawasan Pesisir Jakarta',NULL,NULL,'Penyedia jasa sewa perahu pancing (Saltwater Angling) dan wisata bahari profesional di Jakarta.','2026-03-15 15:48:57','2026-03-15 15:48:57');
/*!40000 ALTER TABLE `vendor_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `is_verified_17_plus` tinyint(1) NOT NULL DEFAULT '0',
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `dob` date DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_status` int NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `document_verified` int NOT NULL DEFAULT '0',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `avg_rating` double NOT NULL DEFAULT '0',
  `show_email_addresss` int NOT NULL DEFAULT '1',
  `show_phone_number` int NOT NULL DEFAULT '1',
  `show_contact_form` int NOT NULL DEFAULT '1',
  `vendor_theme_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ktp_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boat_ownership_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driving_license_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `self_photo_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_username_unique` (`username`),
  UNIQUE KEY `vendors_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (1,'gofishijkt',NULL,NULL,NULL,'vendor@gofishi.id',NULL,NULL,0,9900000.00,NULL,'2026-03-15 15:48:57','628569877491','628569877491',1,'$2y$12$5aeeWhOQX1E3TeMuw3AXCeweEO8ofe.VvZmuqhwIAwcMjCt4HvvM6',1,1,NULL,5,1,1,1,'light','id','admin_id','2026-03-15 15:48:57','2026-03-19 05:26:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'demovendor',NULL,NULL,NULL,'demo@gofishi.com',NULL,NULL,0,0.00,NULL,'2026-03-19 07:57:16','+628569877491',NULL,0,'$2y$12$MvJNLs.uwdJU.q9XulG1bu6Tf7MgVDYGzr2FJIAA/czMpuJiwXLM.',1,1,NULL,0,1,1,1,'light',NULL,NULL,'2026-03-16 03:20:17','2026-03-19 07:57:23',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'juragan_laut',NULL,NULL,NULL,'juragan@gofishi.com',NULL,NULL,0,0.00,NULL,'2026-03-17 01:42:06','081234567890','081234567890',0,'$2y$12$C3W/xI.qY8CwqD5w5oxy5uYhTJPPj0FWeLC8hnIfKe25l29KefI2S',1,1,NULL,0,1,1,1,'light',NULL,NULL,'2026-03-17 01:36:44','2026-03-19 07:55:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'capt_jack',NULL,NULL,NULL,'capt@gofishi.com',NULL,NULL,0,0.00,NULL,'2026-03-17 01:42:06','081234567891','081234567891',0,'$2y$12$aUqk9kRIw11K5v6F4l4wXO1MplISg.OPu5gRHHdH40tPRUQrVQZ6e',1,1,NULL,0,1,1,1,'light',NULL,NULL,'2026-03-17 01:36:44','2026-03-19 07:56:24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint DEFAULT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
INSERT INTO `visitors` VALUES (5,14,1,'127.0.0.1','2024-12-07','2024-12-06 21:46:42','2024-12-06 21:46:42'),(7,11,1,'127.0.0.1','2024-12-07','2024-12-07 01:25:55','2024-12-07 01:25:55'),(8,7,0,'127.0.0.1','2024-12-07','2024-12-07 02:56:19','2024-12-07 02:56:19'),(9,10,3,'127.0.0.1','2024-12-07','2024-12-07 02:59:35','2024-12-07 02:59:35'),(11,13,1,'127.0.0.1','2024-12-07','2024-12-07 03:02:08','2024-12-07 03:02:08'),(13,12,0,'127.0.0.1','2024-12-08','2024-12-08 02:20:51','2024-12-08 02:20:51'),(15,12,0,'127.0.0.1','2024-12-09','2024-12-08 21:50:56','2024-12-08 21:50:56'),(16,11,1,'127.0.0.1','2024-12-09','2024-12-08 22:27:31','2024-12-08 22:27:31'),(17,7,0,'127.0.0.1','2024-12-09','2024-12-09 03:53:32','2024-12-09 03:53:32'),(18,11,1,'127.0.0.1','2024-12-10','2024-12-09 20:36:43','2024-12-09 20:36:43'),(19,12,0,'127.0.0.1','2024-12-10','2024-12-09 21:04:42','2024-12-09 21:04:42'),(20,7,0,'127.0.0.1','2024-12-10','2024-12-10 01:59:57','2024-12-10 01:59:57'),(21,11,1,'127.0.0.1','2024-12-11','2024-12-10 20:39:49','2024-12-10 20:39:49'),(23,11,1,'127.0.0.1','2024-12-12','2024-12-11 22:17:30','2024-12-11 22:17:30'),(25,10,3,'127.0.0.1','2024-12-14','2024-12-14 00:50:14','2024-12-14 00:50:14'),(26,11,1,'127.0.0.1','2024-12-15','2024-12-14 22:05:36','2024-12-14 22:05:36'),(27,10,3,'127.0.0.1','2024-12-15','2024-12-15 03:14:43','2024-12-15 03:14:43'),(28,9,0,'127.0.0.1','2024-12-15','2024-12-15 03:14:59','2024-12-15 03:14:59'),(31,6,2,'127.0.0.1','2024-12-15','2024-12-15 03:19:53','2024-12-15 03:19:53'),(32,15,0,'127.0.0.1','2024-12-15','2024-12-15 03:22:05','2024-12-15 03:22:05'),(33,15,0,'127.0.0.1','2024-12-22','2024-12-22 00:38:40','2024-12-22 00:38:40'),(34,15,0,'127.0.0.1','2024-12-24','2024-12-23 22:45:19','2024-12-23 22:45:19'),(35,11,1,'127.0.0.1','2024-12-24','2024-12-23 22:48:31','2024-12-23 22:48:31'),(36,11,1,'127.0.0.1','2024-12-25','2024-12-24 22:56:15','2024-12-24 22:56:15'),(37,15,0,'127.0.0.1','2024-12-25','2024-12-24 22:56:18','2024-12-24 22:56:18'),(38,14,1,'127.0.0.1','2024-12-25','2024-12-25 01:10:07','2024-12-25 01:10:07'),(39,14,1,'127.0.0.1','2024-12-26','2024-12-25 20:40:37','2024-12-25 20:40:37'),(41,11,1,'127.0.0.1','2024-12-26','2024-12-25 23:14:18','2024-12-25 23:14:18'),(42,19,3,'127.0.0.1','2025-01-01','2024-12-31 23:13:29','2024-12-31 23:13:29'),(43,10,3,'127.0.0.1','2025-01-02','2025-01-02 00:00:14','2025-01-02 00:00:14'),(45,7,0,'127.0.0.1','2025-01-04','2025-01-03 22:33:49','2025-01-03 22:33:49'),(46,18,1,'127.0.0.1','2025-01-04','2025-01-03 23:50:16','2025-01-03 23:50:16'),(48,10,3,'127.0.0.1','2025-01-04','2025-01-04 01:57:25','2025-01-04 01:57:25'),(49,14,1,'127.0.0.1','2025-01-04','2025-01-04 02:25:34','2025-01-04 02:25:34'),(50,11,1,'127.0.0.1','2025-01-04','2025-01-04 03:00:27','2025-01-04 03:00:27'),(51,11,1,'127.0.0.1','2025-05-10','2025-05-09 21:42:29','2025-05-09 21:42:29'),(52,10,3,'127.0.0.1','2025-05-11','2025-05-10 23:03:03','2025-05-10 23:03:03');
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `perahu_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlists_user_id_foreign` (`user_id`),
  KEY `wishlists_perahu_id_foreign` (`perahu_id`),
  CONSTRAINT `wishlists_perahu_id_foreign` FOREIGN KEY (`perahu_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraw_method_inputs`
--

DROP TABLE IF EXISTS `withdraw_method_inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraw_method_inputs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `withdraw_payment_method_id` bigint DEFAULT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1-text, 2-select, 3-checkbox, 4-textarea, 5-datepicker, 6-timepicker, 7-number',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` tinyint NOT NULL DEFAULT '0' COMMENT '1-required, 0- optional',
  `order_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraw_method_inputs`
--

LOCK TABLES `withdraw_method_inputs` WRITE;
/*!40000 ALTER TABLE `withdraw_method_inputs` DISABLE KEYS */;
INSERT INTO `withdraw_method_inputs` VALUES (1,1,1,'Nama Bank','nama_bank','Contoh: BCA / Mandiri',0,1,'2026-03-19 05:14:41','2026-03-19 05:14:41'),(2,1,1,'Nomor Rekening','nomor_rekening','Contoh: 1234567890',0,2,'2026-03-19 05:14:41','2026-03-19 05:14:41'),(3,1,1,'Atas Nama (Pemilik Rekening)','atas_nama','Sesuai buku tabungan',0,3,'2026-03-19 05:14:41','2026-03-19 05:14:41');
/*!40000 ALTER TABLE `withdraw_method_inputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraw_method_options`
--

DROP TABLE IF EXISTS `withdraw_method_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraw_method_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `withdraw_method_input_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraw_method_options`
--

LOCK TABLES `withdraw_method_options` WRITE;
/*!40000 ALTER TABLE `withdraw_method_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdraw_method_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraw_payment_methods`
--

DROP TABLE IF EXISTS `withdraw_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraw_payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_limit` decimal(16,2) NOT NULL DEFAULT '0.00',
  `max_limit` decimal(16,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(16,2) NOT NULL DEFAULT '0.00',
  `percentage_charge` decimal(8,2) NOT NULL DEFAULT '0.00',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraw_payment_methods`
--

LOCK TABLES `withdraw_payment_methods` WRITE;
/*!40000 ALTER TABLE `withdraw_payment_methods` DISABLE KEYS */;
INSERT INTO `withdraw_payment_methods` VALUES (1,'Transfer Bank',50000.00,10000000.00,0.00,0.00,1,'2026-03-19 05:14:41','2026-03-19 05:14:41');
/*!40000 ALTER TABLE `withdraw_payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraws`
--

DROP TABLE IF EXISTS `withdraws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraws` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint DEFAULT NULL,
  `withdraw_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_id` int DEFAULT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payable_amount` float(8,2) NOT NULL DEFAULT '0.00',
  `total_charge` float(8,2) NOT NULL DEFAULT '0.00',
  `additional_reference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feilds` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraws`
--

LOCK TABLES `withdraws` WRITE;
/*!40000 ALTER TABLE `withdraws` DISABLE KEYS */;
INSERT INTO `withdraws` VALUES (1,1,'69bbeb78c653c',1,'100000',100000.00,0.00,NULL,'{\"nama_bank\":\"BCA\",\"nomor_rekening\":\"123456789\",\"atas_nama\":\"gofishijkt\"}',0,'2026-03-19 05:26:32','2026-03-19 05:26:32');
/*!40000 ALTER TABLE `withdraws` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-24 13:43:26
