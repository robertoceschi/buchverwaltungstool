
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

// keine doppelten Fehlermeldungen zulassen
if(strstr(validation_errors(), 'Feld')){
    echo '<p class="fehler">bitte alle *-Felder ausfüllen</p>' .PHP_EOL;
}
elseif(strstr(validation_errors(), 'auswählen')){
    echo '<p class="fehler">bitte Option auswählen</p>' .PHP_EOL;
}
else {
    echo validation_errors() . PHP_EOL;
}

echo '<!-- Start Formular -->' . PHP_EOL;
echo form_open();
echo form_fieldset() . PHP_EOL;
echo form_submit('textfeldneu', 'Textfeld hinzu', 'class="kurz"') . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
if(isset($pressetext[0])){
    foreach($pressetext AS $k=>$v){
        echo form_fieldset() . PHP_EOL;
        $nr = $k + 1;
        echo form_error('pressetext' . $k) ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
        echo form_label('Pressetext ' . $nr, 'pressetext' . $k) . PHP_EOL;

            $data = array(
            'name'          => 'pressetext' . $k,
            'rows'          => '10',
            'cols'          => '10'
        );
        echo form_textarea($data, set_value('pressetext' . $k, $pressetext[$k])) . PHP_EOL;
        echo '<br />' . PHP_EOL;
        echo form_label('Status:', 'status');
        $js2 = 'onchange="this.form.submit();"';
        echo form_dropdown('status' . $k, array('a' => 'sichtbar', 'p' => 'versteckt', 'e' => 'löschen'), $status[$k], $js2);
        echo '</div>' . PHP_EOL;
        echo form_hidden('pressetext_id' . $k, $pressetext_id[$k]);
        echo form_fieldset_close() . PHP_EOL;
    }

}


echo form_fieldset() . PHP_EOL;
echo form_hidden('buch_id', $buch_id);
echo form_hidden('current_page', $current_page);
echo form_hidden('location', $location);
echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
echo form_submit('weiter', $submit_txt, 'class="mittel go"') . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_close();
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->