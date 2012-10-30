<?php
header('Content-Type: application/x-javascript');

// Gesamten Seiten inhalt buffern (und zippen)
function my_obstart() {
    $encode = getenv('HTTP_ACCEPT_ENCODING');
    if (strstr($encode, 'gzip')) {
        ob_start('ob_gzhandler');
    }
    else {
        ob_start();
    }
}
my_obstart();

$pfad = pathinfo(__FILE__, PATHINFO_DIRNAME) . '/';


require_once $pfad . 'jquery.js';
require_once $pfad . 'jquery-ui-1.8.16.custom.js';
require_once $pfad . 'jquery.ui.core.js';
require_once $pfad . 'jquery.ui.datepicker-de.js';
require_once $pfad . 'tiny_mce/jquery.js';
require_once $pfad . 'tiny_mce/jquery.tinymce.js';
//require_once $pfad . 'jquery/jquery.ui.widget.js';

ob_end_flush();