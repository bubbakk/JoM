<?php

define('JOM_DEBUG',     'true');
define('JOM_LOG',       'true');
define('JOM_MILESTONE', '0.0');                         // see MILESTONES.txt for versioning
define('JOM_RELEASE',   '1');
define('JOM_VERSION',   JOM_MILESTONE.'.'.JOM_RELEASE); // milestone.release


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