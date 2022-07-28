<?php 
    /*
        |=====================================================|
        |== Categories Page
        |== You can Add | Edit | Delate categories
        |=====================================================|
    */


// Start Glabal Deffination
    ob_start(); // Output Buffering start
    session_start();
    $TITLE = 'Categories';
// End Glabal Deffination


// Start Global function 
    function validation($req, $to='a') {
        /**
         * @version 1.0.0
         * @todo Check if input request valid or not.
         * @param req The data from request
         */
        $ERRORES = [];

         // Start Check the name
            if(strlen($req['name']) >= 20) {
                array_push($ERRORES, "The name must be lees than 20 character");
            }
            if (strtolower($to) === 'update') {
                if (ItemExistOrRepeate('name', 'categories', $req[0])) {
                    array_push($ERRORES, "This Categorie is Exist");
                }
            }
        // End Check the name

        return $ERRORES;
    }

    function printOptions() {
        ?> <option value="0">Unaffiliated</option> <?PHP
        $categories  = getTable("categories.name, categories.ID", 'categories', "WHERE parent = 0"); 
        foreach($categories as $categorie) { 
            ?>
                <option value="<?php echo $categorie['ID'] ?>"><?php echo $categorie['name'] ?></option>
        <?php }
    }
// End Global function 


// Start Fork (Tools fucntions) Fucntoion
    // Start Categories Add
        function getInfoFromPOST() {
            /**
             * @version 1.0.0
             * @todo Get Value in form 
             * @param No parametrers
             * @return Value of array include all of the values to this categorie
             */
            $ID                 = $_POST['ID'];
            $name               = $_POST['name'];
            $parent             = $_POST['parent'];
            $description        = $_POST['description'];
            $ordering           = $_POST['ordering'];
            $visibility         = $_POST['visibility'];
            $allowComment       = $_POST['allowComment'];
            $allowAdvertisement = $_POST['allowAdvertisement'];
            return [
                'name' => $name, 
                'description' => $description, 
                'ordering' => $ordering, 
                'visibility' => $visibility, 
                'allowComment' => $allowComment, 
                'allowAdvertisement' => $allowAdvertisement, 
                'ID' => $ID, 
                'parent' => $parent
        ];}

        function insertInDB() {
            /**
             * @version 1.0.0
             * @todo quiry to set data in db
             * @param No parametrers
             * @return No return 
             */
            global $db;
            $info = getInfoFromPOST();
            if(ItemExistOrRepeate('name', 'categories', $info['name']) == '0') {
                $stmt = $db->prepare("INSERT INTO 
                                                categories(name, parent, description, ordering, visibility, allowComment, allowAdvertisement, dataAdd)
                                        VALUES
                                                (?, ?,?, ?, ?, ?, ?, NOW()) 
                                    ");
                $stmt->execute([
                    $info['name'],
                    $info['parent'],
                    $info['description'],
                    $info['ordering'],
                    $info['visibility'],
                    $info['allowComment'],
                    $info['allowAdvertisement']
                ]);
                redirect( "<div class='alert alert-success container'>" . $stmt->rowCount() . " Added categorie" . "</div>", 'back');
            } else {
                redirect( "<div class='alert alert-danger container'>" . "This Categorie is exist" . "</div>", 'back');
                
            }
        }

    // End Categories Add

    // Start Categories Manage

        function StructerPanelBodyParent($cat) { ?>
                <ul class="list-unstyled latest-users latest-users-cat">
                    <li>
                        <span class='user cat'>
                            <h3 class="name-cat"><?php echo $cat['name'] ?></h3>
                            <p class="p-cat"><?php ifHasDescription($cat['description']) ?></p>
                            <div class="options">
                                <span class="options vis"><?php ifHasVis($cat['visibility']) ?></span>
                                <span class="options allow-com"><?php ifHascomm($cat['allowComment']) ?></span>
                                <span class="options allow-adds"><?php ifHasadds($cat['allowAdvertisement']) ?></span>
                            </div>
                        </span>
                        <div class="btn-ed-de-ac">
                            <a href="categories.php?actionInCategorie=edit&ID=<?php echo $cat['ID'] ?>"  class ='btn btn-success edit-all'><i class="fa fa-edit"></i>Edit</a>
                            <a href="categories.php?actionInCategorie=delate&ID=<?php echo $cat['ID']?>"  class ='confirm btn btn-danger edit'><i class="fa fa-trash"></i>Delete</a>
                        </div>
                    </li>
                </ul>
        <?php
        }

        function ifHasDescription($description) {
            if (!empty($description)) {
                echo $description;
            } else {
                echo "This Prododuct hasn't discription";
            }
        }

        function ifHasVis($vis) {
            if (!empty($vis)) {
                echo $vis;
            } else {
                echo "<div class='global-options'>Hidden</div>";
            }
        }

        function ifHascomm($comm) {
            if (!empty($comm)) {
                echo $comm;
            } else {
                echo "<div class='global-options'>Hidden</div>";
            }
        }

        function ifHasadds($adds) {
            if (!empty($adds)) {
                echo $adds;
            } else {
                echo "<div class='global-options'>Hidden</div>";
            }
        }

        function printPanelBody($Item='*', $table, $order, $typeorder) {
            $cats = getTable($Item, $table, "WHERE parent = 0", $order, $typeorder);
            foreach ($cats as $cat) {
                StructerPanelBodyParent($cat);
                getChiledCategory($cat['ID']);
            }
        }

    // Start chiled Categoriy Manage

        function StructerPanelBodyChileds($cat) { ?>
            <div class="chiled">
                <ul class="list-unstyled latest-users-chiled latest-users-cat-chiled">
                        <li class="cat-chiled"> 
                            <span class='user cat-chiled'>
                                <h3 class="name-cat-chiled"><?php echo $cat['name'] ?></h3>
                                <p class="p-cat-chiled"><?php ifHasDescription($cat['description']) ?></p>
                                <div class="options-chiled">
                                    <span class="options-chiled vis"><?php ifHasVis($cat['visibility']) ?></span>
                                    <span class="options-chiled allow-com"><?php ifHascomm($cat['allowComment']) ?></span>
                                    <span class="options-chiled allow-adds"><?php ifHasadds($cat['allowAdvertisement']) ?></span>
                                </div>
                            </span>
                            <div class="btn-chiled">
                                <a href="categories.php?actionInCategorie=edit&ID=<?php echo $cat['ID'] ?>"  class ='btn btn-success edit'><i class="fa fa-edit"></i>Edit</a>
                                <a href="categories.php?actionInCategorie=delate&ID=<?php echo $cat['ID']?>"  class ='confirm btn btn-danger edit'><i class="fa fa-trash"></i>Delete</a>
                            </div>
                        </li>
                    </ul>
            </div>
            <?php
        }

        function getChiledCategory($numberParentCat) {
            $chiledCats = getTable('*', 'categories', "WHERE parent = " . $numberParentCat);
            if(!empty($chiledCats)) {
                foreach($chiledCats as $chiledCat) {
                    StructerPanelBodyChileds($chiledCat);
                }
            }
        }

    // End chiled Categoriy Manage



    // End Categories Manage


    // Start Categories Edit

        function CheckTheSourseID($ID) {
            /**
             * @version 1.0.0
             * @todo This function check if categorie exist or not.
             * @param ID : ID of categorie
             */
            if(ItemExistOrRepeate('ID', 'categories', $ID)) {
                echo $ID;
            } else {
                redirect('<div class="alert alert-danger">'."This categorie no exsit".'</div>', 'back', 100);
            }
        }

        function enquiryUpdate() {
            global $db;
            $info = getInfoFromPOST();
            $check = validation(getInfoFromPOST('u'));
            $stmt = $db->prepare('UPDATE categories SET name = ?, parent = ?, description = ?, ordering = ?, visibility = ?, allowComment = ?, allowAdvertisement = ? WHERE ID = ' . $info['ID'] );
            if(empty($check)) {
                $stmt->execute([
                        $info['name'],
                        $info['parent'],
                        $info['description'],
                        $info['ordering'],
                        $info['visibility'],
                        $info['allowComment'],
                        $info['allowAdvertisement']
                    ]);
                redirect("<div class='alert alert-success'>" . $stmt->rowCount() . " Requerd Update</div>", 'back');
            } else {
                printErrors($check);
            }
        }

        function ifSetCheckd($Value, $valOption) {
            /**
             * @version 1.0.0
             * @todo This function print checked if vlaue of options equel value in db.
             * @param Value The name option 
             * @param valOption The Value option in form input.
             */
            if ($Value == $valOption) {
                echo 'checked';
            }
        }


        function setDefulatParentInEdit($parentChiled) {
            /**
             * @version 1.0
             * @todo to set the initial value of the parent categorie in edit form
             * @param parentChiled the chiles's parent categorie (depended from ID Father)
             */
            $categories = getTable('categories.name, categories.ID', 'categories', "WHERE parent = 0");
            foreach($categories as $categorie) {
                if($categorie['ID'] === $parentChiled) { ?>
                        <option value="<?php echo $categorie['ID'] ?>"><?php echo $categorie['name'] ?></option>
                <?php
                        break;
                }
            }
        }
    // End Categories Edit

// End Fork (Tools fucntions) Fucntoion



// Start Main Function in categories (function for this app)

    // Start Add Member PROSEGERS
        function addCategorieForm() { ?>
            <h1 class="text-center h-edit">Add Categories</h1>
            <div class="container container-edit-mem">
                <form action="?actionInCategorie=insert" method="POST" class="form-horizontal mem-edit-form">
                    <!-- Start ID Feaild -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="hidden" name="ID" class="form-control"/>
                            </div>
                        </div>
                    <!-- End ID Feaild -->

                    <!-- Start Name Feaild -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">name</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the categorie">
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

                    <!-- Start Ordering  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="number" name="ordering" class="form-control"  placeholder="Order OF This product">
                            </div>
                        </div>
                    <!-- End Ordering -->

                    <!-- Start follow it  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">follow it</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <select name="parent" id="parent"  class="form-control">
                                    <option value="0">Unaffiliated</option>
                                    <?php printOptions() ?>
                                </select>
                            </div>
                        </div>
                    <!-- End follow it -->

                    <!-- Start visibility  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Visibility</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <div class="check-vis">
                                    <div>
                                        <input type="radio" id="vis-yes" name= "visibility" value = "0" checked/>
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="vis-no" name= "visibility" value = "1"/>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End visibility -->

                    <!-- Start Allow Comment  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Allow Comment</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <div class="check-vis">
                                    <div>
                                        <input type="radio" id="comm-yes" name= "allowComment" value = "0" checked/>
                                        <label for="comm-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="comm-no" name= "allowComment" value = "1"/>
                                        <label for="comm-no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End Allow Comment -->


                    <!-- Start Allow Advertisement  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Allow Advertisement</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <div class="check-vis">
                                    <div>
                                        <input type="radio" id="adds-yes" name= "allowAdvertisement" value = "0" checked/>
                                        <label for="adds-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="adds-no" name= "allowAdvertisement" value = "1"/>
                                        <label for="adds-no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End Allow Advertisement -->

                    <!-- Start submit  -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                <input type="submit" value="Add Categories" class="btn btn-primary btn-edit-mem" />
                            </div>
                        </div>
                    <!-- End submit -->
                </form>
            </div>
        <?php
        }
    // End Add Member PROSEGERS

    // Start Manage Dashboard Proseger

        function structDashboardCategories() {?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading to-add-btn">
                        <p> Manage Categories</p>
                        <div class="">
                            <?php sortForm() ?>
                        </div>
                        <button><a href="categories.php?actionInCategorie=add" class="btn btn-primary"><i class="fa fa-plus"></i>Add Categorie</a></button>
                    </div>
                    <div class="panel-body panel-body-cat">
                        <?php printPanelBody('*', 'categories', 'ordering', valueIsSetGET()) ?>
                    </div>
                </div>
            </div>
            <?php
        }
    // End Manage Dashboard Proseger

    // Start Edit Prosegers
        function structEditCategories() {
                $info = getTable('*', 'categories', 'WHERE ID = ' . getID('ID'), null,  null, 'fetch');
            ?>
            <h1 class="text-center h-edit">Edit Categories</h1>
            <div class="container container-edit-mem">
                <form action="categories.php?actionInCategorie=update" method="POST" class="form-horizontal mem-edit-form">
                    <!-- Start ID Feaild -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="hidden" name="ID" value="<?php CheckTheSourseID( $info['ID'] )?>" class="form-control" />
                            </div>
                        </div>
                    <!-- End ID Feaild -->

                    <!-- Start Name Feaild -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">name</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="text" name="name" value="<?php echo $info['name'] ?>" class="form-control" required="required" placeholder="Name of the categorie">
                            </div>
                        </div>
                    <!-- End name Feaild -->

                    <!-- Start Description -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="text" name="description" value="<?php echo $info['description'] ?>" class="form-control"  placeholder="Descript this product" >
                            </div>
                        </div>
                    <!-- End Description -->

                    <!-- Start Ordering  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <input type="number" name="ordering" value="<?php echo $info['ordering'] ?>" class="form-control"  placeholder="Order OF This product">
                            </div>
                        </div>
                    <!-- End Ordering -->

                    <!-- Start follow it  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">follow it</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <select name="parent" id="parent"  class="form-control">
                                    <?php setDefulatParentInEdit($info['parent']) ; printOptions() ?>
                                </select>
                            </div>
                        </div>
                    <!-- End follow it -->

                    <!-- Start visibility  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Visibility</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <div class="check-vis">
                                    <div>
                                        <input type="radio" id="vis-yes" name= "visibility" value="1" <?php ifSetCheckd($info['visibility'], 1) ?> />
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <!-- <?php echo $info['visibility']?> -->
                                    <div>
                                        <input type="radio" id="vis-no" name= "visibility" value="0" <?php ifSetCheckd($info['visibility'], 0) ?>/>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End visibility -->

                    <!-- Start Allow Comment  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Allow Comment</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <div class="check-vis">
                                    <div>
                                        <input type="radio" id="comm-yes" name= "allowComment" value="1"  <?php ifSetCheckd(['allowComment'], 1) ?>/>
                                        <label for="comm-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="comm-no" name= "allowComment" value="0" <?php ifSetCheckd($info['allowComment'], 0) ?>/>
                                        <label for="comm-no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End Allow Comment -->

                    <!-- Start Allow Advertisement  -->
                        <div class="form-group form-group-lg feild-edit-mem">
                            <label class="label.col-sm-2 control-label">Allow Advertisement</label>
                            <div class="col-sm-10 col-md-4 filed-mem">
                                <div class="check-vis">
                                    <div>
                                        <input type="radio" id="adds-yes" name= "allowAdvertisement" value="1" <?php ifSetCheckd($info['allowAdvertisement'], 1) ?> />
                                        <label for="adds-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="adds-no" name= "allowAdvertisement" value="0" <?php ifSetCheckd($info['allowAdvertisement'], 0)?> />
                                        <label for="adds-no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End Allow Advertisement -->

                    <!-- Start submit  -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 btn-lg feild-edit-mem-btn">
                                <input type="submit" value="Update Categories" class="btn btn-primary btn-edit-mem" />
                            </div>
                        </div>
                    <!-- End submit -->
                </form>
            </div>
            <?php }

    // End Edit Prosegers


// End Main Function in categories



// Start controllar Function 
    function mainStructureCategoriePage() {
        switch (getAction("actionInCategorie")) {
            case 'add':
                addCategorieForm();
                break;

            case 'insert':
                if(ifTypeRequestPOST()) {
                    insertInDB();
                } else {
                    redirect("<div class='alert alert-danger container'> Can\'t enter here directry </div>", 'back');
                }
                break;

            case 'delate':
                echo '<h1 class="text-center h-edit">Delete Information</h1>';
                echo '<div class="container">';
                enquiryDelete('categories', 'ID', getID('ID'));
                break;

            case 'edit':

                structEditCategories();
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

            default:
                structDashboardCategories();
                break;
        } 
    }

// End controllar Function 


        // Root Code (Main)
        if (isset($_SESSION['username'])) {
            include('init.php');
            setNav();
            mainStructureCategoriePage();
            include($tpl . 'footer.php');
        } else {
            header('index.php');
            exit();
        }

        ob_flush();