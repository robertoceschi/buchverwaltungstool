<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php
$status_name = '';
if ($status == 'adm') {
    $status_name = 'Administrator';
}
if ($status == 'usr') {
    $status_name = 'Redaktor';
}
if ($status == 'psv') {
    $status_name = 'inaktiv';
}
?>
<div id="home_site">
<h4>Hallo <?php echo $vorname . ' ' . $nachname ; ?>!</h4>



<ul>
    <?php
    echo '<li <span class="add-on"><i class="icon-edit"></i></span>' . anchor('redaktion/nutzer/edit/' . $nutzer_id . '/0/own', '    Mein Profil bearbeiten') . '</li> ' .  PHP_EOL;

    if ($status_name == 'Administrator') {
        echo '<li<span class="add-on"><i class="icon-pencil"></i></span>' . anchor('redaktion/nutzer/index/own', '   Neuer Account einrichten') . '</li> ' . PHP_EOL;
        echo '<li <span class="add-on"><i class="icon-user"></i></span> ' . anchor('redaktion/nutzeruebersicht', '   alle Benutzer anzeigen') . '</li>' . PHP_EOL;
    }else {

    }
    ?>
</ul>




    <ul>
        <?php
        echo '<li <span class="add-on"><i class="icon-home"></i></span>' . anchor('home', '   Frontend' ) . '</li>' . PHP_EOL;
        ?>
    </ul>



</div>
