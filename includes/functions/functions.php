<?php 

    /**
     * @version 1.0.0
     * @todo set login|signup link Or name user
     * @param nameSession name user from session.
     */
    function setLoginOrUser($nameSession, $secoundSesion) {
        if(isset($_SESSION[$nameSession]) || isset($_SESSION[$secoundSesion]) ) {
            setLinks();
        } else { ?>
            <a href="login.php" class="login-link">
                <span>login | Sigup</span>
            </a>
        <?php
        }
    }


    // /**
    //  * @version 1.0.0
    //  * @todo check user Statues if 1 or 0
    //  * @param name name user  in session
    //  * 
    //  */
    // function checkRegStatus($user) { 
    //     global $db;
    //     $stmt = $db->prepare("SELECT userName = ? FROM users WHERE regStatus = 0");
    //     $stmt->execute([$user]);
    //     $status = $stmt->rowCount();
    //     return $status;
    // }



    /**
     * @version 1.0
     * @todo check if item approve or not
     * @param apprveVlue the value of approve colmun
     * @return true if value equal one false if value equal zero
     */
    function checkIfApproved($approveValue) {
        if($approveValue == 0) {
            return false;
        } else {
            return true;
        }
    }
