<body>
<div class="container">
    <div class="page-header">
        <h1>Autorenübersicht</h1>
    </div>

    <div class="row">

        <ul class="thumbnails">
            <?php foreach ($result AS $k=> $v) {
            echo '<li class="span3">' . PHP_EOL;
            echo '<div class="thumbnail">' . PHP_EOL;
            if ($v['bild'] != '') {
                echo '<img class="tool" src="' . base_url() . 'media/redaktion/images/autor/thumbs/thumb_' . $v['bild'] . '" alt="Autor Bild" title="Autor Bild" />' . PHP_EOL;
                echo '</div>';
            } else {
                echo '<img class="tool" src="' . base_url() . 'media/redaktion/images/autor/thumbs/thumb_unbekannt_autor.png   " alt="Autor Bild noch nicht vorhanden" title="Autor Bild noch nicht vorhanden" /> </div>';
            }
            ;

            echo '<a href="#">' . $v['vorname'] . ' ' . $v['nachname'] . '</a>' . PHP_EOL;


        }
            ?>
    </div>


