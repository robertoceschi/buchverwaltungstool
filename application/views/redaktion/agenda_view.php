<!-- Start Inhalt
================================================== -->



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
    echo form_open_multipart() . PHP_EOL;
    if ($bild == '') {
    } else {
        if ($this->uri->segment(3) === 'edit') :
            echo '<div class="profil"><img src="' . base_url() . 'media/redaktion/images/agenda/thumbs/thumb_' . $bild . '" alt="Agenda Bild" title="Agenda Bild"/><br/>';
            echo anchor("redaktion/agenda/deleteImage/$agenda_id", 'Bild löschen', array('onClick' => "return confirm('Sind sie sicher dass sie dieses Bild löschen möchten?')")) . '</div>'  ?> <?php endif;
    }
    ;
    echo form_fieldset() . PHP_EOL;
    echo form_error('datum') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Datum:*', 'datum') . PHP_EOL;
    $data = array(
        'name'        => 'datum',
        'id'          => 'datepicker',
        'class'       => 'kurzDate',
    );
      if($datum == '' || $datum == '--'){
          $datum = '';
      }
    else{
        $array = explode('-',$datum);
        $datum = $array[2] . '.' . $array[1] . '.' . $array[0];
    }

    echo form_input($data, set_value('datum', $datum)) . PHP_EOL;
    echo '</div>';
    echo form_error('zeit') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Zeit:*', 'zeit') . PHP_EOL;
    $data = array(
        'name'        => 'zeit',
        'id'          => 'timepicker',
        'class'       => 'extra_kurz',
    );
    echo form_input($data, set_value('zeit', substr($zeit,0,5))) . PHP_EOL ;
    echo 'Uhr</div>';
    echo form_error('titel') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Titel:*', 'titel') . PHP_EOL;
    $data = array(
        'name'        => 'titel',
        'id'          => 'titel',
        'maxlength'   => '255',
    );
    echo form_input($data, set_value('titel', $titel)) . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo form_error('ort') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Ort:*', 'ort') . PHP_EOL;
    $data = array(
        'name'        => 'ort',
        'id'          => 'ort',
        'maxlength'   => '80',
    );
    echo form_input($data, set_value('ort', $ort)) . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo form_error('link') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Webseite', 'link') . PHP_EOL;
    $data = array(
        'name'        => 'website',
        'id'          => 'website',
        'class'       => 'link',
        'placeholder' => 'http://',
    );
    echo form_input ($data, set_value('website', prep_url($website)));
    echo '</div>' . PHP_EOL;
    echo form_error('beschreibung') ? '<div ' . $fehler . '>' . PHP_EOL : '<div id="tinyText">' . PHP_EOL;
    echo form_label('Beschreibung:', 'beschreibung') . PHP_EOL;
    $data = array(
        'name'          => 'beschreibung',
        'id'          =>   'text',
        'placeholder' =>   'Eine kurze Beschreibung zum Termin, maximal 1000 Zeichen.',
    );
    echo form_textarea ($data , set_value('beschreibung', $beschreibung)) . PHP_EOL;
    echo '</div> ' . PHP_EOL;
    echo '<p id="chars2">Anzahl verbleibender Zeichen: </span><span id="chars"></span></p>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    echo isset($uploaderror) ? '<div  ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Bild:', 'bild') . PHP_EOL;
    //Image Upload 'custom'
    echo '<span class="file-wrapper">' . PHP_EOL;
    echo '<input type="file" name="userfile" id="bild" /> ' . PHP_EOL;
    echo '<span class="button">' . $agendabild . ' </span>' . PHP_EOL;
    echo '<span class="file-holder">&nbsp;</span>';
    echo '</span>' . PHP_EOL;
    // Angaben zum Bildupload
    echo '<p id="chars2">erlaubte Bildtypen: ' . str_replace('|', ', ', $bildmasse['allowed_types']) . '<br />' .
        'max. ' . $bildmasse['max_width'] .' x ' .
        $bildmasse['max_height'] . ' px, ' .
        number_format($bildmasse['max_size']/1024, 2) .  ' mb' .
        '</span><span id="chars"></span></p>' . PHP_EOL;


    echo '</div>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    echo form_button('abbrechen', 'zurück', 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
    echo form_submit('submit', $submit_text, 'class="mittel  go"') . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_close() . PHP_EOL;?>




<!-- Ende Formular -->
<!-- Ende Inhalt -->

