    <?php
    /*
        |=====================================================|
        |== Manage Member Here
        |== You can Add | Edit | Delate Member
        |=====================================================|
    */


// Start Global Declaration
    ob_start();
    session_start(); // Resumption
    $TITLE = 'Mambers'; // This variable To set Title in page.
    include('init.php');
// End Global Declaration



// Start Global Function
    function getDataInRequest($addOrUpdat = 'update') {
        /**
         * @version  1.2
         * @param $addOrUpdate : for choose if we get reauest from add form or update form
         */

         // Get In file

        if($addOrUpdat === 'update') {
            $password   = empty($_POST['newpassword']) ? $_POST['oldpassword'] : encryptPassword($_POST['newpassword']);
            $userID     = $_POST['userID'];
        } elseif($addOrUpdat === 'add') {
            $password   =  $_POST['password'];
            $userID = NULL;
        }

        // Get Information for user
        $userName   = $_POST['userName'];
        $email      = $_POST['email'];
        $fullName   = $_POST['fullName'];
        $rank       = $_POST['rank'];

        return [
            'email'             => $email, 
            'userID'            => $userID,
            'fullName'          => $fullName,
            'password'          => $password,
            'userName'          => $userName, 
            'rank'           => $rank,
        ];
    }

    function validateForm($info) {
        // This function to check if data in form is valid.'

        $ERRORS = [];
        // Start Check user Name
            if (strlen((string)$info['userName']) == 0) {
                array_push($ERRORS, 'User Can\'t Be <strong>Empty</strong> ');
            }
            if (strlen((string) $info['userName']) <= 3) {
                array_push($ERRORS, 'User Name Must be ><strong>Greate Than 3</strong> ');
            }
            if (!ctype_lower((string) $info['userName'])) {
                array_push($ERRORS,  'The User Name Must <strong>Be lower case </strong> ');
            }
        // End Check user Name


        // Start Check Password
            if (strlen((string)$info['password']) == 0) {
                array_push($ERRORS, 'User Can\'t Be <strong>Empty</strong> ');
            }
        // End Check Password

        //Start Check Email
            if (strlen((string)$info['email']) == 0) {
                array_push($ERRORS, 'Email Can\'t Be <strong>Empty</strong>');
            }
        //End Check Email


        // Start Check Full Name
            if (empty((string)$info['fullName'])) {
                array_push($ERRORS, 'your Name cant Not <strong>Empty</strong>');
                // $ERRORS = '<div class="alert alert-danger"> your Name cant Not <strong>Empty</strong></div>';
            }
        // End Check Full Name

        // Start Check password 
            if (strlen((string)$info['password']) <= 3) {
                array_push($ERRORS, 'The Password Is week Mus Be <strong>Greate Than 3 Charahter</strong> ');
                // $ERRORS = '<div class="alert alert-danger" >The Password Is week Mus Be <strong>Greate Than 3 Charahter</strong> </div>';
            }
            
        // End Check password

        // Start Image
            validateImage('profilePicture', $ERRORS, 'edit');
        // End Image


        if(!empty($ERRORS)) {
            printErrors($ERRORS);
        } else {
            return true;
        }
    }




// End Global Function




