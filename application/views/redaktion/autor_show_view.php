<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<h2><?php echo $heading; ?></h2>
<!-- Ende Seitentitel -->
<!-- Start Show-Formular -->
<?php
    //Initialisierung der Fehleranzeige des Bild-Uploaders
    if ($this->uri->segment(3) === 'edit') {
        $errors = $this->upload->display_errors();
        echo '<p class="fehler"' . $errors . '</p>';
    }

    if ($bild == '' and $this->uri->segment(3) != 'delete') {
        echo '<p class="fehler">Achtung! Sie haben noch kein Autorenbild angegeben. Sie können dies auch noch später nachholen. </p>';
    }

    echo form_open() . PHP_EOL;

    echo form_fieldset( /*$label*/) . PHP_EOL;

    echo '<table class="anzeigen">' . PHP_EOL;
    echo '<tr><td>Vorname:</td><td>' . $vorname . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Nachname:</td><td>' . $nachname . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Biografie:</td><td>' . $biografie . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Webseite:</td><td> <a href="' . prep_url($website) . '" target="_blank">' . prep_url($website). '</a></td></tr>' . PHP_EOL;?>
<tr>
    <td>Bild:</td>
    <td> <?php if ($bild == '') {
        echo '<img src="' . base_url() . 'media/redaktion/images/autor/thumbs/thumb_unbekannt_autor.png"/>';
    } else {
        echo '<img src="' . base_url() . 'media/redaktion/images/autor/thumbs/thumb_' . $bild . '" /></td></tr>' . PHP_EOL;
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
