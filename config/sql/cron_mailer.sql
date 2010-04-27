CREATE TABLE IF NOT EXISTS `queued_emails` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `to` varchar(255) default NULL,
  `from` varchar(255) default NULL,
  `replyTo` varchar(255) default NULL,
  `readReceipt` varchar(255) default NULL,
  `return` varchar(255) default NULL,
  `headers` text,
  `additionalParams` text,
  `attachments` text,
  `subject` text,
  `textMessage` longtext,
  `htmlMessage` longtext,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;