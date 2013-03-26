<?php
// TEST bbkk_session_manager class
define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'category.php');

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../humans.txt"                rel="author" type="text/plain">
    <link href="../css/bootstrap.min.css"     rel="stylesheet" type="text/css" media="screen">
    <link href="../css/font-awesome.min.css"  rel="stylesheet" type="text/css" media="screen">
    <link href="../css/jom_default_style.css" rel="stylesheet" type="text/css" media="screen">
    <script language="javascript" type="text/javascript" src="../js/lib/jquery-1.9.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="../js/generic_lib.js"></script>
    <title>***</title>
    <script>
    $(document).ready(function() {

    });
    </script>
    <style>

    </style>
</head>
<body>
    <h1>Login test</h1>
<?php
echo "<strong>Opening DB</strong><br>";
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB
$DBH = $PDO->get_dbh();

echo "<strong>Category object level 1 instancing</strong><br>";
$cat1 = new JOM_Cateogry(TBL_CATEGORIES_GENERIC, $DBH);    // constructor
$cat1->level = 1;
$cat1->load();

echo "<strong>Category object level 2 instancing</strong><br>";
echo "<p>If setting parent, the query filters it.</p>";
$cat2 = new JOM_Cateogry(TBL_CATEGORIES_GENERIC, $DBH);    // constructor
$cat2->level = 2;
$cat2->parent_id = 1;
$cat2->load();

echo "<p>If parent is unset (null), the query return full table rows.</p>";
$cat2->parent_id = 2;
$cat2->parent_id = null;
$cat2->load();
?>
</body>
</html>
