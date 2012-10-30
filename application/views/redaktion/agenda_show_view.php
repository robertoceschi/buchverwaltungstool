<!-- Start Inhalt
================================================== -->


<!-- Start Seitentitel -->
<h2><?php echo $heading; ?></h2>
<!-- Ende Seitentitel -->
<!-- Start Show-Formular -->
<?php

    echo form_open() . PHP_EOL;
    echo form_fieldset(/*$label*/) . PHP_EOL;
    echo '<table class="anzeigen">' . PHP_EOL;
    $array = explode('-',$datum);
    $datum = $array[2] . '.' . $array[1] . '.' . $array[0];
    echo '<tr><td>Datum:</td><td>' . $datum . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Zeit:</td><td>' . substr($zeit, 0,5) . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Titel:</td><td>' . $titel . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Ort:</td><td>' . $ort . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Beschreibung:</td><td>' .$beschreibung .  '</td></tr>' . PHP_EOL;
    echo '<tr><td>Webseite:</td><td> <a href="' . prep_url($website) . '" target="_blank">' . prep_url($website). '</a></td></tr>' . PHP_EOL;?>
    <tr><td>Bild:</td><td> <?php if ($bild == '') {echo 'Für diesen Termin gibt es (noch) kein Bild.';} else {
    echo '<img src="' . base_url() . 'media/redaktion/images/agenda/thumbs/thumb_' . $bild . '" /> </td></tr>' . PHP_EOL;
}
    echo '</table>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_open() . PHP_EOL;
    echo form_button('abbrechen', $back_text, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
    echo form_button('bearbeiten', $submit_text, 'onClick=self.location.href="' . $submit_link . '"') . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_close() . PHP_EOL;
?>
<!-- Ende Show-Formular -->
<!-- Ende Inhalt -->
