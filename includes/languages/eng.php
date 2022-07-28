<?php
    if(!function_exists('lang')) {
        function lang($phrase) {
            //  The static keyword is also used to declare variables in a function 
            // which keep their value after the function has ended.
            static $language = [

                // ٍStart Navbar
                'Home'              => "Home",
                'Items'             => "Items",
                'Statistics'        => "Statistics",
                'Members'           => "Members",
                'Gategores'         => "Gategores",
                'Comments'          => "Comments",
                'Go To Shop'        => "Go To Shop",
                'Settings'          => "Settings",
                'Edit Profile'      => "Edit Profile",
                'Logout'            => "Logout",
            ];

            return $language[$phrase];
        }
    }
