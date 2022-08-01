<?php

// Start Global Deffination
    session_start();
    ob_start();
    $TITLE = 'Add Item';
    include('init.php');
// End Global Deffination

// Start Globale Functions
    function getInfoFromPOSt() {
        $name           = filter_var($_POST['nameItem'], FILTER_UNSAFE_RAW);
        $description    = filter_var($_POST['description'], FILTER_UNSAFE_RAW);
        $price          = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $madeIn         = filter_var($_POST['madeIn'], FILTER_UNSAFE_RAW);
        $catID          = filter_var($_POST['catID'], FILTER_VALIDATE_INT);
        $status         = filter_var($_POST['status'], FILTER_VALIDATE_INT);
        $tage           = filter_var($_POST['tage'], FILTER_UNSAFE_RAW);

        return [
                'name'              => $name, 
                'description'       => $description,
                'price'             => $price,
                'madeIn'            => $madeIn,
                'catID'             => $catID,
                'status'            => $status,
                'tage'            => $tage
            ];
    }


    function PrintFeilds() {
        $felids = getTable('categories.ID, categories.name', 'categories', 'WHERE parent = 0');
        foreach($felids as $felid) { ?>
            <option value="<?php echo $felid['ID'] ?>"><?php echo $felid['name']?></option>
            <?php
        }
    }
// End Globale Functions

