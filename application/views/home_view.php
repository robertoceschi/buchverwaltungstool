<body>

<div class="container">

    <div class="hero-unit">
        <hgroup>

            <h1>Buchverwaltungssystem</h1>

            <h2>
                <small>Version 2.0.0 - Parobri</small>
            </h2>
        </hgroup>
    </div>
    <div class="row">
        <div class="span4">
            <h2>Autoren</h2>

            <p>Eine Übersicht über alle Autoren:</p>

            <p><?php echo anchor('autoren', 'Autorenübersicht  &raquo;', array('class' => 'btn')); ?> </p>
        </div>
        <div class="span4">
            <h2>Bücher</h2>

            <p>Eine Übersicht über alle Bücher:</p>
            <ul>
                <li>Krimis</li>
                <li>Romane</li>
                <li>Biografien</li>
            </ul>
            <p><?php echo anchor('buecher', 'Bücher  &raquo;', array('class' => 'btn')); ?> </p>
        </div>
        <div class="span4">
            <h2>Agenda</h2>

            <p>Eine Übersicht über alle Termine:</p>

            <p><?php echo anchor('agenda', 'Agenda  &raquo;', array('class' => 'btn')); ?> </p>
        </div>
    </div>
    <!-- end row 1 -->



