<?php 
    /**
     * @version 1.0
     * @todo get categories from DB
     * @param No parameters
     * @version 1.1
     * @todo get all information from table
     * @param table determant table you want get data from
     * @param orderd the defullt value of this param is null if user geven value the information sortd dependen the of orderd value parameter
     * @param typeOrderd orderd descending or aescending defult value aesending
     * @version 1.2
     * @param condition to add condition
     * @version 1.3
     * @param filed select field you want fetch
     * 
     * @version 1.4
     * @param typeFetch This parameter to select the type of fetch data
     * @version 1.5
     * @param limit select number item you want return (LIMIT nubmer)
     */
    function getTable($filed='*', $table, $condition=NULL,  $ordered = null, $typeOrders = 'DESC', $typeFetch = 'fetchAll', $limit = NULL) {
        global $db;
        if($ordered === null) {
            $stmt = $db->prepare("SELECT $filed FROM $table $condition $limit");
        }
        else
            $stmt = $db->prepare("SELECT * FROM $table $condition ORDER BY $ordered $typeOrders $limit");
        $stmt->execute();

        if ($typeFetch === 'fetchAll')
            $requerd = $stmt->fetchAll();
        elseif($typeFetch === 'fetch') {
            $requerd = $stmt->fetch();
        }
        return $requerd;
    }



    /**
     * @version 1.0.0
     * @todo set title in current page.
     * @return No Return it print title in page
     */
    function setTitle() {
        global $TITLE;
        if (isset($TITLE)) {
            echo $TITLE;
        }
    }


    /**
     * @version 1.0.0
     * @todo  I will encrypte by sha1 code
     * @return encrypte string
     */
    function encryptPassword($pass) {
        return sha1($pass);
    }


    /**
     * @version 1.0
     * @todo In this function we will comfirem to type of requset.
     * @return true if Requeat POST
     * 
     */
    function ifTypeRequestPOST($namePost = NULL) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            return true;
    }



    /**
     * @version 1.0.0
     * @todo  comfirem to type of requset. is GET
     * @return True if Request GET
     * @version 1.0.1
     * @todo In this version has option check if Sepsific name with spesafic value
     * @param nameGET : the name of get you will check if exist
     * @param valueGET : The value of name
     * @version 1.0.2
     * @param 
    */
    function ifTypeRequestGET($nameGET=null, $valueGET=null) {
        if($nameGET == null){
            if ($_SERVER['REQUEST_METHOD'] == 'GET') 
                return true;
        } elseif(isset($_GET[$nameGET]) && $_GET[$nameGET] == $valueGET) {
                return true;
            
        }
    }

    /**
     * @version 1.0
     * @todo Get Value of ID of categorie From Get request.
     * @param No parametrers
     * @return Value of ID
     */
    function getID($nameID, $typeRequest='int') {
        if ($typeRequest === 'int')
            // return isset($_GET[$nameID]) && intval($_GET[$nameID]) ?  $_GET[$nameID] : 0;
            return isset($_GET[$nameID]) && is_numeric($_GET[$nameID]) ?  intval($_GET[$nameID]) : 0;
        if ($typeRequest === 'string' || $typeRequest === 'str')
                return isset($_GET[$nameID]) && strval($_GET[$nameID]) ?  $_GET[$nameID] : '';
        }

    /**
     * @version 1.0
     * @todo Enquiry To delete Items
     * @param table The table you want delete from it
     * @param nameIDInTable The name id item in this table.
     */
    function enquiryDelete($table, $nameIDInTable, $valueCondition) {
        global $db;
        $stmt = $db->prepare("DELETE FROM $table WHERE $nameIDInTable = ?");
        // $stmt->execute([getID($nameIDInTable)]);
        $stmt->execute([$valueCondition]);

        redirect("<div class='alert alert-success container'>" . $stmt->rowCount() . " Requerd Delete </div>", 'back');
    }

    /**
     * @version 1.0.0
     * @param ms : print error maseage
     * @param direction : Select gool after action
     * @param sec  : How many Secound To Show The Error
     * @todo In This version redirect to index page after specific secound.
     * ==============================================================
     * ------------------------ Version 1.0.1 -----------------------
     * ==============================================================
     * @param Ms  : print massege.
     * @param sec : How many Secound To Show The Error
     * 
     * @version 1.0.2
     * In this version add redirect at the same bage
     */
    function redirect($ms, $direction=null, $sec = 5) {
        if ($direction === null) {
            $direction = 'index.php';
            $url = 'Home Page';
        } elseif($direction === 'back') {  
            $direction = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
            $url = "Previes Page";
        } else {
            $url = $direction;
        }

        echo $ms;
        echo "<div class ='alert alert-info container'>You Will Redirect To $url After $sec sec</div>";
        header("refresh:$sec;url=$direction"); // we will use refresh not index becoues wait many sec befor redirect.
        exit();
    }


    /**
     * @version  1.0.0
     * @todo check if memeber | product | admins ext.. if exist
     * @param colums  : Select Item
     * @param table   : Select Table
     * @param valus   : Select The values
     * @return Row count
     */
    function ItemExistOrRepeate($colums, $table, $valus) {
        global $db;
        $enquiry = $db->prepare("SELECT $colums FROM $table WHERE $colums = ?");
        $enquiry->execute([$valus]);
        $result = $enquiry->rowCount();

        if ($result > 0){
            return $result;
        } else {
            return $result;
        }
    }

    /**
     * @version numbers Items fucntion v.1
     * @todo Count Items from DB.
     * @param  col The item we want count it
     * @param tabel The table you want fetch data from.
     * @throws if you want select value of thes colums. (In this case using `ItemExistOrRepeate()` function declarated above)
     * @return Number of items
     */
    function numbersItems($col, $table) {
        global $db;
        $stmt = $db->prepare("SELECT COUNT($col) FROM $table");
        $stmt->execute();
        $result = $stmt->fetchColumn();

        return $result;
        // return $stmt->rowCount();
    }


    /**
     * @version 1.0.0
     * @todo This function set Activet member buttem.
     * @param requerd All infon for user
     * @version 1.0.1
     * @param namePage name page who you want to go to
     * @param nameAction Name action in get request 
     * @param activeOrProve you want use this fucntion to active memeber or approve item
     * @param nameID name id column in db
     * @param status name column administrator to select status
     * @return No return 
     */
    function setActivateBtn($requerd,$namePage, $nameAction, $activeOrProve, $nameID, $status) {
        if ($requerd[$status] == 0) {
            echo "<a href='$namePage?$nameAction=$activeOrProve&$nameID=$requerd[$nameID]' class  ='btn btn-info activ active-item'> <i class='fas fa-toggle-on'></i> Active</a>";
        }
    }


    /**
     * @version 1.0.0
     * @todo Check if GET request is exist.
     * @param The Name of the GET request.
     */
    function getAction($path='actionInMember') {
        $actionInMember = isset($_GET[$path]) ? $_GET[$path] : 'Mange';
        return $actionInMember;
    }


    /**
     * @version 1.0.0
     * @todo Print errors come form edit or insert categorie
     * @version 1.0.1
     * @param dir to select direction you will return
     */
    function printErrors($errors, $dir='back', $time=5) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger container" >' . ($error) . '<br>' . '</div>';
        }

        // Start Select appropritate time to read errors
                $lenArray = count((array) $errors);
                if ($lenArray < 4 && $time == 5)
                    $time = 4;
                elseif ($lenArray > 4 && $time ==5)
                    $time = 7;
        // End Select appropritate time to read errors

            redirect("", $dir, $time);
    }



    // Start Functions To Sort Item in manege Dashboard

        /**
         * @version 1.0
         * @todo to retrun type of sort.
         */
        function valueIsSetGET() {
            if(ifTypeRequestGET('sort', 'ASC')) {
                return 'ASC';
            } elseif(ifTypeRequestGET('sort', 'DESC')){
                return 'DESC';
            } else {
                return 'DESC';
            }
        }

        /**
         * @version 1.0
         * @todo to set icone (check box) next to effective type ordaring
         * @param to to select if you neeb this what you will using this function.
         */

        function setIcone($to='ord') {
            if(strtolower($to) === 'ord') {
                $result = valueIsSetGET();
                if ($result == 'DESC') {
                        echo '<a href="?sort=DESC"><i class="fas fa-check-square"></i>Descending</a>';
                        echo '<a href="?sort=ASC">ASC</a>';
                } elseif($result == 'ASC') {
                    echo '<a href="?sort=DESC">Descending</a>';
                    echo '<a href="?sort=ASC"><i class="fas fa-check-square"></i>ASC</a>';
                } else {
                    echo '<a href="?sort=DESC"><i class="fas fa-check-square"></i>Descending</a>';
                    echo '<a href="?sort=ASC">ASC</a>';
                }
            }
        }

        /**
         * @version 1.0.0
         * @todo set Label
         */

        function sortForm() {?>
            <div class="sort">
                    <span>Ordring:</span>
                    <?php setIcone() ?>
                    
            </div>
        <?php
        }
    // End Functions To Sort Item in manege Dashboard

    // Start Command Between Items And Commants
        /**
         * @version 1.0
         * @todo get speasific comment debend to get request
         * @version 1.1
         * @param nameIDFromGEt this to select name id from get request
         */
        function getSpecificCommentInfoFromDB($nameIDFromGEt='itemID') {
            global $db;
            $stmt = $db->prepare("SELECT comments.*, users.userName
                                    FROM 
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.userID = comments.commUserID
                                    WHERE 
                                        commItemID = ?
                                ");
            $stmt->execute([getID($nameIDFromGEt)]);
            $requerds = $stmt->fetchAll();
            return $requerds;
        }

        function setSpecificInfoInTable() {
            $requerds = getSpecificCommentInfoFromDB();
            foreach($requerds as $requerd) {?>
                <tr>
                    <td><?php echo $requerd['comment']  ?></td>
                    <td><?php echo $requerd['userName'] ?></td>
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

        function structerManageDashboardSpecificComments() {
            global $db;
            ?>
                <h1 class=" h-manage" >Manage  Comments</h1>
                <div class="container">
                    <div class="table-responsive in-edit-comment">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>comment</td>
                                <td>Member</td>
                                <td>Regester Date</td>
                                <td>Control</td>
                            </tr>
                            <?php setSpecificInfoInTable($db) ?>
                        </table>
                    </div>
                    <!-- <a href="comments.php?actionInComments=add" class="btn btn-primary"> <i class="fa fa-plus"></i> New comment</a> -->
                </div>
            <?php }
    // End Command Between Items And Commants


    // Start Image fucntions
        /**
         * @version 1.0
         * @todo to get all information file
         * @param the value of name tag from form
         */
        function getImgFileInfo($namePost) {
            if(isset($_FILES)) {
                $avatar = $_FILES[$namePost]; 
                $nameAvatar = $avatar['name'];
                $pathAvatar = $avatar['full_path'];
                $typeAvatar = $avatar['type'];
                $sizeAvatar = $avatar['size'];
                $tempNameFileAvatar = $avatar['tmp_name'];
                $error = $avatar['error'];
                return [
                    'sizeAvatar'        => $sizeAvatar,
                    'pathAvatar'        => $pathAvatar,
                    'type'              => $typeAvatar,
                    'nameAvatar'        => $nameAvatar,
                    'tempNameFileAvatar'    => $tempNameFileAvatar,
                    'error'    => $error,
                ];
            } else {
                echo 'Must Enter Image';
            }
        }

        /**
         * @version 1.0
         * @todo rename image (no dublicated name images)
         * @param nameImage default name image
         * @param nameItem help us to rename image
         */
        function prepareImageName($nameImage,  $nameItem) {
            // return $nameItem . "_" . rand(1, 100000) . "_" . $nameImage;
            return $nameItem . "_" . $nameImage; 
        }

        /**
         * @version 1.0
         * @todo to save image in folder when inserted
         * @param tempNameFile the temporary folder image
         * @param pathFolde the path you want save image in
         * @param nameImage the name image
         * @param nameItem this param to help change name image
         */
        function moveImageInFolder($tempNameFile, $pathFolder, $nameImage, $nameItem) {
            move_uploaded_file($tempNameFile, $pathFolder . prepareImageName($nameImage, $nameItem));
        }

        /**
         * @version 1.0
         * @todo confirm validate extention image
         * @param extention curent extention image
         * @return true if extention valid false if not valid
         */
        function validImgExtenion($extention) {
            $extention = explode('.', $extention);
            static $extentions = ['jpeg', 'jpg', 'png', 'gif'];
            if(in_array(end($extention), $extentions))  return true;
        }
    

        /**
         * @version 1.0
         * @todo check if image valid to insert to DB
         * @param tagNameInForm the tag name in form requerst
         * @param ERRORS the array errors (by refrence) to add error image and another feild errors
         * @version 1.2
         * @param to to select why you use this function if to edit delete if empty name image condition
         */
        function validateImage($tagNameInForm, &$ERRORS, $to = 'add') {
            $imageInfo = getImgFileInfo($tagNameInForm);
            if(!empty($imageInfo['nameAvatar']) && !validImgExtenion($imageInfo['nameAvatar'])) {
                array_push($ERRORS, 'This Extenstion <strong> Not valid </strong>');
            }

            if($to !== 'edit') {
                if (empty($imageInfo['nameAvatar'])) {
                    array_push($ERRORS, 'Must be Enter <strong> Image Name </strong>');
                }
            }

            if((int) $imageInfo['sizeAvatar'] > 51194 * 3) {
                array_push($ERRORS, 'Size image less than <strong> 4MB </strong> your Size Image ' . $imageInfo['sizeAvatar']);
            }
        }

        /** 
         * @version 1.0
         * @todo to set image
         * @param to the path whene saved image
         * @param name name image
         * @version 1.2
         * @param nameClass this param to sit spicafic class in image (dependent where found image  any page)
         */

        function setPricterImage($to, $nameFolder, $name, $nameClass = 'pictuer-in-front') {
            if($name != NULL) {
                ?> <td><img src="<?php pathImg($to, $nameFolder, $name, 'print')  ?>" alt="pictuer" class="pictuer-in-td <?php echo $nameClass ?>"></td> <?php
            } else {
                ?> <td><img src="layout\images\defaultImg.png" alt="pictuer" class="pictuer-in-td <?php echo $nameClass ?>"></td> <?php
            }
        }

        /**
         * @version 1.0
         * @todo print or retrun path uploded img folder
         * @param to select where you sit image if in dashborde the path directe in uplode if users you enter in admin folder (you can sit path)
         * @param nameFolder the name dir you want save image
         * @param returnOrPrint you want return or print path
         */

        function pathImg($to = 'admin', $nameFolder = 'ProfilePictuer', $nameImg, $retrunOrPrint = 'return') {
            $path = '';
            if ($to === 'admin' ) {
                $path =  'uploded//' . $nameFolder. '//' . $nameImg ;
            } elseif($to === 'index' ) {
                $path = 'admin//uploded//' . $nameFolder . '//'. $nameImg;
            }

            if ($retrunOrPrint === 'return') {
                return $path;
            } else {
                echo $path;
            }
        }

    // End image fucntions


    /**
     * @version 1.0
     * @todo Check if user admin or not
     * @param user name
     */
    function checkifPermeationUser($nameuser)  {
        $groubID = getTable('groubID', 'users', 'WHERE userName = \'' . $nameuser . '\'', NULL, NULL, 'fetch');
        if ($groubID['groubID'] === 1) {
            return true;
        } else {
            return false;
        }
    }







