<?php
    // include Configration file.
        include('config.php');

    // Paths

        $lang   = 'includes/languages/';      // Laguage Directory.
        $tpl    = 'includes/templates/';     // Template Directory.
        $func   = 'includes/functions/';    // Functions Directory.
        $css    = 'layout/css/';            // Css Directory
        $js     = 'layout/js/';             // JS Directory.
        if(isset($_SESSION['user']))
            $sessionUser = $_SESSION['user'];

    // Reserved Word (Word can not use it)
        $RESERVED = ['Shit', ];

    // Include Importat Files.
        include($lang . 'eng.php');
        include($func. 'functions.php');
        include('admin/' . $func. 'mainFunctions.php');
        include($tpl . 'header.php');
        include($tpl . 'navigationBar.php')
        ?> 
            <!-- <link rel="stylesheet" href="<?php echo $css?>backend.css"> -->
        <?php


