<?php echo doctype('html5'); ?>

<head>

    <!-- Basic Page Needs
   ================================================== -->
    <meta charset="utf-8">
    <title>Buchverwaltungs-System - <?php echo '';?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <meta name="description" content="">
    <meta name="author" content="">

    <style type="text/css">

        body {
            background: #FBFBFB;

        }



        #login_form {
            width: 300px;
            background: #f0f0f0 url(/images/login_bg.jpg) repeat-x 0 0;
            border: 1px solid white;
            margin: 250px auto 0;
            padding: 1em;
            -moz-border-radius: 3px;
        }



        h1,h2,h3,h4,h5 {
            color:#CC0000;
            font-weight: normal;
            font-family: arial black, arial;
            text-align: center;
        }

        input[type=text], input[type=password] {
            display: block;
            margin: 0 0 1em 0;
            width: 280px;
            border: 5px;
            -moz-border-radius: 1px;
            -webkit-border-radius: 1px;
            padding: 1em;
        }

        input[type=submit], form a {
            border: none;
            margin-right: 1em;
            padding: 6px;
            text-decoration: none;
            font-size: 12px;
            -moz-border-radius: 4px;
            background: #CC0000;
            color: white;
            box-shadow: 0 1px 0 white;
            -moz-box-shadow: 0 1px 0 white;
            -webkit-box-shadow: 0 1px 0 white;

        }

        input[type=submit]:hover, form a:hover {
            background: #CC0000;
            opacity: 0.5;
            cursor: pointer;
        }


        .footer {
            color: #999999;
            font-size: 80%;
            text-align: center;
        }

        .logout {
            background-color: #FFFFE0;
            border-color: #E6DB55;
            width: 330px;
            Top:20%;
            Left:41.5%;
            position: absolute;
        }



    </style>




</head>


<!-- Start Login-Formular
================================================== -->
<div id="login_form">
    <h2>Login</h2>
    <?php
    if(isset($message) && $message !=''){
        echo '<p>' . $message . '</p>' . PHP_EOL;
    }
    //wird zum login Controller gesendet und die methode zum validieren der Eingaben wird aufgerufen
    echo form_open('redaktion/login/validate_credentials') . PHP_EOL;
    echo form_input('benutzername', '', 'placeholder="Benutzername"') . PHP_EOL;
    echo form_password('passwort', '', 'placeholder="Passwort"') . PHP_EOL;
    echo form_submit('submit', 'Login') . PHP_EOL;
    echo form_close() . PHP_EOL;
    ?>
</div>
<!-- Ende Login-Formular -->

