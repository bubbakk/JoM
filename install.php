<?php
define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/config.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="./css/bootstrap.min.css" rel="stylesheet"  type="text/css" media="screen">
    <link href="./css/jom_default_style.css" rel="stylesheet"  type="text/css" media="screen">
    <link href="./css/install.css" rel="stylesheet"  type="text/css" media="screen">
    <script language="javascript" type="text/javascript" src="./js/lib/jquery-1.9.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/installation_procedure.js"></script>
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <title>Procedura di installazione</title>
    <script>
    $(document).ready(function() {

        // install tooltips
        $("#inst_db_createdb").tooltip();
        $("#inst_db_tableprepend").tooltip();

        // set default form statuses
        create_new_database_toggled(0);
        delete_existing_file_toggled(0)
        $("#inst_db_createdb").removeAttr("checked");
        $("#inst_db_removeifany").removeAttr("checked");
        $("#inst_db_delprevfile").removeAttr("checked");
        $("#inst_db_removeifanyfile").removeAttr("checked");

        // set install step data
        $("body").data('step', 0);

        // smooth intro animation... just to add something unuseful...
        animate_opacity($("h1 > img"), 1, function(){
            animate_opacity($("h2"), 1, function(){
                go_to_step(0);
            });
        });

    });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-center">
            <img src="./img/JoM_logo.png" alt="JoM logo image: JoM - the Job Manager" title="JoM logo"0>
        </h1>
        <div class="row">
            <h2>Software installation procedure</h2>
        </div>

<!-- BACK -->
        <div class="row" id="jom_rowback">
            <div class="span12 text-center">
                <div class="controls">
                    <a class="btn btn-primary" href="#" onclick="javascript: go_to_step(-1);"><i class="icon-chevron-left"></i> Go back</a>
                </div>
            </div>
        </div>

<!-- FIELDSET 0 - database configuration -->
        <div class="row" id="fieldset_0">
            <div class="span6 offset3">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>1. Database configuration</legend>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_type">Database type</label>
                            <div class="controls">
                                <select id="inst_db_type" placeholder="Select database type">
                                    <option value="MySQL">MySQL</option>
                                    <option value="SQLite">SQLite</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_name">Database name</label>
                            <div class="controls">
                                <input class="input-medium" type="text" id="inst_db_name">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

<!-- FIELDSET 1a - DBMS deeper parameters -->
        <div class="row" id="fieldset_1">
            <div class="span6">
                <form class="form-horizontal">
                    <fieldset id="fieldset_1_a">
                        <legend>2a. Database server parameters</legend>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_hostname">Host</label>
                            <div class="controls">
                                <input class="input-medium" id="inst_db_hostname" type="text" value="localhost">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_createdb">Create new database</label>
                            <div class="controls">
                                <input id="inst_db_createdb" type="checkbox" title="if checked, existing database with the same name will be deleted" onclick="javascript: create_new_database_toggled($(this).is(':checked'));">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_removeiftableexist">Clear tables if database exists</label>
                            <div class="controls">
                                <input id="inst_db_removeiftableexist" type="checkbox">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_username">Username</label>
                            <div class="controls">
                                <input id="inst_db_username" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_password">Password</label>
                            <div class="controls">
                                <input id="inst_db_password" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_tableprepend">Tables prepend</label>
                            <div class="controls">
                                <input class="input-small" id="inst_db_tableprepend" type="text" title="Useful if using one single database for more than one application">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="span6">
                <form class="form-horizontal">
                    <fieldset id="fieldset_1_b">
                        <legend>2b. Database admin parameters</legend>
                        <div class="control-group">
                            <p><span class="label label-info">Info</span> - following data will not be saved</p>
                        </div>
                        <div class="control-group jom_create_db">
                            <label class="control-label" for="inst_db_superuser">Username</label>
                            <div class="controls">
                                <input id="inst_db_superuser" type="text">
                            </div>
                        </div>
                        <div class="control-group jom_create_db">
                            <label class="control-label" for="inst_db_superpass">Password</label>
                            <div class="controls">
                                <input id="inst_db_superpass" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_removeifany">Remove database if exists</label>
                            <div class="controls">
                                <input id="inst_db_removeifdbexists" type="checkbox">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

<!-- FIELDSET 1c - SQLITE parameters -->
        <div class="row" id="fieldset_2">
            <div class="span6 offset3">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>2. Database parameters</legend>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_delprevfile">Delete existing file</label>
                            <div class="controls">
                                <input id="inst_db_delprevfile" type="checkbox" title="if checked, existing database file with the same name will be deleted" onclick="javascript: delete_existing_file_toggled($(this).is(':checked'))">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_removetables">Clear tables if database exists</label>
                            <div class="controls">
                                <input id="inst_db_removetables" type="checkbox">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inst_db_tableprependfile">Tables name prepend</label>
                            <div class="controls">
                                <input class="input-small" id="inst_db_tableprependfile" type="text" title="Useful if using one single database for more than one application">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

        <div class="row" id="jom_install_feedback_bar">
            <br>
            <br>
            <div class="span8 offset2">
                <div class="progress">
                  <div class="bar" style="width: 10%;"></div>
                </div>
            </div>
        </div>
        <div id="jom_msgs_container">
            <div class="row" id="jom_install_feedback_messages"  style="opacity: 0">
                <div class="span6 offset2">
                    <div class="alert alert-info">
                        <strong>Creazione</strong> file di configurazione
                    </div>
                </div>
                <div class="span2 text-center" style="opacity: 0">
                    <div class="alert alert-success">
                        <strong>OK</strong>
                    </div>
                </div>
            </div>
        </div>

<!-- GO ON -->
        <div class="row" id="jom_rowgoon">
            <div class="span12 text-center" style="border-top: 1px solid #B9B9B9;">
                <label class="control-label"></label>
                <div class="controls">
                    <a class="btn btn-primary" href="#" onclick="javascript: go_to_step(1);">Proceed <i class="icon-chevron-right"></i></a>
                </div>
            </div>
        </div>

    <script src="./js/lib/bootstrap.min.js"></script>
    <div id="jom_version_ribbon">
        <div class="jom_label">ver.</div>
        <div class="jom_version" title="<?php print(JOM_DESC_VER);?>" onclick="javascript: $(this).next().text(get__e_commerce_bullshit()); animate_opacity($(this).next(), 1);"><?php print(JOM_VERSION);?></div>
        <div class="jom_useful_sentence"></div>
    </div>
</body>
</html>
