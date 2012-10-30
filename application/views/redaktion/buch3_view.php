
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
elseif(strstr(validation_errors(), 'Dezimalzahl')){
    echo '<p class="fehler">bitte eine Dezimalzahl (mit zwei Nachkommastellen) eintragen</p>' .PHP_EOL;
}
else {
    echo validation_errors() . PHP_EOL;
}


echo '<!-- Start Formular -->' . PHP_EOL;
// Auflage wählen hinzufügen
echo form_open();
echo form_fieldset() . PHP_EOL;
echo form_label('Auflage*:', 'auflage') . PHP_EOL;
$options = $auflage_arr;
$options[-1]= 'neue Auflage';
$js      = 'id="auflage_id" onchange="this.form.submit();"';
echo form_dropdown('auflage_id', $options, $auflage_id, $js);
echo form_fieldset_close() . PHP_EOL;
echo form_close() . PHP_EOL;

// Formular mit Angaben
echo form_open_multipart() . PHP_EOL;
if($bild != ''){
    echo '<div class="profil"><img src="' . base_url() . 'media/redaktion/images/auflage/thumbs/thumb_' . $bild . '" alt="Auflage Bild" title="Auflage Bild"/><br/>';
    echo anchor("redaktion/buch/deleteImage/" . $buch_id . "/" . $current_page . "/" . $auflage_id . "/" . $location, 'Bild löschen', array('onClick' => "return confirm('Sind sie sicher dass sie dieses Bild löschen möchten?')")) . '</div>';
}
echo form_fieldset($label) . PHP_EOL;

//echo form_hidden('auflage', set_value('auflage', $auflage), 'maxlength="255"') . PHP_EOL;

echo form_error('auflage') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Auflage*:', 'auflage') . PHP_EOL;
echo form_input('auflage', set_value('auflage', $auflage), 'maxlength="255"') . PHP_EOL;
echo '</div>';

echo form_error('menge') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Anzahl Artikel:', 'menge') . PHP_EOL;
if($menge !=-1){
    echo form_input('menge', set_value('menge', $menge), 'maxlength="255"') . PHP_EOL;
}
else{
    echo form_input('menge', set_value('menge', ''), 'maxlength="255"') . PHP_EOL;
}

echo '</div>';

echo form_error('vergriffen') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('vergriffen*:', 'vergriffen') . PHP_EOL;

$radio_vergr_j = array('name'        => 'vergriffen',
                       'value'       => 'j',
                       'class'       => 'mini',
                       'checked'     => $vergriffen=='j'?true:''
                    );
$radio_vergr_n = array('name'        => 'vergriffen',
                       'value'       => 'n',
                       'class'       => 'mini',
                       'checked'     => $vergriffen!='j'?true:''
);
echo form_radio($radio_vergr_n) . 'nein' . PHP_EOL;
echo form_radio($radio_vergr_j) . 'ja'  . PHP_EOL;
echo '</div><br />';

echo form_error('erscheinungsdatum') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('erschienen am*:', 'erscheinungsdatum') . PHP_EOL;
$data = array(
    'name'        => 'erscheinungsdatum',
    'id'          => 'datepicker'
    //'class'       => 'kurz',
);
if($erscheinungsdatum =='' || $erscheinungsdatum == '--'){
    $datum = '';
}
else{
    $array = explode('-',$erscheinungsdatum);
    $datum = $array[2] . '.' . $array[1] . '.' . $array[0];
}
echo form_input($data, set_value('erscheinungsdatum', $datum)) . PHP_EOL;
echo '</div><br />';

echo form_error('preis_sfr') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Preis in SFR*:', 'preis_sfr') . PHP_EOL;
echo form_input('preis_sfr', set_value('preis_sfr', $preis_sfr), 'maxlength="255"') . PHP_EOL;
echo '</div>';

echo form_error('preis_euro') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Preis in Euro*:', 'preis_euro') . PHP_EOL;
echo form_input('preis_euro', set_value('preis_euro', $preis_euro), 'maxlength="255"') . PHP_EOL;
echo '</div>';

echo form_error('isbn') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('ISBN*:', 'isbn') . PHP_EOL;
if(strlen($isbn) == 13){

    $isbn = substr($isbn,0,3) . '-' .
            substr($isbn,3,1) . '-' .
            substr($isbn,4,3) . '-' .
            substr($isbn,7,5) . '-' .
            substr($isbn,12,1);
}
echo form_input('isbn', set_value('isbn', $isbn), 'maxlength="255"') . PHP_EOL;
echo '</div>';
echo form_error('medium_id') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Medium*:', 'medium_id') . PHP_EOL;
$js      = 'onchange="this.form.submit();"';
echo form_dropdown('medium_id', $medium_arr, $medium_id, $js . 'style="width:190px;"') . PHP_EOL;
echo form_submit('mediumneu', 'Neues Medium', 'class="kurz"') . PHP_EOL;
echo '</div>' . PHP_EOL;
if(count($format_arr)>1){
    echo form_error('format_id') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Format:', 'format_id') . PHP_EOL;
    echo form_dropdown('format_id', $format_arr, $format_id, $js) . PHP_EOL;
    echo '</div>' . PHP_EOL;
}
echo form_error('status') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Status Auflage*:', 'status') . PHP_EOL;
echo form_dropdown('status', array('a' =>'sichtbar', 'p' => 'versteckt'), $status, $js) . PHP_EOL;
echo '</div>' . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo isset($uploaderror) ? '<div  ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
echo form_label('Buchcover:', 'userfile') . PHP_EOL;
//Image Upload 'custom'
echo '<span class="file-wrapper">' . PHP_EOL;
echo '<input type="file" name="userfile" id="bild" /> ' . PHP_EOL;
echo '<span class="button">' . $buchbild . ' </span>' . PHP_EOL;
echo '<span class="file-holder">&nbsp;</span>';
echo '</span>' . PHP_EOL;
// Angaben Bildupload
echo '<p id="chars2">erlaubte Bildtypen: ' . str_replace('|', ', ', $bildmasse['allowed_types']) . '<br />' .
      'max. ' . $bildmasse['max_width'] .' x ' .
      $bildmasse['max_height'] . ' px, ' .
      number_format($bildmasse['max_size']/1024, 2) .  ' mb' .
     '</span><span id="chars"></span></p>' . PHP_EOL;
echo '</div>' . PHP_EOL;

echo form_fieldset_close() . PHP_EOL;
echo form_fieldset() . PHP_EOL;
echo form_hidden('auflage_id', $auflage_id);
echo form_hidden('location', $location);
echo form_hidden('buch_id', $buch_id);
echo form_hidden('current_page', $current_page);
echo form_hidden('auflage_id', $auflage_id);
echo form_hidden('location', $location);
echo form_hidden('url', $this->uri->uri_string());
echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
echo form_submit('weiter', $submit_txt, 'class="mittel go"') . PHP_EOL;
echo form_fieldset_close() . PHP_EOL;
echo form_close();
?>

<!-- Ende Formular -->
<!-- Ende Inhalt -->