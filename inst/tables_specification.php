<?php

if ( !defined('TABLES_PREFIX') ) define('TABLES_PREFIX', '');

$tables = array (
  'Users' => array (
    'mysql'   => "",
    'sqlite'  => ""
  ),
  'Companies' => array (
    'mysql'   => "CREATE TABLE IF NOT EXISTS `".TBL_COMPANIES."` (
                        `Company_id`                    INTEGER unsigned                NOT NULL AUTO_INCREMENT,
                        `Company_name`                  varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_address`               varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
                        `Company_geo_location`          varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
                        `Company_main_telephone_number` varchar(30) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_piva`                  varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_trashed`               tinyint(1) unsigned             NOT NULL DEFAULT '0',
                        PRIMARY KEY (`Company_id`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_COMPANIES." (
                        Company_id                      INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        Company_name                    VARCHAR NOT NULL,
                        Company_address                 VARCHAR NOT NULL,
                        Company_geo_location            VARCHAR NOT NULL,
                        Company_main_telephone_number   VARCHAR NOT NULL,
                        Company_piva                    VARCHAR NOT NULL,
                        Company_trashed                 BOOL DEFAULT 0)"
  ),
  'Users'     => array(
    'mysql'   =>"CREATE TABLE IF NOT EXISTS `".TBL_USERS."` (
                        `User_id`                       integer unsigned                NOT NULL AUTO_INCREMENT,
                        `User_firstname`                varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_lastname`                 varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_username`                 varchar(20) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_password`                 varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_nickname`                 varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '' COMMENT 'the name the system will show to other users',
                        `User_contacts_internal_telephone_number` varchar(20) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_contacts_telephone_number` varchar(50)          CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_contacts_mobile`          varchar(50)  CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_contacts_email`           varchar(100) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_is_internal_company`      tinyint(1) unsigned             NOT NULL DEFAULT '1' COMMENT 'if true, the user works in the company',
                        `User_company_id`               integer                         NOT NULL,
                        `User_chainedgroup_id_catA`     integer unsigned                NOT NULL DEFAULT '0',
                        `User_chainedgroup_id_catB`     integer unsigned                NOT NULL DEFAULT '0',
                        `User_chainedgroup_id_catC`     integer unsigned                NOT NULL DEFAULT '0',
                        `User_chainedgroup_id_catD`     integer unsigned                NOT NULL DEFAULT '0',
                        `User_freegroup_id_grpA`        integer unsigned                NOT NULL DEFAULT '0',
                        `User_freegroup_id_grpB`        integer unsigned                NOT NULL DEFAULT '0',
                        `User_freegroup_id_grpC`        integer unsigned                NOT NULL DEFAULT '0',
                        `User_freegroup_id_grpD`        integer unsigned                NOT NULL DEFAULT '0',
                        `User_external_id_join`         integer                         NOT NULL DEFAULT '0' COMMENT 'to be used only if external authenication is enabled (to be developed)',
                        `User_trashed`                  tinyint(1) unsigned             NOT NULL DEFAULT '0',
                        PRIMARY KEY (`User_id`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_USERS." (
                        User_id                       INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        User_firstname                VARCHAR(50) NOT NULL,
                        User_lastname                 VARCHAR(50) NOT NULL,
                        User_username                 VARCHAR(20) NOT NULL,
                        User_password                 VARCHAR(50) NOT NULL,
                        User_nickname                 VARCHAR(50) NOT NULL,
                        User_contacts_internal_telephone_number VARCHAR(20) NOT NULL,
                        User_contacts_telephone_number VARCHAR(50)          NOT NULL,
                        User_contacts_mobile          VARCHAR(50)           NOT NULL,
                        User_contacts_email           VARCHAR(100)          NOT NULL,
                        User_is_internal_company      BOOL(1)               NOT NULL DEFAULT 1,
                        User_company_id               INTEGER               NOT NULL,
                        User_chainedgroup_id_catA     INTEGER               NOT NULL DEFAULT 0,
                        User_chainedgroup_id_catB     INTEGER               NOT NULL DEFAULT 0,
                        User_chainedgroup_id_catC     INTEGER               NOT NULL DEFAULT 0,
                        User_chainedgroup_id_catD     INTEGER               NOT NULL DEFAULT 0,
                        User_freegroup_id_grpA        INTEGER               NOT NULL DEFAULT 0,
                        User_freegroup_id_grpB        INTEGER               NOT NULL DEFAULT 0,
                        User_freegroup_id_grpC        INTEGER               NOT NULL DEFAULT 0,
                        User_freegroup_id_grpD        INTEGER               NOT NULL DEFAULT 0,
                        User_external_id_join         INTEGER               NOT NULL DEFAULT 0,
                        User_trashed                  BOOL NOT NULL DEFAULT 0)"
  )
);
