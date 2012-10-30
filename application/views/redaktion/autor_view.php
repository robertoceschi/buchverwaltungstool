<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php echo '<h2>' . $heading . '</h2>'; ?>
<!-- Ende Seitentitel -->

<?php
//Initialisieren
    $fehler = 'class="fehler"';

    //echo '<p class="fehler">' . strip_tags($error) . '</p>' . PHP_EOL;

    if (isset($message)) {
        echo '<p class="fehler">' . strip_tags($message) . '</p>' . PHP_EOL;
    }
    echo strstr(validation_errors(), 'Feld') ? '<p class="fehler">bitte alle *-Felder ausfüllen</p>' . PHP_EOL : validation_errors() . PHP_EOL;
    echo '<!-- Start Formular -->';
    echo form_open_multipart() . PHP_EOL;
    if ($bild == '') {
    } else {
        if ($this->uri->segment(3) === 'edit') :

            echo '<div class="profil"><img src="' . base_url() . 'media/redaktion/images/autor/thumbs/thumb_' . $bild . '" alt="Autor Bild" title="Autor Bild"/><br/>';
            echo anchor("redaktion/autor/deleteImage/$autor_id", 'Bild löschen', array('onClick' => "return confirm('Sind sie sicher dass sie dieses Bild löschen möchten?')")) . '</div>'  ?> <?php endif;
    }
    ;
    echo form_fieldset() . PHP_EOL;
    echo form_error('vorname') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Vorname*:', 'vorname') . PHP_EOL;
    echo form_input('vorname', set_value('vorname', $vorname)) . PHP_EOL;
    echo '</div>';
    echo form_error('nachname') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Nachname*:', 'nachname') . PHP_EOL;
    echo form_input('nachname', set_value('nachname', $nachname)) . PHP_EOL;
    echo '</div>';
    echo form_error('link') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Webseite:', 'link') . PHP_EOL;
    $data = array(
        'name'        => 'website',
        'id'          => 'website',
        'class'       => 'link',
        'placeholder' => 'http://',
    );
    echo form_input($data, set_value('website', prep_url($website)));
    echo '</div>' . PHP_EOL;
    echo form_error('biografie') ? '<div  ' . $fehler . '>' . PHP_EOL : '<div id="tinyText">' . PHP_EOL;
    echo form_label('Biografie* :', 'biografie') . PHP_EOL;
    $data = array(
        'name'          => 'biografie',
        'id'            => 'text',
        'placeholder'   => 'Eine kurze Biografie des Autores, maximal 2000 Zeichen.',
    );
    echo form_textarea($data, set_value('biografie', $biografie)) . PHP_EOL;
    echo '</div> ' . PHP_EOL;    echo '<p id="chars2">Anzahl verbleibender Zeichen: </span><span id="chars"></span></p>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    //Image Upload 'custom'
    echo form_label('Bild*:', 'bild') . PHP_EOL;
    echo '<span class="file-wrapper">' . PHP_EOL;
    echo '<input type="file" name="userfile" id="bild" /> ' . PHP_EOL;
    echo '<span class="button">' . $autorenbild . ' </span>' . PHP_EOL;
    echo '<span class="file-holder">&nbsp;</span>';
    echo '</span>' . PHP_EOL;
    // Anaben zum Bildupload
    echo '<p id="chars2">erlaubte Bildtypen: ' . str_replace('|', ', ', $bildmasse['allowed_types']) . '<br />' .
        'max. ' . $bildmasse['max_width'] .' x ' .
        $bildmasse['max_height'] . ' px, ' .
        number_format($bildmasse['max_size']/1024, 2) .  ' mb' .
        '</span><span id="chars"></span></p>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    echo form_button('abbrechen', 'zurück', 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
    echo form_submit('submit', $submit_text, 'class="mittel go"') . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_close() . PHP_EOL;
?>

