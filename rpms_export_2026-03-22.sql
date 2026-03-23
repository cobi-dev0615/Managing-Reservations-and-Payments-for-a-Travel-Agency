-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: rpms
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,'Reserva criada','Booking',1,'{\"tour\": \"Europa Clássica 2026\", \"client\": \"Maria Silva\"}','127.0.0.1','2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,'Pagamento registrado','Installment',1,'{\"valor\": \"1500.00\", \"método\": \"pix\"}','127.0.0.1','2026-03-11 10:12:49','2026-03-11 10:12:49'),(3,'Tour criado','Tour',1,'{\"nome\": \"Europa Clássica 2026\"}','127.0.0.1','2026-03-11 10:12:49','2026-03-11 10:12:49'),(4,'Cliente cadastrado','Client',1,'{\"nome\": \"Maria Silva\"}','127.0.0.1','2026-03-11 10:12:49','2026-03-11 10:12:49'),(5,'E-mail reenviado','Installment',3,'{\"cliente\": \"João Santos\", \"template\": \"Aviso de Atraso\"}','127.0.0.1','2026-03-11 10:12:49','2026-03-11 10:12:49'),(6,'alterou status','Tour',8,'{\"name\": \"Agência Parceira - Maldivas\", \"status\": \"inativo\"}','127.0.0.1','2026-03-11 12:47:52','2026-03-11 12:47:52'),(7,'alterou status','Tour',8,'{\"name\": \"Agência Parceira - Maldivas\", \"status\": \"ativo\"}','127.0.0.1','2026-03-11 12:47:57','2026-03-11 12:47:57'),(8,'reenviou e-mail','Installment',4,'{\"booking_id\": 2, \"template_type\": \"aviso_atraso\"}','127.0.0.1','2026-03-15 23:51:20','2026-03-15 23:51:20'),(9,'criou template','EmailTemplate',5,'{\"name\": \"phoenix\", \"type\": \"confirmacao_reserva\"}','127.0.0.1','2026-03-15 23:56:22','2026-03-15 23:56:22'),(10,'excluiu template','EmailTemplate',NULL,'{\"name\": \"phoenix\", \"type\": \"confirmacao_reserva\"}','127.0.0.1','2026-03-15 23:56:52','2026-03-15 23:56:52'),(11,'excluiu','Booking',NULL,'{\"tour\": \"Europa Clássica 2026\", \"client\": \"Maria Silva\"}','127.0.0.1','2026-03-15 23:57:10','2026-03-15 23:57:10'),(12,'excluiu','Booking',NULL,'{\"tour\": \"Patagônia Adventure 2026\", \"client\": \"João Santos\"}','127.0.0.1','2026-03-15 23:57:32','2026-03-15 23:57:32'),(13,'excluiu','Booking',NULL,'{\"tour\": \"Turquia Exclusiva 2026\", \"client\": \"Ana Costa\"}','127.0.0.1','2026-03-15 23:57:42','2026-03-15 23:57:42'),(14,'atualizou','Tour',8,'{\"name\": \"Agência Parceira - Maldivas\"}','127.0.0.1','2026-03-16 00:01:10','2026-03-16 00:01:10');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `tour_id` bigint unsigned DEFAULT NULL,
  `tour_manual` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `currency` enum('BRL','USD','EUR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BRL',
  `total_value` decimal(10,2) NOT NULL,
  `discount_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `num_travelers` int NOT NULL DEFAULT '1',
  `status` enum('confirmado','pendente','cancelado','concluido') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_client_id_foreign` (`client_id`),
  KEY `bookings_tour_id_foreign` (`tour_id`),
  KEY `bookings_created_by_foreign` (`created_by`),
  CONSTRAINT `bookings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_tour_id_foreign` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (4,4,3,NULL,'2026-08-11','USD',2800.00,NULL,1,'pendente',NULL,3,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(5,5,7,NULL,'2026-05-11','USD',1500.00,NULL,1,'confirmado','Pacote influencer com conteúdo obrigatório',3,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(6,6,1,NULL,'2026-06-11','EUR',2200.00,NULL,1,'confirmado','Pagou tudo via PIX adiantado',1,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(7,9,4,NULL,'2026-09-11','USD',12000.00,NULL,4,'pendente','Família - 2 adultos + 2 crianças',1,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(8,10,2,NULL,'2026-05-11','USD',3200.00,NULL,1,'confirmado',NULL,1,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(9,8,NULL,'Roteiro Personalizado - Grécia 2026','2026-08-11','EUR',5500.00,NULL,2,'pendente','Tour sob medida - Atenas, Santorini, Mykonos',1,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(10,3,6,NULL,'2026-01-11','USD',4000.00,NULL,2,'concluido','Tour finalizado com sucesso',1,'2026-03-11 10:12:49','2026-03-11 10:12:49');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
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
  `expiration` int NOT NULL,
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
  `expiration` int NOT NULL,
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
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Maria Silva','maria.silva@email.com','Cliente frequente, prefere parcelamento','2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,'João Santos','joao.santos@email.com','Primeira viagem internacional','2026-03-11 10:12:49','2026-03-11 10:12:49'),(3,'Ana Costa','ana.costa@email.com','Viaja com marido, prefere tours privados','2026-03-11 10:12:49','2026-03-11 10:12:49'),(4,'Pedro Oliveira','pedro.oliveira@email.com',NULL,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(5,'Luciana Ferreira','luciana.ferreira@email.com','Influenciadora digital - @luciana.viaja','2026-03-11 10:12:49','2026-03-11 10:12:49'),(6,'Carlos Mendes','carlos.mendes@email.com','Sempre paga via PIX','2026-03-11 10:12:49','2026-03-11 10:12:49'),(7,'Beatriz Lima','beatriz.lima@email.com','Agente de viagem parceira','2026-03-11 10:12:49','2026-03-11 10:12:49'),(8,'Roberto Alves','roberto.alves@email.com',NULL,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(9,'Fernanda Rocha','fernanda.rocha@email.com','Viaja com família (4 pessoas)','2026-03-11 10:12:49','2026-03-11 10:12:49'),(10,'Gustavo Pereira','gustavo.pereira@email.com','Prefere pagamento via Wise','2026-03-11 10:12:49','2026-03-11 10:12:49');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `installment_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `template_id` bigint unsigned DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('enviado','falhou') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enviado',
  `trigger_type` enum('manual','automatico') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `sent_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_logs_installment_id_foreign` (`installment_id`),
  KEY `email_logs_client_id_foreign` (`client_id`),
  KEY `email_logs_template_id_foreign` (`template_id`),
  CONSTRAINT `email_logs_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `email_logs_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `email_logs_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `email_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_logs`
--

LOCK TABLES `email_logs` WRITE;
/*!40000 ALTER TABLE `email_logs` DISABLE KEYS */;
INSERT INTO `email_logs` VALUES (1,NULL,1,2,'Lembrete: Parcela 1 - Europa Clássica 2026','Lembrete de pagamento enviado automaticamente','enviado','automatico','2026-02-22 00:00:00','2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,NULL,2,3,'URGENTE: Parcela em Atraso - Patagônia Adventure 2026','Aviso de atraso enviado automaticamente','enviado','automatico','2026-03-07 00:00:00','2026-03-11 10:12:49','2026-03-11 10:12:49'),(3,NULL,1,4,'Pagamento Recebido - Parcela 1 - Europa Clássica 2026','Recibo de pagamento enviado manualmente','enviado','manual','2026-02-27 00:00:00','2026-03-11 10:12:49','2026-03-11 10:12:49'),(4,NULL,2,3,'URGENTE: Parcela em Atraso - Patagônia Adventure 2026','Olá João Santos,\n\nIdentificamos que a parcela 1 do tour Patagônia Adventure 2026 está em atraso.\n\nValor: USD 1.600,00\nVencimento: 06/03/2026\n\nPor favor, regularize o pagamento o mais breve possível.\n\nhttps://pay.example.com/joao-usd-1\nChave PIX: email@agencia.com\nBanco: Nubank\nTitular: Agência de Turismo LTDA\nCNPJ: 12.345.678/0001-00\n\nAtenciosamente,\nEquipe de Turismo','enviado','manual','2026-03-15 23:51:20','2026-03-15 23:51:20','2026-03-15 23:51:20');
/*!40000 ALTER TABLE `email_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('confirmacao_reserva','lembrete_pagamento','aviso_atraso','recibo_pagamento') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` VALUES (1,'confirmacao_reserva','Confirmação de Reserva Padrão','Reserva Confirmada - {tour_name}','Olá {client_name},\n\nSua reserva para o tour {tour_name} ({tour_code}) foi confirmada!\n\nDetalhes:\n- Valor total: {currency} {total_value}\n- Viajantes: sua reserva está confirmada\n\nEm breve enviaremos as informações de pagamento.\n\nAtenciosamente,\nEquipe de Turismo','2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,'lembrete_pagamento','Lembrete de Pagamento','Lembrete: Parcela {installment_number} - {tour_name}','Olá {client_name},\n\nEste é um lembrete sobre a parcela {installment_number} do seu tour {tour_name}.\n\nValor: {currency} {amount}\nVencimento: {due_date}\n\n{payment_link}\n{pix_instructions}\n\nQualquer dúvida, entre em contato conosco.\n\nAtenciosamente,\nEquipe de Turismo','2026-03-11 10:12:49','2026-03-11 10:12:49'),(3,'aviso_atraso','Aviso de Atraso','URGENTE: Parcela em Atraso - {tour_name}','Olá {client_name},\n\nIdentificamos que a parcela {installment_number} do tour {tour_name} está em atraso.\n\nValor: {currency} {amount}\nVencimento: {due_date}\n\nPor favor, regularize o pagamento o mais breve possível.\n\n{payment_link}\n{pix_instructions}\n\nAtenciosamente,\nEquipe de Turismo','2026-03-11 10:12:49','2026-03-11 10:12:49'),(4,'recibo_pagamento','Recibo de Pagamento','Pagamento Recebido - Parcela {installment_number} - {tour_name}','Olá {client_name},\n\nConfirmamos o recebimento do pagamento da parcela {installment_number} do tour {tour_name}.\n\nValor: {currency} {amount}\n\nObrigado!\n\nAtenciosamente,\nEquipe de Turismo','2026-03-11 10:12:49','2026-03-11 10:12:49');
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
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
-- Table structure for table `installments`
--

DROP TABLE IF EXISTS `installments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `installments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `installment_number` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pendente','pago','atrasado','falta_link') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente',
  `payment_method` enum('link','pix','wise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'link',
  `payment_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `last_email_sent_at` datetime DEFAULT NULL,
  `last_email_template_id` bigint unsigned DEFAULT NULL,
  `email_paused` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `installments_booking_id_foreign` (`booking_id`),
  KEY `installments_last_email_template_id_foreign` (`last_email_template_id`),
  CONSTRAINT `installments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `installments_last_email_template_id_foreign` FOREIGN KEY (`last_email_template_id`) REFERENCES `email_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installments`
--

LOCK TABLES `installments` WRITE;
/*!40000 ALTER TABLE `installments` DISABLE KEYS */;
INSERT INTO `installments` VALUES (8,4,1,1400.00,'2026-03-16','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(9,4,2,1400.00,'2026-05-11','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(10,5,1,750.00,'2026-03-08','pago','pix',NULL,'2026-03-07 00:00:00',NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(11,5,2,750.00,'2026-03-25','pendente','pix',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(12,6,1,2200.00,'2026-02-25','pago','pix',NULL,'2026-02-18 00:00:00',NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(13,7,1,3000.00,'2026-03-12','atrasado','link','https://pay.example.com/fernanda-1',NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-12 02:06:42'),(14,7,2,3000.00,'2026-04-11','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(15,7,3,3000.00,'2026-05-11','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(16,7,4,3000.00,'2026-06-11','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(17,8,1,1600.00,'2026-03-11','atrasado','wise',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(18,8,2,1600.00,'2026-04-11','pendente','wise',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(19,9,1,1833.33,'2026-03-21','pendente','link','https://pay.example.com/roberto-1',NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(20,9,2,1833.33,'2026-05-11','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(21,9,3,1833.34,'2026-06-11','falta_link','link',NULL,NULL,NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:28:48'),(22,10,1,2000.00,'2025-11-11','pago','wise',NULL,'2025-11-11 00:00:00',NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(23,10,2,2000.00,'2025-12-11','pago','wise',NULL,'2025-12-11 00:00:00',NULL,NULL,0,'2026-03-11 10:12:49','2026-03-11 10:12:49');
/*!40000 ALTER TABLE `installments` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_tours_table',1),(5,'2024_01_01_000002_create_clients_table',1),(6,'2024_01_01_000003_create_bookings_table',1),(7,'2024_01_01_000004_create_email_templates_table',1),(8,'2024_01_01_000005_create_installments_table',1),(9,'2024_01_01_000006_create_email_logs_table',1),(10,'2024_01_01_000007_create_activity_logs_table',1),(11,'2024_01_01_000008_create_settings_table',1),(12,'2024_01_01_000009_add_role_to_users_table',1),(13,'2024_01_01_000010_add_email_paused_to_installments_table',1),(14,'2026_03_16_001405_add_created_by_to_bookings_table',2),(15,'2026_03_17_092208_add_status_to_users_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('eG9h6ZQVstLif5pb9QF0farkZfK2PKgDIbYslTkR',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidU5JalRIS0dzTFkyaDhwZ2NRYTZDUDJra2M3bUhKUDdXZFZmd0ZwTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1773862836),('UGb8xiyVyGDlhY2vIIMHXXc1fu653koHdswfY6u2',3,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiclliQVBXOFhtVlFiTTlqeXU0eWJSS09tc3VNVVJOdlFmTm9yUm9FSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9wYWdhbWVudG9zIjtzOjU6InJvdXRlIjtzOjE0OiJwYXltZW50cy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1773620566),('uOTbLNQkOG6V91FkSgwRt433WYjYTyfZi9eBRktQ',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZm4yeWhYREF6Z0lUcW1XTG1mZ3JkZlhaTndCNUh1cTNmR3pxMGNwUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9jbGllbnRzIjtzOjU6InJvdXRlIjtzOjEzOiJjbGllbnRzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1773863689),('YOBxDBlyrlfqi0FEFfHxO8DbCjjEyVGiPcPFBHwg',NULL,'127.0.0.1','curl/8.5.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUHozM01QTUZFc0lnNXMyVkp4M3N5VUFHNmxRRnNzdlBlc0RuamJxVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAxIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMSI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1773862276);
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
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'msg_link','Clique no link abaixo para realizar o pagamento:','payment_messages','2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,'msg_pix','Realize o pagamento via PIX utilizando os dados abaixo:','payment_messages','2026-03-11 10:12:49','2026-03-11 10:12:49'),(3,'msg_wise','Realize a transferência internacional via Wise para a conta indicada:','payment_messages','2026-03-11 10:12:49','2026-03-11 10:12:49'),(4,'pix_instructions','Chave PIX: email@agencia.com\nBanco: Nubank\nTitular: Agência de Turismo LTDA\nCNPJ: 12.345.678/0001-00','pix','2026-03-11 10:12:49','2026-03-11 10:12:49'),(5,'auto_7days_before','1','automation','2026-03-11 10:12:49','2026-03-11 10:12:49'),(6,'auto_3days_before','1','automation','2026-03-11 10:12:49','2026-03-11 10:12:49'),(7,'auto_due_date','1','automation','2026-03-11 10:12:49','2026-03-11 10:12:49'),(8,'auto_1day_after','1','automation','2026-03-11 10:12:49','2026-03-11 10:12:49'),(9,'auto_7days_after','1','automation','2026-03-11 10:12:49','2026-03-11 10:12:49'),(10,'smtp_host','smtp.gmail.com','smtp','2026-03-11 10:12:49','2026-03-11 10:12:49'),(11,'smtp_port','587','smtp','2026-03-11 10:12:49','2026-03-11 10:12:49'),(12,'smtp_encryption','tls','smtp','2026-03-11 10:12:49','2026-03-11 10:12:49'),(13,'smtp_from_name','Agência de Turismo','smtp','2026-03-11 10:12:49','2026-03-11 10:12:49'),(14,'smtp_from_email','contato@agencia.com','smtp','2026-03-11 10:12:49','2026-03-11 10:12:49'),(15,'cron_schedule','0 8 * * * (diariamente às 08:00)','cron','2026-03-11 10:12:49','2026-03-11 10:12:49');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('grupo','privado','agencia','influencer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_currency` enum('BRL','USD','EUR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BRL',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('ativo','inativo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ativo',
  `max_travelers` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tours_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (1,'Europa Clássica 2026','EUR-CLA-2026','grupo','EUR','Paris, Roma, Barcelona - 15 dias','ativo',20,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,'Patagônia Adventure 2026','PAT-ADV-2026','grupo','USD','Torres del Paine, Glaciar Perito Moreno - 10 dias','ativo',12,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(3,'Egito Milenar 2026','EGI-MIL-2026','grupo','USD','Cairo, Luxor, cruzeiro no Nilo - 12 dias','ativo',15,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(4,'Japão Sakura 2026','JAP-SAK-2026','grupo','USD','Tokyo, Kyoto, Osaka - temporada de cerejeiras','ativo',16,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(5,'Turquia Exclusiva 2026','TUR-EXC-2026','privado','EUR','Istambul, Capadócia, Pamukkale - roteiro privado','ativo',6,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(6,'Safari Tanzânia 2025','SAF-TAN-2025','grupo','USD','Serengeti, Ngorongoro - tour finalizado','inativo',10,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(7,'Influencer Bali Experience','INF-BAL-2026','influencer','USD','Pacote para influenciadores - Bali e Nusa Penida','ativo',4,'2026-03-11 10:12:49','2026-03-11 10:12:49'),(8,'Agência Parceira - Maldivas','AGN-MAL-2026','agencia','USD','Pacote operado via agência parceira','ativo',NULL,'2026-03-11 10:12:49','2026-03-11 12:47:57');
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
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
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@rpms.com','admin','approved',NULL,'$2y$12$9MG6NjMBfxm2JcwevkJ/J.Dnbz5svUAzK4w7Vdbo4E6e5XJyJtByS','teVCmz9cFuI1RnYMiK9SiZm82bemjPR7NlGuPY7ijPgSUvPCqfpqpBQezaxa','2026-03-11 10:12:49','2026-03-11 10:12:49'),(2,'Gerente','gerente@rpms.com','manager','approved',NULL,'$2y$12$2mm84V46taHeez.BucDxZOxPkudapZw/SfDL1V5kykBFpLNtcGmeS',NULL,'2026-03-12 22:17:37','2026-03-12 22:17:37'),(3,'Visualizador','visualizador@rpms.com','viewer','approved',NULL,'$2y$12$ew1Rr0r72UTyIceBTkq5IeupFyOstXCQvQK9gVJMhu1JvRATQa4Eq',NULL,'2026-03-12 22:18:31','2026-03-12 22:18:31');
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

-- Dump completed on 2026-03-22 16:05:53
