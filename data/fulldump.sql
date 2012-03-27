-- MySQL dump 10.13  Distrib 5.1.58, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: MarkHill
-- ------------------------------------------------------
-- Server version	5.1.58-1ubuntu1

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap": true}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `category_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Haircare',6),(2,'Electrical Beauty',7);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competition_page`
--

DROP TABLE IF EXISTS `competition_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competition_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_image` varchar(255) NOT NULL COMMENT '{"widget":"image", "label":"Title image", "help":"The image used for the title"}\n             ',
  `title_image_alt_text` varchar(255) NOT NULL COMMENT '{"label":"Title image alt text", "help":"The alt text for the title image", "titlemap":true}\n             ',
  `content` mediumtext NOT NULL COMMENT '{"widget":"htmleditor", "label":"Description", "help":"The description of this competition"}\n             ',
  `image` varchar(255) DEFAULT NULL COMMENT '{"widget":"image", "label":"Side Image", "help":"The image for this competition"}\n             ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competition_page`
--

LOCK TABLES `competition_page` WRITE;
/*!40000 ALTER TABLE `competition_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `competition_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_page`
--

DROP TABLE IF EXISTS `content_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap": true, "group":"Content"}\n             ',
  `content` mediumtext COMMENT '{"widget":"htmleditor", "label": "Page content", "help":"Please enter the content for the page","group":"Content"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `content_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_page`
--

LOCK TABLES `content_page` WRITE;
/*!40000 ALTER TABLE `content_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_page`
--

DROP TABLE IF EXISTS `gallery_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"group":"Details", "titlemap":true, "label":"Gallery title", "help":"Please enter the name of the gallery"}\n             ',
  `description` mediumtext COMMENT '{"widget":"htmleditor", "help":"Description of the gallery and its contents"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `gallery_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_page`
--

LOCK TABLES `gallery_page` WRITE;
/*!40000 ALTER TABLE `gallery_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_page`
--

DROP TABLE IF EXISTS `home_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap": true,"group":"General"}\n             ',
  `middle_pod_top_image` varchar(255) NOT NULL COMMENT '{ "group":"Middle Pod", "widget": "image", "help" : "This is the image that sticks out of the top of the pod", "label": "Top image"}\n             ',
  `middle_pod_top_image_alt_text` varchar(255) DEFAULT NULL COMMENT '{ "group":"Middle Pod", "help" : "The alt text for the top image", "label": "Top image alt text"}\n             ',
  `middle_pod_content` mediumtext NOT NULL COMMENT '{ "group":"Middle Pod", "help" : "The content for the middle pod"}\n             ',
  `middle_pod_url` varchar(255) NOT NULL COMMENT '{ "group":"Middle Pod", "help" : "Where should the middle pod link to?"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `home_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_page`
--

LOCK TABLES `home_page` WRITE;
/*!40000 ALTER TABLE `home_page` DISABLE KEYS */;
INSERT INTO `home_page` VALUES (1,'Mark Hill','/uploads/new_look.png','Our Brand, New Look','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut elit quis libero lobortis dictum suscipit.','http://www.google.com',1);
/*!40000 ALTER TABLE `home_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_page`
--

DROP TABLE IF EXISTS `image_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap": true}\n             ',
  `image` varchar(255) NOT NULL COMMENT '{"widget" : "image", "help":"The image for the image page", "label" : "Image"}\n             ',
  `description` mediumtext NOT NULL COMMENT '{"widget":"htmleditor", "help":"Description of the image (be descriptive, blind people will rely on this)"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `image_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_page`
--

LOCK TABLES `image_page` WRITE;
/*!40000 ALTER TABLE `image_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `image_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookbook_category`
--

DROP TABLE IF EXISTS `lookbook_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookbook_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE_idx` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookbook_category`
--

LOCK TABLES `lookbook_category` WRITE;
/*!40000 ALTER TABLE `lookbook_category` DISABLE KEYS */;
INSERT INTO `lookbook_category` VALUES (3,'Green Carpet Styles'),(1,'Red Carpet Styles'),(2,'Yellow Carpet Styles');
/*!40000 ALTER TABLE `lookbook_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookbook_filter`
--

DROP TABLE IF EXISTS `lookbook_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookbook_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookbook_filter`
--

LOCK TABLES `lookbook_filter` WRITE;
/*!40000 ALTER TABLE `lookbook_filter` DISABLE KEYS */;
INSERT INTO `lookbook_filter` VALUES (1,'Long'),(2,'Medium'),(3,'Short');
/*!40000 ALTER TABLE `lookbook_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookbook_hairstyle`
--

DROP TABLE IF EXISTS `lookbook_hairstyle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookbook_hairstyle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookbook_hairstyle`
--

LOCK TABLES `lookbook_hairstyle` WRITE;
/*!40000 ALTER TABLE `lookbook_hairstyle` DISABLE KEYS */;
INSERT INTO `lookbook_hairstyle` VALUES (1,'Green Raven Long','/uploads/usericon.png','2012-02-15 17:14:33','2012-02-16 17:38:45'),(3,'Green Raven Short','/uploads/usericon.png','2012-02-16 12:19:51','2012-02-16 14:35:03'),(4,'Green Raven Short','/uploads/favourites.png','2012-02-16 15:10:43','2012-02-16 17:38:57'),(5,'Green Raven Short 2','/uploads/usericon.png','2012-02-16 15:13:09','2012-02-16 17:39:08'),(6,'short 1','/uploads/short1.jpg','2012-02-16 17:36:49','2012-02-16 17:36:49'),(7,'short 2','/uploads/short2.jpg','2012-02-16 17:37:10','2012-02-16 17:37:10'),(8,'medium 1','/uploads/med1.jpg','2012-02-16 17:37:30','2012-02-16 17:37:30'),(9,'medium 2','/uploads/med2.jpg','2012-02-16 17:37:47','2012-02-16 17:37:47'),(10,'long 1','/uploads/long1.jpg','2012-02-16 17:38:06','2012-02-16 17:38:06'),(11,'long 2','/uploads/long2.jpg','2012-02-16 17:38:23','2012-02-16 17:38:23'),(12,'long 3','/uploads/long3.jpg','2012-02-21 11:34:22','2012-02-21 11:34:22'),(13,'long 4','/uploads/long4.jpg','2012-02-21 11:34:40','2012-02-21 11:34:40'),(14,'long 5','/uploads/long5.jpg','2012-02-21 11:35:00','2012-02-21 11:35:00'),(15,'long 6','/uploads/long6.jpg','2012-02-21 11:35:41','2012-02-21 11:35:41'),(16,'medium 3','/uploads/med3.jpg','2012-02-21 11:36:04','2012-02-21 11:36:04'),(17,'medium 4','/uploads/med5.jpg','2012-02-21 11:36:27','2012-02-21 11:36:27'),(18,'medium 5','/uploads/med4.jpg','2012-02-21 11:36:55','2012-02-21 11:36:55'),(19,'medium 6','/uploads/med6.jpg','2012-02-21 11:37:18','2012-02-21 11:37:18'),(20,'short 3','/uploads/short3.jpg','2012-02-21 11:37:42','2012-02-21 11:37:42'),(21,'short 4','/uploads/short4.jpg','2012-02-21 11:38:17','2012-02-21 11:38:17'),(22,'short 5','/uploads/short5.jpg','2012-02-21 11:38:35','2012-02-21 11:38:35'),(23,'short 6','/uploads/short6.jpg','2012-02-21 11:38:55','2012-02-21 11:38:55');
/*!40000 ALTER TABLE `lookbook_hairstyle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookbook_hairstyle_has_lookbook_category`
--

DROP TABLE IF EXISTS `lookbook_hairstyle_has_lookbook_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookbook_hairstyle_has_lookbook_category` (
  `lookbook_hairstyle_id` int(11) NOT NULL DEFAULT '0',
  `lookbook_category_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lookbook_hairstyle_id`,`lookbook_category_id`),
  KEY `hairstyle_has_lookbook_category_idx` (`lookbook_category_id`),
  KEY `hairstyle_has_lookbook_idx` (`lookbook_hairstyle_id`),
  CONSTRAINT `llli` FOREIGN KEY (`lookbook_hairstyle_id`) REFERENCES `lookbook_hairstyle` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `llli_1` FOREIGN KEY (`lookbook_category_id`) REFERENCES `lookbook_category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookbook_hairstyle_has_lookbook_category`
--

LOCK TABLES `lookbook_hairstyle_has_lookbook_category` WRITE;
/*!40000 ALTER TABLE `lookbook_hairstyle_has_lookbook_category` DISABLE KEYS */;
INSERT INTO `lookbook_hairstyle_has_lookbook_category` VALUES (6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(1,3),(3,3),(4,3),(5,3);
/*!40000 ALTER TABLE `lookbook_hairstyle_has_lookbook_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookbook_hairstyle_has_lookbook_filter`
--

DROP TABLE IF EXISTS `lookbook_hairstyle_has_lookbook_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookbook_hairstyle_has_lookbook_filter` (
  `lookbook_hairstyle_id` int(11) NOT NULL DEFAULT '0',
  `lookbook_filter_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lookbook_hairstyle_id`,`lookbook_filter_id`),
  KEY `hairstyle_has_lookbook_idx` (`lookbook_filter_id`),
  KEY `hairstyle_has_filter_idx` (`lookbook_hairstyle_id`),
  CONSTRAINT `llli_2` FOREIGN KEY (`lookbook_hairstyle_id`) REFERENCES `lookbook_hairstyle` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `llli_3` FOREIGN KEY (`lookbook_filter_id`) REFERENCES `lookbook_filter` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookbook_hairstyle_has_lookbook_filter`
--

LOCK TABLES `lookbook_hairstyle_has_lookbook_filter` WRITE;
/*!40000 ALTER TABLE `lookbook_hairstyle_has_lookbook_filter` DISABLE KEYS */;
INSERT INTO `lookbook_hairstyle_has_lookbook_filter` VALUES (1,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(8,2),(9,2),(16,2),(17,2),(18,2),(19,2),(3,3),(4,3),(5,3),(6,3),(7,3),(20,3),(21,3),(22,3),(23,3);
/*!40000 ALTER TABLE `lookbook_hairstyle_has_lookbook_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookbook_hairstyle_has_product_page`
--

DROP TABLE IF EXISTS `lookbook_hairstyle_has_product_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookbook_hairstyle_has_product_page` (
  `lookbook_hairstyle_id` int(11) NOT NULL DEFAULT '0',
  `product_page_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lookbook_hairstyle_id`,`product_page_id`),
  KEY `hairstyle_has_product_page_idx` (`product_page_id`),
  KEY `hairstyle_has_product_idx` (`lookbook_hairstyle_id`),
  CONSTRAINT `llli_4` FOREIGN KEY (`lookbook_hairstyle_id`) REFERENCES `lookbook_hairstyle` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `lppi` FOREIGN KEY (`product_page_id`) REFERENCES `product_page` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookbook_hairstyle_has_product_page`
--

LOCK TABLES `lookbook_hairstyle_has_product_page` WRITE;
/*!40000 ALTER TABLE `lookbook_hairstyle_has_product_page` DISABLE KEYS */;
INSERT INTO `lookbook_hairstyle_has_product_page` VALUES (1,1);
/*!40000 ALTER TABLE `lookbook_hairstyle_has_product_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_article`
--

DROP TABLE IF EXISTS `news_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap": true, "group":"General"}\n             ',
  `publish_date` datetime NOT NULL COMMENT '{"group":"General", "options" : {"date":{"format":"%day%/%month%/%year%"}}}\n             ',
  `content` mediumtext NOT NULL COMMENT '{"widget" : "htmleditor", "help":"The content for the news article", "label" : "Article content", "group":"General"}\n             ',
  `listing_image` varchar(255) NOT NULL COMMENT '{"widget" : "image", "help":"The image for the listing page", "label" : "Listing image", "group":"General"}\n             ',
  `listing_image_alt_text` varchar(255) DEFAULT NULL COMMENT '{"help":"The alt text for the image on the listing page", "label" : "Listing image alt text", "group":"General"}\n             ',
  `article_image` varchar(255) NOT NULL COMMENT '{"widget" : "image", "help":"The image for the news article", "label" : "Article Image", "group":"Image"}\n             ',
  `article_image_alt_text` varchar(255) DEFAULT NULL COMMENT '{"group":"Image", "label" : "Image alt text", "help" : "The alt text for this image"}\n             ',
  `article_image_caption` mediumtext COMMENT '{"help": "The caption for the image", "label" : "Image caption", "group":"Image"}\n             ',
  `article_video` varchar(255) DEFAULT NULL COMMENT '{"group":"Image","widget":"sfWidgetFormYoutube", "label": "Associated YouTube video", "help":"YouTube video that will be linked to from the image (optional)", "options": {"account":"Mikeemoo1000"}}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `news_article_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_article`
--

LOCK TABLES `news_article` WRITE;
/*!40000 ALTER TABLE `news_article` DISABLE KEYS */;
INSERT INTO `news_article` VALUES (1,'headline goes here','2012-01-25 13:10:00','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam gravida interdum mattis. Aenean id eros ac lacus vulputate vestibulum dignissim ut ante. Maecenas sed vulputate ipsum. Ut tempus auctor odio rutrum feugiat. Donec bibendum porta rutrum. Nullam aliquet sem quis neque interdum iaculis. Sed hendrerit, dui fringilla elementum tempor, massa metus egestas mi, vitae luctus lorem augue et risus. Integer diam quam, placerat at lobortis vitae, tristique non purus.</p>\r\n<p>In hac habitasse platea dictumst. Suspendisse quis ligula faucibus lectus viverra dapibus vel at felis. Nunc ac lectus et eros commodo volutpat. Phasellus nec neque felis, nec elementum elit. Praesent sed molestie sapien. Ut facilisis lacinia fermentum. Phasellus vitae laoreet orci. Donec luctus nisi condimentum nibh accumsan sit amet porta mauris tempus. Phasellus pretium iaculis enim, in pharetra lorem auctor vehicula. Maecenas mollis feugiat mauris eu semper. Maecenas odio sem, gravida ut convallis vitae, auctor sed massa. Phasellus metus justo, mollis in faucibus eu, cursus eu risus. Mauris luctus vulputate odio, sollicitudin ullamcorper diam euismod in.</p>\r\n<p>Cras eu ante et enim eleifend auctor non eget elit. Phasellus malesuada fermentum augue vitae commodo. Curabitur ipsum orci, fringilla sit amet volutpat sit amet, pellentesque a quam. Morbi vitae lorem ac neque mattis interdum nec eget erat. Ut venenatis vehicula neque lobortis consectetur. Duis egestas hendrerit ultrices. Suspendisse eleifend, turpis sit amet hendrerit placerat, felis dolor tincidunt leo, quis sollicitudin eros mauris ac dolor. Morbi a quam orci, ut egestas neque. Nulla libero urna, posuere ac dignissim sed, hendrerit rutrum est.</p>\r\n<p>Praesent ullamcorper blandit lacus vitae gravida. Aenean non lacus at arcu suscipit molestie. Ut quis eros sit amet lorem faucibus facilisis. Nullam ultrices pretium lacinia. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum dignissim aliquam libero imperdiet ultricies. Ut congue eleifend sagittis. Nulla sit amet eros eget justo varius rhoncus ac eget nisl.</p>\r\n<p>Duis sollicitudin mollis odio, vitae tristique tellus hendrerit pellentesque. Nam sollicitudin bibendum ipsum non eleifend. Fusce a odio sed velit consequat vestibulum. Mauris pellentesque fermentum lacinia. Nulla facilisi. Phasellus urna magna, ullamcorper ut molestie non, consequat eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>','/uploads/hydrangeas.jpg','alt text for the listing image','/uploads/hydrangeas.jpg','alt text for image','This is the caption for the image','',3);
/*!40000 ALTER TABLE `news_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_listing_page`
--

DROP TABLE IF EXISTS `news_listing_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_listing_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap": true, "group":"General"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `news_listing_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_listing_page`
--

LOCK TABLES `news_listing_page` WRITE;
/*!40000 ALTER TABLE `news_listing_page` DISABLE KEYS */;
INSERT INTO `news_listing_page` VALUES (1,'Salon Gossip',2);
/*!40000 ALTER TABLE `news_listing_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `path`
--

DROP TABLE IF EXISTS `path`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_type` varchar(45) NOT NULL,
  `object_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `meta_page_title` varchar(255) DEFAULT NULL,
  `meta_navigation_title` varchar(255) DEFAULT NULL,
  `meta_path` varchar(255) DEFAULT NULL,
  `meta_keywords` mediumtext,
  `meta_description` mediumtext,
  `meta_visible_in_navigation` tinyint(1) NOT NULL DEFAULT '1',
  `root_id` bigint(20) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `level` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `path`
--

LOCK TABLES `path` WRITE;
/*!40000 ALTER TABLE `path` DISABLE KEYS */;
INSERT INTO `path` VALUES (1,'HomePage',1,'2012-02-03 18:34:11','2012-02-05 12:50:54','','Mark Hill','','','',1,1,1,16,0),(2,'NewsListingPage',1,'2012-02-05 12:20:21','2012-02-05 12:20:21','Salon Gossip','Salon Gossip','salon-gossip','','',1,1,10,13,1),(3,'NewsArticle',1,'2012-02-05 12:44:03','2012-02-05 12:44:03','headline goes here','headline goes here','headline-goes-here','','',1,1,11,12,2),(4,'TeamListingPage',1,'2012-02-05 16:58:07','2012-02-05 16:58:07','Mark Hill Salon','Mark Hill Salon','mark-hill-salon','','',1,1,14,15,1),(5,'ProductRange',1,'2012-02-11 15:36:43','2012-02-11 16:03:47','Accessories','Accessories','accessories','','',1,1,6,9,1),(6,'Category',1,'2012-02-11 18:51:45','2012-02-11 18:51:45','Haircare','Haircare','haircare','','',1,1,2,3,1),(7,'Category',2,'2012-02-11 18:52:11','2012-02-11 18:52:11','Electrical Beauty','Electrical Beauty','electrical-beauty','','',1,1,4,5,1),(8,'ProductPage',1,'2012-02-12 16:28:05','2012-02-12 16:28:05','Bedazzled Anti-humidity Shine Spray','Bedazzled Anti-humidity Shine Spray','bedazzled-anti-humidity-shine-spray','','',1,1,7,8,2);
/*!40000 ALTER TABLE `path` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_page`
--

DROP TABLE IF EXISTS `product_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"group":"Details", "titlemap":true, "label":"Product title", "help":"Please enter the name of the product"}\n             ',
  `range_page_introduction` mediumtext NOT NULL COMMENT '{"group":"Details", "label":"Range page introduction", "help": "The introductory text on the range page"}\n             ',
  `product_details` mediumtext NOT NULL COMMENT '{"group":"Details", "widget":"htmleditor", "label":"Product details", "help":"Please enter the full product description"}\n             ',
  `rrp` varchar(20) NOT NULL COMMENT '{"group":"Details", "label":"RRP", "help":"Please enter the recommended retail price"}\n             ',
  `rrp_size` varchar(100) DEFAULT NULL COMMENT '{"group":"Details", "label":"RRP Size", "help":"i.e. 250ml"}\n             ',
  `buy_now_link` varchar(255) DEFAULT NULL COMMENT '{"group":"Details", "label" : "Buy now URL", "help" : "Please enter the url for the Buy Now button"}\n             ',
  `range_page_image` varchar(255) NOT NULL COMMENT '{"group":"Images","widget":"image", "help":"The image used in the product list on the range page"}\n             ',
  `small_image` varchar(255) NOT NULL COMMENT '{"group":"Images", "widget":"image", "label":"Small image", "help":"Please select the image to use. Note: This must be a transparent PNG of the dimensions 100px x 300px. If also adding a packshot please use a 300px x 300px image"}\n             ',
  `small_image_alt_text` varchar(255) NOT NULL COMMENT '{"group":"Images","help":"The alt text for the product image"}\n             ',
  `large_image` varchar(255) DEFAULT NULL COMMENT '{"group":"Images","widget":"image", "help":"The large image of the product for when its magnified. Dimensions must be 400px x 400px"}\n             ',
  `packshot` varchar(255) DEFAULT NULL COMMENT '{"group":"Images","widget":"image", "help":"Adding a packshot will change the template"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `product_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_page`
--

LOCK TABLES `product_page` WRITE;
/*!40000 ALTER TABLE `product_page` DISABLE KEYS */;
INSERT INTO `product_page` VALUES (1,'Bedazzled Anti-humidity Shine Spray','This ultra-light spray seals each strand of hair with an invisible jacket, protecting your style and adding a beautiful long lasting shine.','<p>This ultra-light spray seals each strand of hair with an invisible jacket, protecting your style and adding a beautiful long lasting shine.</p>','£6.99','250ml','http://www.google.com','/uploads/ranges/product_range_pic.png','/uploads/hydrangeas.jpg','test','',NULL,8);
/*!40000 ALTER TABLE `product_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_range`
--

DROP TABLE IF EXISTS `product_range`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_range` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_text` varchar(255) NOT NULL COMMENT '{"titlemap": true, "label": "Title text", "help" : "This is the text used for the alt tag of the range title"}\n             ',
  `title_image` varchar(255) NOT NULL COMMENT '{"widget":"image", "label":"Title image", "help":"This is the title image. An image is used so this can be customised more"}\n             ',
  `introduction` mediumtext NOT NULL COMMENT '{"widget":"htmleditor", "label":"Introduction", "help":"This is the introduction to the range"}\n             ',
  `model_image` varchar(255) NOT NULL COMMENT '{"widget":"image", "help":"This is the model image used for the background of every product within the range"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `product_range_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_range`
--

LOCK TABLES `product_range` WRITE;
/*!40000 ALTER TABLE `product_range` DISABLE KEYS */;
INSERT INTO `product_range` VALUES (1,'Accessories','/uploads/hydrangeas.jpg','<h3>Fed up with Frizz - Feeling frazzled with flyaways?</h3>\r\n<p>If you\'ve got frizzy hair, then you\'ll know just how annoying it can be! Wave goodbye to frizz and say hello to gorgeously smooth hair! Defrizz-licious is packed with all the frizz-busting, moisture- quenching, humidity-blocking ingredients your hair will ever need! With three super-smoothing products in the range, you\'ll soon be frizz-free every day!</p>','/uploads/rangepage_bg.jpg',5);
/*!40000 ALTER TABLE `product_range` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_forgot_password`
--

DROP TABLE IF EXISTS `sf_guard_forgot_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_forgot_password` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `unique_key` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `sf_guard_forgot_password_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_forgot_password`
--

LOCK TABLES `sf_guard_forgot_password` WRITE;
/*!40000 ALTER TABLE `sf_guard_forgot_password` DISABLE KEYS */;
/*!40000 ALTER TABLE `sf_guard_forgot_password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_group`
--

DROP TABLE IF EXISTS `sf_guard_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_group`
--

LOCK TABLES `sf_guard_group` WRITE;
/*!40000 ALTER TABLE `sf_guard_group` DISABLE KEYS */;
INSERT INTO `sf_guard_group` VALUES (1,'admin','Administrator group','2012-02-02 21:14:16','2012-02-02 21:14:16');
/*!40000 ALTER TABLE `sf_guard_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_group_permission`
--

DROP TABLE IF EXISTS `sf_guard_group_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_group_permission` (
  `group_id` bigint(20) NOT NULL DEFAULT '0',
  `permission_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`group_id`,`permission_id`),
  KEY `sf_guard_group_permission_permission_id_sf_guard_permission_id` (`permission_id`),
  CONSTRAINT `sf_guard_group_permission_group_id_sf_guard_group_id` FOREIGN KEY (`group_id`) REFERENCES `sf_guard_group` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sf_guard_group_permission_permission_id_sf_guard_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `sf_guard_permission` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_group_permission`
--

LOCK TABLES `sf_guard_group_permission` WRITE;
/*!40000 ALTER TABLE `sf_guard_group_permission` DISABLE KEYS */;
INSERT INTO `sf_guard_group_permission` VALUES (1,1,'2012-02-02 21:14:16','2012-02-02 21:14:16');
/*!40000 ALTER TABLE `sf_guard_group_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_permission`
--

DROP TABLE IF EXISTS `sf_guard_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_permission`
--

LOCK TABLES `sf_guard_permission` WRITE;
/*!40000 ALTER TABLE `sf_guard_permission` DISABLE KEYS */;
INSERT INTO `sf_guard_permission` VALUES (1,'admin','Administrator permission','2012-02-02 21:14:16','2012-02-02 21:14:16');
/*!40000 ALTER TABLE `sf_guard_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_remember_key`
--

DROP TABLE IF EXISTS `sf_guard_remember_key`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_remember_key` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `remember_key` varchar(32) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `sf_guard_remember_key_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_remember_key`
--

LOCK TABLES `sf_guard_remember_key` WRITE;
/*!40000 ALTER TABLE `sf_guard_remember_key` DISABLE KEYS */;
INSERT INTO `sf_guard_remember_key` VALUES (1,1,'l21j2yvglts0c8c8w8gc8goo40wgssc','127.0.0.1','2012-02-17 09:48:13','2012-02-17 09:48:13');
/*!40000 ALTER TABLE `sf_guard_remember_key` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_user`
--

DROP TABLE IF EXISTS `sf_guard_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) NOT NULL,
  `username` varchar(128) NOT NULL,
  `algorithm` varchar(128) NOT NULL DEFAULT 'sha1',
  `salt` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_super_admin` tinyint(1) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email_address`),
  UNIQUE KEY `username` (`username`),
  KEY `is_active_idx_idx` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_user`
--

LOCK TABLES `sf_guard_user` WRITE;
/*!40000 ALTER TABLE `sf_guard_user` DISABLE KEYS */;
INSERT INTO `sf_guard_user` VALUES (1,'John','Doe','john.doe@gmail.com','admin','sha1','d0a5f0fcee0efde0935024a20f2a21c3','766613af0602e375434c0ecee2f7d06e6f657ec5',1,1,'2012-02-21 11:33:54','2012-02-02 21:14:16','2012-02-21 11:33:54');
/*!40000 ALTER TABLE `sf_guard_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_user_group`
--

DROP TABLE IF EXISTS `sf_guard_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_user_group` (
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `group_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `sf_guard_user_group_group_id_sf_guard_group_id` (`group_id`),
  CONSTRAINT `sf_guard_user_group_group_id_sf_guard_group_id` FOREIGN KEY (`group_id`) REFERENCES `sf_guard_group` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sf_guard_user_group_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_user_group`
--

LOCK TABLES `sf_guard_user_group` WRITE;
/*!40000 ALTER TABLE `sf_guard_user_group` DISABLE KEYS */;
INSERT INTO `sf_guard_user_group` VALUES (1,1,'2012-02-02 21:14:16','2012-02-02 21:14:16');
/*!40000 ALTER TABLE `sf_guard_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf_guard_user_permission`
--

DROP TABLE IF EXISTS `sf_guard_user_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_user_permission` (
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `permission_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `sf_guard_user_permission_permission_id_sf_guard_permission_id` (`permission_id`),
  CONSTRAINT `sf_guard_user_permission_permission_id_sf_guard_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `sf_guard_permission` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sf_guard_user_permission_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_guard_user_permission`
--

LOCK TABLES `sf_guard_user_permission` WRITE;
/*!40000 ALTER TABLE `sf_guard_user_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `sf_guard_user_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_listing_page`
--

DROP TABLE IF EXISTS `team_listing_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_listing_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '{"titlemap":true, "group":"General"}\n             ',
  `left_side_content` mediumtext COMMENT '{"widget" : "htmleditor", "help":"The content for the left side of the page", "label" : "Left side content", "group":"General"}\n             ',
  `bottom_right_side_content` mediumtext COMMENT '{"widget" : "htmleditor", "help":"The content for block on the right side, below the team member list", "group":"General"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `team_listing_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_listing_page`
--

LOCK TABLES `team_listing_page` WRITE;
/*!40000 ALTER TABLE `team_listing_page` DISABLE KEYS */;
INSERT INTO `team_listing_page` VALUES (1,'Mark Hill Salon','<h2>The Salon</h2>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sit amet gravida mauris. Morbi condimentum odio turpis, non blandit velit. Vestibulum ultricies magna eget sapien aliquam feugiat. Nulla facilisi. Nulla adipiscing tincidunt urna ut semper. In fringilla varius vulputate. Nunc ac porttitor augue. Phasellus adipiscing varius ipsum, quis laoreet tortor rhoncus ut. Maecenas congue consectetur dignissim. Aenean porta blandit mauris, non rutrum justo faucibus at. Sed tincidunt velit id sem pretium congue. Nulla facilisi. Aliquam mattis mattis dui, vitae elementum magna pharetra dignissim. Sed et felis nec sapien placerat dignissim nec vel nisi. Ut facilisis dolor erat, eget faucibus ipsum.</p>\r\n<p>Integer convallis iaculis lacinia. Nunc a odio quis risus consectetur interdum. Sed sed sodales turpis. Fusce et sagittis urna. Nam iaculis tortor ut libero dictum eget imperdiet leo aliquam. Aliquam at turpis at nulla sollicitudin mattis. Etiam enim mi, varius sed dignissim id, facilisis eget tortor. Mauris ullamcorper, diam ut sodales feugiat, enim risus suscipit massa, et lobortis mauris dolor ut felis. Integer ut magna convallis velit vulputate tempor at sit amet arcu. Pellentesque tristique scelerisque dui quis tincidunt. Fusce eu massa id tellus fermentum congue.</p>',NULL,4);
/*!40000 ALTER TABLE `team_listing_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_member`
--

DROP TABLE IF EXISTS `team_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '{"titlemap": true,"label": "Team members name", "help":"The team members name"}\n             ',
  `strapline` varchar(255) NOT NULL COMMENT '{"titlemap": true,"label": "Strap line", "help":"The strap line that appears next to the team members name"}\n             ',
  `behind_the_scenes` tinyint(1) NOT NULL COMMENT '{"help": "Is the team member behind the scenes?"}\n             ',
  `thumbnail_image` varchar(255) NOT NULL COMMENT '{"widget":"image", "label": "Thumbnail Image", "help": "A thumbnail picture of the team member"}\n             ',
  `main_image` varchar(255) NOT NULL COMMENT '{"widget":"image"}\n             ',
  `about` mediumtext NOT NULL COMMENT '{"widget":"htmleditor", "help":"Information about the team member"}\n             ',
  `additional_information` mediumtext COMMENT '{"widget":"htmleditor", "help":"Any additional information to appear under the image"}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `team_member_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_member`
--

LOCK TABLES `team_member` WRITE;
/*!40000 ALTER TABLE `team_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_page`
--

DROP TABLE IF EXISTS `video_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `introduction` mediumtext NOT NULL COMMENT '{"widget":"htmleditor", "help":"The introductory text that appears at the top of the page","group":"Introduction"}\n             ',
  `category_1_title` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 1"}\n             ',
  `category_1_subtitle` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 1"}\n             ',
  `video_1` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 1","widget":"youtube", "options": {"account":"Mikeemoo1000"}}\n             ',
  `video_2` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 1","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `video_3` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 1","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `video_4` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 1","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `category_2_title` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 2"}\n             ',
  `category_2_subtitle` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 2"}\n             ',
  `video_5` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 2", "label":"Video 1","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `video_6` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 2", "label":"Video 2","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `video_7` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 2", "label":"Video 3","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `video_8` varchar(255) DEFAULT NULL COMMENT '{"group":"Category 2", "label":"Video 4","widget":"youtube", "options": {"account":"Mikeemoo1000"}}}\n             ',
  `path_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path_id_idx` (`path_id`),
  CONSTRAINT `video_page_path_id_path_id` FOREIGN KEY (`path_id`) REFERENCES `path` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_page`
--

LOCK TABLES `video_page` WRITE;
/*!40000 ALTER TABLE `video_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_page` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-02-22 17:33:50
