<?php

// Start Global Deffination
    ob_start();
    session_start();
    $TITLE = 'item';
    include('init.php');
// End Global Deffination

// Start Fork Function

    function getSesionID() {
        $idCurentUser = $_SESSION['IDuser'];
        return [
            'idCurentUser' => $idCurentUser,
        ];
    }

    function getItemFromDB() {
        global $db;
        $id = getID('itemid');
        if($id != 0) {
                $stmt = $db->prepare("SELECT 
                                            items.*,
                                            categories.name AS catName,
                                            users.userName  As memberName
                                        FROM 
                                            items
                                        INNER JOIN
                                            categories
                                        ON
                                            categories.ID = items.catID
                                        INNER JOIN
                                            users
                                        ON
                                            users.userID = items.memberID
                                        WHERE 
                                            approve = 1
                                        AND
                                            itemID=$id");
                    $stmt->execute();

                if($stmt->rowCount() > 0) {
                    // If fine this category retrun information for this category
                        return $stmt->fetch();

                } else { ?>
                    <div class="alert alert-danger  container" >This Category Not Exist</div>
                    <?php
                }
        } else {?>
            <div class="alert alert-danger  container" >This Category Not Exist</div>
            <?php
        }
    }

    function getInfoFromPOST() {
        if(ifTypeRequestPOST()) {
            $info = getItemFromDB();
            $idForCurentUser = getSesionID();

            $comment = filter_var($_POST['comment'], FILTER_UNSAFE_RAW);
            $userID =  $idForCurentUser['idCurentUser'];
            $itemID =$info['itemID'];

            return [
                'comment'       => $comment, 
                'userID'        => $userID, 
                'itemID'        => $itemID, 
            ];}
        
    }

    function StructReadComment() { 
            $comments  = getSpecificCommentInfoFromDB('itemid');
        ?>
        <div class="row comment-row container">
            <div class="col-md-9">
                <?php
                    foreach($comments as $comment) { ?>
                        <div class="comment-box">
                            <div class="row">
                                <div class="col-sm-2 read-comment text-center">
                                    <!-- Sit user Pictuer -->
                                    <?PHP setPricterImage('index', 'ProfilePictuer', getTable('profilePicture', 'users', 'WHERE userID = '. $comment['commUserID'], NULL, NULL, 'fetch')['profilePicture']) ?>
                                    <?php echo $comment['userName'] ?>
                                </div>
                                <div class="col-sm-10">
                                    <p class="p-comment lead"><?php echo $comment['comment']?></p>
                                    <span class="date"><?php echo $comment['commDate']?></span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <?php
                    }
                ?>
            </div>
        </div>
    <?php
    }

    function setMaseages($donOrNot) {
        if($donOrNot > 0 ){ header("refresh:0;");?>
                <div class="alert alert-success container">success Add Comment</div>
            <?php
            } else { ?>
                <div class="alert alert-danger container">Loded To Server try Later Please</div>
            <?php }


    }

    function insertComment() {
        global $db;
        $infoComment = getInfoFromPOST();
        if(empty($infoComment['comment']) && ifTypeRequestPOST()) { ?>
            <div class="alert alert-danger container">Can't Add Empty Commment</div>
            <?php 
        }

        if( ifTypeRequestPOST() && !empty($infoComment) && !empty($infoComment['comment'])) {
            

            $stmt = $db->prepare("INSERT INTO 
                                    comments(comment, status, commDate, commItemID, commUserID)
                            VALUES
                                    (:xcomment, 0, NOW(), :xitem, :xmember)");

            $stmt->execute([
            "xcomment"  => $infoComment['comment'],
            "xmember"   => $infoComment['userID'],
            "xitem"     => $infoComment['itemID'],
            ]);

            // Show masages
                setMaseages($stmt->rowCount());
        }

    }

    function structAddComment($approveValue) { 
        $id = getID('itemid');
        if($approveValue == 1) {
        ?>
            <h3 class="text-center h-edit">Add Your Comment</h3>
            <div class=" write-comm">
                <div class="row">
                    <div class="col-md-offset-3">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>?itemid=<?php echo $id?>" method="POST">
                            <textarea name="comment" id="" cols="50" rows="7" required></textarea>
                            <input type="submit" value="Share Comment" class="btn btn-primary add-comm-btn">
                        </form>
                        <!-- Show Comment -->
                            <?php insertComment() ?>
                    </div>
                </div>
            </div>

        <!-- Start Read Comment -->
            <?php } else { ?>
                <div class="container alert alert-danger">This Item Not Approve</a></div>
            <?php
            }
            
            StructReadComment() ?>
        <!-- End Read Comment -->
    <?php
    }

    function grtAllTages($tage) {
        ?>  <?php
        $tages = explode(',', $tage);
        
        foreach($tages as $tage) { 
            $tage = strtolower($tage);
            if(!empty($tage)) {
            ?> <a href="tages.php?nameTage=<?php echo strtolower($tage) ?>" class="tages"><?php echo $tage ?></a> 
            <?php } else {
                ?> <span class="">No Tages</span> <?php
            }
        }

    }


// End Fork Function

// Start Main Fucntoin
    function mainStructer($infoItem) { 
        ?>
                <h1 class="text-center h-edit"><?php if(isset($infoItem['nameItem'])) echo $infoItem['nameItem']?></h1>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- <img src="layout/images/download.png" alt="img Category" class="img-responsive    img-thumbnail center-block"> -->
                            <?php setPricterImage('index', 'ItemsPictuer', $infoItem['pictureItem'], 'img-item center-block img-thumbnail' )   ?> <!-- Sit Image -->

                        </div>
                        <div class="col-md-9 info-item">
                            <h2 class=""><?php if(isset($infoItem['nameItem'])) echo $infoItem['nameItem']?></h2>
                            <p class="desc"><?php if(isset($infoItem['description'])) echo $infoItem['description']?></p>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                    <span>Price</span>:<p>$<?php if(isset($infoItem['price'])) echo $infoItem['price']?></p>
                                </li>
                                <li><i class="fa fa-user" aria-hidden="true"></i> <span>Add By </span>:<a href="#"><p class="name-user"><?php if(isset($infoItem['memberName'])) echo $infoItem['memberName']?></p></a> </li>
                                <li><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Category</span>:<a href="categories.php?catID=<?php echo $infoItem['catID'] ?>&nameCategorie=<?php echo $infoItem['catName'] ?>"><p class="name-cat"><?php if(isset($infoItem['catName']))    echo $infoItem['catName']?></p></a></li>
                                <li><i class="fa-solid fa-globe"></i> <span>Made In</span>:<p><?php if(isset($infoItem['madeIn']))     echo $infoItem['madeIn']?></p></li>
                                <li><i class="fa fa-calendar" aria-hidden="true"></i> <span>Add Date</span>:<p><?php if(isset($infoItem['dateAdd']))    echo $infoItem['dateAdd']?></p></li>
                                <li>
                                    <i class="fa-solid fa-tags" aria-hidden="true"></i>
                                    <span>Tages    : </span>
                                    <p><?php grtAllTages($infoItem['tage']) ?></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php }
// End Main Fucntoin


    // Main Code
        $infoItem = getItemFromDB();
        mainStructer($infoItem);
        if(isset($_SESSION['user'])) {
            structAddComment($infoItem['approve']);
        } else {
            header("Location: login.php");
            ?>
                <div class="container alert alert-danger">login Or Regester To Add And Read Comment <a href="login.php " class="container">Login | Signup</a></div>
            <?php
        }
        include($tpl . 'footer.php');
        ob_end_flush();