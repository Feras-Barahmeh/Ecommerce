<?php

// Start Global Deffination
    session_start();
    ob_start();
    $TITLE = 'Profile';
    include('init.php');
// End Global Deffination

// Start Fork Functions
    // function getInfoUserFromDB() {
    //     global $db; global $sessionUser;
    //     $stmt = $db->prepare("SELECT * FROM users WHERE userName = '$sessionUser'");
    //     $stmt->execute();
    //     return $stmt->fetch();
    // }

    function setNotApproveIcone() { ?>
            <div class="not-approve">Awaiting approval</div>
        <?php
    }

    function structerItemBox($item) { ?>
        <div class="col-sm-6 col-md-2 profile-col">
            <div class="thumbnail-profile">
                <span class="price price-profile"><?php echo '$'.$item['price'] ?></span>
                    <!-- Sit picter -->
                    <!-- <img src="layout/images/download.png" alt=""/> -->
                    <?php setPricterImage('index', 'ItemsPictuer', getTable('pictureItem', 'items', 'WHERE nameItem = \'' . $item['nameItem'] . '\'', null, null,'fetch')['pictureItem'], ) ?>
                    <?php if(!checkIfApproved($item['approve'])) setNotApproveIcone()?> <!-- auxiliary -->
                <div class="caption">
                    <a href="items.php?itemid=<?php echo $item['itemID'] ?>"><h3><?php echo $item['nameItem']?></h3></a>
                    <!-- <p ><?php echo $item['description'] ?></p> -->
                    <p class="date"><?php echo $item['dateAdd'] ?></p>
                </div>
            </div>
        </div>

    <?php
    }


    function structerItemsInCategories($idUSer) {
        /**
         * @version 1.0.0
         * @todo to set advertisement in profile page 
         */
        ?>
            <div class="container">
                <div class="row container-item">
                    <?php
                        $items = getSpecificItems('memberID', $idUSer);
                        if (!empty($items)) {
                            foreach($items as $item) {
                                    structerItemBox($item);
                            } 
                        }
                        else {?>
                            <img src="layout/images/Empty_set.svg.png" alt="No found categorie" class="empty-img">
                            <p class="p-error">You Not Ensert Categories Yet</p>
                        <?php }
                        
                    ?>
                </div>
            </div>
        <?php
    }


    // function getCommentsFromDb($commUser) {
    //     global $db;
    //     $stmt = $db->prepare("SELECT comment FROM comments WHERE commUserID  = $commUser LIMIT 5");
    //     $stmt->execute();
    //     return $stmt->fetchAll();
    // }


    function PrintCommentsInProfile($userID) {
        // $comments = getCommentsFromDb($userID);
        $comments = getTable('comment', 'comments', 'WHERE commUserID = \'' . $userID . '\' LIMIT 5');

        if (!empty($comments)) {
            foreach($comments as $comment) { ?>
                <p class="comment-p"><?php if(!empty($comment)) echo $comment['comment']; else echo "No Comments Yet"; ?></p>
            <?php
            }
        } else { ?>
            <div class="no-comment">
                <img src="layout/images/Empty_set.svg.png" alt="No found comments" class="empty-img">
                <p class="p-error">You Not Ensert Comments Yet</p>
            </div>
            <?php
        }
    }
    
// End Fork Functions

// Start Main Structer
    function mainStruct() { 
        global $sessionUser;
        //         $stmt = $db->prepare("SELECT * FROM users WHERE userName = '$sessionUser'");
        // $info = getInfoUserFromDB();
        $info = getTable('*', 'users', 'WHERE userName = \'' . $sessionUser . '\'' , NULL, NULL, 'fetch');
        ?>
        <h1 class="text-center container"><?php echo $_SESSION['user'] ?> Profile</h1>
        <div class="information">
            <div class="container">
                <!-- Start Information -->
                    <div class = "panel panel-primary">
                        <div class = "panel-heading"><h3 class = "panel-title">My Information</h3></div>
                            <div class = "panel-body panel-prfile">
                                <!-- Start user Name -->
                                    <div class="block-info">
                                        <span class="name-title"><i class="fa fa-user" aria-hidden="true"></i>  user Name</span>
                                        <span class="inf"><?php echo $info['userName']?> <a href="editProfile.php" class="edit-info-user-btn">Edit Profile</a></span>
                                        
                                    </div>
                                <!-- End User Name -->

                                <!-- Start Email -->
                                    <div class="block-info">
                                        <span class="name-title"> <i class="fa fa-envelope" aria-hidden="true"></i> Email</span>
                                        <span class="inf"><?php echo $info['email'] ?></span>
                                    </div>
                                <!-- End Email -->

                                <!-- Start Full Name -->
                                    <div class="block-info">
                                        <span class="name-title"><i class="fa fa-address-card" aria-hidden="true"></i> Full Name </span>
                                        <span class="inf"><?php if(!empty($info['fullName'])) echo $info['fullName']; else echo "you don't tell us the name you want us to call you by"; ?></span>
                                    </div>
                                <!-- End Full Name -->

                                <!-- Start regester Date -->
                                    <div class="block-info">
                                        <span class="name-title"><i class="fa fa-calendar" aria-hidden="true"></i> Register Date</span>
                                        <span class="inf date-from-info"><?php echo $info['preDate'] ?></span>
                                    </div>
                                <!-- End Regestier Date -->

                                <!-- Start Faverite Categorie -->
                                    <div class="block-info">
                                        <span class="name-title"><i class="fa fa-list-alt" aria-hidden="true"></i> Faverite Categorie</span>
                                        <span class="inf"><?php echo "not prepare yet" ?></span>
                                    </div>
                                <!-- End Faverite Categorie -->

                                <!-- Start number Not Approve item -->
                                    <div class="block-info">
                                        <span class="name-title"><i class="fa fa-check" aria-hidden="true"></i> Awaiting approval</span>
                                        <span class="inf"><?php echo "not prepare yet" ?></span>
                                    </div>
                                <!-- End number Not Approve item -->
                            </div>
                        </div>
                <!-- End Information -->

                <!-- Start advertisement -->
                    <div class = "panel panel-primary">
                        <div class = "panel-heading"><h3 class = "panel-title">My Advertisement</h3> </div>
                            <div class = "panel-body body-advertisement">
                                    <div class="block-info-advertisement">
                                        <?php structerItemsInCategories($info['userID']) ?>
                                    </div>
                            </div>
                    </div>
                <!-- End advertisement -->

                <!-- Start comments -->
                    <div class = "panel panel-primary">
                        <div class = "panel-heading"><h3 class = "panel-title">My Comments</h3> </div>
                            <div class = "panel-body body-advertisement">
                                    <div class="block-info-advertisement">
                                        <?php  PrintCommentsInProfile($info['userID']) ?>
                                    </div>
                            </div>
                    </div>
                <!-- End comments -->
            </div>
        </div>
    <?php
    }
// ُEnd Main Structer

    // Main Code
        if(isset($_SESSION['user'])) {
            mainStruct();
        } else {
            header("Location: login.php");
        }
        include($tpl . 'footer.php');
        ob_end_flush();