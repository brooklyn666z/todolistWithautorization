use testdb;
/*CREATE TABLE `users` (
    `user_id` int(11) unsigned NOT NULL auto_increment,
    `user_login` varchar(30) NOT NULL,
    `user_password` varchar(32) NOT NULL,
    `user_hash` varchar(32) NOT NULL default '',
    `user_ip` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;*/
CREATE TABLE `tree` (
    `branch_id` int(11) unsigned NOT NULL auto_increment,
    `branch_parrent_id` int(11) ,
    `branch_name` varchar(32) NOT NULL default '',
    `branch_desc` varchar(32) NOT NULL default '',
    PRIMARY KEY (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;