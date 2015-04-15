-- MySQL dump 10.13  Distrib 5.5.42, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: allergeeks
-- ------------------------------------------------------
-- Server version	5.5.42-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blacklist`
--

DROP TABLE IF EXISTS `blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist` (
  `user_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`ingredient_id`),
  KEY `fk_User_has_Ingredient_Ingredient1_idx` (`ingredient_id`),
  KEY `fk_User_has_Ingredient_User1_idx` (`user_id`),
  CONSTRAINT `fk_User_has_Ingredient_User1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Ingredient_Ingredient1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist`
--

LOCK TABLES `blacklist` WRITE;
/*!40000 ALTER TABLE `blacklist` DISABLE KEYS */;
INSERT INTO `blacklist` VALUES (1,1),(5,1),(1,2),(4,2),(5,2),(2,9);
/*!40000 ALTER TABLE `blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (2,'Milchprodukte (Laktose)'),(1,'Nüsse');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device`
--

DROP TABLE IF EXISTS `device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device` (
  `token` char(20) NOT NULL,
  `name` varchar(45) NOT NULL,
  `valid` datetime NOT NULL,
  `admin_right` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`token`),
  KEY `fk_Device_User1_idx` (`user_id`),
  CONSTRAINT `fk_Device_User1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device`
--

LOCK TABLES `device` WRITE;
/*!40000 ALTER TABLE `device` DISABLE KEYS */;
INSERT INTO `device` VALUES ('08fbea1d314d9c6089ec','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-16 14:07:57',1,1),('0bfb685ef01ea9d31ea2','Browser: Mozilla/5.0 (Windows NT 6.1; WOW64; ','2015-04-16 16:09:39',1,3),('11111111111111111111','Vuzix M100','2015-04-15 09:53:11',0,1),('151bc8528291b2857f7c','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 07:07:09',1,1),('15a0498031e01bec5b74','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:40:22',1,3),('1f1f1f1f1f1f1f1f1f1f','Android 5.0','2016-01-01 03:00:00',1,1),('2a0757c3e25aefd3cfd0','Browser: Mozilla/5.0 (Windows NT 6.1; WOW64; ','2015-04-12 12:59:31',1,1),('2e07fb0e93e03e292315','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:42:14',1,1),('31e87ab44d503ab333b0','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:54:22',1,3),('3822b146285ec579fd42','Wearable Device Vuzix M100','2015-04-15 09:41:49',0,1),('3ca75658bdbb66e1296a','Wearable Device Vuzix M100','2015-04-15 09:54:33',0,1),('3d8eb4bcd56efe33aeb7','Browser: Mozilla/5.0 (Windows NT 6.1; Win64; ','2015-04-15 08:28:48',1,1),('43bd21da434ab57970a3','Wearable Device Vuzix M100','2015-04-15 09:41:52',0,1),('441a45a9206612c41719','Browser: Mozilla/5.0 (X11; Linux x86_64) Appl','2015-04-15 08:26:43',1,4),('471e056e06fb21506814','Browser: Mozilla/5.0 (Windows NT 6.1; Win64; ','2015-04-15 08:26:34',1,5),('49897706ca5a92a96fb0','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:41:27',1,1),('4b814a24a0ecc46b861f','Browser: Mozilla/5.0 (X11; Linux x86_64) Appl','2015-04-16 15:26:52',1,1),('552c73c4d8090c18834b','Wearable Device Vuzix M100','2015-04-15 12:02:12',0,3),('680113d4480005585216','Wearable Device Vuzix M100','2015-04-15 09:41:58',0,1),('6929938b6b297bdf6c10','Browser: Mozilla/5.0 (X11; Linux x86_64; rv:3','2015-04-16 23:28:34',1,2),('6f40a95e8f998d0c80cb','Browser: Mozilla/5.0 (X11; Linux x86_64) Appl','2015-04-16 13:48:03',1,2),('75837b2ccbed79e119a2','Wearable Device Vuzix M100','2016-04-14 23:28:54',0,2),('7672d486f9290a416367','Wearable Device Vuzix M100','2015-04-15 09:42:02',0,1),('7abc14315536d20ec77f','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 07:08:07',1,1),('825bcd4408ad92009589','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:45:05',1,3),('882d1e02c5b68835746e','Browser: Mozilla/5.0 (X11; Linux x86_64) Appl','2015-04-15 13:41:15',1,4),('9492a28e192c702ce12e','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:54:45',1,1),('9aaea424c139cc5cf119','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:56:19',1,3),('a1d72c4933f53d67951c','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 07:07:06',1,1),('aaaaaaaaaaaaaaaaaaaa','Browser','2015-05-01 21:00:00',1,1),('ab172260a4ff17d82eae','Wearable Device Vuzix M100','2015-04-15 12:20:22',0,3),('b0b4ff4ac70bd3143863','Wearable Device Vuzix M100','2015-04-15 09:42:11',0,1),('b5ce0ccb1e1afffb84e9','Browser: Mozilla/5.0 (Windows NT 6.1; WOW64; ','2015-04-15 09:10:04',1,3),('b90c4b440676230a0c4e','Wearable Device Vuzix M100','2015-04-15 09:42:04',0,1),('bd427a5ce5ac7e962503','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:53:17',1,1),('d2fd2fd2fd2fd2fd2fd','Browser','2015-01-01 03:00:00',1,2),('dbb8300df40fd4015c91','Wearable Device Vuzix M100','2015-04-15 09:42:08',0,1),('e7a7fe9f07ee0daf9e33','Browser: Mozilla/5.0 (X11; Ubuntu; Linux x86_','2015-04-15 09:44:13',1,3),('e8d694059d6d8b347dd5','Browser: Mozilla/5.0 (X11; Linux x86_64) Appl','2015-04-16 13:47:37',1,2),('ea4afb25a6e46d61f6ef','Wearable Device Vuzix M100','2015-04-15 12:01:47',0,3),('ef0982afec9922c02a1d','Browser: Mozilla/5.0 (Windows NT 6.1; WOW64; ','2015-04-15 09:10:06',1,3);
/*!40000 ALTER TABLE `device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES (1,'Testingredient',NULL),(2,'Nueffe','May contain nuts.'),(3,'Haselnüsse',NULL),(5,'Mandeln',NULL),(6,'Cashewkerne',NULL),(7,'Sonnenblumenöl',NULL),(8,'Walnüsse',NULL),(9,'Erdnüsse',NULL),(10,'Speisesalz',NULL),(11,'Weizenmehl',NULL),(12,'Gummi Arabicum','Überzugsmittel'),(13,'Pfeffer',NULL),(14,'Sorbitol','Stabilisator'),(15,'modifizierte Kartoffelstärke',NULL),(16,'Hefeextrakt',NULL),(17,'Zucker',NULL),(18,'Wasser',NULL),(19,'Sojabohnen',''),(20,'Lithothamnium Calcareum','Meeresalge'),(21,'Kakaomasse',NULL),(22,'Zucker',''),(23,'Kakaobutter',NULL),(24,'Sojalecithine','Emulgator'),(25,'Kartoffeln',NULL),(26,'Paprika',NULL),(27,'Habanero Chili',NULL),(28,'Knoblauch',NULL),(31,'Milch',NULL),(32,'Lab',''),(33,'Lysozym','(aus Ei)\r\nKonservierungsstoff'),(34,'Fettarme Milch 1,5% (Laktosefrei)',''),(35,'Vollmilch 3,5%','');
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredient_has_categorie`
--

DROP TABLE IF EXISTS `ingredient_has_categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredient_has_categorie` (
  `ingredient_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`ingredient_id`,`category_id`),
  KEY `fk_Ingredient_has_Kategorie_Kategorie1_idx` (`category_id`),
  KEY `fk_Ingredient_has_Kategorie_Ingredient1_idx` (`ingredient_id`),
  CONSTRAINT `fk_Ingredient_has_Kategorie_Ingredient1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ingredient_has_Kategorie_Kategorie1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient_has_categorie`
--

LOCK TABLES `ingredient_has_categorie` WRITE;
/*!40000 ALTER TABLE `ingredient_has_categorie` DISABLE KEYS */;
INSERT INTO `ingredient_has_categorie` VALUES (1,1),(3,1),(5,1),(6,1),(8,1),(9,1),(31,2),(35,2);
/*!40000 ALTER TABLE `ingredient_has_categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `ean` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text,
  PRIMARY KEY (`ean`)
) ENGINE=InnoDB AUTO_INCREMENT=4311501372143 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (20219369,'Knabsi Black Pepper Erdnüsse','150g'),(23685345,'San Fabio Grana Padano','125g'),(23756267,'Penny Zartbitter-Schokolade','100g'),(23808409,'NaturGut BIO-Soja-Drink Naturell','1L'),(43115471,'Gut & Günstig Quellwasser Still','1,5L'),(1234567890123,'Testproduct','This is a test product.'),(1234567890125,'Test Toilet Paper','Does not contain Nueffe.'),(4008258154014,'Seeberger Studentenfutter','200g'),(4260168310076,'Pepper-King Habanero Kartoffelchips','125g'),(4311501318485,'EDEKA Bio Wertkost H-Vollmilch 3,8%','1L'),(4311501372142,'EDEKA Fettarme H-Milch 1,5%, Laktosefrei','1L');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_has_ingredient`
--

DROP TABLE IF EXISTS `product_has_ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_has_ingredient` (
  `product_ean` bigint(20) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `amount` float DEFAULT NULL,
  `amount_unit` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`product_ean`,`ingredient_id`),
  KEY `fk_Product_has_Ingredient_Ingredient1_idx` (`ingredient_id`),
  KEY `fk_Product_has_Ingredient_Product_idx` (`product_ean`),
  CONSTRAINT `fk_Product_has_Ingredient_Product` FOREIGN KEY (`product_ean`) REFERENCES `product` (`ean`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Product_has_Ingredient_Ingredient1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_has_ingredient`
--

LOCK TABLES `product_has_ingredient` WRITE;
/*!40000 ALTER TABLE `product_has_ingredient` DISABLE KEYS */;
INSERT INTO `product_has_ingredient` VALUES (20219369,9,NULL,NULL),(20219369,10,NULL,NULL),(20219369,11,NULL,NULL),(20219369,12,NULL,NULL),(20219369,13,NULL,NULL),(20219369,14,NULL,NULL),(20219369,15,NULL,NULL),(20219369,16,NULL,NULL),(20219369,17,NULL,NULL),(23685345,31,NULL,NULL),(23685345,32,NULL,NULL),(23685345,33,NULL,NULL),(23756267,21,NULL,NULL),(23756267,22,NULL,NULL),(23756267,23,NULL,NULL),(23756267,24,NULL,NULL),(23808409,18,NULL,NULL),(23808409,19,NULL,NULL),(23808409,20,NULL,NULL),(43115471,18,NULL,NULL),(1234567890123,1,NULL,NULL),(4008258154014,3,NULL,NULL),(4008258154014,5,NULL,NULL),(4008258154014,6,NULL,NULL),(4008258154014,7,NULL,NULL),(4008258154014,8,NULL,NULL),(4260168310076,7,NULL,NULL),(4260168310076,10,NULL,NULL),(4260168310076,17,NULL,NULL),(4260168310076,26,NULL,NULL),(4260168310076,27,NULL,NULL),(4260168310076,28,NULL,NULL),(4311501318485,34,NULL,NULL),(4311501372142,34,NULL,NULL);
/*!40000 ALTER TABLE `product_has_ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'marco.heumann@web.de','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'),(2,'test@example.com','b444ac06613fc8d63795be9ad0beaf55011936ac'),(3,'palackal@sepp.de','df85485fe016bd220f6d87485dbab7c3c4bfc243'),(4,'mail@mbaestlein.de','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'),(5,'acc@win.neubauer','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3');
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

-- Dump completed on 2015-04-16  0:20:14
