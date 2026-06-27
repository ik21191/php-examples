-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ecom2
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Minimalist Wireless Headphones',129.99,'Experience studio-grade acoustics with these minimalist wireless headphones. Featuring active hybrid noise cancellation (ANC), premium memory-foam leather earcups, and an ultra-lightweight ergonomic frame. Enjoy up to 40 hours of continuous high-fidelity playback on a single quick-charge cycle.','uploads/prod_296c0969a268a89b86d0d947490a09b3.jpg','2026-06-26 16:58:58'),(2,'Smart Fitness Watch',199.99,'Track your vitals, sports metrics, and daily lifestyle routine seamlessly. This smart fitness watch features a high-definition AMOLED display, real-time heart-rate logging, integrated GPS tracking, and a water-resistant rating up to 50 meters. Syncs perfectly with all major iOS and Android operating systems.','uploads/prod_e144cec30c2723d2e59f3d6427c600bb.jpg','2026-06-26 16:58:58'),(3,'Premium Leather Backpack',89.50,'Handcrafted from ethically sourced top-grain leather, this rugged backpack merges vintage aesthetics with modern executive storage. Features a dedicated padded compartment for laptops up to 16 inches, secure heavy-duty metal buckle fasteners, and weather-proof lining to protect your gear on any commute.','uploads/prod_2cdc3f9cd21da9e1f6da81e2fb1c1501.jpg','2026-06-26 16:58:58'),(4,'Mechanical Gaming Keyboard',145.00,'Elevate your tactical gameplay and drafting typing speeds. This professional-grade mechanical keyboard is built with hot-swappable linear red switches, premium wear-resistant double-shot PBT keycaps, fully customizable per-key RGB backlighting arrays, and an anodized aluminum core chassis frame.','uploads/prod_8b7b3cd04d43bfb0e99a2a7dea27e4e9.jpg','2026-06-26 16:58:58'),(11,'Ear Buds',1699.00,NULL,'uploads/prod_401e791c8c7be602c31207aa4b7e9b1f.jpg','2026-06-27 12:35:43'),(12,'MacBook Air',150000.00,NULL,'uploads/prod_44fa4971bf2e23e45cdc430ec6415bd2.jpg','2026-06-27 12:37:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-27 19:31:56
