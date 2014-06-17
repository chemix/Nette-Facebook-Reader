-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `facebook_wallposts`;
CREATE TABLE `facebook_wallposts` (
  `id` varchar(100) CHARACTER SET ascii NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `type` varchar(100) CHARACTER SET ascii DEFAULT NULL,
  `status_type` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `link` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `message` text COLLATE utf8_czech_ci,
  `caption` text COLLATE utf8_czech_ci,
  `picture` text COLLATE utf8_czech_ci,
  `status` char(1) COLLATE utf8_czech_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- 2014-06-16 17:41:44
