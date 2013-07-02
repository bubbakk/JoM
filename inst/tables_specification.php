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
                        `User_password_hash`            char(128)   CHARACTER SET utf8  NOT NULL DEFAULT '',
                        `User_salt`                     char(128)   CHARACTER SET utf8  NOT NULL DEFAULT '',
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
                        UNIQUE  KEY `USERNAME` (`User_username`),
                        UNIQUE  KEY `USEREMAIL` (`User_contacts_email`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_USERS." (
                        User_id                       INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        User_firstname                VARCHAR(50)           NOT NULL,
                        User_lastname                 VARCHAR(50)           NOT NULL,
                        User_username                 VARCHAR(20)           NOT NULL,
                        User_password_hash            CHAR(128)             NOT NULL,
                        User_salt                     CHAR(128)             NOT NULL,
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
                        User_trashed                  BOOL                  NOT NULL DEFAULT 0);
                CREATE UNIQUE INDEX \"main\".\"Users_username_idx\" ON \"".TBL_USERS."\" (\"User_username\" ASC);
                CREATE UNIQUE INDEX \"main\".\"Users_contacts_email_idx\" ON \"".TBL_USERS."\" (\"User_contacts_email\" ASC);"
  ),
  'Jobs' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_JOBS."` (
                        `Job_id`                      INTEGER unsigned      NOT NULL AUTO_INCREMENT,
                        `Job_subject`                 varchar(255)          NOT NULL DEFAULT '',
                        `Job_description`             text,
                        `Job_category_level_1`        INTEGER unsigned      DEFAULT '1',
                        `Job_category_level_2`        INTEGER unsigned      DEFAULT '1',
                        `Job_category_level_3`        INTEGER unsigned      DEFAULT '1',
                        `Job_tags`                    text                  DEFAULT '',
                        `Job_priority`                tinyint(4) unsigned   DEFAULT '1',
                        `Job_creation_datetime`       INTEGER unsigned      DEFAULT '0',
                        `Job_start_datetime`          INTEGER unsigned      DEFAULT '0',
                        `Job_deadline_datetime`       INTEGER unsigned      DEFAULT '0',
                        `Job_status`                  INTEGER unsigned      DEFAULT '1',
                        `Job_percent_completed`       tinyint(1) unsigned   DEFAULT '0',
                        `Job_assigned_to_User`        INTEGER unsigned      DEFAULT '0',
                        `Job_assigned_to_chainedgroup_A` INTEGER unsigned DEFAULT '0',
                        `Job_assigned_to_chainedgroup_B` INTEGER unsigned DEFAULT '0',
                        `Job_assigned_to_chainedgroup_C` INTEGER unsigned DEFAULT '0',
                        `Job_assigned_to_chainedgroup_D` INTEGER unsigned DEFAULT '0',
                        `Job_assigned_to_freegroup_A` INTEGER unsigned      DEFAULT '0',
                        `Job_assigned_to_freegroup_B` INTEGER unsigned      DEFAULT '0',
                        `Job_assigned_to_freegroup_C` INTEGER unsigned      DEFAULT '0',
                        `Job_assigned_to_freegroup_D` INTEGER unsigned      DEFAULT '0',
                        `Job_trashed`                 tinyint(1)            NOT NULL DEFAULT '0',
                        PRIMARY KEY (`Job_id`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
    'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_JOBS." (
                        Job_id                      INTEGER       PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                        Job_subject                 VARCHAR       NOT NULL DEFAULT '',
                        Job_description             TEXT,
                        Job_category_level_1        INTEGER       DEFAULT 1,
                        Job_category_level_2        INTEGER       DEFAULT 1,
                        Job_category_level_3        INTEGER       DEFAULT 1,
                        Job_tags                    TEXT          DEFAULT '',
                        Job_priority                INTEGER       DEFAULT 1,
                        Job_creation_datetime       INTEGER       DEFAULT 0,
                        Job_start_datetime          INTEGER       DEFAULT 0,
                        Job_percent_completed       INTEGER       DEFAULT 0,
                        Job_deadline_datetime       INTEGER       DEFAULT 0,
                        Job_status                  INTEGER       DEFAULT 1,
                        Job_assigned_to_User        INTEGER       DEFAULT 0,
                        Job_assigned_to_chainedgroup_A INTEGER    DEFAULT 0,
                        Job_assigned_to_chainedgroup_B INTEGER    DEFAULT 0,
                        Job_assigned_to_chainedgroup_C INTEGER    DEFAULT 0,
                        Job_assigned_to_chainedgroup_D INTEGER    DEFAULT 0,
                        Job_assigned_to_freegroup_A INTEGER       DEFAULT 0,
                        Job_assigned_to_freegroup_B INTEGER       DEFAULT 0,
                        Job_assigned_to_freegroup_C INTEGER       DEFAULT 0,
                        Job_assigned_to_freegroup_D INTEGER       DEFAULT 0,
                        Job_trashed                 INTEGER       NOT NULL DEFAULT 0)"
  ),
  'Categories_1' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_CATEGORIES_1."` (
                          `Category_1_id`            int(10) unsigned    NOT NULL AUTO_INCREMENT,
                          `Category_1_name`          varchar(50)         NOT NULL DEFAULT '',
                          `Category_1_description`   varchar(255)        NOT NULL DEFAULT '',
                          `Category_1_trashed`       tinyint(1) unsigned NOT NULL DEFAULT '0',
                          PRIMARY KEY (`Category_1_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_CATEGORIES_1." (
                          Category_1_id              INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                          Category_1_name            VARCHAR NOT NULL DEFAULT '',
                          Category_1_description     VARCHAR NOT NULL DEFAULT '',
                          Category_1_trashed         INTEGER NOT NULL DEFAULT '0');"
  ),
  'Categories_2' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_CATEGORIES_2."` (
                          `Category_2_id`            int(10) unsigned    NOT NULL AUTO_INCREMENT,
                          `Category_2_id_Category_1` int(10) unsigned    NOT NULL,
                          `Category_2_name`          varchar(50)         NOT NULL DEFAULT '',
                          `Category_2_description`   varchar(255)        NOT NULL DEFAULT '',
                          `Category_2_trashed`       tinyint(1) unsigned NOT NULL DEFAULT '0',
                          PRIMARY KEY (`Category_2_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;",
    'sqlite'  => "CREATE TABLE IF NOT EXISTS ".TBL_CATEGORIES_2." (
                          Category_2_id              INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                          Category_2_id_Category_1   INTEGER NOT NULL,
                          Category_2_name            VARCHAR NOT NULL DEFAULT '',
                          Category_2_description     VARCHAR NOT NULL DEFAULT '',
                          Category_2_trashed         INTEGER NOT NULL DEFAULT '0');"
  ),
  'Login_attempts' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_LOGIN_ATTEMPTS."` (
                          `Login_attempts_User_id`   INT NOT NULL     DEFAULT '0',
                          `Login_attempts_time`      INT NOT NULL     DEFAULT '0',
                          UNIQUE (`Login_attempts_time`)
                        ) ENGINE = MYISAM ;",
    'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_LOGIN_ATTEMPTS." (
                          Login_attempts_User_id     INT NOT NULL     DEFAULT '0',
                          Login_attempts_time        INT NOT NULL     DEFAULT '0');"
  ),
  'Sessions' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_SESSIONS."` (
                          `Session_id`               CHAR(128) NOT NULL DEFAULT '',
                          `Session_set_time`         INT       NOT NULL DEFAULT '0',
                          `Session_data`             TEXT      NOT NULL DEFAULT '',
                          `Session_key`              CHAR(128) NOT NULL DEFAULT '',
                          PRIMARY KEY (`Session_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",
    'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_SESSIONS." (
                          Session_id               CHAR(128) PRIMARY KEY NOT NULL DEFAULT '',
                          Session_set_time         INT       NOT NULL DEFAULT '0',
                          Session_data             TEXT      NOT NULL DEFAULT '',
                          Session_key              CHAR(128) NOT NULL DEFAULT '');"
  ),
  'Nonces'   => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS `".TBL_NONCES."` (
                          `Nonce_id`                 INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                          `Nonce_timestamp`          INTEGER UNSIGNED NOT NULL ,
                          `Nonce_nonce`              CHAR(64)         NOT NULL ,
                          PRIMARY KEY ( `Nonce_id` )
                        ) ENGINE = MYISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",
    'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_NONCES." (
                          Nonce_id                  INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                          Nonce_timestamp           INTEGER                             NOT NULL,
                          Nonce_nonce               CHAR(64)                            NOT NULL);"
  ),
  'Statuses' => array(
    'mysql'  => "CREATE TABLE IF NOT EXISTS ".TBL_STATUSES." (
                         `Status_id`       INT           UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `Status_name`     VARCHAR( 50 ) NOT NULL DEFAULT  '',
                         `Status_is_final` TINYINT( 1 )  UNSIGNED NOT NULL DEFAULT  '0',
                         `Status_order`    INT           UNSIGNED NOT NULL DEFAULT  '1',
                         `Status_trashed`  TINYINT( 1 )  UNSIGNED NOT NULL  DEFAULT  '0'
                        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;",
    'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_STATUSES." (
                         Status_id       INTEGER          PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                         Status_name     VARCHAR( 50 )    NOT NULL DEFAULT  '',
                         Status_is_final INTEGER          NOT NULL DEFAULT  '0',
                         Status_order    INTEGER UNSIGNED NOT NULL DEFAULT  '1',
                         Status_trashed  INTEGER          NOT NULL DEFAULT  '0'
                        );"
  ),
  'Logger' => array(
    'mysql' => "CREATE TABLE IF NOT EXISTS ".TBL_LOGGER." (
                        `Logger_id`                 INT UNSIGNED   NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `Logger_type`               INT UNSIGNED   NOT NULL DEFAULT  '0',
                        `Logger_datetime`           INT UNSIGNED   NOT NULL DEFAULT  '0',
                        `Logger_text`               TEXT           NOT NULL DEFAULT  '',
                        `Logger_file`               VARCHAR( 255 ) NOT NULL DEFAULT  '',
                        `Logger_class`              VARCHAR( 255 ) NOT NULL DEFAULT  '',
                        `Logger_method_function`    VARCHAR( 255 ) NOT NULL DEFAULT  '',
                        `Logger_session_id`         CHAR ( 128 )   NOT NULL
                        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;",
   'sqlite' => "CREATE TABLE IF NOT EXISTS ".TBL_LOGGER." (
                         Logger_id                  INTEGER        PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE,
                         Logger_type                INTEGER        NOT NULL DEFAULT  0,
                         Logger_datetime            INTEGER        NOT NULL DEFAULT  0,
                         Logger_text                TEXT           NOT NULL DEFAULT  '',
                         Logger_file                VARCHAR( 255 ) NOT NULL DEFAULT  '',
                         Logger_class               VARCHAR( 255 ) NOT NULL DEFAULT  '',
                         Logger_method_function     VARCHAR( 255 ) NOT NULL DEFAULT  '',
                         Logger_session_id          CHAR ( 128 )   NOT NULL
                        );"
  )
);
