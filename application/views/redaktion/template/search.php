<?php
// Reiter für Unterkategorien
    if ($this->uri->segment(2) === 'buecheruebersicht' ||
        $this->uri->segment(2) === 'genreuebersicht' ||
        $this->uri->segment(2) === 'editionuebersicht' ||
        $this->uri->segment(2) === 'medienuebersicht' ||
        $this->uri->segment(2) === 'themenkreisuebersicht'
    ) {


        $this->uri->segment(2) === 'buecheruebersicht' ? $b = '_aktiv' : $b = '';
        $this->uri->segment(2) === 'genreuebersicht' ? $g = '_aktiv' : $g = '';
        $this->uri->segment(2) === 'editionuebersicht' ? $e = '_aktiv' : $e = '';
        $this->uri->segment(2) === 'medienuebersicht' ? $m = '_aktiv' : $m = '';
        $this->uri->segment(2) === 'themenkreisuebersicht' ? $t = '_aktiv' : $t = '';

        echo '<!-- Reiter Unterseiten --> ';
        //echo '<div class="neuer_eintrag"><a href="buchbearbeiten1.php">Neues Buch hinzufügen</a></div>' . PHP_EOL;
        echo '<div class="neuer_eintrag_min' . $g . '"><a href="' . base_url() . 'redaktion/genreuebersicht">Genres</a></div>' . PHP_EOL;
        echo '<div class="neuer_eintrag_min' . $e . '"><a href="' . base_url() . 'redaktion/editionuebersicht">Editionen</a></div>' . PHP_EOL;
        echo '<div class="neuer_eintrag_min' . $m . '"><a href="' . base_url() . 'redaktion/medienuebersicht">Medien</a></div>' . PHP_EOL;
        echo '<div class="neuer_eintrag_min' . $t . '"><a href="' . base_url() . 'redaktion/themenkreisuebersicht">Themenkreise</a></div>' . PHP_EOL;
        echo '<div class="neuer_eintrag_min' . $b . '"><a href="' . base_url() . 'redaktion/buecheruebersicht">Bücher</a></div>' . PHP_EOL;
        echo '<!-- Ende Reiter Unterseiten -->';
    }

?>
<!-- Start Suchfeld -->
<?php

    echo form_open(base_url() . 'redaktion/' . $controller . '/index/') . PHP_EOL;
    echo '<div id="suchen">';
    echo form_input('search', set_value('search', $search), 'class="mittel", placeholder="Suchbegriff eingeben"') . PHP_EOL;
    echo form_submit('submit', '', 'class="submit"') . PHP_EOL;
    echo form_close() . PHP_EOL;
    echo '</div>';
    if (isset($search) && $search != '') {
        echo '<span><a href="' . base_url() . 'redaktion/' . $controller . '/showall">alle anzeigen</a></span>' . PHP_EOL;
    }

?>

