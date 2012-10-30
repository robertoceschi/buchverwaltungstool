
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
        echo '<td>' . $v['buchtitel'] . '</td>' . PHP_EOL;
        //echo '<td>' . $v['untertitel'] . '</td>' . PHP_EOL;
        echo '<td>' . str_replace('+', ' ', $v['autor']) . '</td>' . PHP_EOL;
        echo '<td>' . $v['edition'] . '</td>' . PHP_EOL;

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
            '<a href="' . base_url() . 'redaktion/' . $uri_edit . '/editb/' . $v['id'] .'/' . $current_page . '">' . PHP_EOL .
            '<span class="add-on"><i class="icon-edit" alt="bearbeiten" title="bearbeiten"></i></span></a> | ' . PHP_EOL .
            '<a href="' . base_url() . 'redaktion/' . $uri_edit . '/delete/' . $v['id'] .'/' . $current_page . '/">' . PHP_EOL .
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

