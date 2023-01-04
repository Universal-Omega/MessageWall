CREATE TABLE `message_wall` (
  `mw_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mw_thread_id` varbinary(255) NOT NULL DEFAULT '',
  `mw_parent_id` varbinary(255) DEFAULT NULL,
  `mw_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `mw_timestamp` varbinary(14) NOT NULL DEFAULT '',
  `mw_latest_rev_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mw_id`),
  KEY `mw_thread_id` (`mw_thread_id`),
  KEY `mw_parent_id` (`mw_parent_id`),
  KEY `mw_user_id` (`mw_user_id`),
  KEY `mw_timestamp` (`mw_timestamp`),
  KEY `mw_latest_rev_id` (`mw_latest_rev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
