
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
        <th class="bearbeiten">Bearbeitung</th>
    </tr>
    <?php
    foreach($result AS $k=>$v){
        // Tabellenfelder
        echo '<tr class="results row">' . PHP_EOL;
        echo '<td>' . $v['edition'] . '</td>' . PHP_EOL;

        // Editierfelder
        echo '<td><a href="' . base_url() . 'redaktion/' . $uri_edit . '/edit/' . $v['id'] .'/' . $current_page . '">' . PHP_EOL .
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

