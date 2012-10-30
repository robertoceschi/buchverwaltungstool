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
    $attributes = array('class' => '', 'id' => 'commentForm');
    echo form_open('', $attributes);
    echo form_fieldset($label) . PHP_EOL;
    echo form_error('vorname') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Vorname*:', 'vorname') . PHP_EOL;
    $data = array('name'           => 'vorname',
                  'id'             => 'vorname',);
    echo form_input($data, set_value('vorname', $vorname)) . PHP_EOL;
    echo form_error('nachname') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Nachname*:', 'nachname') . PHP_EOL;
    $data = array('name'           => 'nachname',
                  'id'             => 'nachname',);
    echo form_input($data, set_value('nachname', $nachname)) . PHP_EOL;
    echo '</div>';
    echo form_error('emailadresse') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Email*:', 'emailadresse') . PHP_EOL;
    $data = array(
        'name' => 'emailadresse',
        'id'   => 'emailadresse'
    );
    echo form_input($data, set_value('emailadresse', $emailadresse)) . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_fieldset('Login Info') . PHP_EOL;
    echo form_error('benutzername') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label('Benutzername*:', 'benutzername') . PHP_EOL;
    $data = array('name'           => 'benutzername',
                  'id'             => 'benutzername',);
    echo form_input($data, set_value('benutzername', $benutzername)) . PHP_EOL;
    echo '</div>' . PHP_EOL;
    if (isset($paswd_hide) && $paswd_hide == 'yes') {
        echo '<span><a class="newpassword" href="#">neues Passwort?</a></span>&nbsp;';
        echo '<div class="newpassword">';
    } else {
        echo '<div>';
    }
    echo form_error('passwort') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label($paswd_text, 'passwort') . PHP_EOL;
    $data = array('name'        => 'passwort',
                  'class'       => 'required',
                  'id'          => 'passwort'
    );
    echo form_password($data, set_value('passwort', '')) . PHP_EOL;
    echo form_error('passwort2') ? '<div ' . $fehler . '>' . PHP_EOL : '<div>' . PHP_EOL;
    echo form_label($paswd_text . ' wiederholen*:', 'passwort2') . PHP_EOL;
    $data = array('name' => 'passwort2',
                  'id'   => 'passwort2'
    );
    echo form_password($data, set_value('passwort2', '')) . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo '</div>';
    echo form_fieldset_close() . PHP_EOL;


    if($status == 'adm') {
    echo form_fieldset() . PHP_EOL;
    echo '<div>'. PHP_EOL;
    echo form_label('Status:', 'status') . PHP_EOL;
    $options= array('adm' => 'Administrator', 'usr' => 'Redaktor', 'psv' => 'Inaktiv');
    echo form_dropdown('status',$options) . PHP_EOL;
    echo '</div>'. PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    }
    echo form_fieldset() . PHP_EOL;
    echo form_button('abbrechen', 'zurück', 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
    echo form_submit('submit', $submit_text, 'class="mittel go"') . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_close() . PHP_EOL;
?>
<!-- Ende Formular -->
<!-- Ende Inhalt -->
