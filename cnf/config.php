<?php

define('JOM_DEBUG',     'true');
define('JOM_LOG',       'true');
define('JOM_MILESTONE', '0.1');                         // installer version (refer to MILESTONES.md for versioning progression)
define('JOM_RELEASE',   '2');
define('JOM_VERSION',   JOM_MILESTONE.'.'.JOM_RELEASE); // milestone.release
define('JOM_DESC_VER',  'v. '.JOM_MILESTONE.'.x: installation procedure / database main tables');   // milestone version description


// directories definition
//define('DIR_BLK',   'blk/');
//define('DIR_CACHE', 'cache/');
define('DIR_CSS',    'css/');
define('DIR_I18N',   'i18n/');
define('DIR_IMG',    'img/');
define('DIR_JS',     'js/');
define('DIR_JSLIB',  'js/lib/');
define('DIR_LOG',    'log/');
define('DIR_LIB',    'lib/');
define('DIR_OOL',    'oolib/');
define('DIR_DBSQLT', 'sqlite/');


// tables definition
if ( !defined(TABLES_PREFIX) ) define('TABLES_PREFIX', '');
define('TBL_USERS',               TABLES_PREFIX.'users');
define('TBL_USERS_ACL',           TABLES_PREFIX.'users_acl');
define('TBL_PROBLEMS_CATEGORIES', TABLES_PREFIX.'problems_categories');
define('TBL_LOGGER',              TABLES_PREFIX.'log');
