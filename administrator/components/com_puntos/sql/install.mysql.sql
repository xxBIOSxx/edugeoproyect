CREATE TABLE IF NOT EXISTS `#__puntos_marker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL COMMENT 'FK to #__assets',
  `catid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `plz` varchar(10) NOT NULL,
  `town` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `gmlat` float(10,6) NOT NULL,
  `gmlng` float(10,6) NOT NULL,
  `description` mediumtext NOT NULL,
  `description_small` mediumtext NOT NULL,
  `picture` varchar(255) NOT NULL,
  `picture_thumb` varchar(255) NOT NULL,
  `created_by_alias` varchar(255) NOT NULL,
  `created_by_ip` int(11) unsigned NOT NULL,
  `created_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `vote` float NOT NULL DEFAULT '0',
  `votenum` int(11) NOT NULL,
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL,
  `params` text NOT NULL,
  `language` char(7) NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `import_table` varchar(255) NOT NULL COMMENT 'If we import data from 3rd party components we store the table_id here',
  `import_id` int(11) NOT NULL COMMENT 'Original id of the stored object',
  PRIMARY KEY (`id`),
  KEY `gmlat` (`gmlat`),
  KEY `gmlng` (`gmlng`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__puntos_categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_description` mediumtext NOT NULL,
  `cat_autor` varchar(255) NOT NULL,
  `cat_icon` varchar(255) NOT NULL,
  `cat_shadowicon` varchar(255) NOT NULL,
  `cat_date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL,
  `cat_image` varchar(255) NOT NULL,
  `import_table` varchar(255) NOT NULL COMMENT 'If we import data from 3rd party components we store the table_id here',
  `import_id` int(11) NOT NULL COMMENT 'Original id of the stored object',
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__puntos_kmls` (
  `puntos_kml_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `catid` int(11) NOT NULL COMMENT 'FK to #__puntos_categorie',
  `description` text NOT NULL,
  `original_filename` varchar(1024) NOT NULL,
  `mangled_filename` varchar(1024) NOT NULL,
  `mime_type` varchar(255) NOT NULL DEFAULT 'application/octet-stream',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`puntos_kml_id`)
);

CREATE TABLE IF NOT EXISTS `#__puntos_version` (
`id` int(11) NOT NULL,
`version` varchar(55) NOT NULL
) DEFAULT CHARSET=utf8;