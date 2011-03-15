CREATE TABLE `pages` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `parent` int(255) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `htmlTitle` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `htmlMetaDescription` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `htmlMetaKeywords` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `htmlContent` text COLLATE utf8_czech_ci,
  `seoLink` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `internalPointer` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `phpInclude` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `phpExe` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `tplInclude` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `menuOrder` int(10) DEFAULT '0',
  `mainMenu` enum('no','yes') COLLATE utf8_czech_ci DEFAULT 'no',
  `seo` enum('no','yes') COLLATE utf8_czech_ci DEFAULT 'yes',
  `enabled` enum('no','yes') COLLATE utf8_czech_ci DEFAULT 'no',
  `deleted` enum('no','yes') COLLATE utf8_czech_ci DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `internalPointer` (`internalPointer`),
  KEY `enabled` (`enabled`,`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `pages` (`id`, `title`, `htmlTitle`, `htmlMetaDescription`, `htmlMetaKeywords`, `htmlContent`, `seoLink`, `internalPointer`, `phpInclude`, `phpExe`, `tplInclude`, `menuOrder`, `mainMenu`, `seo`, `enabled`, `deleted`) VALUES (1, NULL, NULL, NULL, NULL, NULL, 'index', 'index', NULL, NULL, 'homepage.tpl', 0, 'yes', 1, 'yes', 'no');
INSERT INTO `pages` (`id`, `title`, `htmlTitle`, `htmlMetaDescription`, `htmlMetaKeywords`, `htmlContent`, `seoLink`, `internalPointer`, `phpInclude`, `phpExe`, `tplInclude`, `menuOrder`, `mainMenu`, `seo`, `enabled`, `deleted`) VALUES (2, NULL, NULL, NULL, NULL, NULL, '404', '404', NULL, NULL, '404.tpl', 0, 'no', 1, 'yes', 'no');
