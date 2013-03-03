<?php

define('JOM_DEBUG',     true);
define('JOM_LOG',       true);
define('JOM_MILESTONE', '0.1');                         // installer version (refer to MILESTONES.md for versioning progression)
define('JOM_RELEASE',   '10');
define('JOM_VERSION',   JOM_MILESTONE.'.'.JOM_RELEASE); // milestone.release
define('JOM_DESC_VER',  'v. '.JOM_MILESTONE.'.x: installation procedure / database, tables and sample data');   // milestone version description


// directories definition
if ( !defined('DIR_BASE') ) define('DIR_BASE', './');
//define('DIR_BLK',   'blk/');
//define('DIR_CACHE', 'cache/');
define('DIR_CFG',    DIR_BASE.'cfg/');
define('DIR_CSS',    DIR_BASE.'css/');
define('DIR_I18N',   DIR_BASE.'i18n/');
define('DIR_IMG',    DIR_BASE.'img/');
define('DIR_JS',     DIR_BASE.'js/');
define('DIR_JSLIB',  DIR_BASE.'js/lib/');
define('DIR_LOG',    DIR_BASE.'log/');
define('DIR_LIB',    DIR_BASE.'lib/');
define('DIR_OOL',    DIR_BASE.'oolib/');
define('DIR_DBSQLT', DIR_BASE.'sqlite/');


// tables definition
if ( !defined('TABLES_PREFIX') ) define('TABLES_PREFIX', '');
define('TBL_COMPANIES',           TABLES_PREFIX.'Company');
define('TBL_USERS',               TABLES_PREFIX.'Users');
define('TBL_JOBS',                TABLES_PREFIX.'Jobs');
define('TBL_USERS_ACL',           TABLES_PREFIX.'Users_acl');
define('TBL_CATEGORIES_1',        TABLES_PREFIX.'Categories_1');
define('TBL_CATEGORIES_2',        TABLES_PREFIX.'Categories_2');
define('TBL_LOGGER',              TABLES_PREFIX.'log');
