DROP TABLE IF EXISTS `cpena_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cpena_articles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` text  DEFAULT NULL,
  `link` varchar(500)  DEFAULT NULL,
  `image` varchar(255)  DEFAULT NULL,
  `type` varchar(255)  DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

