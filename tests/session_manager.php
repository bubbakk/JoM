<?php
// TEST bbkk_session_manager class
define('DIR_BASE', '../');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'bbkk_session_manager.php');

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
    <h1><?php echo "Will test BBKK_SESSION_MANAGER<br>\n";?></h1>
<?php
echo "<strong>Opening DB</strong><br>";
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB
$DBH = $PDO->get_dbh();

echo "<strong>Session object instancing</strong><br>";
$ses_man = new BBKK_Session_Manager(TBL_SESSIONS, $DBH);    // constructor

echo "<strong>Setting salt</strong><br>";
$ses_man->salt = $config['SALT'];                           // explicitly set application salt

echo "<strong>Calling start_session</strong><br>";
$ses_man->start_session('', false);

/*
 * NOTE: in order to test following code, should turn called methods into public
$ses_key = $ses_man->getkey('');
$ses_man->salt = $config['SALT'];

// encrypt
$a = array( 1 => 'avocado', 'pippo' => 'apple', array('pippo', 'pluto', array(5,8)));
$ENC = $ses_man->encrypt($a, $ses_key);
echo "ENC: ".$ENC."<br>";

// decrypt
$DEC = $ses_man->decrypt($ENC, $ses_key);
echo "<pre>".print_r($DEC)."</pre><br>";
 *
 */

//echo "<strong>Store value into session</strong><br>";
//$_SESSION['something'] = 'A value.';

echo "<strong>Read stored value from session: </strong><br>";
echo "<strong>" . $_SESSION['something'] . "</strong>";

$_SESSION['something'] = 'ZUZUZUZUZUZ';

?>
</body>
</html>
