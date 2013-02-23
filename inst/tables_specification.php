<?php

$tables = array (
  'Users' => array (
    'MySQL'   => "",
    'SQLite'  => ""
  ),
  'Companies' => array (
    'MySQL'   => "CREATE TABLE IF NOT EXISTS `Companies` (
                        `Company_id`                    int(10) unsigned                NOT NULL AUTO_INCREMENT,
                        `Company_name`                  varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_address`               varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
                        `Company_location`              varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
                        `Company_main_telephone_number` varchar(30) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_piva`                  varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_trashed`               tinyint(1) unsigned             NOT NULL DEFAULT '0',
                        PRIMARY KEY (`Company_id`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;",
    'SQLite'  => "CREATE TABLE IF NOT EXISTS Companies (
                        Company_id`                    INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        Company_name`                  VARCHAR NOT NULL,
                        Company_address`               VARCHAR NOT NULL,
                        Company_location`              VARCHAR NOT NULL,
                        Company_main_telephone_number` VARCHAR NOT NULL,
                        Company_piva`                  VARCHAR NOT NULL,
                        Company_trashed`               BOOL DEFAULT 0)"
  ),
  'Users_ACL' => array('')
);
