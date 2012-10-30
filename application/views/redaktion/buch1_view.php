
<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php echo '<h2>' . $heading . '</h2>'; ?>
<!-- Ende Seitentitel -->

<?php
//Initialisieren
$fehler = 'class="fehler"';
$js     = 'onchange="this.form.submit();" class="dropdown"';

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
echo form_open_multipart() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
    echo form_error('buchtitel') ? '<div ' . $fehler .'>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Buchtitel*:', 'buchtitel') . PHP_EOL;
    echo form_input('buchtitel', set_value('buchtitel', $buchtitel), 'maxlength="255"') . PHP_EOL;
    echo '</div>';

    echo form_error('untertitel') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Untertitel*:', 'untertitel') . PHP_EOL;
    echo form_input('untertitel', set_value('untertitel', $untertitel), 'maxlength="255"') . PHP_EOL;
    echo '</div>';

    echo form_error('beschreibung') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Beschreibung* :', 'beschreibung') . PHP_EOL;
    $data = array(
    'name'          => 'beschreibung',
    'rows'          => '10',
    'cols'          => '10',
    'id'            => 'text',
    'placeholder'   => 'Eine kurze Beschreibung des Buches, maximal 2000 Zeichen',
    );
    echo form_textarea($data, set_value('beschreibung', $beschreibung)) . PHP_EOL;
    echo '<p id="chars2">Anzahl verbleibender Zeichen: </span><span id="chars"></span></p>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;

    echo form_fieldset() . PHP_EOL;
    echo form_error('autorhinzu') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Autor(en)*:', 'autorhinzu') . PHP_EOL;
    // Dropdown mit den übrigen Autoren zusammenstellen
    foreach($autor_arr AS $k=>$v){
        if(!array_key_exists($k, array_flip($autor))){
            $autorarray[$k] = $v;
        }
    }
    echo form_dropdown('autorhinzu', $autorarray, '', $js) . PHP_EOL;
    echo form_submit('autorneu', 'Neuer Autor', 'class="kurz"') . PHP_EOL;
    echo form_hidden('autor', $autor);
    echo '</div>' . PHP_EOL;
    // ausgewählte Autoren auflisten
    if($autor != array()){
        echo '<ul class="eingerueckt">' . PHP_EOL;
        foreach($autor AS $v){
            echo '<li>' . $autor_arr[$v] . form_submit('autorweg', $v, 'class="loeschen listeloeschen"') . '</li>' . PHP_EOL;
        }
        echo  '</ul>' . PHP_EOL;
    }
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;

    echo form_error('themenkreishinzu') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Themenkreis(e)*:', 'themenkreishinzu') . PHP_EOL;
    // Dropdown mit den ÜBRIGEN Themenkreise zusammenstellen
    foreach($themenkreis_arr AS $k=>$v){
        if(!array_key_exists($k, array_flip($themenkreis))){
            $themenkreisarray[$k] = $v;
        }
    }
    echo form_dropdown('themenkreishinzu', $themenkreisarray, '', $js) . PHP_EOL;
    echo form_submit('themenkreisneu', 'Neuer Themenkreis', 'class="kurz"') . PHP_EOL;
    echo form_hidden('themenkreis', $themenkreis);
    echo '</div>';
    // ausgewählte Autoren auflisten
    if($themenkreis != array()){
        echo '<ul class="eingerueckt">' . PHP_EOL;
        foreach($themenkreis AS $v){
            echo '<li>' . $themenkreis_arr[$v] . form_submit('themenkreisweg', $v, 'class="loeschen listeloeschen"') . '</li>' . PHP_EOL;
        }
        echo  '</ul>' . PHP_EOL;
    }
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    echo form_error('edition_id') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Edition*:', 'edition_id') . PHP_EOL;
    echo form_dropdown('edition_id', $edition, $edition_id, 'class="dropdown"') . PHP_EOL;
    echo form_submit('editionneu', 'Neue Edition', 'class="kurz"') . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo form_error('genre_id') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Genre*:', 'genre_id') . PHP_EOL;
    echo form_dropdown('genre_id', $genre, $genre_id, 'class="dropdown"') . PHP_EOL;
    echo form_submit('genreneu', 'Neues Genre', 'class="kurz"') . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo isset($uploaderror) ? '<div  ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Leseprobe:', 'userfile') . PHP_EOL;
    //PDF Upload 'custom'
    echo '<span class="file-wrapper">' . PHP_EOL;
    echo '<input type="file" name="userfile" id="leseprobe" /> ' . PHP_EOL;
    echo '<span class="button">' . $leseprobe_titel . ' </span>' . PHP_EOL;
    echo '<span class="file-holder">&nbsp;</span>';
    echo '</span>' . PHP_EOL;
    echo '<p id="chars2">Erlaubter Dateityp: ' . $pdfmasse['allowed_types'] . ', max ' . number_format($pdfmasse['max_size']/1024, 2) .' mb</span></p>' . PHP_EOL;
    echo '</div>' . PHP_EOL;

    // Bereits existierende der Leseprobe (wenn vorhanden)
    if($leseprobe!=''){
        echo '<label>&nbsp;</label>' . PHP_EOL;
        echo'<ul class="eingerueckt">' . PHP_EOL;
        echo '<li><a href="' . base_url() . 'media/redaktion/leseprobe/' . $leseprobe . '" target="_blank">' . $leseprobe . '</a>' . form_submit('leseprobeweg', $leseprobe, 'class="loeschen listeloeschen"') . '</li>' . PHP_EOL;
        echo '</ul>' . PHP_EOL;
    }

    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    echo form_error('status') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Status Buch*:', 'status') . PHP_EOL;
    echo form_dropdown('status', array('a' =>'sichtbar', 'p' => 'versteckt'), $status) . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;

    echo form_fieldset() . PHP_EOL;
    echo form_hidden('buch_id', $buch_id);
    echo form_hidden('current_page', $current_page);
    echo form_hidden('location', $location);
    echo form_hidden('url', $this->uri->uri_string());
    echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
    echo form_submit('weiter', $submit_txt, 'class="mittel go"') . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->





