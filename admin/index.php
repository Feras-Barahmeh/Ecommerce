<?php

// Start Global Defination

    ob_start();
    session_start(); // To Register all of action of the user.
    $TITLE = 'Login'; // To set Title in page
    include('init.php');

// End Global Defination

    function getInfoInForm() {
        $username = $_POST['userName'];
        $password = $_POST['password'];
        $info = ['username' => $username, 'password' => encryptPassword($password)];
        return $info; 
    }


    function setSession() {
        $info = getInfoInForm();
        if(checkifPermeationUser($info['username'])) {
            $_SESSION['username']     = $info['username'];
            $_SESSION['password']     = $info['password'];
            $_SESSION['userID']       = getTable('userID', 'users', 'WHERE userName = \'' . $info['username'] . "'", NULL, NULL, 'fetch')['userID'];
        } else {
            ?> <div class="alert alert-danger container">No Permeation to you</div> <?php
        }
    }


    function checkIfExsist() {
        $info = getInfoInForm();
        if (ifTypeRequestPOST()) {
            encryptPassword($info['password']);
            return ItemExistOrRepeate('userName', 'users', $info['username']);
        }
    }

    function toDashboard() {
        if(ifTypeRequestPOST()) {
            if(checkIfExsist()) {
                setSession();
                if ($_SESSION['username'] == getInfoInForm()['username']) {
                    header('Location: dashboard.php');
                }
                
            } else {
                ?> <div class="alert alert-danger container">Not Exist</div> <?php
            }
        }
    }

        toDashboard();
?>

<!-- Start Form -->

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login container" autocomplete="FALSE">
    <h4 class="login-h">Login</h4>
    <input class="form-control" type="username" placeholder="Username" name="userName">
    <input type="password" class="form-control" placeholder="Password" name="password">
    <input type="submit" class="form-control btn" value="login">
</form>

<?php
include($tpl . 'footer.php');
ob_end_flush();
?>