<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="./css/install.css" rel="stylesheet" type="text/css">
    <script language="javascript" type="text/javascript" src="./js/installation_procedure.js"></script>
    <script language="javascript" type="text/javascript" src="./js/jquery-1.9.0.min.js"></script>
    <title>Procedura di installazione</title>
</head>
<body>
    <h1><img src="./imgs/JoM_logo.png" alt="JoM logo image: JoM - the Job Manager" title="JoM logo"></h1>
    <h2>Installazione</h2>

    <label for="inst_db_type">Tipo database</label>
    <input id="inst_db_type" list="database_supportati">
    <datalist id="database_supportati">
        <option value="MySQL">
        <option value="SQLite">
    </datalist>
    <br>
    <label for="inst_db_name">Nome database</label>
    <input id="inst_db_name">
    <br>
    <label for="inst_db_hostname">Database server host (no SQLITE)</label>
    <input id="inst_db_hostname">
    <br>
    <label for="inst_db_username">Database user name (no SQLITE)</label>
    <input id="inst_db_username">
    <br>
    <label for="inst_db_password">Database password (no SQLITE)</label>
    <input id="inst_db_password">
    <br>
    <br>
    <button onclick='javascript: run_install_step(0);'>Avvia</button>

    <h3>Fase di installazione</h3>
    <h4 id="inst_fase">attesa input utente</h4>
</body>
</html>