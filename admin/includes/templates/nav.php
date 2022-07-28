<?php 
    function printNameSeesion() {
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        }

        elseif(isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
    } 
?>
    <link rel="stylesheet" href="layout\css\backend.css" />
    <link rel="stylesheet" href="layout\js\backend.js" />

<div class="header">
    <div class="body">
        <div class="container">
            <nav>
                <a href="dashboard.php" class="logo" id=""><?php echo lang('Home'); ?></a>
                <div>
                    <ul>
                        <li><a href="<?php echo 'items.php?sort=DESC'; ?>"><?php echo lang('Items'); ?></a></li>
                        <li><a href="<?php echo 'members.php'; ?>"><?php echo lang('Members'); ?></a></li>
                        <li><a href="<?php echo 'comments.php'; ?>"><?php echo lang('Comments'); ?></a></li>
                        <li><a href="<?php echo 'categories.php?sort=DESC'; ?>"><?php echo lang('Gategores'); ?></a></li>
                    </ul>
                </div>

                <div class="pull-right">
                    <div class="dropdown info-user">
                        <button onclick="myFunction()" class="dropbtn">
                            <?php echo printNameSeesion()?> <i class="fa fa-caret-down" aria-hidden="true" ></i>
                        <a href="index.php"><?php setPricterImage('admin', 'ProfilePictuer', getTable('profilePicture', 'users', 'WHERE userID =  ' . $_SESSION['userID'], null, null,'fetch')['profilePicture'])  ?> </a>
                        <div id="myDropdown" class="dropdown-content">
                            <a href="../index.php"><?php echo lang('Go To Shop') ?></a>
                            <a href="members.php?actionInMember=edit&userID=<?php echo $_SESSION['userID'] ?>"><?php echo lang('Edit Profile'); ?></a>
                            <a href="#"><?php echo lang('Settings') ?></a>
                            <a href="logout.php"><?php echo lang('Logout') ?></a>
                        </div>
                    </div>
                </div>


            </nav>


        </div>
    </div>
</div>
