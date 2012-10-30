<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<h2><?php echo $heading; ?></h2>
<!-- Ende Seitentitel -->
<!-- Start Show-Formular -->
<?php
    if ($status == 'adm') {
        $status = 'Administrator';
    } else
        if ($status == 'usr') {
            $status = 'Redaktor';
        } else
            if ($status == 'psv') {
                $status = 'inaktiv';
            }

    echo form_open() . PHP_EOL;
    echo form_fieldset($label) . PHP_EOL;
    echo '<table class="anzeigen">' . PHP_EOL;
    echo '<tr><td>Vorname:</td><td>' . $vorname . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Nachname:</td><td>' . $nachname . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Emailadresse:</td><td>' . $emailadresse . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Benutzername:</td><td>' . $benutzername . '</td></tr>' . PHP_EOL;
    echo '<tr><td>Passwort:</td><td>***</td></tr>' . PHP_EOL;
    if ($status == 'adm') {
    echo '<tr><td>Status:</td><td>' . $status . '</td></tr>' . PHP_EOL;
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
