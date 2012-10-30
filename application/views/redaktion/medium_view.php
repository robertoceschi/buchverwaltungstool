

<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php echo '<h2>' . $heading . '</h2>'; ?>
<!-- Ende Seitentitel -->

<?php
//Initialisieren

$fehler = 'class="fehler"';

if (isset($message)) {
    echo '<p class="fehler">' . $message . '</p>' . PHP_EOL;
}
echo strstr(validation_errors(), 'Feld') ? '<p class="fehler">bitte alle *-Felder ausfüllen</p>' . PHP_EOL : validation_errors() . PHP_EOL;
echo '<!-- Start Formular -->';
echo form_open() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_error('medium') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Medium*:', 'medium') . PHP_EOL;
echo form_input('medium', set_value('medium', $medium)) . PHP_EOL;
echo form_submit('formathinzu', 'Format hinzu', 'class="kurz"') . PHP_EOL;
echo '</div>' . PHP_EOL;
if(isset($formatarr)){
    foreach($formatarr AS $k=>$v){
        echo form_error('format' . $k) ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
        echo form_label($k +1 . '. Format:', 'format' . $k) . PHP_EOL;
        echo form_input('format' . $k, set_value('format' . $k, $v));
        echo form_hidden('format_id' . $k, $format_idarr[$k]);
        echo form_submit('formatweg', $k, 'class="loeschen"');
        echo '</div>' . PHP_EOL;
    }
}
echo form_fieldset_close() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
echo form_submit('speichern', $submit_text, 'class="kurz go"')   . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->





