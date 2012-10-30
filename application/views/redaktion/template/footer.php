<!-- Start Footer
================================================== -->
 </div>
<p class="footer">
    <?php
    if ($this->session->userdata('benutzername') != '' && !isset($logout)) {
        echo 'Eingeloggt als ' . $this->session->userdata('benutzername') .
            ' (<a href="' . base_url() . 'redaktion/login/logout">logout</a>) | Copyright &copy; <?php echo date("Y"); ?> <a href="http://www.parobri.ch" target="_blank"> parobri.ch</a> | <a><span id="opener">Hilfe</span></a> ';

    } else {
        echo 'Nicht eingeloggt  ';

    }
    ?>

    <!-- leerer div für die Ausgabe der Hilfe-Datei-->
<div id="hilfe"></div>

</p>
<!-- Ende Footer -->
</body>
</html>
