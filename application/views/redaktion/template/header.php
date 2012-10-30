<?php echo doctype('html5'); ?>

<head>

    <!-- Basic Page Needs
   ================================================== -->
    <meta charset="utf-8">
    <title>BVS - <?php echo $sPageTitle;?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
    if(isset($meta)){
        foreach ($meta as $k => $v) {
            echo $v . PHP_EOL;
        }
    }
    ?>

    <!-- CSS
   ================================================== -->
    <?php
    foreach ($css_files as $media => $css_file) {
        echo '<link rel="stylesheet"  href="'
            . base_url() . $css_file . '" media="' . $media . '" />' . PHP_EOL;
    }
    //echo '<link href=" ' . base_url() . 'css/bootstrap.min.css" rel="stylesheet">' ;
    ?>




    <!-- JavaScript
   ================================================== -->
    <?php
    foreach ($js_files as $key => $jsFile) {
        echo '<script type="text/javascript"  src="'
            . base_url() . $jsFile . '"></script>' . PHP_EOL;
    }
    ?>



    <!-- JS-Webroot Konstante-->
    <script>
         var WEBROOT= '<?php echo base_url();?>';
     </script>

</head>

<body>

<!-- Start Navigation
================================================== -->
<div id="box">
    <?php
  $nutzer = $this->session->userdata('status');
    if ($nutzer == 'adm') {
        $nutzer = 'Administrator';
    }
    if ($nutzer == 'usr') {
        $nutzer = 'Redaktor';
    }
    if ($nutzer == 'psv') {
        $nutzer = 'Inaktiv';
    }


?>

    <div id="header">
         <?php
            echo '<div id="rolle"><p>' . $nutzer  ; ?> </p></div>
        <?php echo heading('BVS - ' . $heading, 1) . PHP_EOL;?>


        <ul id="hauptnavi">
            <? if ($this->uri->segment(2) === 'home' ||
            $this->uri->segment(2) === 'nutzeruebersicht' ||
            $this->uri->segment(2) === 'nutzer'
        ) : ?>
            <li id="current"><? else : ?><li><? endif; ?><?php echo anchor('redaktion/home', 'Home'); ?></li>
            <? if ($this->uri->segment(2) === 'autorenuebersicht' ||
            $this->uri->segment(2) == 'autor'
        ) : ?>
            <li id="current"><? else : ?><li><? endif; ?><?php echo anchor('redaktion/autorenuebersicht', 'Autoren bearbeiten');?></li>
            <? if ($this->uri->segment(2) === 'buecheruebersicht' ||
            $this->uri->segment(2) == 'buch' ||
            $this->uri->segment(2) == 'genreuebersicht' ||
            $this->uri->segment(2) == 'genre' ||
            $this->uri->segment(2) == 'editionuebersicht' ||
            $this->uri->segment(2) == 'edition' ||
            $this->uri->segment(2) == 'themenkreis' ||
            $this->uri->segment(2) == 'themenkreisuebersicht' ||
            $this->uri->segment(2) == 'medium' ||
            $this->uri->segment(2) == 'medienuebersicht'
        ) : ?>
            <li id="current"><? else : ?><li><? endif; ?><?php echo anchor('redaktion/buecheruebersicht', 'Bücher bearbeiten');?></li>
            <? if ($this->uri->segment(2) == 'agendauebersicht' ||
            $this->uri->segment(2) == 'agenda'
        ) : ?>
            <li id="current"><? else : ?><li><? endif; ?><?php echo anchor('redaktion/agendauebersicht', 'Agenda bearbeiten');?></li>
            <? if ($this->uri->segment(2) === 'logout') : ?>
            <li id="current"><? else : ?><li><? endif; ?><?php echo anchor('redaktion/login/logout', 'Logout (' . $this->session->userdata('benutzername') . ')');?></li>
        </ul>
    </div>

<!-- Ende Navigation -->
