
<!-- Start Inhalt
================================================== -->
<?php
if(isset($heading)){
    echo '<!-- Start Seitentitel -->' . PHP_EOL;
    echo '<h2>' . $heading . '</h2>' . PHP_EOL;
    echo '<!-- Ende Seitentitel -->' . PHP_EOL;
}
if(isset($msg)){
    echo '<p>' . $msg . '</p>' . PHP_EOL;
}
if(isset($link) && isset($linktxt)){
    echo '<p><a href="' . $link . '">' . $linktxt . '</a></p>' . PHP_EOL;
}
?>
<!-- Ende Inhalt -->

