CREATE TABLE `iv_seo` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `LID` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `ACTIVE` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `CANONICAL` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `URL` varchar(766) COLLATE utf8_unicode_ci NOT NULL,
  `H1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TITLE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DESCRIPTION` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `KEYWORDS` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TEXT1` text COLLATE utf8_unicode_ci,
  `TEXT2` text COLLATE utf8_unicode_ci,
  `TEXT3` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`),
  KEY `LIDindex` (`LID`)
) ENGINE=InnoDB AUTO_INCREMENT=0;