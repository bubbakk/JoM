<?php

if ( !defined('TABLES_PREFIX') ) define('TABLES_PREFIX', '');

$tables = array(
  'Users' => array(
    'mysql'   => "",
    'sqlite'  => ""
  ),
  'Companies' => array(
    'mysql'   => "CREATE TABLE IF NOT EXISTS `".TBL_COMPANIES."` (
                        `Company_id`                    INTEGER unsigned                NOT NULL AUTO_INCREMENT,
                        `Company_name`                  varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_address`               varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
                        `Company_geo_location`          varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
                        `Company_main_telephone_number` varchar(30) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_piva`                  varchar(50) CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `Company_trashed`               tinyint(1) unsigned             NOT NULL DEFAULT '0',
                        PRIMARY KEY (`Company_id`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8",
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
                        PRIMARY KEY (`User_id`),
                        UNIQUE  KEY `USERNAME` (`User_username`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_USERS." (
                        User_id                       INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        User_firstname                VARCHAR(50)           NOT NULL,
                        User_lastname                 VARCHAR(50)           NOT NULL,
                        User_username                 VARCHAR(20)           NOT NULL,
                        User_password                 VARCHAR(50)           NOT NULL,
                        User_nickname                 VARCHAR(50)           NOT NULL,
                        User_contacts_internal_telephone_number VARCHAR(20) NOT NULL,
                        User_contacts_telephone_number VARCHAR(50)          NOT NULL,
                        User_contacts_mobile          VARCHAR(50)           NOT NULL,
                        User_contacts_email           VARCHAR(100)          NOT NULL,
                        User_is_internal_company      BOOL                  NOT NULL DEFAULT 1,
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
                        User_trashed                  BOOL                  NOT NULL DEFAULT 0)"
  ),
  'Jobs' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_JOBS."` (
                        `Job_id`                      INTEGER unsigned      NOT NULL AUTO_INCREMENT,
                        `Job_subject`                 varchar(255)          NOT NULL DEFAULT '',
                        `Job_description`             text                  NOT NULL,
                        `Job_category_level_1`        INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_category_level_2`        INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_category_level_3`        INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_tags`                    text                  NOT NULL DEFAULT '',
                        `Job_priority`                tinyint(4) unsigned   NOT NULL DEFAULT '1',
                        `Job_creation_datetime`       INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_percent_completed`       tinyint(1) unsigned   NOT NULL DEFAULT '0',
                        `Job_attachment_1`            mediumblob            NOT NULL,
                        `Job_attachment_2`            mediumblob            NOT NULL,
                        `Job_deadline_datetime`       INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_assigned_to_User`        INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_assigned_to_chainedgroup_cat_A` INTEGER unsigned NOT NULL DEFAULT '0',
                        `Job_assigned_to_chainedgroup_cat_B` INTEGER unsigned NOT NULL DEFAULT '0',
                        `Job_assigned_to_chainedgroup_cat_C` INTEGER unsigned NOT NULL DEFAULT '0',
                        `Job_assigned_to_chainedgroup_cat_D` INTEGER unsigned NOT NULL DEFAULT '0',
                        `Job_assigned_to_freegroup_A` INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_assigned_to_freegroup_B` INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_assigned_to_freegroup_C` INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_assigned_to_freegroup_D` INTEGER unsigned      NOT NULL DEFAULT '0',
                        `Job_trashed`                 tinyint(1)            NOT NULL DEFAULT '0',
                        PRIMARY KEY (`Job_id`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
    'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_JOBS." (
                        Job_id                      INTEGER       PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        Job_subject                 VARCHAR       NOT NULL DEFAULT '',
                        Job_description             TEXT          NOT NULL,
                        Job_category_level_1        INTEGER       NOT NULL DEFAULT 0,
                        Job_category_level_2        INTEGER       NOT NULL DEFAULT 0,
                        Job_category_level_3        INTEGER       NOT NULL DEFAULT 0,
                        Job_tags                    TEXT          NOT NULL DEFAULT '',
                        Job_priority                INTEGER       NOT NULL DEFAULT 1,
                        Job_creation_datetime       INTEGER       NOT NULL DEFAULT 0,
                        Job_percent_completed       INTEGER       NOT NULL DEFAULT 0,
                        Job_attachment_1            BLOB          NOT NULL,
                        Job_attachment_2            BLOB          NOT NULL,
                        Job_deadline_datetime       INTEGER       NOT NULL DEFAULT 0,
                        Job_assigned_to_User        INTEGER       NOT NULL DEFAULT 0,
                        Job_assigned_to_chainedgroup_cat_A INTEGER  NOT NULL DEFAULT 0,
                        Job_assigned_to_chainedgroup_cat_B INTEGER  NOT NULL DEFAULT 0,
                        Job_assigned_to_chainedgroup_cat_C INTEGER  NOT NULL DEFAULT 0,
                        Job_assigned_to_chainedgroup_cat_D INTEGER  NOT NULL DEFAULT 0,
                        Job_assigned_to_freegroup_A INTEGER       NOT NULL DEFAULT 0,
                        Job_assigned_to_freegroup_B INTEGER       NOT NULL DEFAULT 0,
                        Job_assigned_to_freegroup_C INTEGER       NOT NULL DEFAULT 0,
                        Job_assigned_to_freegroup_D INTEGER       NOT NULL DEFAULT 0,
                        Job_trashed                 INTEGER       NOT NULL DEFAULT 0)"
  ),
  'Categories_A' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_CATEGORIES_A."` (
                          `Category_A_id`            int(10) unsigned    NOT NULL AUTO_INCREMENT,
                          `Category_A_name`          varchar(50)         NOT NULL DEFAULT '',
                          `Category_A_description`   varchar(255)        NOT NULL DEFAULT '',
                          `Category_A_trashed`       tinyint(1) unsigned NOT NULL DEFAULT '0',
                          PRIMARY KEY (`Category_A_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_CATEGORIES_A." (
                          Category_A_id              INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                          Category_A_name            VARCHAR NOT NULL DEFAULT '',
                          Category_A_description     VARCHAR NOT NULL DEFAULT '',
                          Category_A_trashed         INTEGER NOT NULL DEFAULT '0');"
  ),
  'Categories_B' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_CATEGORIES_B."` (
                          `Category_B_id`            int(10) unsigned    NOT NULL AUTO_INCREMENT,
                          `Category_B_id_Category_A` int(10) unsigned    NOT NULL,
                          `Category_B_name`          varchar(50)         NOT NULL DEFAULT '',
                          `Category_B_description`   varchar(255)        NOT NULL DEFAULT '',
                          `Category_B_trashed`       tinyint(1) unsigned NOT NULL DEFAULT '0',
                          PRIMARY KEY (`Category_B_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_CATEGORIES_B." (
                          Category_B_id              INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                          Category_B_id_Category_A   INTEGER NOT NULL,
                          Category_B_name            VARCHAR NOT NULL DEFAULT '',
                          Category_B_description     VARCHAR NOT NULL DEFAULT '',
                          Category_B_trashed         INTEGER NOT NULL DEFAULT '0');"
  )
);
