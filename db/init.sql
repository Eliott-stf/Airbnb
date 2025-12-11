-- MariaDB dump 10.19-11.3.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: airbnb
-- ------------------------------------------------------
-- Server version	11.3.2-MariaDB-1:11.3.2+maria~ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `available`
--

DROP TABLE IF EXISTS `available`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `available` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_in` date NOT NULL,
  `date_out` date NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `available_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `available`
--

LOCK TABLES `available` WRITE;
/*!40000 ALTER TABLE `available` DISABLE KEYS */;
INSERT INTO `available` VALUES
(2,'2025-12-24','2025-12-28',8),
(11,'2025-12-26','2025-12-31',17),
(12,'2025-12-10','2025-12-12',18),
(20,'2025-12-08','2025-12-08',26),
(31,'2025-12-01','2025-12-07',37),
(32,'2025-12-08','2025-12-08',37),
(33,'2025-12-08','2025-12-08',37),
(34,'2025-12-08','2025-12-08',37),
(35,'2025-12-08','2025-12-08',37),
(36,'2025-12-08','2025-12-08',37),
(37,'2025-12-01','2025-12-07',37),
(38,'2025-12-23','2025-12-28',38),
(39,'2025-12-15','2025-12-21',39),
(43,'2025-12-18','2025-12-21',43),
(47,'2025-12-09','2025-12-14',48),
(48,'2025-12-13','2025-12-29',49);
/*!40000 ALTER TABLE `available` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_in` date NOT NULL,
  `date_out` date NOT NULL,
  `guest_count` int(11) NOT NULL DEFAULT 1,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
INSERT INTO `booking` VALUES
(1,'2025-12-25','2025-12-26',2,8,1),
(5,'2025-12-27','2025-12-27',1,17,1),
(7,'2025-12-03','2025-12-04',3,37,1),
(9,'2025-12-14','2025-12-15',1,49,2);
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES
(1,'Electroménager'),
(2,'Sécurité'),
(3,'Loisir'),
(4,'Extérieur'),
(5,'Accessibilité'),
(6,'Confort'),
(7,'Connectivité');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment`
--

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` VALUES
(1,'Lave-linge',1),
(2,'Sèche-linge',1),
(3,'Lave-vaisselle',1),
(4,'Micro-ondes',1),
(5,'Four',1),
(6,'Machine à café',1),
(7,'Réfrigérateur',1),
(8,'Grille-pain',1),
(9,'Fer à repasser',1),
(10,'Digicode',2),
(11,'Alarme incendie',2),
(12,'Extincteur',2),
(13,'Détecteur de fumée',2),
(14,'Caméra de surveillance',2),
(15,'Serrure sécurisée',2),
(16,'Interphone',2),
(17,'TV',3),
(18,'Console de jeux',3),
(19,'Bibliothèque',3),
(20,'Table de ping-pong',3),
(21,'Instruments de musique',3),
(22,'Home cinéma',3),
(23,'Piscine',4),
(24,'Jardin',4),
(25,'Balcon',4),
(26,'Terrasse',4),
(27,'BBQ',4),
(28,'Transats',4),
(29,'Hamac',4),
(30,'Ascenseur',5),
(31,'Accès handicapé',5),
(32,'Rampe d’accès',5),
(33,'WC adaptés',5),
(34,'Climatisation',6),
(35,'Chauffage',6),
(36,'Ventilateur',6),
(37,'Cheminée',6),
(38,'Jacuzzi',6),
(39,'Bureau',6),
(40,'Wifi',7),
(41,'Routeur supplémentaire',7),
(42,'Enceinte connectée',7),
(43,'Téléphone fixe',7);
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_post`
--

DROP TABLE IF EXISTS `equipment_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_post` (
  `post_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`equipment_id`),
  KEY `equipment_id` (`equipment_id`),
  CONSTRAINT `equipment_post_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  CONSTRAINT `equipment_post_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_post`
--

LOCK TABLES `equipment_post` WRITE;
/*!40000 ALTER TABLE `equipment_post` DISABLE KEYS */;
INSERT INTO `equipment_post` VALUES
(49,1),
(48,2),
(49,2),
(49,3),
(49,4),
(49,5),
(48,6),
(49,6),
(48,7),
(49,7),
(49,8),
(49,9),
(48,11),
(48,14),
(48,15),
(48,19),
(48,25),
(48,31),
(48,36),
(48,37),
(48,42);
/*!40000 ALTER TABLE `equipment_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price_day` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` int(11) NOT NULL,
  `city` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `bed_count` int(11) NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES
(1,'Maison 7 chambres mimi','Je met en location cette maison pcq tu connais les tals en vrais',48,'','Chez moi mgl',66000,'Pepri','2025-12-04 11:58:07',NULL,NULL,2,2,1,1),
(2,'ZDUvyhz','dzzzzzzzzz',15,'','dzzddzdzd',0,'dzdzdzd','2025-12-04 11:59:27',NULL,NULL,15,15,1,7),
(6,'A la bien','Hotel 8 etoiles a la bien vue sur la merde',65,'France','78 avenue poubelle',78500,'Cacaland','2025-12-05 09:05:37',NULL,NULL,1,2,1,10),
(8,'Appart sympa','Appart sympa en centre d emontpelier',84,'France','78 rue foch',74852,'Montpelier','2025-12-05 11:10:54',NULL,NULL,4,2,1,2),
(17,'Villa tranquilou','Grosse grosse vila de fou sa mer',41,'Hongrie','45 avenue papipo',8540,'Papipo','2025-12-05 15:51:01','/uploads/media_6932f155066fd3.78084295_804a2924.jpg',NULL,1,1,1,7),
(18,'Secoupe volante biorpienne','Met a dispo ma secoupe',42,'Mars','92 avenue des biorpion',8754,'Vorp','2025-12-08 11:23:36','/uploads/media_6936a728d390e3.83626197_2f904c8e.jpg',NULL,3,3,1,6),
(26,'Maison T2 privée','Maison calme avec vue sur la mer et plage privée, calme et idyllique. Parfait pour un séjour en amoureux.',87,'Grece','7 rue agéos',84753,'Athene','2025-12-08 15:45:03','/uploads/media_6936e46f7d4e45.24213031_5ce2520f.webp',NULL,1,2,1,1),
(37,'Villa 10 personnes vue sur la mer','Villa bord de la falaise',124,'Pays-Bas','7 boulevard du rist',87000,'La Haye','2025-12-08 16:13:16','/uploads/media_6936ece624d6c4.67561218_c70560f3.webp',NULL,8,10,1,7),
(38,'aaaaaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaaaaaaaaaaaa',100,'France','7 boulevard du rist',66000,'pERPI','2025-12-09 10:03:48','/uploads/media_6937e5f48d4ba3.57694362_e5586995.jpg',NULL,5,8,1,4),
(39,'aaaaaaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaaa',87,'France','7 boulevard du rist',8754,'La Haye','2025-12-09 10:05:23','/uploads/media_6937e653303d84.71021336_643be702.webp',NULL,4,5,1,8),
(43,'aaaaaaaaaaa','aaaaaaaaaaaaaa',124,'eazeaz','45 avenue papipo',66000,'Papipo','2025-12-09 10:43:34',NULL,NULL,1,1,1,8),
(47,'wwwwwwwwwwwww','wwwwwwwwwww',41,'France','7 rue agéos',8700,'Athene','2025-12-09 14:37:25','/uploads/media_69382615825170.48575731_1f50036c.jpg',NULL,1,1,1,1),
(48,'wwwwwwwwwwwww','wwwwwwwwwww',41,'France','7 rue agéos',8700,'Athene','2025-12-09 14:39:30','/uploads/media_693826924c86d5.59336464_87bfe8f1.jpg',NULL,1,1,1,6),
(49,'azdededededededededededededededede','wwwwwwwwwwwwwwwwwwz',87,'Pays-Bas','45 avenue papipo',66000,'La Haye','2025-12-09 14:45:48','/uploads/media_6938280cf3a652.65246133_a1e0904b.webp',NULL,1,1,1,3);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type`
--

LOCK TABLES `type` WRITE;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` VALUES
(1,'Maison'),
(2,'Appartement'),
(3,'Chambre d\'hôte'),
(4,'Bateau'),
(5,'Cabane'),
(6,'Caravane'),
(7,'Château'),
(8,'Tente'),
(9,'Maison d\'hôtes'),
(10,'Hôtel');
/*!40000 ALTER TABLE `type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'jeanfigue@gmail.com','$2y$10$rWR0tyEjeMmeGOLdONSTO.GxU4XyfYAAayYke67F7azRa08kTA30e','Jean','Figue','2025-12-03 16:29:31',1),
(2,'babil.marouc@gmail.com','$2y$10$QJ5zd20jp7q/h3l1Nxf4VeMQ2xB1kRMwpMXyFFxPKPZNfNp0ZaAo.','Babil','Marouc','2025-12-08 13:33:36',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-11  7:56:05
