CREATE DATABASE IF NOT EXISTS `tests`;

USE tests;

CREATE TABLE IF NOT EXISTS `basic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `basic` (`id`) VALUES (1);

CREATE TABLE IF NOT EXISTS `non_standard` (
  `non_standard` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`non_standard`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `non_standard` (`non_standard`) VALUES ('AA');
