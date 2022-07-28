<?php 

// Start Global Deffination
    ob_start();
    session_start(); // To Register all of action of the user.
    $TITLE = 'Login'; // To set Title in page
    include('init.php');
// End Global Deffination


// Start Fork Function
    function getInfoFromForm($to) {
        /**
         * @version 1.0
         * @todo return data from form login or signup
         * @param to select why you use this function login or signup
         */
        if($to === 'login') {
            $user       = filter_var($_POST['user'],  FILTER_UNSAFE_RAW );
            $password   = filter_var($_POST['password'], FILTER_UNSAFE_RAW );
            $login      = $_POST['login'];
            return ['user' => $user, 'password' => $password, 'login' => $login];
        } else {
            $user           =  filter_var($_POST['user'], FILTER_UNSAFE_RAW );
            $password       =  filter_var($_POST['password'], FILTER_UNSAFE_RAW );
            $confirmPass    =  filter_var($_POST['password_confirm'], FILTER_UNSAFE_RAW);
            $email          =  filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            return ['user' => $user, 'password' => $password, 'confirmPass' => $confirmPass, 'email' => $email];
        }
    }

    function signupUser() {
        global $db;
        $data = getInfoFromForm('signup');
        $stmt = $db->prepare(
                            "INSERT INTO 
                                users(userName, password, email, preDate, regStatus, groubID) 
                            VALUES
                                (?,?,?, NOW(), 0, 0)");
            $stmt->execute([$data['user'], sha1($data['password']), $data['email']]);
            if($stmt->rowCount() > 0) {
                ?> <hr> <div class='alert alert-success container'>Welcom <?php echo $data['user']?> you are member now <?php
            } else {
                echo "Loded in server try Later";
            }
    }


    function compareBetweenPassWord($nameuser, $passFromForm) {
        /**
         * @version 1.0.0
         * @todo compare between password come from form and password from db
         * @param nameuser the user you want check password
         * @param passFromForm the password come from form
         */
        $passInDB = getTable('password', 'users', 'WHERE userName = \'' . $nameuser . '\'', null, null, 'fetch');
        if($passInDB['password'] == sha1($passFromForm)) {
            return true;
        }
    }

    function setSession($user) {
        /**
         * @version 1.0
         * @todo set Name Session
         * @param user this session for eche user
         */
        if (checkifPermeationUser($user)) {
            $_SESSION['username'] = $user;
            $_SESSION['userID'] = getTable('userID', 'users', 'WHERE userName = \'' . $user . '\'', null, null, 'fetch')['userID'];
        } else {
            $_SESSION['user'] = $user;
            $_SESSION['IDuser'] = getTable('userID', 'users', 'WHERE userName = \'' . $user . '\'', null, null, 'fetch')['userID'];
        }
    }




    function validateLogin($data) {
        $errors = [];
        if(isset($_POST['login']) && $_POST['login'] === 'login') {
            $data = getInfoFromForm('login'); $ifuserExist = false;
            // Start User Name
                if(!ItemExistOrRepeate('userName', 'users', $data['user'])) {
                    array_push($errors, 'user name not exist Or faild PassWord');
                    $ifuserExist = true;
                }

                if(strlen($data['user']) <= 3) {
                    array_push($errors, 'user name must be grater than 3');
                } 

                if (ctype_upper($data['user'])) {
                    array_push($errors, 'user name must be lower');
                }
            // End User Name

            // Start Password
                if (strlen($data['password']) <= 3) {
                    array_push($errors, 'password must be grater than 3');
                }

                if(!$ifuserExist) {
                    $pass = getTable('password', 'users', 'WHERE userName = \'' . $data['user'] . '\'', NULL, NULL, 'fetch');
                    if(!empty($pass) && (string) encryptPassword($data['password']) !== (string) $pass['password']  ) {
                        array_push($errors, 'Failed password Or user Name');
                    }
                }
            // End Password

        }
        return $errors;
    }

    function validateSignup($data) {
        $errors = [];
        // Start User Name
        if(isset($_POST['signup']) && $_POST['signup'] === 'signup') {

                if(ItemExistOrRepeate('userName', 'users', $data['user'])) {
                    array_push($errors, 'This user name alrady Exist');
                }

                if(strlen($data['user']) <= 3) {
                    array_push($errors, 'user name must be grater than 3');
                } 

                if (!ctype_lower($data['user'])) {
                    array_push($errors, 'user name must be lower');
                }
            // End User Name

            // Start Password
                if (strlen($data['password']) <= 3) {
                    array_push($errors, 'password must be grater than 3');
                }
            // End Password 

            // Start Comfirem passeord
                if($data['password'] !== $data['confirmPass']) {
                    array_push($errors, 'Password not identical');
                }
            // End Comfirem passeord

            // Start Email 
                if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) != true) {
                    array_push($errors, 'Email Not valid');
                }
            // End Email 
        }
        return $errors;
    }

    function validateErrors() {
        $errors = [];
        if(isset($_POST['login']) && $_POST['login'] === 'login') {
            $data = getInfoFromForm('login');
            $errors = validateLogin($data);

        } elseif(isset($_POST['signup']) && $_POST['signup'] == 'signup') {
            $data = getInfoFromForm('signup');
            $errors = validateSignup($data);
        }

        if (!empty($errors)) {
            printErrors($errors, '', 4);
        } else {
            return true;
        }
    }


    /**
     * @version 1.0
     * @todo prepare actions after login (set session, directen user controll panel or home page ext..)
     */
    function  prepareAfterLoginAndSignup($to) {
        $info = getInfoFromForm($to);
        if(validateErrors()) {
            if($to === 'login') {
                if(checkifPermeationUser($info['user'])) {
                    setSession($info['user']);
                    header('Location: admin/dashboard.php');
                } else {
                    setSession($info['user']);
                    header('Location: index.php');
                }
            } 
            elseif($to === 'signup') {
                signupUser($info);
                if(checkifPermeationUser($info['user'])) {
                    setSession($info['user']);
                    header('Location: admin/dashboard.php');
                } else {
                    setSession($info['user']);
                    header('Location: index.php');
                }
            }
        }
    }



