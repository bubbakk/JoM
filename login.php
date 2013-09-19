<?php

/*
 * Copyright 2013, 2013 Andrea Ferroni
 *
 * This file is part of "JoM|The Job Manager".
 *
 * "JoM|The Job Manager" is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * "JoM|The Job Manager" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Nome-Programma.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/user_config.php');
require_once(DIR_BASE.'cfg/config.php');
require_once(DIR_LIB.'generic_lib.php');
require_once(DIR_LIB.'nonce_lib.php');
require_once(DIR_OOL.'bbkk_base_class.php');
require_once(DIR_OOL.'bbkk_pdo.php');
require_once(DIR_OOL.'bbkk_session_manager.php');


//
// database connection
//
$PDO = open_database($config['DB']['type'], $config['DB'][$config['DB']['type']]);  // open DB
$DBH = $PDO->get_dbh();                                                             // get the handler
//
// session manager
//
$SMAN = new BBKK_Session_Manager(TBL_SESSIONS, $DBH);   // constructor
$SMAN->debug_on_screen = false;
$SMAN->salt = $config['SALT'];                          // explicitly set application salt
$SMAN->start_session('', false);                        // starting session

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link type="text/plain" rel="author" href="./humans.txt" />
    <link href="./css/bootstrap.min.css"     rel="stylesheet" type="text/css" media="screen">
    <link href="./css/jom_default_style.css" rel="stylesheet" type="text/css" media="screen">
    <link href="./css/font-awesome.min.css"  rel="stylesheet" type="text/css" media="screen">
    <script language="javascript" type="text/javascript" src="./js/lib/jquery-1.9.0.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/pidCrypt/pidcrypt.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/pidCrypt/pidcrypt_util.js"></script>
    <script language="javascript" type="text/javascript" src="./js/lib/pidCrypt/sha512.js"></script>
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <title>JoM - Authentication</title>
    <script>
    $(document).ready(function() {
        $('#jom_logo').css('opacity', 0);
        $('#jom_loginpanel').css('opacity', 0);
        jsJOMlib__animate_opacity($("#jom_logo"), 1, function(){
            jsJOMlib__animate_opacity($("#jom_loginpanel"), 1, function(){
                // set focus to username field after page is appeared
                $('#user').focus();
            });
        });

        // info panel is hidden at the beginning
        $('#jom_infopanel').hide();

        // show message container if there is a reason. If there is not, hide it
        var reason = jsJOMlib__getParameterByName(document.location.search, 'r');
        if ( reason !== undefined ) {
            switch ( reason )
            {
                // session EXPired
                case 'exp':
                    jsJOMlib__GUI_set_alert_message_and_show('Session expired. Please log in', undefined, 1000);
                    break;
                // Not SigneIn
                case 'nsi':
                    jsJOMlib__GUI_set_alert_message_and_show('Please sign in', undefined, 1000);
                    break;
                // Not SigneIn
                case 'bye':
                    jsJOMlib__GUI_set_alert_message_and_show('Have a nice day.', undefined, 1000);
                    break;
                default:
                    console.warn('reason' + reason + ' unknown');
                    break;
            }
        }
        else {
            $('#jom_message_container').hide();
        }

        $("button[data-dismiss='alert']").on("click", function(){
            $('#jom_message_container').fadeOut();
        });

        // bind click event for "show info" link-button
        $('#jom_infopanelctrl').unbind().bind('click', function(){
            $('#jom_infopanel').slideToggle('slow');
            var old_text = $(this).contents().last()[0].textContent;
            if ( old_text == ' show details...' ) {
                $(this).contents().last()[0].textContent = ' hide details...';
            }
            else {
                $(this).contents().last()[0].textContent = ' show details...';
            }
        });

        // bind enter key for username field
        $('#user').unbind().keypress(function(e){
            if ( e.which == 13 ) { $('#pass').val(""); $('#pass').focus(); }
        });

        // bind enter key for password field
        $('#pass').unbind().keypress(function(e){
            if ( e.which == 13 ) { check_login( $("#submit") ); }
        });

    });

    function check_login(el) {

        var password   = $('#pass').val();
        var hashedpass = pidCrypt.SHA512(password);
        var username   = $('#user').val();
        var nonce      = $("input[name='_nonce']").val();
        var timestamp  = $("input[name='_timestamp']").val();

        // minimal checks
        if ( password === "" || username === "" )
        {
            // prompt error
            jsJOMlib__GUI_set_alert_message_and_show("Username and/or password can't be empty");
            return false;
        }

        // loading cursor...
        $(el).find('i').attr("class", "icon-spinner icon-spin");

        // ajax call to check login and
        $.ajax({
            url : "./ard.php",
            type : "POST",
            dataType : 'json',
            data : 'd=usr&r=lin&u=' + username + '&p=' + hashedpass + '&n=' + nonce + '&t=' + timestamp + '&c=login',
            success : function(data) {
                $(el).find('i').attr("class", "icon-signin");
                // if successful
                if ( data.success )
                {
                    // redirect to application page
                    $('#jom_message_container').hide();
                    jsJOMlib__animate_opacity($("#jom_loginpanel"), 0, function(){
                        jsJOMlib__animate_opacity($("#jom_logo"), 0, function(){
                            // redirect to application
                            window.location.href = "./application.php";
                        });
                    });
                }
                // if not
                else {
                    // prompt error
                    jsJOMlib__GUI_set_alert_message_and_show(data.usr_msg);

                    if ( data.new_timestamp != undefined ) {
                        $("input[name='_timestamp']").val(data.new_timestamp);
                    }
                    if ( data.new_nonce != undefined ) {
                        $("input[name='_nonce']").val(data.new_nonce);
                    }
                }
            },
            error : function(jqxhr, text, error) {
                $(el).find('i').attr("class", "icon-signin");
                alert("errore");
            }
        });
    }

    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="span4 offset4">
                <img src="./img/JoM_logo.png" alt="JoM logo image: JoM - the Job Manager" title="JoM logo" id="jom_logo">
            </div>
        </div>
        <br>
        <br>
        <div style="margin: 0 auto; width: 450px;">
            <div style="position: absolute; top: 122px; width: 420px; padding: 0 15px 0 15px; display: none" id="jom_message_container">
                <div class="alert text-center" style="box-shadow: 2px 2px 8px #AAA;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div id="jom_message"></div>
                </div>
            </div>
        </div>

        <div class="login-form" id="jom_loginpanel">
            <div style="border-bottom: 1px solid lightgray;">
                <h2><img src="./img/user_info.png" alt="Authentication icon" title="Authentication" style="vertical-align: text-bottom;"> Authentication</h2>
            </div>
            <br>
            <div class="text-center">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span>
                    <input id="user" type="text" class="form-control" placeholder="Username or email">
                </div>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input id="pass" type="password" class="form-control" placeholder="password">
                </div>
                <?php echo generate_html_input_form_nonces( '/users/login', 0, session_id(), $config['SALT'], $config['HASH_ALG'] ); ?>
                <div>
                    <button id="submit" type="submit" class="btn" onclick='javascript: check_login(this);'><i class="icon-signin"></i> Enter</button>
                </div>
                <div class="text-left" id="jom_infopanelctrl" style="cursor: pointer;">
                    <i class="icon-info-sign"></i> show details...
                </div>
                <div id="jom_infopanel">
                    <p style="border-top: 1px solid #E5E5E5; padding-top: 5px;" class="text-right">
                        <em style="font-size: 8px;">(c) 2013, 2013 Andrea Ferroni<br>
                       This software is distributed under the terms of the<br>
                       <a href='./COPYING' target='_blanc'>GNU Affero General Public License</a>.
                       </em>
                    </p>
                    <p class="text-right">
                        <a href="https://github.com/bubbakk/JoM" target="_blanc"><i class="icon-github"></i> Join the project</a><br>
                        <a href="https://twitter.com/bubbakk" target="_blanc"><i class="icon-twitter"></i> Follow me on Twitter</a>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div id="jom_version_ribbon">
        <div class="jom_label">ver.</div>
        <div class="jom_version" title="<?php print(JOM_DESC_VER);?>" onclick="javascript: $(this).next().text(jsJOMlib__get_e_commerce_bullshit()); jsJOMlib__animate_opacity($(this).next(), 1);"><?php print(JOM_VERSION);?></div>
        <div class="jom_useful_sentence"></div>
    </div>

    <script src="./js/lib/bootstrap.min.js"></script>
</body>
</html>
