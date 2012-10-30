
<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php echo '<h2>' . $heading . '</h2>'; ?>
<!-- Ende Seitentitel -->

<?php
echo form_open() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_hidden('buch_id', $buch_id);
echo form_hidden('auflage_id', $auflage_id);
echo form_hidden('current_page', $current_page);
echo '<p>' . $description . '</p>' . PHP_EOL;
echo '<h1>' . $buchtitel . '</h1>' . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
if(isset($submit_link)){
    echo form_button('löschen', $submit_txt, 'onClick=self.location.href="' . $submit_link . '"') . PHP_EOL;
}
else {
    echo form_submit('loeschen', $submit_txt, 'class="kurz go"') . PHP_EOL;
}

echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;
?>

<!-- Ende Formular -->
<!-- Ende Inhalt -->