// End Fork Function

// Start Main Structer
    function structerLoginSignupForm() { ?>
        <div class="body-login">
            <div class="container">
                <h1 class="login-h"><span class="active" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
                <!-- Start Login  -->
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
                        <input type="text"   title="user name" name="user" autocomplete="off" class="form-control" placeholder="User Name" required/>
                        <input type="password" name="password" minlength="4" autocomplete="new-password" class="form-control" placeholder="Password" required/>
                        <input type="submit" name="login" value="login" autocomplete="off" class="btn btn-primary"/>
                    </form>
                <!-- End Login -->

                <!-- Start signup -->
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="signup">
                        <input type="text" name="user" autocomplete="off" class="form-control" placeholder="User Name" required/>
                        <input type="password" name="password" autocomplete="new-password" class="form-control" placeholder="Password" required/>
                        <input type="password" name="password_confirm" minlength="4" autocomplete="new-password" class="form-control" placeholder="Confirm Password" required/>
                        <input type="email" title="Must be valid email we will send varivy maseg" name="email" autocomplete="off" class="form-control" placeholder="Your Eamil" required/>
                        <input type="submit" name="signup" value="signup" autocomplete="off" class="btn btn-primary"/>
                    </form>
                <!-- End signup -->
            </div>
        </div>
        <?php 
    }

// End Main Structer


    structerLoginSignupForm();


    if(ifTypeRequestPOST()) {
        if(isset($_POST['login']) && $_POST['login'] === 'login') {
            prepareAfterLoginAndSignup('login');
        }
        elseif(isset($_POST['signup']) && $_POST['signup'] === 'signup') {
            prepareAfterLoginAndSignup('signup');
        }
    }


    include($tpl . 'footer.php');
    ob_end_flush();




