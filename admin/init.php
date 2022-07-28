<?php
    // include Configration file.
        include('config.php');

    // Paths

        $lang   = 'includes/languages/';      // Laguage Directory.
        $tpl    = 'includes/templates/';     // Template Directory.
        $func   = 'includes/functions/';    // Functions Directory.
        $css    = 'layout/css/';            // Css Directory
        $js     = 'layout/js/';             // JS Directory.

    // Reserved Word (Word can not use it)
        $RESERVED = ['Shit', ];

    // Include Importat Files.
        include($lang . 'eng.php');
        include($func. 'mainFunctions.php');
        include($tpl . 'header.php');
        ?> <link rel="stylesheet" href="<?php echo $css?>backend.css">
        <?php


    // If You Set Navegation par In page Call This Function.
        if (!function_exists('setNav')) { // To comfirem declaration function Onse
            function setNav() {
                global $tpl, $css;
                include($tpl . 'nav.php');
            }
        }



