CREATE TABLE IF NOT EXISTS `#__rsseo_broken_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `url` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `code` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__rsseo_competitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '0',
  `alexa` int(11) NOT NULL DEFAULT '-1',
  `technorati` int(11) NOT NULL DEFAULT '-1',
  `googlep` int(11) NOT NULL DEFAULT '-1',
  `bingp` int(11) NOT NULL DEFAULT '-1',
  `googleb` int(11) NOT NULL DEFAULT '-1',
  `bingb` int(11) NOT NULL DEFAULT '-1',
  `googler` int(11) NOT NULL DEFAULT '-1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `mozpagerank` int(11) NOT NULL DEFAULT '0',
  `mozpa` int(11) NOT NULL DEFAULT '0',
  `mozda` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_data` (
  `type` varchar(255) NOT NULL DEFAULT '',
  `data` longtext NOT NULL,
  PRIMARY KEY (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `error` int(5) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(500) NOT NULL DEFAULT '',
  `layout` text NOT NULL,
  `itemid` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_error_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(500) NOT NULL DEFAULT '',
  `code` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_error_links_referer` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idl` INT NOT NULL DEFAULT '0',
  `referer` VARCHAR( 500 ) NOT NULL DEFAULT '',
  `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY ( `id` ) ,
  INDEX ( `idl` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_keyword_position` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idk` INT NOT NULL DEFAULT '0',
  `position` INT NOT NULL DEFAULT '0',
  `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY ( `id` ) ,
INDEX ( `idk` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `importance` enum('low','relevant','important','critical') NOT NULL DEFAULT 'low',
  `bold` int(2) NOT NULL DEFAULT '0',
  `underline` int(2) NOT NULL DEFAULT '0',
  `limit` int(3) NOT NULL DEFAULT '0',
  `attributes` text NOT NULL,
  `link` text NOT NULL,
  `lastcheck` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__rsseo_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `hash` varchar(32) NOT NULL DEFAULT '',
  `sef` varchar(444) NOT NULL DEFAULT '',
  `title` text NOT NULL,
  `keywords` text NOT NULL,
  `keywordsdensity` text NOT NULL,
  `description` text NOT NULL,
  `sitemap` tinyint(1) NOT NULL DEFAULT '0',
  `insitemap` int(2) NOT NULL DEFAULT '0',
  `crawled` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` int(3) NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `grade` float(10,2) NOT NULL DEFAULT '-1.00',
  `params` text NOT NULL,
  `densityparams` text NOT NULL,
  `canonical` varchar(500) NOT NULL DEFAULT '',
  `robots` varchar(255) NOT NULL DEFAULT '',
  `frequency` varchar(255) NOT NULL DEFAULT '',
  `priority` varchar(255) NOT NULL DEFAULT '',
  `imagesnoalt` text NOT NULL,
  `imagesnowh` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `custom` text NOT NULL,
  `parent` varchar(333) NOT NULL DEFAULT '',
  `external` int(11) NOT NULL DEFAULT '0',
  `internal` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sef` (`sef`(333)),
  KEY `hash` (`hash`),
  KEY `hash_2` (`hash`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL DEFAULT '',
  `to` varchar(255) NOT NULL DEFAULT '',
  `type` enum('301','302') NOT NULL DEFAULT '301',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_redirects_referer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `referer` varchar(500) NOT NULL DEFAULT '',
  `url` varchar(500) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `age` varchar(255) NOT NULL DEFAULT '',
  `googlep` int(11) NOT NULL DEFAULT '0',
  `googleb` int(11) NOT NULL DEFAULT '0',
  `googler` int(11) NOT NULL DEFAULT '0',
  `bingp` int(11) NOT NULL DEFAULT '0',
  `bingb` int(11) NOT NULL DEFAULT '0',
  `alexa` int(11) NOT NULL DEFAULT '0',
  `gplus` int(11) NOT NULL DEFAULT '0',
  `fb_share_count` int(11) NOT NULL DEFAULT '0',
  `fb_like_count` int(11) NOT NULL DEFAULT '0',
  `linkedin` int(11) NOT NULL DEFAULT '0',
  `mozpagerank` int(11) NOT NULL DEFAULT '0',
  `mozpa` int(11) NOT NULL DEFAULT '0',
  `mozda` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` varchar(20) NOT NULL DEFAULT '',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `agent` varchar(500) NOT NULL DEFAULT '',
  `referer` text NOT NULL,
  `page` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `id-session_id` (`id`,`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_gkeywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `site` varchar(255) NOT NULL DEFAULT '',
  `lastcheck` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_gkeywords_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idk` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `page` varchar(500) NOT NULL DEFAULT '',
  `device` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `clicks` varchar(255) NOT NULL DEFAULT '',
  `impressions` varchar(255) NOT NULL DEFAULT '',
  `ctr` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idk` (`idk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsseo_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__rsseo_pages` (`id`, `url`, `title`, `keywords`, `keywordsdensity`, `description`, `sitemap`, `insitemap`, `crawled`, `date`, `modified`, `level`, `grade`, `params`, `densityparams`, `canonical`, `robots`, `frequency`, `priority`, `imagesnoalt`, `imagesnowh`, `published`) VALUES (1, '', '', '', '', '', 0, 0, 0, NOW(), 0, 0, 0, '', '', '', '', '', '', '', '', 1);

INSERT IGNORE INTO `#__rsseo_statistics` (`id`, `date`, `age`, `googlep`, `googleb`, `googler`, `bingp`, `bingb`, `alexa`, `gplus`, `fb_share_count`, `fb_like_count`, `linkedin`) VALUES(1, '0000-00-00 00:00:00', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);