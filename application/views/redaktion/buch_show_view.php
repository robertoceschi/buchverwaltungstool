
<!-- Start Inhalt
================================================== -->
<!-- Start Seitentitel -->
<?php echo '<h2>' . $heading . '</h2>'; ?>
<!-- Ende Seitentitel -->

<div class="neuer_eintrag"><a href="<?php echo base_url() . 'redaktion/buch/edita/' . $buch_id . '/' . $current_page . '/' . -2; ?>">Neue Auflage</a></div>

<?php
    echo form_open() . PHP_EOL;
    echo form_fieldset() . PHP_EOL;
    echo form_hidden('buchauswahl', 'na');
    if(!isset($fix)){
        $js = 'id="buecher" onchange="this.form.submit();"';
        echo form_dropdown('buch_id', $buchliste, $buch_id, $js) . PHP_EOL;
    }
    else {
        echo $buchliste[$buch_id] . PHP_EOL;
    }
    echo form_fieldset_close() . PHP_EOL;
?>

<div id="accordion">

    <h3>
        <table>
            <tr>
                <td class="descr"><a href="#">Buchinformationen allgemein (<?php echo $status =='a' ? 'sichtbar' : 'versteckt'; ?>)</a></td>

                <td><span class="add-on"><i class="icon-eye-open" alt="anzeigen" title="anzeigen"></i></span> |
                    <span class="add-on"><i class="icon-edit" alt="bearbeiten" title="bearbeiten" onclick="window.location.href = '<?php echo base_url() . 'redaktion/buch/editb/' . $buch_id . '/' . $current_page . '/' . 'show'; ?>'"></i></span> |
                    <span class="add-on"><i class="icon-trash" alt="löschen" title="löschen" onclick="window.location.href = '<?php echo base_url() . 'redaktion/buch/delete/' . $buch_id . '/' . $current_page . '/-1/show'; ?>'"></i></span>
            </tr>
        </table>
    </h3>
    <div>
        <table>
        <?php
            echo '<tr>' . PHP_EOL;
            echo '<td class="klein">Buchtitel:</td>' . PHP_EOL;
            echo '<td>' . $buchtitel . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

            echo '<tr>';
            echo '<td>Untertitel:</td>' . PHP_EOL;
            echo '<td>' . $untertitel . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

            echo '<tr>' . PHP_EOL;
            echo '<td>Beschreibung:</td>' . PHP_EOL;
            echo '<td>' . strip_tags($beschreibung) . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

            echo '<tr>' . PHP_EOL;
            echo '<td>Genre:</td>' . PHP_EOL;
            echo '<td>' . $genre . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

            echo '<tr>' . PHP_EOL;
            echo '<td>Edition:</td>' . PHP_EOL;
            echo '<td>' . $edition . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

            echo '<tr>' . PHP_EOL;
            echo '<td>Autor(en):</td>' . PHP_EOL;
            echo '<td>' . PHP_EOL;
            foreach($autor AS $v){
                echo $v['vorname'] . ' ' . $v['nachname'] . '<br />';
            }
            echo '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;
            echo '<tr>' . PHP_EOL;
            echo '<td>Themenkreis(e):</td>' . PHP_EOL;
            echo '<td>' . PHP_EOL;
            foreach($themenkreis AS $v){
                echo $v['themenkreis'] . '<br />';
            }
            echo '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;



            echo '<tr>' . PHP_EOL;
            echo '<td>Leseprobe:</td>' . PHP_EOL;
            echo '<td>';
            if($leseprobe !=''){
                echo '<a href="' . base_url() . 'media/redaktion/leseprobe/' . $leseprobe . '" target="_blank">' . $leseprobe . '</a>';
            }
            else {
                echo '(keine Leseprobe vorhanden)';
            }
            echo '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;
            echo '<tr>' . PHP_EOL;
            echo '<td>Status Buch:</td>' . PHP_EOL;
            echo '<td>';
            if($status == 'a'){
                echo 'sichtbar';
            }
            else{
                echo 'versteckt';
            }
            echo '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

        ?>
        </table>
    </div>

    <?php


        echo '<h3>' . PHP_EOL;
        echo '<table>' . PHP_EOL;
        echo '<tr>' . PHP_EOL;
        echo '<td class="descr">' . PHP_EOL;
        echo '<a href="#">Pressetexte (';
        echo $status =='a' ? 'sichtbar' : 'versteckt';
        echo ')</a>' . PHP_EOL;
        echo '</td>' . PHP_EOL;
        echo '<td>' . PHP_EOL;
        echo '<span class="add-on"><i class="icon-eye-open" alt="anzeigen" title="anzeigen"></i></span> | ' . PHP_EOL;
        echo '<span class="add-on"><i class="icon-edit" alt="bearbeiten" title="bearbeiten" onclick="window.location.href = \'' . base_url() . 'redaktion/buch/editp/' . $buch_id . '/' . $current_page . '/' . 'show\'"></i></span> | ' . PHP_EOL;
    if(isset($pressetextarr) && $pressetextarr != array()){
        echo '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen" onclick="window.location.href = \'' . base_url() . 'redaktion/buch/editp/' . $buch_id . '/' . $current_page . '/show/delete\'"></i></span>' . PHP_EOL;
    }
    else {
        echo '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen"></i></span>' . PHP_EOL;
    }
        echo '</td>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
        echo '</table>' . PHP_EOL;
        echo '</h3>' . PHP_EOL;
        echo '<div>' . PHP_EOL;
        echo '<table>' . PHP_EOL;

    if(isset($pressetextarr) && $pressetextarr != array()){
        $i = '1';
        foreach($pressetextarr AS $v){

            echo '<tr>' . PHP_EOL;
            echo '<td class="klein">' . PHP_EOL;
            echo 'Text ' . $i . ':<br />' . PHP_EOL;
            if($v['status'] == 'a'){
                echo '(sichtbar)';
            }
            else {
                echo '(versteckt)';
            }
            echo '</td>' . PHP_EOL;
            echo '<td>' . PHP_EOL;
            echo strip_tags($v['pressetext']) . PHP_EOL;
            echo '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;

            $i++;
        }
    }
    else{
        echo '<tr>' . PHP_EOL;
        echo '<td class="klein">' . PHP_EOL;
        echo '(kein Pressetext vorhanden)' . PHP_EOL;
        echo '</td>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
    }
        echo '</table>' . PHP_EOL;
        echo '</div>' . PHP_EOL;


    // Bestehende Auflagen anzeigen, wenn vorhanden
    if(isset($auflage)){
        foreach($auflage AS $k=>$v){
            ?>
            <h3>
                <table>
                    <tr>
                         <td class="descr">
                             <a href="#"><?php echo $v['auflage']; ?>. Auflage (<?php echo $v['status']=='a' && $status =='a' ? 'sichtbar' :'versteckt'; ?>)</a>
                         </td>
                         <td>
                            <span class="add-on"><i class="icon-eye-open" alt="anzeigen" title="anzeigen"></i></span> |
                            <span class="add-on"><i class="icon-edit" alt="bearbeiten" title="bearbeiten" onclick="window.location.href = '<?php echo base_url() . 'redaktion/buch/edita/' . $buch_id . '/' . $current_page . '/' . $v['auflage_id'] . '/' . 'show'; ?>'"></i></span> |
                            <span class="add-on"><i class="icon-trash" alt="löschen" title="löschen" onclick="window.location.href = '<?php echo base_url() . 'redaktion/buch/delete/' . $buch_id . '/' . $current_page . '/' . $v['auflage_id'] . '/show'; ?>'"></i></span>
                         </td>
                    </tr>
                </table>
            </h3>
            <?php
            echo '<div>' . PHP_EOL;
            echo '<table>';

            echo '<tr>';
            echo '<td>Menge</td>';
            echo '<td>';
            echo $v['menge']!=-1 ? $v['menge'] . ' Stück' : '(keine Angabe)';
            echo '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td class="klein">Erscheinungsdatum</td>';
            echo '<td>' . date('d. M. Y', strToTime($v['erscheinungsdatum'])) . '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>vergriffen</td>';
            echo '<td>';
            echo $v['vergriffen']=='n'?'nein':'ja';
            echo '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>ISBN(13)</td>';



            echo '<td>';
            echo substr($v['isbn'],0,3) . '-' .
                 substr($v['isbn'],3,1) . '-' .
                 substr($v['isbn'],4,4) . '-' .
                 substr($v['isbn'],8,4) . '-' .
                 substr($v['isbn'],12,1);
            echo '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>Preis SFR</td>';
            echo '<td>' . $v['preis_sfr'] . ' SFR</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>Preis Euro</td>';
            echo '<td>' . $v['preis_euro'] . ' €</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>Medium</td>';
            echo '<td>' . $v['medium'] . '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>Format</td>';
            echo '<td>';
            echo $v['format']!='' ? $v['format'] : '(kein Format gewählt)';
            echo '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>Bild</td>';
            echo '<td>';
            if($v['bild'] != ''){
                echo '<img src="' . base_url() . 'media/redaktion/images/auflage/thumbs/thumb_' . $v['bild'] . '" alt="Auflage Bild" title="Auflage Bild"/><br/>';
                //echo $v['bild'];
            }
            else{
                echo '(kein Bild vorhanden)';
            }
            echo '</td>' . PHP_EOL;
            echo '</tr>';

            echo '<tr>';
            echo '<td>Status Auflage</td>';
            echo '<td>';
            if($v['status'] == 'a'){
                echo 'sichtbar';
            }
            else {
                echo 'versteckt';
            }
            echo '</td>' . PHP_EOL;
            echo '</tr>';

            echo '</table>';
            echo '</div>' . PHP_EOL;
        }
    }
    ?>

</div>
<?php
    echo form_fieldset() . PHP_EOL;
    echo form_button('abbrechen', $back_txt, 'onClick=self.location.href="' . $back_link . '"') . PHP_EOL;
    echo form_fieldset_close() . PHP_EOL;
    echo form_close() . PHP_EOL;
?>




<!-- Ende Formular -->
<!-- Ende Inhalt -->

