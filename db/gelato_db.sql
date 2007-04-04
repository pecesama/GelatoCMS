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
  `url_friendly` tinyint(1) NOT NULL,
  `rich_text` tinyint(1) NOT NULL,
  PRIMARY KEY  (`title`)
) ENGINE = MYISAM ;

-- Example data for table `gel_config`

INSERT INTO `gel_config` VALUES (10, 'Tumble title', 'tumble description', 'en', 'tumblr', 'http://localhost/gelato', 1, 1);

--  The password is "demo" without the "

INSERT INTO `gel_users` VALUES (1, 'System administrator', 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 'correo@correo.com', 'page', 'about');