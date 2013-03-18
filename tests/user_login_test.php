<?php
// TEST bbkk_session_manager class
define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'bbkk_session_manager.php');
require_once(DIR_OOL.'user.php');

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

echo "<strong>User object instancing</strong><br>";
$user = new JOM_User(TBL_USERS, $DBH);    // constructor


echo "<strong>Going to authenticate right user</strong><br>";
$res = $user->authenticate('bubbakk', '83d97b71499bee6b9d42dee9d3a6e5d00ecc8c891346d25d1909b3aac9abaa0ad4864fe4eacf159cd3f4a0ad764178d014ac378dfffc5e4023f6dbcfb0992648');                           // explicitly set application salt
var_dump($res);
echo '<br>';


echo "<strong>Going to authenticate right user email</strong><br>";
$res = $user->authenticate('bubbakk@gmail.com', '83d97b71499bee6b9d42dee9d3a6e5d00ecc8c891346d25d1909b3aac9abaa0ad4864fe4eacf159cd3f4a0ad764178d014ac378dfffc5e4023f6dbcfb0992648');                           // explicitly set application salt
var_dump($res);
echo '<br>';


echo "<strong>Going to authenticate unknown user</strong><br>";
$res = $user->authenticate('bubbak', '83d97b71499bee6b9d42dee9d3a6e5d00ecc8c891346d25d1909b3aac9abaa0ad4864fe4eacf159cd3f4a0ad764178d014ac378dfffc5e4023f6dbcfb0992648');                           // explicitly set application salt
var_dump($res);
echo '<br>';


echo "<strong>Going to authenticate unknown user</strong><br>";
$res = $user->authenticate('bubbakk', '-3d97b71499bee6b9d42dee9d3a6e5d00ecc8c891346d25d1909b3aac9abaa0ad4864fe4eacf159cd3f4a0ad764178d014ac378dfffc5e4023f6dbcfb0992648');                           // explicitly set application salt
var_dump($res);
echo '<br>';


?>
</body>
</html>
