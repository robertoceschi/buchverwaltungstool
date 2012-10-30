
<!-- Start Tabelle -->
<?php
echo $pagination . PHP_EOL; ?>
<table class="stripeMe">
    <tr class="heading">
        <?php
        foreach($tablehead AS $th){
            echo '<th><a href="' .current_url() .
                 '?order=' . strtolower($th) .
                 '&sort=' . $sort . '">' .
                 $th . '</a></th>' . PHP_EOL;
        }
        ?>
        <th class="bearbeiten">Status</th>
        <th class="bearbeiten">Bearbeitung</th>
    </tr>
    <?php
    foreach($result AS $k=>$v){
        // Tabellenfelder


        echo '<tr class="results row">' . PHP_EOL;
        $array = explode('-',$v['datum']);
        $datum = $array[2] . '.' . $array[1] . '.' . $array[0];
        echo '<td>' . $datum . '</td>' . PHP_EOL;
        echo '<td>' . substr($v['zeit'], 0,5) . '</td>' . PHP_EOL;
        echo '<td>' . $v['titel'] . '</td>' . PHP_EOL;
        echo '<td>' . $v['ort'] . '</td>' . PHP_EOL;
        if ($v['bild'] == '') {
            echo  '<td>  </a></td>' . PHP_EOL;
        }else  {
            echo '<td><a href="' . base_url() . 'media/redaktion/images/agenda/thumbs/thumb_' . $v['bild'] . '"class="preview">Bild</a></td>' . PHP_EOL;
        }
        //echo '<td>' . $v['bild'] . '</td>' . PHP_EOL;


        //Status ändern
        echo '<td>' . PHP_EOL;
        $attributes = array('class' => 'inline');
        $options= array('a' => 'sichtbar', 'p' => 'versteckt');
        echo form_open(current_url(), $attributes) . PHP_EOL;
        echo form_dropdown('status',$options, $v['status'], 'onchange="this.form.submit()"') . PHP_EOL;
        echo form_hidden('id', $v['id']) . PHP_EOL;
        echo '<noscript>' . form_submit('submit', 'Senden') . '</noscript>' . PHP_EOL;
        echo form_close() . PHP_EOL;
        echo '</td>'. PHP_EOL;

        // Editierfelder
        echo '<td><a href="' . base_url() . 'redaktion/' . $uri_edit . '/show/' . $v['id'] .'/' . $current_page . '">' . PHP_EOL .
            '<span class="add-on"><i class="icon-eye-open" alt="anzeigen" title="anzeigen"></i></span></a> | ' . PHP_EOL .
            '<a href="' . base_url() . 'redaktion/' . $uri_edit . '/edit/' . $v['id'] .'/' . $current_page . '">' . PHP_EOL .
            '<span class="add-on"><i class="icon-edit" alt="bearbeiten" title="bearbeiten"></i></span></a> | ' . PHP_EOL .
            '<a href="' . base_url() . 'redaktion/' . $uri_edit . '/delete/' . $v['id'] .'/' . $current_page . '/no">' . PHP_EOL .
            '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen"></i></span></a>' . PHP_EOL .
            '</td>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
    }
    ?>
</table>
<?php
echo 'Anzahl Einträge: ' . count($result) . '/ ' . $gesamt . '<br />' . PHP_EOL;
echo $pagination . PHP_EOL;
?>
<!-- Ende Tabelle -->

<!-- Ende Inhalt -->


