
<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php echo '<h2>' . $heading . '</h2>'; ?>
<!-- Ende Seitentitel -->

<?php
//Initialisieren
$fehler = 'class="fehler"';

if(isset($message)){
    echo '<p class="fehler">' . $message . '</p>' . PHP_EOL;
}

echo strstr(validation_errors(), 'Feld') ? '<p class="fehler">bitte alle *-Felder ausfüllen</p>' .
     PHP_EOL: validation_errors() . PHP_EOL;
echo '<!-- Start Formular -->' . PHP_EOL;

// AUSWAHLMENU
echo form_open() . PHP_EOL;
echo form_fieldset() . PHP_EOL;

$options = array(''     => 'Bitte wählen',
    'nb'   => 'Neues Buch eintragen',
    'na'   => 'Neue Auflage hinzufügen');
$js      = 'id="buchauswahl" onchange="this.form.submit();"';
echo form_dropdown('buchauswahl', $options, $buchauswahl, $js);
echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->





