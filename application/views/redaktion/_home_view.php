<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php if ($status == 'adm') {
    $status = 'Administrator';
} else
    if ($status == 'usr') {
        $status = 'Redaktor';
    } else
        if ($status == 'psv') {
            $status = 'inaktiv';
        }
?>


<h2>Hallo <?php echo $vorname . ' ' . $nachname ; ?>!<br/>


    <!-- Ende Seitentitel -->
<h3>Einstellungen</h3>
<ul>
    <?php
    echo '<li>' . anchor('redaktion/nutzer/edit/' . $nutzer_id . '/0/own', 'Mein Profil bearbeiten') . '</li>' . PHP_EOL;

    if ($status == 'Administrator') {
        echo '<li>' . anchor('redaktion/nutzer/index/own', 'Neuer Account einrichten') . '</li>' . PHP_EOL;
        echo '<li>' . anchor('redaktion/nutzeruebersicht', 'alle Benutzer anzeigen') . '</li>' . PHP_EOL;
    }else {

    }
    ?>
</ul>



    <h3>Zum Frontend</h3>
    <ul>
        <?php
        echo '<li>' . anchor('home', 'Frontend') . '</li>' . PHP_EOL;
        ?>
    </ul>



<!--<div id="rolle"><h5>eingeloggt als<?php echo $status; ?></h5></div>-->
<!-- Ende Inhalt -->
