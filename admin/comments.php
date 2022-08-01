<?php
    /*
        |=====================================================|
        |=================== Comments Page ===================|
        |== You can Edit | Delate | Approve comments =========|
        |=====================================================|
    */

// Start Global Deffinetion
    session_start(); // Resumption
    $TITLE = 'Comments'; 
// End Global Deffinetion


// Start Fork Fucntions
    // Start Edit 
        function getDataInRequest() {
            /**
             * get Data In Request v.1 
             */
            // Get Information for user
            $commID     = $_POST['commID']; 
            $comment    = $_POST['comment'];
            $status     = $_POST['status'];
            return [$comment, $status, $commID]; // must be sorted as an Enquiry.
        }

        // function getInfoBeforEdit() {
        //     global $db;
        //     // Data user Befor Update (sent to form).
        //     $stmt = $db->prepare(' SELECT * FROM comments WHERE commID = ?');
        //     $stmt->execute([getID('commID')]);
        //     $requerd = $stmt->fetch();
        //     return $requerd;
        // }
    // End Edit 


    // Start Manage

        function getCommentInfoFromDB() {
            global $db;
            $stmt = $db->prepare("SELECT comments.*, items.nameItem, users.userName
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        items
                                    ON
                                        items.itemID = comments.commItemID
                                    INNER JOIN 
                                        users
                                    ON
                                        users.userID = comments.commUserID
                                ");
            $stmt->execute();
            $requerds = $stmt->fetchAll();
            return $requerds;
        }

        function setInfoInTable() {
            // $stmt = $db->prepare(' SELECT * FROM comments WHERE commID = ?');
            $requerds = getCommentInfoFromDB(); 
            foreach($requerds as $requerd) {?>
                <tr>
                    <td><?php echo $requerd['commID']   ?></td>
                    <td><?php echo $requerd['comment']  ?></td>
                    <td><?php echo $requerd['userName'] ?></td>
                    <td><?php echo $requerd['nameItem'] ?></td>
                    <td><?php echo $requerd['status']   ?></td>
                    <td><?php echo $requerd['commDate'] ?></td>
                    <td>
                        <!-- becerful to capces after links -->
                        <a href="comments.php?actionInComments=edit&commID=<?php echo $requerd['commID'] ?>"
                            class  ="btn btn-success"> <i class="fas fa-edit"></i> Edit</a>
                        <a href="comments.php?actionInComments=delate&commID=<?php echo $requerd['commID'] ?>" 
                            class  ="btn btn-danger confirm"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                        <?php setActivateBtn($requerd, 'comments.php', 'actionInComments', 'approve', 'commID', 'status') ?>
                    </td>
                </tr>
            <?php }
        }
    // End Manage

    // Start Approve
        function prepareBeforApproveDB($to) {
            /**
             *@version 1.0.0
            * @param to : Determenate if want use this function to delete or Approve Comment.  (if first character 'd' this mean delete if 'approve' this mean activate)
            */
            $commID  = getID('commID');
            $result = ItemExistOrRepeate('commID', 'comments', $commID);

            if ($result > 0 ) {
                strtolower($to) === 'delete' ?  enquiryDelete('comments',  'commID', $commID) : enquiryApprove($commID);
            } else {
                redirect("<div class='alert alert-danger'> This User Not Exist </div>", 'back');
            }
        }
    // End Approve


// End Fork Fucntions


// Start Main Functions

    // Start Edit Procegers
        function editFormInfoUser() {
            $info = getTable('*', 'comments', 'WHERE commID = ' . getID('commID'), NULL, NULL, 'fetch'); ?>
            <h1 class="text-center h-edit">Edit Comment</h1>
            <div class="container container-edit-mem">
                <form action="?actionInComments=update" method="POST" class="form-horizontal mem-edit-form">
                    <!-- Start Comment ID (hidden) -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label hidden class="label.col-sm-2 control-label"></label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="hidden" name="commID" value="<?php echo $info['commID'] ?>" class="form-control" />
                            </div>
                        </div>
                    <!-- End Comment ID -->

                    <!-- Start Comment Feaild -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Comment</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <textarea name="comment" id="comment" class="form-control" cols="30" rows="10" required="required"><?php echo $info['comment'] ?></textarea>
                            </div>
                        </div>
                    <!-- End Comment Feaild -->

                    <!-- Start Status  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input inputmode="numeric" pattern="[0-1]*" name="status" value="<?php echo $info['status']?>" class="form-control" />
                            </div>
                        </div>
                    <!-- End Status -->

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
    // End Edit Procegers

    // Start Manage 
        function structerManageDashboardComments() {
            global $db;
            ?>
                <h1 class=" h-manage" >Manage  Comments</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>ID</td>
                                <td>comment</td>
                                <td>Member</td>
                                <td>Item Name</td>
                                <td>status</td>
                                <td>Regester Date</td>
                                <td>Control</td>
                            </tr>
                            <?php setInfoInTable($db) ?>
                        </table>
                    </div>
                    <!-- <a href="comments.php?actionInComments=add" class="btn btn-primary"> <i class="fa fa-plus"></i> New comment</a> -->
                </div>
            <?php }
    // End Manage 


    // Start Update 
        function updateInDB() {
            global $db;
            // This function to update info in DB.
            $stmt = $db->prepare('UPDATE comments SET comment = ?, status = ? WHERE commID = ?');
            if(getDataInRequest()) { // If Has Not Error Update Data in DB.
                $stmt->execute(getDataInRequest());
                echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Update Comment</div>";
            }
        }
    // End Update

    // Start Approve 
        function enquiryApprove($commID) {
            global $db;
            $stmt = $db->prepare('UPDATE comments SET status = 1 WHERE commID = ?');
            
            $stmt->execute([$commID]);
            redirect("<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Approved </div>", 'back', 2);
        }
    // End Approve 


// End Main Functions


// Start Main Staructer
    function structureCommentPage() {
        switch (getAction('actionInComments')) {
            case 'edit':
                if (ItemExistOrRepeate("commID", 'comments', getID('commID'))) {
                    editFormInfoUser();
                } else {
                    redirect("<h1 class='error'><span> Error: You Are Not Regester In ecommerce</span> <br>
                    <h3> If You Want use This Ecommerce Please Login Or Regester</h3></h1>", 'back');
                }
                break;

            case 'update':
                echo '<h1 class="text-center h-edit">Update Information</h1>';
                echo '<div class="container">';
                    if(ifTypeRequestPOST()) {
                        updateInDB();
                    } else {
                        redirect('<h1 class="error"><span> Error: You can\'t enter This Page Directrly</span> <br>', 'back');
                    }
                echo '</div>';
                break;

            case 'delate':
                echo '<h1 class="text-center h-edit">Delete  Member</h1>';
                echo '<div class="container">';
                if (ifTypeRequestGET()) {
                    prepareBeforApproveDB('delete');
                } else {
                    redirect("<div class='alert alert-danger'>Can\'t Enter This Bage Directry </div>", 'back');
                }
                echo '</div>';
                break;

            case 'approve':
                echo '<h1 class="text-center h-edit">Approve Comment</h1>';
                echo '<div class="container">';
                if (ifTypeRequestGET()) {
                    prepareBeforApproveDB('approve');
                } else {
                    redirect("<div class='alert alert-danger'>Can\'t Enter This Bage Directry </div>", 'back');
                }
                echo '</div>';
                break;
            case 'specific_comment':
                structerManageDashboardSpecificComments();
                break;

            default:
            // In manage page we can => Edit | Delete | update users information.
                structerManageDashboardComments();
            break;
        }
    }
// End Main Staructer

    // Root Code (Main)
    if (isset($_SESSION['username'])) {
        include('init.php');
        setNav();
        structureCommentPage();
        include($tpl . 'footer.php');
    } else {
        header('index.php');
        exit();
    }
