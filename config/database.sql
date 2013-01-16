
CREATE TABLE `tl_itemjuggler` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',  
  
  `source_table` varchar(255) NOT NULL default '',
  `source_column` varchar(255) NOT NULL default '',
  
  `filterpart` text NULL,
  `action` text NULL,
  
  `dca_trigger_callback` varchar(1) NOT NULL default '',
  `dca_trigger_callback_list` text NULL,
  
  `dca_trigger_cron` varchar(1) NOT NULL default '',
  `dca_trigger_cron_list` text NULL,
  
  `logging` varchar(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
