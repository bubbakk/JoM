<?php
define('DIR_BASE', './');
require_once(DIR_BASE.'cfg/config.php');
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
    <script language="javascript" type="text/javascript" src="./js/lib/pidCrypt/sha256.js"></script>
    <script language="javascript" type="text/javascript" src="./js/generic_lib.js"></script>
    <title>Entra in JoM - autenticazione</title>
    <script>
    $(document).ready(function() {
        $('#jom_logo').css('opacity', 0);
        $('#jom_loginpanel').css('opacity', 0);
        animate_opacity($("#jom_logo"), 1, function(){
            animate_opacity($("#jom_loginpanel"), 1, function(){
                $('#user').focus();
            });
        });
        $('#jom_infopanel').hide();
        $('#jom_infopanelctrl').unbind().bind('click', function(){
            $('#jom_infopanel').slideToggle('slow');
        });

        $('#user').unbind().keypress(function(e){
            if ( e.which == 13 ) { $('#pass').val(""); $('#pass').focus(); }
        });

        $('#pass').unbind().keypress(function(e){
            if ( e.which == 13 ) { check_login( $("#submit") ); }
        });

    });




    function check_login(el) {

        // loading cursor...

        var hashedpass = pidCrypt.SHA256($('#pass').val());
        var username   = $('#user').val();

        // ajax call to check login and
        // if successful redirect to application page
        // if not, prompt error
            // unset loading cursor

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
        <div class="row" id="jom_loginpanel">
            <div class="span4 offset4" style="border: 1px solid #AFAFAF; border-radius: 10px; box-shadow: 1px 1px 2px #888; background-color: white; padding: 15px;">
                <div class="row">
                    <div class="span1">
                        <img src="./img/user_info.png" alt="Authentication icon" title="Authentication">
                    </div>
                    <div class="span3 text-center">
                        <h2>Authentication</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <legend style="margin-bottom: 5px"></legend>
                        <div style="padding: 0px 15px 0 35px;">
                            <label style="padding-top: 15px;">User name or email</label>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input id="user" class="input-block-level" type="text" placeholder="enter user name or email" style="font-size: 18px; font-weight: bold;">
                            </div>
                            <label>Password</label>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span>
                                <input id="pass" class="input-block-level" type="password" placeholder="enter password" style="font-size: 18px; font-weight: bold;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="span2 offset1 text-center">
                        <button id="submit" type="submit" class="btn" onclick='javascript: check_login(this);'><i class="icon-signin"></i> Enter</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="span4" id="jom_infopanelctrl" style="cursor: pointer;">
                        <i class="icon-info-sign"></i> show details...
                    </div>
                </div>
                <div class="row" id="jom_infopanel">
                    <div class="span4">
                        <p style="border-top: 1px solid #E5E5E5; padding-top: 5px;" class="text-right">
                            <em style="font-size: 8px;">(c) Andrea Ferroni<br>
                           This software is distributed under the terms of the<br>
                           <a href='./LICENSE.md' target='_blanc'>GNU General Public License</a>.
                           </em>
                        </p>
                        <p class="text-right">
                            <a href="https://github.com/bubbakk/JoM" target="_blanc"><i class="icon-github"></i> Join the project</a><br>
                            <a href="https://twitter.com/bubbakk" target="_blanc"><i class="icon-twitter"></i> Tweet me @bubbakk</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="jom_version_ribbon">
        <div class="jom_label">ver.</div>
        <div class="jom_version" title="<?php print(JOM_DESC_VER);?>" onclick="javascript: $(this).next().text(get__e_commerce_bullshit()); animate_opacity($(this).next(), 1);"><?php print(JOM_VERSION);?></div>
        <div class="jom_useful_sentence"></div>
    </div>

    <script src="./js/lib/bootstrap.min.js"></script>
</body>
</html>
