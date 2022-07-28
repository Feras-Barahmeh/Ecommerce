<?php
    echo '<link rel="stylesheet" href="layout\css\frontend.css" />';
    echo '<link rel="stylesheet" href="layout\js\frontend.js" />';

    function setLinkAdminPanel() {
        if (isset($_SESSION['user']) ) {
            if(checkifPermeationUser($_SESSION['user'])) {
                ?> <a href="admin/dashboard.php">Controlle panel</a> <?php
            }
        } else {
            if ( isset($_SESSION['username'])) {
                if(checkifPermeationUser($_SESSION['username'])) {
                    ?> <a href="admin/dashboard.php">Controlle panel</a> <?php
                }
            }
        }
        
    }

    function getInfoSession() {
        if (isset($_SESSION['user'])) 
            return ['name' => $_SESSION['user'], 'ID' => $_SESSION['IDuser']];
        else 
            return ['name' => $_SESSION['username'], 'ID' => $_SESSION['userID']];
    }
?>

<div class="container">
    <?php 
        setLoginOrUser('user', 'username'); 
        // if(checkRegStatus('user') >= 1) {
        //     // The user Not Activate
        // }

        /**
         * @version 1.0.0
         * @todo set links own user
         */
        function setLinks() { 
            $session = getInfoSession();
            ?>
                
                <div class="pull-right">
                    <div class="dropdown info-user">
                        <button onclick="myFunction()" class="dropbtn">
                            <?php echo $session['name'] ?> <i class="fa fa-caret-down" aria-hidden="true" ></i>
                        </button>
                        <!-- Sit pictuer -->
                        <?php   setPricterImage('index', 'ProfilePictuer', getTable('profilePicture', 'users', 'WHERE userID =  ' . $session['ID'], null, null,'fetch')['profilePicture'])  ?>
                        <div id="myDropdown" class="dropdown-content">
                            <a href="profile.php">Profile</a>
                            <a href="additem.php">add Item</a>
                            <?php setLinkAdminPanel(); ?>
                            <a href="logout.php" class="confirm">logout</a>
                        </div>
                    </div>
                </div>

                
            <?php
        }
        
    
    ?>
</div>
<div class="header">
    <div class="body">

        <div class="container container-nav">
            <a href="index.php" class="logo" id=""><?php echo lang('Home'); ?></a>
            <nav>
                <ul>
                    <?php
                        $categories = getTable('name', 'categories', 'WHERE parent = 0', 'ID', 'ASC');
                        foreach($categories as $categorie) {?>
                            <li><a href="categories.php?catID=<?php echo $categorie['ID'] ?>&nameCategorie=<?php echo str_replace(" ", "-", $categorie['name'])?>"><?Php echo $categorie['name']?></a></li>
                            <?php
                        }
                    ?>
                </ul>
            </nav>


        </div>
    </div>
</div>

<?php
    echo '<link rel="stylesheet" href="layout\css\frontend.css" />';
    echo '<link rel="stylesheet" href="layout\js\frontend.js" />';

?>