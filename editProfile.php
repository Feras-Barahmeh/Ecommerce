<?php 

// Start Golbal Defination
    ob_start();
    session_start();
    $TITLE = 'Edit Profile';
    include('init.php');
// End Golbal Defination


// Start Forke functions

    function getInfoFromForm() {
        $userID         = filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
        $oldpassword    = filter_var($_POST['oldpassword'], FILTER_UNSAFE_RAW);
        $newpassword    = !empty($_POST['newPassword']) ? filter_var($_POST['newpassword'], FILTER_UNSAFE_RAW) : $oldpassword;
        $currentlyPassword = filter_var($_POST['confirm'], FILTER_UNSAFE_RAW);
        $fullName       = filter_var($_POST['fullName'], FILTER_UNSAFE_RAW);
        return ['userID' => $userID, 'oldpassword' => $oldpassword, 'newpassword' => $newpassword, 'fullName' => $fullName, 'currentlyPassword' => $currentlyPassword];
    }

    function updateInfoUser() {
        global $db; $info = getInfoFromForm(); $infoImage = getImgFileInfo('profilePicture');
        $userName = getTable('userName', 'users', 'WHERE userID = ' . $info['userID'], NULL, NULL, 'fetch');
        moveImageInFolder($infoImage['tempNameFileAvatar'], 'admin\\uploded\\ProfilePictuer\\', $infoImage['nameAvatar'],  $userName['userName']);

        $stmt = $db->prepare("UPDATE 
                                    users
                                SET 
                                    password = ?, fullName = ?, profilePicture = ?
                                WHERE 
                                    userID = ?");
        $stmt->execute([$info['newpassword'], $info['fullName'], prepareImageName($infoImage['nameAvatar'], $userName['userName']), $info['userID']]);

        if($stmt) {
            redirect('<div class="alert alert-success container text-center">Success Edit</div>', '', 4);
        } else {
            ?> <div class="alert alert-danger container text-center">Not success Edit</div> <?php
        }
    }

    function validateFormEdit() {
        $info = getInfoFromForm(); $ERRORES = [];

        if(encryptPassword($info['currentlyPassword']) !== $info['oldpassword']) {
            array_push($ERRORES, 'Faild <strong>Currently</strong> Password');
        } else  {
            // Start Full Name
                if(strlen($info['fullName']) >= 25) {
                    array_push($ERRORES, '<strong>Long</strong> Name Must be less than 15');
                }
            // End Full Name

            // Start Image
                validateImage('profilePicture', $ERRORES);
            // End Image 
        }

        if(!empty($ERRORES)) {
            if(count($ERRORES) == 1) {
                ?> <br> <div class="alert alert-danger container text-center"><?php echo $ERRORES[0] ?></div> <?php
            }else {
                printErrors($ERRORES, '', 10);
            }
        } else {
            return true;
        }
    }

// End Forke functions



// Start Main Function 
   // Start Edit Fucntion
        function editFormInfoUser() {
                $info = getTable('*', 'users', 'WHERE userID = '. $_SESSION['IDuser'], null, null, 'fetch');
                // echo $info['profilePicture'];s
                ?>
                <h1 class="text-center h-edit">Edit Information</h1>
                <div class="container container-edit-mem">
                    <form action="" method="POST" class="form-horizontal mem-edit-form" enctype="multipart/form-data">
                        <!-- Start user ID (hidden) -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label hidden class="label.col-sm-2 control-label"></label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="hidden" name="userID" value="<?php echo $info['userID'] ?>" class="form-control" autocomplete="off">
                                </div>
                            </div>

                        <!-- Start confirm password  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Currently Password</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="password" name="confirm" class="form-control" autocomplete="new-password" />
                                </div>
                            </div>
                        <!-- End confirm Password -->

                        <!-- Start password  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="hidden" name="oldpassword" value="<?php echo $info['password'] ?>">
                                    <input type="password" name="newpassword" class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                        <!-- End Password -->

                        <!-- Start Full name  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Full Name</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="text" name="fullName" class="form-control" value="<?php echo $info['fullName'] ?>" autocomplete="off">
                                </div>
                            </div>
                        <!-- End Full naem -->

                        <!-- Start profile picture  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Profile Picture</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="file" name="profilePicture"  class="form-control" />
                                </div>
                            </div>
                        <!-- End profile pictuer-->

                        <!-- Start submit  -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                <input type="submit" value="Save Edit" class="btn btn-primary btn-edit-mem" />
                            </div>
                        </div>
                        <!-- End submit -->
                    </form>
                    <div class="pictuer-user">
                        <?php setPricterImage('index', 'ProfilePictuer', $info['profilePicture'], 'edit-profile-pictuer') ?>
                    </div>
                </div>
                <?php
            }
    // End Edit Fucntion
// End Main Functions


// Controllar
    editFormInfoUser();
    if(ifTypeRequestPOST()) {
        if(validateFormEdit()) {
            updateInfoUser();
        }
    }
    include($tpl . 'footer.php');
    ob_end_flush();