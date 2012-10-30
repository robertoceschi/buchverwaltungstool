

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
echo '<div><p>' . $description . '</p><h2>"' . $objekt . '"<h2></div>';
echo form_hidden('objekt', $objekt);


if(isset($description2)){
    echo '<hr />';
    echo '<div><p>' . $description2 . '</p><ul>';
    foreach($bucharr AS $v){
        echo '<li><a href="' . base_url() . 'redaktion/buch/show/' . $v['buch_id'] . '">' . $v['buchtitel'] . '</a></li>';
    }
    echo '</ul></div>';
}
echo form_fieldset_close() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
if(isset($submit_text)){
    echo form_submit('submit', $submit_text, 'class="kurz go"')   . PHP_EOL;
}
echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->





