    <?php
        session_start(); // Resumption

    // Start Dashboard functions

// Main fucntion in dashboard
    function printPanelBody($Item='*', $table, $order, $numItem = 5, $to='member') {
        if($to=='member') {
            $results = feedBack($Item, $table, $order, 'DESC', $numItem);
            if(!empty($results)) {
                foreach ($results as $result) {?>
                    <ul class="list-unstyled latest-users">
                        <li>
                            <span class='user'>
                                <?php echo $result['userName'] ?>
                            </span>
                            <div class="btn">
                                <a href="members.php?actionInMember=edit&userID=<?php echo $result['userID']?>"  class ='edit'><i class="fa fa-edit"></i>Edit</a>
                                <?php setActivateBtn($result,'members.php', 'actionInMember', 'activate', 'userID','regStatus' ) ?>
                            </div>
                        </li>
                    </ul>
                    <?php
                }
        } else {
            echo "No Requerds To show";
        }
        } elseif($to='items') {
            $results = feedBack($Item, $table, $order, 'DESC', $numItem);
            if(!empty($results)) {
                foreach ($results as $result) {?>
                    <ul class="list-unstyled latest-users">
                        <li>
                            <span class='user'>
                                <?php echo $result['nameItem'] ?>
                            </span>
                            <div class="btn-dashboed">
                                <a href="items.php?actionInItems=edit&itemID=<?php echo $result['itemID']?>"  class ='edit-item'><i class="fa fa-edit"></i>Edit</a>
                                <a href="comments.php?actionInComments=specific_comment&itemID=<?php echo $result['itemID']?>"><i class="fa-solid fa-comment"></i> Comments</a>
                                <?php setActivateBtn($result, 'items.php', 'actionInItems', 'approve', 'itemID', 'approve') ?>
                            </div>
                        </li>
                    </ul>
                <?php
                }
        } else {
            echo "No Requerd to show";
        }
        }
    }

    function structureDashboard() {?>

        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-member">
                        <div class="text">
                            <i class="fa fa-users"></i>
                            <P>Total Memeber</P>
                        <span><a href="members.php"><?php echo numbersItems('userID', 'users') ?></a></span>
                        </div>
                        
                        
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat st-pending">
                        <div class="text">
                            <i class="fa fa-user-plus"></i>
                            <p>Pending Memeber</p>
                            <span><a href="members.php?actionInMember=manage&activate=pending"><?php echo ItemExistOrRepeate('regStatus', 'users', 0)?> </a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 ">
                    <div class="stat st-items">
                        <div class="text">
                            <i class="fa fa-tag"></i>
                            <p>Total Items</p>
                            <span><a href="items.php"><?php echo numbersItems('itemID', 'items') ?></a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat  st-com">
                        <div class="text">
                            <i class="fa fa-comments"></i>
                            <p>Total comments</p>
                            <span><a href="comments.php"><?php echo numbersItems('commID', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="last">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Laset 5 Registerd Users
                                <span class="pull-right toggel-info">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                    <?php printPanelBody("*", 'users', 'userID', 5, 'member') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Laset 5 Items
                                <span class="pull-right toggel-info">
                                        <i class="fa fa-plus fa-lg"></i>
                                    </span>
                            </div>
                            <div class="panel-body">
                                    <?php printPanelBody("*", 'items', 'itemID', 5, 'items') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    <?php
    }


// End Dashboard functions


    if (isset($_SESSION['username']) || isset($_SESSION['user'])) {
        $TITLE = 'Dashboard';
        include('init.php');

        // Start 
            setNav();
            structureDashboard();
        // End
        include($tpl . 'footer.php');
    } else {
        header('index.php');
        exit();
    }
