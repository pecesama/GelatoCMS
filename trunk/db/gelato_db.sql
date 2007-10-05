-- DB Gelato CMS v0.90

CREATE TABLE `gel_data` (
  `id_post` int(11) NOT NULL auto_increment,
  `title` text NULL,
  `url` varchar(250)  default NULL,
  `description` text NULL,
  `type` tinyint(4) NOT NULL default '1',
  `date` datetime NOT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY  (`id_post`)
) ENGINE = MYISAM ;

CREATE TABLE `gel_users` (
  `id_user` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `login` varchar(100) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `email` varchar(100) default NULL,
  `website` varchar(150) default NULL,
  `about` text,
  PRIMARY KEY  (`id_user`)
) ENGINE = MYISAM ;

CREATE TABLE `gel_config` (
  `posts_limit` int(3) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `lang` varchar(10) NOT NULL,
  `template` varchar(100) NOT NULL,
  `url_installation` varchar(250) NOT NULL,
  PRIMARY KEY  (`title`)
) ENGINE = MYISAM ;

CREATE TABLE `gel_options` (
  `name` varchar(100) NOT NULL,
  `val` varchar(255) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE = MYISAM ;

CREATE TABLE `gel_comments` (
  `id_comment` int(11) NOT NULL auto_increment,
  `id_post` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(250) default NULL,
  `content` text NOT NULL,
  `ip_user` varchar(50) NOT NULL,
  `comment_date` datetime NOT NULL,
  `spam` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_comment`)
) ENGINE = MYISAM ;

CREATE TABLE `gel_feeds` (
	`id_feed` int(11) NOT NULL auto_increment,
	`url` varchar(255) NOT NULL,
	`title` varchar(255) NOT NULL,
	`type` tinyint(4) NOT NULL default '1',
	`updated_at` datetime NOT NULL,
	`error` tinyint(1) NOT NULL default '0',
	`credits` int(1) NOT NULL default '0',
	`site_url` varchar(255) NOT NULL,
	`id_user` int(10) NOT NULL,
	PRIMARY KEY  (`id_feed`)
) ENGINE=MyISAM1 ;


-- Example data for table `gel_config`

INSERT INTO `gel_config` VALUES (10, 'Tumble title', 'tumble description', 'en', 'tumblr', 'http://localhost/gelato');

-- Example data for table `gel_users`
--  The password is "demo" without the "

INSERT INTO `gel_users` VALUES (1, 'System administrator', 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 'correo@correo.com', 'page', 'about');

-- Example data for table `gel_options`

INSERT INTO `gel_options` VALUES ('url_friendly', '1');
INSERT INTO `gel_options` VALUES ('rich_text', '0');
INSERT INTO `gel_options` VALUES ('allow_comments', '0');
INSERT INTO `gel_options` VALUES ('offset_city', 'Mexico/General');
INSERT INTO `gel_options` VALUES ('offset_time', '-6');
INSERT INTO `gel_options` VALUES ('rss_import_frec', '5 minutes');
