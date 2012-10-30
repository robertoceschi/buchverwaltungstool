
<!-- Start Inhalt
================================================== -->

<!-- Start Seitentitel -->
<?php
if(isset($sPageTitle)){
    echo '<h2>' . $sPageTitle . '</h2>' . PHP_EOL;
}
if(isset($sUriNew)){
    echo '<div class="neuer_eintrag"><a href="' . base_url() . 'redaktion/' . $sUriNew . '">Neuer Eintrag hinzufügen</a></div>';
}
?>
<!-- Ende Seitentitel -->
