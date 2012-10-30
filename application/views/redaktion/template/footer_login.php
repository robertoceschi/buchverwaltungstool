<!-- Start Footer
================================================== -->

<p class="footer">
    <?php
    if ($this->session->userdata('username') != '' && !isset($logout)) {
        echo 'Eingeloggt als ' . $this->session->userdata('username') .
            ' (<a href="' . base_url() . 'redaktion/login/logout">logout</a>) | ';

    } else {
        echo 'Nicht eingeloggt |';
    }
    ?>

    &copy; <?php echo date("Y"); ?> <a href="http://www.parobri.ch" target="_blank"> parobri.ch</a>
</p>


</p>
<!-- Ende Footer -->
</body>
</html>