// Start Fork Fucntions

    // Start Add fork function
        function addInDB() {
            // This function to update(Linked To Add) info in DB.
            global $db;
            $info = getDataInRequest('add');
            $infoImage = getImgFileInfo('profilePicture');

            $stmt = $db->prepare('INSERT INTO 
                                                users(userName, email, password, fullName, regStatus, preDate, profilePicture, groubID) 
                                        VALUES 
                                                (:userName, :email, :password, :fullName, 1, NOW(), :profilePicture, :rank)');

            if(validateForm($info)) { 
                moveImageInFolder($infoImage['tempNameFileAvatar'],'uploded\\ProfilePictuer\\', $infoImage['nameAvatar'], $info['userName']);
                if(!ItemExistOrRepeate('userName', 'users', $info['userName'])) {
                    $stmt->execute([
                        'userName'          => $info['userName'],
                        'email'             => $info['email'],
                        'password'          => encryptPassword($info['password']),
                        'fullName'          => $info['fullName'],
                        'profilePicture'    => prepareImageName($infoImage['nameAvatar'], $info['userName']),
                        'rank'              => $info['rank'],
                    ]);

                    redirect( "<div class='alert alert-success'>" . $stmt->rowCount() . " Added Member" . "</div>", 'back');

                } else {
                    redirect("<div class='alert alert-danger'>This Account Are Already Exist User Anather Name</div>", 'back');
                }

            } 
        }

        function addQueryActivatePage() {
            /**
             * @version 1.2
             * @todo add value status of user in query
             */
            $query = NULL;
            if(getID('activate', 'string') == 'pending') {
                $query = " AND regStatus = 0";
            }
            return $query;
        }
    // End Add fork function


    // Start Update Functions
        function updateInDB() {
            global $db;
            $info = getDataInRequest('update');
            $infoImage = getImgFileInfo('profilePicture');
            moveImageInFolder($infoImage['tempNameFileAvatar'],'uploded\\ProfilePictuer\\', $infoImage['nameAvatar'], $info['userName']);
            // Add new image in db;

            $stmt = $db->prepare('UPDATE users SET userName = ?, email = ?, password = ?, fullName = ?, profilePicture = ?, groubID = ? WHERE userID = ?');
            if(validateForm($info)) {
                $stmt->execute([
                    $info['userName'],
                    $info['email'],
                    $info['password'],
                    $info['fullName'],
                    prepareImageName($infoImage['nameAvatar'], $info['userName']),
                    $info['rank'],
                    $info['userID'],
                ]);
                redirect("<div class='alert alert-success'>" . $stmt->rowCount() . " Requerd Activated </div>", 'back', 3);
            }
        }
    // End Update Functions



    // Start Mange
        function setInfoInTable() {

            $requerds = getTable('*', 'users', 'WHERE groubID != 1' . addQueryActivatePage());
            foreach($requerds as $requerd) { ?>
                <tr>
                    <td><?php echo $requerd['userID'] ?></td>
                    <?php setPricterImage('admin', 'ProfilePictuer', $requerd['profilePicture']) ?>
                    <td><?php echo $requerd['userName'] ?></td>
                    <td><?php echo $requerd['password'] ?></td>
                    <td><?php echo $requerd['email'] ?></td>
                    <td><?php echo $requerd['fullName'] ?></td>
                    <td><?php echo $requerd['preDate'] ?></td>
                    <td>
                        <!-- becerful to capces after links -->
                        <a href="members.php?actionInMember=edit&userID=<?php echo $requerd['userID'] ?>" class    ="btn btn-success"> <i class="fas fa-edit"></i> Edit</a>
                        <a href="members.php?actionInMember=delate&userID= <?php echo $requerd['userID'] ?>" class  ="btn btn-danger confirm"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                        <?php setActivateBtn($requerd, 'members.php', 'actionInMember', 'activate', 'userID', 'regStatus') ?>
                    </td>
                </tr>
            <?php }
        }


    // End Mange

    // Start Delete
        function prepareBeforActioninDB($to) {
            /**
             * @version 1.0
             * @todo check if user exist in db before delete or activate
             * @param to : Determenate if want use this function to delete or Activate Member. 
             *              (if first character 'delete' this mean delete if 'activate' this mean activate)
             */

            // Select ID to user I want delete it.
                $userID  = getID('userID');
            // Select info to user that you want delete.
                $result = ItemExistOrRepeate('userID', 'users', $userID);

            if ($result > 0 ) {
                strtolower($to[0]) == 'delete' ? enquiryDelete('users', 'userID', getID('userID')) : enquiryActivate($userID);
            } else {
                redirect("<div class='alert alert-danger'> This User Not Exist </div>", 'back');
            }
        }
    // End Delete

    // Start Activate 
        function enquiryActivate($userID) {
            global $db;
            $stmt = $db->prepare('UPDATE users SET regStatus = 1 WHERE userID = ?');
            $stmt->execute([$userID]);
            redirect("<div class='alert alert-success'>" . $stmt->rowCount() . " Requerd Activated </div>", 'back', 2);
        }
    // End Activate 


// End Fork Fucntions



// Start Main Structers

    // Start Add Structer
        function addMemberForm() { ?>
            <h1 class="text-center h-edit">Add Memeber</h1>
            <div class="container container-edit-mem">
                <form action="?actionInMember=insert" method="POST" class="form-horizontal mem-edit-form" enctype="multipart/form-data">
                    <!-- Start Usernaem Feaild -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="text" name="userName" class="form-control" autocomplete="off" required="required" placeholder="User Name To Enter The shop">
                            </div>
                        </div>
                    <!-- End Usernaem Feaild -->

                    <!-- Start password  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="password" name="password" class="form-control" autocomplete="new-password" required="required" placeholder="password Must Be Complex" >
                            </div>
                        </div>
                    <!-- End Password -->

                    <!-- Start Email  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="email" name="email" class="form-control" autocomplete="off" required="required" placeholder="Email Must Be Valid">
                            </div>
                        </div>
                    <!-- End Email -->

                    <!-- Start Full name  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="text" name="fullName" class="form-control"  autocomplete="off" require='require' placeholder="Full Name">
                            </div>
                        </div>
                    <!-- End Full naem -->

                    <!-- Start profile picture  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Profile Picture</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="file" name="profilePicture" required='required' class="form-control" />
                            </div>
                        </div>
                    <!-- End profile pictuer-->

                    <!-- Start rank user picture  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Change Premation</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <select name="rank" id="Rang" class="form-control">
                                        <option value="0">Member</option>
                                        <option value="2">co leader</option>
                                        <option value="3">store owner</option>
                                    </select>
                                </div>
                        </div>
                    <!-- End user pictuer-->

                    <!-- Start submit  -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                <input type="submit" value="Add Member" class="btn btn-primary btn-edit-mem" />
                            </div>
                        </div>
                    <!-- End submit -->
                </form>
            </div>
        <?php }

    // End Add Structer

    
    // Start Edit Fucntion
            function editFormInfoUser() {
                $info = getTable('*', 'users', 'WHERE userID = '. getID('userID'), null, null, 'fetch');
                ?>
                <h1 class="text-center h-edit">Edit Information</h1>
                <div class="container container-edit-mem">
                    <form action="?actionInMember=update" method="POST" class="form-horizontal mem-edit-form" enctype="multipart/form-data">
                        <!-- Start user ID (hidden) -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label hidden class="label.col-sm-2 control-label"></label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="hidden" name="userID" value="<?php echo $info['userID'] ?>" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        <!-- End user ID(hidden) -->

                        <!-- Start Usernaem Feaild -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Username</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="text" name="userName" class="form-control" value="<?php echo $info['userName'] ?>" autocomplete="off" required="required">
                                </div>
                            </div>
                        <!-- End Usernaem Feaild -->

                        <!-- Start password  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="hidden" name="oldpassword" value="<?php echo$info['password'] ?>">
                                    <input type="password" name="newpassword" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                        <!-- End Password -->

                        <!-- Start Email  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <input type="email" name="email" class="form-control" value="<?php echo $info['email'] ?>" autocomplete="off" required="required">
                                </div>
                            </div>
                        <!-- End Email -->

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
                                    <input type="file" name="profilePicture" value="<?php echo $info['profilePicture'] ?>" class="form-control" />
                                </div>
                            </div>
                        <!-- End profile pictuer-->

                        <!-- Start rank user picture  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Change Premation</label>
                                <div class="col-sm-10 col-md-4 filed-mem">
                                    <select name="rank" id="Rang" class="form-control">
                                        <option value="0">Member</option>
                                        <option value="2">co leader</option>
                                        <option value="3">store owner</option>
                                    </select>
                                </div>
                            </div>
                        <!-- End user pictuer-->

                        <!-- Start submit  -->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                    <input type="submit" value="Save Edit" class="btn btn-primary btn-edit-mem" />
                                </div>
                            </div>
                        <!-- End submit -->
                    </form>
                </div>
                <?php
            }

    // End Edit Fucntion 




    // Start Manage
        function structerManage() {
            global $db; ?>
                <h1 class=" h-manage" >Manage  Member</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>ID</td>
                                <td>Picture</td>
                                <td>User Name</td>
                                <td>Password</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Regester Date</td>
                                <td>Control</td>
                            </tr>
                            <?php setInfoInTable($db) ?>
                        </table>
                    </div>
                    <a href="members.php?actionInMember=add" class="btn btn-primary"> <i class="fa fa-plus"></i> New Member</a>
                </div>
            <?php 
        }
    // End Manage


// End Main Structers




// Start Controllar Function
    function structureMemberPage() {
        switch (getAction('actionInMember')) {

            case 'add':
                addMemberForm();
                break;
            case 'insert':
                if(ifTypeRequestPOST()) {
                    echo '<h1 class="text-center h-edit">Insert  Member</h1>';
                    echo '<div class="container">';
                    addInDB(); 
                } 
                else {
                    redirect('<h1 class="container error"><span> Error:</span> You can\'t enter This Page Directrly <br>', 'back');
                }
                echo '</div>';
                break;

            case 'delate': 
                echo '<h1 class="text-center h-edit">Delete  Member</h1>';
                echo '<div class="container">';
                if (ifTypeRequestGET()){
                    prepareBeforActioninDB('delete');
                } 
                else {
                    redirect("<div class='alert alert-danger'>Can\'t Enter This Bage Directry </div>", 'back');
                }
                echo '</div>';
                break;

            case 'activate':
                echo '<h1 class="text-center h-edit">Actevate  Member</h1>';
                echo '<div class="container">';
                if (ifTypeRequestGET()){
                    prepareBeforActioninDB('activate');
                } 
                else {
                    redirect("<div class='alert alert-danger'>Can\'t Enter This Bage Directry </div>", 'back');
                }
                echo '</div>';
                break;

            case 'edit':
                if (ItemExistOrRepeate('userID', 'users', getID('userID'))) {
                    editFormInfoUser();
                } 
                else {
                    redirect("<h1 class='error'><span> Error: You Are Not Regester In ecommerce</span> <br>
                    <h3> If You Want use This Ecommerce Please Login Or Regester</h3></h1>", 'back');
                }
                break;

            case 'update':
                echo '<h1 class="text-center h-edit">Update Information</h1>';
                echo '<div class="container">';
                    if(ifTypeRequestPOST()) {
                        updateInDB();
                    } 
                    else {
                        redirect('<h1 class="error"><span> Error: You can\'t enter This Page Directrly</span> <br>', 'back');
                    }
                echo '</div>';
                break;

            default:
                // In manage page we can => Edit | Delete | update users information.
                structerManage();
                break;
        }
    }

// End Controllar Function

    // Root Code (Main)
    if (isset($_SESSION['username'])) {
        setNav();
        structureMemberPage();
        include($tpl . 'footer.php');
    } else {
        header('index.php');
        exit();
    }

    ob_end_flush();
