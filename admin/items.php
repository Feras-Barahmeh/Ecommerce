<?php 
    /*
        |=====================================================|
        |=================== Items Page ======================|
        |== You can Add | Edit | Delate Items
        |=====================================================|
    */

// Start Global deffination
    ob_start();
    session_start();
    $TITLE = 'Items';
// End Global deffination


// Start Global Functions

    function validationFormData($req, $to='a') {
        /**
         * @version 1.0.0
         * @todo Check if input request valid or not.
         * @param req The data from request
         * @param to : To Check if you eant use this fucntion to add or update if to update you dosn't use ItemExistOrRepeate()
         */
        $ERRORES = [];
         // Start Check the name Item
            if(strlen($req['nameItem']) <= 1) {
                array_push($ERRORES, "The name Cant't be empty");
            }
            if($to != 'u')
                if (ItemExistOrRepeate('nameItem', 'items', $req['nameItem']) != 0) {
                    array_push($ERRORES, "This Item is Exist");
                }
        // End Check the name Item

        // Start Check the Description item
            if(strlen($req['description']) <= 1) {
                array_push($ERRORES, "The Description Cant't be empty");
            }
            if($to != 'u')
                if (ItemExistOrRepeate('nameItem', 'items', $req['nameItem']) != 0) {
                    array_push($ERRORES, "This Description is Exist");
                }
        // End Check the Description item

        // Start Check the price item
            if(strlen($req['price']) <= 1) {
                array_push($ERRORES, "The price Cant't be empty");
            }
        // End Check the price item

        // Start Check the Made In item
            if(strlen($req['madeIn']) <= 1) {
                array_push($ERRORES, "The country Cant't be empty");
            }
        // End Check the price item

        // Start Image
            validateImage('pictureItem', $ERRORES);
        // End Image

        return $ERRORES;
    }

    function enquiryGetValuesItems($to='dashbord', $order='', $typeorder='') {
        global $db;

        if($to == 'dashbord') {
                $stmt = $db->prepare("SELECT 
                            items.*,
                            categories.name AS name_cat,
                            users.userName
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
                    ORDER BY
                        $order $typeorder
                    ");
                $stmt->execute();
                $requerd = $stmt->fetchAll();
                return $requerd;
        } else {
            return getTable('*', 'items', 'WHERE itemID = '. getID('itemID'), null, 'DESC', 'fetch');
        }
        }


    function getInfoFromPOST() {
        /**
         * @version 1.0
         * @todo Get Value from form
         * @param No parametrers
         * @return Value of array include all of the values to this item
         * 
         * @version 1.2 
         * return array index by name
         */
        $itemID       = $_POST['itemID'];
        $nameItem     = $_POST['nameItem'];
        $description  = $_POST['description'];
        $price        = $_POST['price'];
        $madeIn       = $_POST['madeIn'];
        $status       = $_POST['status'];
        $catID        = $_POST['catID'];
        $memberID     = $_POST['memberID'];
        $tage         = $_POST['tage'];
        // return [$nameItem, $description, $price, $madeIn, $status, $catID, $memberID,  $itemID, $tage];
        return [
            'nameItem' => $nameItem, 
            'description' => $description, 
            'price' => $price, 
            'madeIn' => $madeIn, 
            'status' => $status, 
            'catID'=>$catID, 
            'memberID' => $memberID, 
            'itemID' => $itemID, 
            'tage' => $tage
        ];
    }

// End Global Functions


// Start Fork Functions

    // Start Add Item
        function setValueOptionsFromEditForm($info, $value) {
            if($info == $value) {
                return 'selected';
            }
        }



        function insertInDB() {
                /**
                 * @version 1.0
                 * @todo quiry to set data in db
                 * @param No parametrers
                 * @return No return 
                 */
                global $db;
                $info = GetInfoFromPOST();
                $infoImage = getImgFileInfo('pictureItem');
                $errors = validationFormData($info);
                
                
                if(empty($errors)) {
                    moveImageInFolder($infoImage['tempNameFileAvatar'],'uploded\\ItemsPictuer\\', $infoImage['nameAvatar'], $info['nameItem']);
                    $stmt = $db->prepare("INSERT INTO 
                                                    items(nameItem, description, price, madeIn, status, dateAdd, catID, memberID, rating, tage, pictureItem) 
                                            VALUES 
                                                    (?,?,?,?,?, NOW(), ?,?, 0, ?, ?)");
                    $stmt->execute([
                        $info['nameItem'],
                        $info['description'],
                        $info['price'],
                        $info['madeIn'],
                        $info['status'], 
                        $info['catID'], 
                        $info['memberID'],
                        $info['tage'],
                        prepareImageName($infoImage['nameAvatar'], $info['nameItem']),
                    ]);
                    redirect( "<div class='alert alert-success container'>" . $stmt->rowCount() . " Added Item" . "</div>", 'back');
                } else {
                    printErrors($errors);
                }
        }


        function printFields($to='member', $info='') {
            if ($to === 'member') {
                $count = 0;
                $members = getTable('users.userID, users.userName', 'users');
                foreach ($members as $member) {
                    $count++;
                    $select = setValueOptionsFromEditForm($info, $count);
                    echo "<option value = '$member[userID]' $select>$member[userName]</option>";
                }
            } 
            elseif ($to ==='category') {
                $categories = getTable('categories.ID, categories.name', 'categories', 'WHERE parent = 0');
                $count = 0;
                foreach ($categories as $categorie) {
                    $count++;
                    $select = setValueOptionsFromEditForm($info, $count);
                    echo "<option value = '$categorie[ID]' $select>$categorie[name]</option>";
                }
            }
        }

    // End Add Item

    // Start Manage Dashboard item
        function ifItemHasApprove($approve, $id) {
            if($approve == 0) { ?>
                <a href="items.php?actionInItems=approve&itemID=<?php echo $id?>"  class ="confirm btn btn-info activ edit"> <i class="fas fa-toggle-on"></i> Approve</a>
                <?php  approveItem();
            }
        }


        function approveItem() {
            global $db;
            $stmt = $db->prepare("UPDATE items SET approve = 1 WHERE itemID = ?");
            $stmt->execute([getID('itemID')]);
            
        }


    // End Manage Dashboard item

    // Start Edit Fucntion

        function checkIfIDExist($itemID) {

            
            if(ItemExistOrRepeate('itemID', 'items', $itemID)) {
                echo $itemID;
            } else {
                redirect('<div class="alert alert-danger">'."This categorie no exsit".'</div>', 'back', 500);
            }
        }

        function checkIfNameITemExist($name) {
            if(ItemExistOrRepeate('nameItem', 'items', $name)) {
                echo $name;
            } else {
                redirect('<div class="alert alert-danger">'."This categorie no exsit".'</div>');
            }
        }

        function checkIfDescExist($desc) {
            if(ItemExistOrRepeate('description', 'items', $desc)) {
                echo $desc;
            } else {
                redirect('<div class="alert alert-danger">'."This categorie no exsit".'</div>', 'back');
            }
        }

        function checkIfPriceExist($price) {
            if(ItemExistOrRepeate('price', 'items', $price)) {
                echo $price;
            } else {
                redirect('<div class="alert alert-danger">'."This categorie no exsit".'</div>');
            }
        }

        function checkIfMadeInExist($madeIn) {
            if(ItemExistOrRepeate('madeIn', 'items', $madeIn)) {
                echo $madeIn;
            } else {
                redirect('<div class="alert alert-danger">'."This categorie no exsit".'</div>');
            }
        }
    // End Edit Fucntion

    //Start Update Functions
        function enquiryUpdate() {
            global $db;
            $info = getInfoFromPOST();
            $check = validationFormData($info, 'u');
            $infoImage = getImgFileInfo('pictureItem');
            $stmt = $db->prepare("UPDATE items SET nameItem = ?, description = ?, price = ?, 
                                madeIn = ?, status = ?, catID  = ?, memberID = ?,pictureItem = ?, tage = ? WHERE itemID = ?");

            if (empty($check)) {
                $stmt->execute([
                    $info['nameItem'],
                    $info['description'],
                    $info['price'],
                    $info['madeIn'],
                    $info['status'],
                    $info['catID'],
                    $info['memberID'], 
                    prepareImageName($infoImage['nameAvatar'], $info['nameItem']),
                    $info['tage'],
                    $info['itemID'],
                ]);

                moveImageInFolder($infoImage['tempNameFileAvatar'], 'uploded\\ItemsPictuer\\', $infoImage['nameAvatar'], $info['nameItem']);

                redirect("<div class='alert alert-success'>" . $stmt->rowCount() . " Requerd Update</div>", 'back');

            } else {
                printErrors($check);
            }
        }
    //End Update Functions

// End Fork Functions

// Start Main Function in Items
        // Start Add Item
            function structureAddForm() { 

                ?>
                    <h1 class="text-center h-edit">Add Items</h1>
                    <div class="container container-edit-mem">
                        <form action="?actionInItems=insert" method="POST" class="form-horizontal mem-edit-form" enctype="multipart/form-data">
                            <!-- Start ID Feaild -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="hidden" name="itemID" class="form-control"/>
                                    </div>
                                </div>
                            <!-- End ID Feaild -->

                            <!-- Start Name Feaild -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">name</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="nameItem" class="form-control" required="required" placeholder="Name of the Item">
                                    </div>
                                </div>
                            <!-- End name Feaild -->

                            <!-- Start Description  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="description" class="form-control"  placeholder="Descript this product" >
                                    </div>
                                </div>
                            <!-- End Description -->

                            <!-- Start Price  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">price</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="price" class="form-control"  placeholder="Order OF This product">
                                    </div>
                                </div>
                            <!-- End Price -->

                            <!-- Start Made in  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Made In</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="madeIn" class="form-control"  placeholder="Made in">
                                    </div>
                                </div>
                            <!-- End Made in -->

                            <!-- Start Member  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Member</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <select name="memberID" id="memberID" class="form-control">
                                            <option value="0">...</option>
                                            <?php printFields('member') ?>
                                        </select>
                                    </div>
                                </div>
                            <!-- End Member -->

                            <!-- Start categories  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">categorie</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <select name="catID" id="catID" class="form-control">
                                            <option value="0">...</option>
                                            <?php printFields('category') ?>
                                        </select>
                                    </div>
                                </div>
                            <!-- End categories -->

                            <!-- Start status  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <select name="status" id="status" class="form-control">
                                            <option value="0">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">used</option>
                                            <option value="4">Very old</option>
                                        </select>
                                    </div>
                                </div>
                            <!-- End status -->
                            

                            <!-- Start image in item  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Add Image Item</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="file" name="pictureItem" class="form-control" required>
                                    </div>
                                </div>
                            <!-- End image in item -->

                            <!-- Start tags in item  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="tage" class="form-control"  placeholder="Add Tages separeted by comma ',' ">
                                    </div>
                                </div>
                            <!-- End tags in item -->

                            <!-- Start submit  -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                        <input type="submit" value="Add Categories" class="btn btn-primary btn-edit-mem" />
                                    </div>
                                </div>
                            <!-- End submit -->
                        </form>
                    </div>
                <?php }
        // End Add Item

        // Start Manig Dashboard Items
            function printPanelBody( $order, $typeorder, $numItem = 5) {
                $results = enquiryGetValuesItems('dashbord', $order, $typeorder);
                foreach ($results as $result) {?>
                    <ul class="list-unstyled latest-users latest-users-cat">
                        <li>
                            <span class='user cat'>
                                <h3 class="name-cat"><?php echo $result['nameItem'] ?></h3>
                                <p class="p-cat"><?php echo $result['description'] ?></p>
                                <div class="options">
                                    <span class="options vis"><?php echo  $result['price'] ?></span>
                                    <span class="options allow-com"><?php echo  $result['madeIn'] ?></span>
                                    <span class="options allow-com"><?php echo  $result['userName'] ?></span>
                                    <span class="options allow-com"><?php echo  $result['name_cat'] ?></span>

                                </div>
                            </span>
                            <div class="btn-ed-de-ac">
                                <a href="items.php?actionInItems=edit&itemID=<?php echo $result['itemID'] ?>"  class ='btn btn-success edit-all'><i class="fa fa-edit"></i>Edit</a>
                                <a href="items.php?actionInItems=delate&itemID=<?php echo $result['itemID']?>"  class ='confirm btn btn-danger edit'><i class="fa fa-trash"></i>Delete</a>
                                <?php ifItemHasApprove($result['approve'], $result['itemID']) ?>
                            </div>
                        </li>
                    </ul>
                <?php
                }
            }

            function structureDashboardItems() { ?>
                <h1 class="text-center">Manage Items</h1>
                <div class="container">
                    <div class="panel panel-default">
                        <div class="panel-heading to-add-btn">
                            <p> Manage Items</p>
                            <div class="">
                                <?php sortForm() ?>
                            </div>
                            <button><a href="items.php?actionInItems=add" class="btn btn-primary"><i class="fa fa-plus"></i>Add Item</a></button>
                        </div>
                        <div class="panel-body panel-body-cat">
                            <?php printPanelBody('itemID', valueIsSetGET()) ?>
                        </div>
                    </div>
                </div>
            <?php
            }
        // End Manig Dashboard Items

        // Start Edit Function
                function structEditItems() {
                    $info = enquiryGetValuesItems('edit');
                        ?>
                    <h1 class="text-center h-edit">Edit Items</h1>
                    <div class="container container-edit-mem">
                        <form action="?actionInItems=update" method="POST" class="form-horizontal mem-edit-form" enctype="multipart/form-data">
                            <!-- Start ID Feaild -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="hidden" name="itemID" value="<?php checkIfIDExist($info['itemID'])?>" class="form-control"/>
                                    </div>
                                </div>
                            <!-- End ID Feaild -->

                            <!-- Start Name Feaild -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">name</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="nameItem" value="<?php checkIfNameITemExist($info['nameItem'])?>" class="form-control" required="required" placeholder="Name of the Item">
                                    </div>
                                </div>
                            <!-- End name Feaild -->

                            <!-- Start Description  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="description" value="<?php checkIfDescExist($info['description'])?>" class="form-control"  placeholder="Descript this item" >
                                    </div>
                                </div>
                            <!-- End Description -->

                            <!-- Start Price  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">price</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="price" value="<?php checkIfPriceExist($info['price']) ?>" class="form-control"  placeholder="Order OF This product">
                                    </div>
                                </div>
                            <!-- End Price -->

                            <!-- Start Made in  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Made In</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="text" name="madeIn" value="<?php checkIfMadeInExist($info['madeIn'])?>" class="form-control"  placeholder="Made in">
                                    </div>
                                </div>
                            <!-- End Made in -->

                            <!-- Start Member  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Member</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <select name="memberID" id="memberID" class="form-control">
                                            <option value="0">...</option>
                                            <?php printFields('member', $info['memberID']) ?>
                                        </select>
                                    </div>
                                </div>
                            <!-- End Member -->

                            <!-- Start categories  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">categorie</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <select name="catID" v id="catID" class="form-control">
                                            <option value="0">...</option>
                                            <?php printFields('category', $info['catID']) ?>
                                        </select>
                                    </div>
                                </div>
                            <!-- End categories -->

                            <!-- Start status  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <select name="status" id="status" class="form-control">
                                            <option value="0">...</option>
                                            <option value="1" <?php echo setValueOptionsFromEditForm($info['status'], 1)?>>New</option>
                                            <option value="2" <?php echo setValueOptionsFromEditForm($info['status'], 2)?>>Like New</option>
                                            <option value="3" <?php echo setValueOptionsFromEditForm($info['status'], 3)?>>used</option>
                                            <option value="4" <?php echo setValueOptionsFromEditForm($info['status'], 4)?>>Very old</option>
                                        </select>
                                    </div>
                                </div>
                            <!-- End status -->

                            <!-- Start Image in item  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <input type="file" name="pictureItem"  class="form-control"  />
                                </div>
                            <!-- End Image in item -->

                            <!-- Start tags in item  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-4 filed-mem">
                                        <textarea name="tage"  class="form-control"  placeholder="Add Tages separeted by comma ',' " cols="30" rows="5"><?php echo $info['tage'] ?></textarea>
                                    </div>
                                </div>
                            <!-- End tags in item -->


                            <!-- Start submit  -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                        <input type="submit" value="Update Categories" class="btn btn-primary btn-edit-mem" />
                                    </div>
                                </div>
                            <!-- End submit -->
                        </form>

                        <!-- Start Call Comment in edit page -->
                            <?php structerManageDashboardSpecificComments(); ?>
                        <!-- End Call Comment in edit page -->
                    </div>
            <?php
            } 
        // End Edit Function 

// End Main Function in Items


// Start Controllar Fucntion
    function mainStructureItemsPage() {

        switch (getAction("actionInItems")) {
            case 'add':
                structureAddForm();
                break;

            case 'insert':
                echo '<h1 class="text-center h-edit">Insert  Member</h1>';
                echo '<div class="container">';
                $info = getInfoFromPOST();
                if ( ifTypeRequestPOST()) {
                    if (ItemExistOrRepeate('nameItem', 'items', $info['nameItem']) == 0)
                        insertInDB();
                    else 
                        redirect( "<div class='alert alert-danger container'>" . "Can't Repate The Items" . "</div>", 'back');
                } else {
                        redirect("<div class='alert alert-danger container9'> Can\'t enter here directry </div>", 'back');
                }
                break;

            case 'delate':
                enquiryDelete('items', 'itemID', getID('itemID'));
                break;

            case 'edit':
                structEditItems();
                break;

            case "update";
                echo '<h1 class="text-center h-edit">Update Information</h1>';
                echo '<div class="container">';
                if(ifTypeRequestPOST()) {
                    enquiryUpdate();
                } else {
                    redirect("<div class='alert alert-danger container'> Can\'t enter here directry </div>", 'back');
                }
                break;
            
            case "approve":
                echo '<h1 class="text-center h-edit">Actevate  Member</h1>';
                echo '<div class="container">';
                if (ifTypeRequestGET()){
                    approveItem();
                    redirect("<div class='alert alert-success container'>" ."1" . " Requerd Approved </div>", 'back');
                } else {
                    redirect("<div class='alert alert-danger'>Can\'t Enter This Bage Directry </div>", 'back');
                }
                echo '</div>';
                break;
            case 'specific_comment':
                structerManageDashboardSpecificComments();
                break;

            default:
                structureDashboardItems();
                break;
        } 
    }

// End Controllar Fucntion


    // Root Code (Main)
    if (isset($_SESSION['username'])) {
        include('init.php');
        setNav();
        mainStructureItemsPage();
        include($tpl . 'footer.php');
    } else {
        header('index.php');
        exit();
    }
    ob_flush();