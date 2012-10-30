<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <?php echo anchor('home', 'Hauptübersicht', array('class' => 'brand')); ?>

            <div class="nav-collapse">
                <ul class="nav">
                    <? if ($this->uri->segment(1) === 'autoren'): ?>
                    <li class="active"><? else : ?>
                    <li><? endif; ?><?php echo anchor('autoren', 'Autoren'); ?></li>
                    <? if ($this->uri->segment(1) === 'buecher'): ?>
                    <li class="active"><? else : ?>
                    <li><? endif; ?><?php echo anchor('buecher', 'Bücher'); ?></li>
                    <? if ($this->uri->segment(1) === 'agenda'): ?>
                    <li class="active"><? else : ?>
                    <li><? endif; ?><?php echo anchor('agenda', 'Agenda'); ?></li>
                </ul>
                <?php echo anchor('redaktion/login', 'Backend', array('class' => 'btn btn-info pull-right')); ?>

            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

