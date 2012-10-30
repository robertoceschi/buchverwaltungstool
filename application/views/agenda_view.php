<body>



<div class="container">
    <div class="page-header">
        <h1>Agendaübersicht</h1>
    </div>

    <table class="table table table-bordered table-hover ">

        <?php echo '<thead>' . PHP_EOL;
        echo '<tr>' . PHP_EOL;
        echo  '<th>Datum</th>' . PHP_EOL;
        echo    '<th>Zeit</th>' . PHP_EOL;
        echo    '<th>Titel</th>' . PHP_EOL;
        echo    '<th>Ort</th>' . PHP_EOL;
        //echo    '<th>Beschreibung:</th>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
        echo '</thead >' . PHP_EOL;
        foreach ($result AS $k=> $v) {
            echo '<tbody >' . PHP_EOL;
            echo '<tr>' . PHP_EOL;
            echo    '<td>' . $v['datum'] . '</td>' . PHP_EOL;
            echo    '<td>' . $v['zeit'] . '</td>' . PHP_EOL;
            echo    '<td>' . $v['titel'] . '</td>' . PHP_EOL;
            echo    '<td>' . $v['ort'] . '</td>' . PHP_EOL;
            //echo    '<td>' . $v['beschreibung'] . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;
            echo '</tbody >' . PHP_EOL; }
        echo '</table >' . PHP_EOL;

        ?>




        <!-- Initialize Scripts -->
        <script>
            $(document).ready(function () {
                //$('a[rel=tooltip]').tooltip();
                $('a[rel=popover]').popover();
            }); // end document.ready
        </script>



