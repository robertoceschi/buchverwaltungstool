

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
echo '<!-- Start Formular -->' . PHP_EOL;
echo form_open() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_error('edition') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Edition*:', 'edition') . PHP_EOL;
echo form_input('edition', set_value('edition', $edition)) . PHP_EOL;
echo '</div>' . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
echo form_submit('submit', $submit_text, 'class="kurz go"')   . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->