// Start Fork Fucntion
    // start Add item 
        function addItemInDB() {
            global $db;
            $info = getInfoFromPOSt();
            $infoImage = getImgFileInfo('pictureItem');

            $stmt = $db->prepare("INSERT INTO
                                                items(nameItem, description, price, madeIn, catID, status, pictureItem, memberID, tage, dateAdd)
                                        VALUES
                                                (:name, :description, :price, :madeIn, :catID, :status, :pictureItem, :userID, :tage, NOW())
                                ");
            moveImageInFolder($infoImage['tempNameFileAvatar'], 'admin\\uploded\\ItemsPictuer\\', $infoImage['nameAvatar'], $info['name']);
            $stmt->execute([
                        'name'          => $info['name'],
                        'description'   => $info['description'],
                        'price'         => $info['price'],
                        'madeIn'        => $info['madeIn'],
                        'catID'         => $info['catID'],
                        'status'        => $info['status'],
                        'pictureItem'   => prepareImageName($infoImage['nameAvatar'], $info['name']),
                        'userID'        => $_SESSION['IDuser'],
                        'tage'          => $info['tage'],
            ]);
            if($stmt) {?>
                <div class='alert alert-success container'> One Item Add <br> Look to Approval from manager </div>
            <?php
            } else {?>
                <div class="alert alert-danger container" >Loded to Server Not Add Item Try Again</div>
            <?php
            }
        }

        function structureAddForm() { 
            ?>
                <!-- <h1 class="text-center h-edit">Add Items</h1> -->
                <div class="container container-edit-mem add-item-panel">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form-horizontal mem-edit-form" enctype="multipart/form-data">

                        <!-- Start Name Feaild -->
                            <div class="form-group form-group-lg feild-edit-mem ">
                                <label class="label.col-sm-2 control-label">name</label>
                                <div class="col-sm-10 col-md-9 filed-mem">
                                    <input type="text" name="nameItem" class="form-control live-name add-item-profile-user" required="required" placeholder="Name of the Item" required="required">
                                </div>
                            </div>
                        <!-- End name Feaild -->

                        <!-- Start Description  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-9 filed-mem">
                                    <input type="text" name="description" class="form-control live-description add-item-profile-user"  placeholder="Descript this product" required="required">
                                    
                                </div>
                            </div>
                        <!-- End Description -->

                        <!-- Start Price  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">price</label>
                                <div class="col-sm-10 col-md-9 filed-mem">
                                    <input type="text" name="price" class="form-control live-price add-item-profile-user"  placeholder="Order OF This product" required="required">
                                    
                                </div>
                            </div>
                        <!-- End Price -->

                        <!-- Start Made in  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Made In</label>
                                <div class="col-sm-10 col-md-9 filed-mem">
                                    <input type="text" name="madeIn" class="form-control add-item-profile-user"  placeholder="Made in" required="required">
                                </div>
                            </div>
                        <!-- End Made in -->

                        <!-- Start categories  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">categorie</label>
                                <div class="col-sm-10 col-md-9 filed-mem">
                                    <select name="catID" id="catID" class="form-control add-item-profile-user" required="required"> 
                                        <option value="0">...</option>
                                        <?php PrintFeilds(); ?>
                                    </select>
                                </div>
                            </div>
                        <!-- End categories -->

                        <!-- Start status  -->
                            <div class="form-group form-group-lg feild-edit-mem">
                                <label class="label.col-sm-2 control-label">Status</label>
                                <div class="col-sm-10 col-md-9 filed-mem">
                                    <select name="status" id="status" class="form-control add-item-profile-user" required="required">
                                        <option value="0">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">used</option>
                                        <option value="4">Very old</option>
                                    </select>
                                </div>
                            </div>
                        <!-- End status -->

                        <!-- Start tags in item  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9 filed-mem">
                                        <textarea name="tage"  class="form-control add-item-profile-user"  placeholder="Add Tages separeted by comma ',' " cols="30" rows="1">Tags Item</textarea>
                                    </div>
                                </div>
                        <!-- End tags in item -->

                        <!-- Start image in item  -->
                                <div class="form-group form-group-lg feild-edit-mem">
                                    <label class="label.col-sm-2 control-label">Pictuer Item</label>
                                    <div class="col-sm-10 col-md-9 filed-mem">
                                        <input type="file" name="pictureItem" class="form-control add-item-profile-user" required>
                                    </div>
                                </div>
                        <!-- End image in item -->

                        <!-- Start submit  -->
                                <div class="add-item">
                                    <input type="submit" value="Add Item" name="add-cat" class="btn btn-primary" />
                                </div>
                        <!-- End submit -->
                    </form>
                </div>
                <?php 
        }

        function validateForm() {
            $post = getInfoFromPOSt();
            $errors = [];
            // Start Name
                if (empty($post['name'])) {
                    array_push($errors, "Can't Enter Empty Nmae");
                }

                if(ItemExistOrRepeate('nameItem', 'items', $post['name'])) {
                    array_push($errors, "This Item Is Exsit");
                }
            // End Name

            // Start Price
                if (empty($post['price'])) {
                    array_push($errors, "Can't Enter Empty Price");
                }
            // End Price 

            // Start Cantry
                if (empty($post['madeIn'])) {
                    array_push($errors, "Can't Enter Empty Cantry");
                }
            // End Cantry 

            // Start categorie
                if (empty($post['catID'])) {
                    array_push($errors, "Can't Enter Empty categorie");
                }
            // End categorie 

            // Start Status
                if (empty($post['status'])) {
                    array_push($errors, "Can't Enter Empty Status");
                }
            // End Status 

            // Start Image
                validateImage('pictureItem', $errors);
            // Start Image

            if (!empty($errors)) {
                printErrors($errors, 'additem.php', 300);
            } else {
                // Add Item
                addItemInDB();
            }
        }

    // End Add Item
// End Fork Fucntion

// Start Main Structer
    function mainStruct() { 
        global $TITLE;
        
        ?>
        <!-- <h1 class="text-center container"><?php echo $TITLE ?></h1>     -->
        <div class="information">
            <div class="container">
                <div class = "panel panel-primary">
                    <h3 class = "panel-title"><?php echo $TITLE ?></h3>
                        <div class = "panel-body">
                            <div class="row add-item-panel">
                                <div class="col-md-8">
                                    <?php structureAddForm() ?>
                                </div>

                                <div class="col-md-4 add-item-4-col live-add-item">
                                    <div class="live-edit">
                                        <span class="price price-add-item">$0</span>
                                        <img src="admin\layout\images\defaultImg.png" alt="pictuer"/>
                                        <div class="caption">
                                            <h3>title</h3>
                                            <p>description</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
    <?php
    }
// ُEnd Main Structer


    // Main Code
    if(isset($_SESSION['user'])) {
        mainStruct();
        if(isset($_POST['add-cat'])) {
            validateForm();
        }
    } else {
        header("Location: login.php");
        exit();
    }
    include($tpl . 'footer.php');
    ob_end_flush();