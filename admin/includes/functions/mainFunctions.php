<?php 
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

    function setTitle() {
        global $TITLE;
        if (isset($TITLE)) {
            echo $TITLE;
        }
    }

    function encryptPassword($pass) {
        return sha1($pass);
    }



    function ifTypeRequestPOST($namePost = NULL) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            return true;
    }




    function ifTypeRequestGET($nameGET=null, $valueGET=null) {
        if($nameGET == null){
            if ($_SERVER['REQUEST_METHOD'] == 'GET') 
                return true;
        } elseif(isset($_GET[$nameGET]) && $_GET[$nameGET] == $valueGET) {
                return true;
            
        }
    }

    function getID($nameID, $typeRequest='int') {
        if ($typeRequest === 'int')
            return isset($_GET[$nameID]) && is_numeric($_GET[$nameID]) ?  intval($_GET[$nameID]) : 0;
        if ($typeRequest === 'string' || $typeRequest === 'str')
                return isset($_GET[$nameID]) && strval($_GET[$nameID]) ?  $_GET[$nameID] : '';
        }


    function enquiryDelete($table, $nameIDInTable, $valueCondition) {
        global $db;
        $stmt = $db->prepare("DELETE FROM $table WHERE $nameIDInTable = ?");
        $stmt->execute([$valueCondition]);

        redirect("<div class='alert alert-success container'>" . $stmt->rowCount() . " Requerd Delete </div>", 'back');
    }


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
        header("refresh:$sec;url=$direction"); 
        exit();
    }



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


    function numbersItems($col, $table) {
        global $db;
        $stmt = $db->prepare("SELECT COUNT($col) FROM $table");
        $stmt->execute();
        $result = $stmt->fetchColumn();

        return $result;
    }


    function setActivateBtn($requerd,$namePage, $nameAction, $activeOrProve, $nameID, $status) {
        if ($requerd[$status] == 0) {
            echo "<a href='$namePage?$nameAction=$activeOrProve&$nameID=$requerd[$nameID]' class  ='btn btn-info activ active-item'> <i class='fas fa-toggle-on'></i> Active</a>";
        }
    }


    function getAction($path='actionInMember') {
        $actionInMember = isset($_GET[$path]) ? $_GET[$path] : 'Mange';
        return $actionInMember;
    }


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

        function valueIsSetGET() {
            if(ifTypeRequestGET('sort', 'ASC')) {
                return 'ASC';
            } elseif(ifTypeRequestGET('sort', 'DESC')){
                return 'DESC';
            } else {
                return 'DESC';
            }
        }


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


        function sortForm() {?>
            <div class="sort">
                    <span>Ordring:</span>
                    <?php setIcone() ?>
                    
            </div>
        <?php
        }
    // End Functions To Sort Item in manege Dashboard

    // Start Command Between Items And Commants

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
                </div>
            <?php }
    // End Command Between Items And Commants


    // Start Image fucntions

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


        function prepareImageName($nameImage,  $nameItem) {
            return $nameItem . "_" . $nameImage; 
        }


        function moveImageInFolder($tempNameFile, $pathFolder, $nameImage, $nameItem) {
            move_uploaded_file($tempNameFile, $pathFolder . prepareImageName($nameImage, $nameItem));
        }


        function validImgExtenion($extention) {
            $extention = explode('.', $extention);
            static $extentions = ['jpeg', 'jpg', 'png', 'gif'];
            if(in_array(end($extention), $extentions))  return true;
        }

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


        function setPricterImage($to, $nameFolder, $name, $nameClass = 'pictuer-in-front') {
            if($name != NULL) {
                ?> <td><img src="<?php pathImg($to, $nameFolder, $name, 'print')  ?>" alt="pictuer" class="pictuer-in-td <?php echo $nameClass ?>"></td> <?php
            } else {
                ?> <td><img src="layout\images\defaultImg.png" alt="pictuer" class="pictuer-in-td <?php echo $nameClass ?>"></td> <?php
            }
        }



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



    function checkifPermeationUser($nameuser)  {
        $groubID = getTable('groubID', 'users', 'WHERE userName = \'' . $nameuser . '\'', NULL, NULL, 'fetch');
        if ($groubID['groubID'] === 1) {
            return true;
        } else {
            return false;
        }
    }







