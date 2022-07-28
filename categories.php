<?php 

// Start Glabal Deffination
    ob_start(); // Output Buffering start
    session_start();
    $TITLE = 'Categories';
// End Glabal Deffination

    function loginSideBar() {
        
    }


    function structerItemsInCategories() {?>
        <div class="container">
            <div class="row container-item">
                <?php
                    if(getID('catID')) {
                        ?> <h1 class="text-center"> <?php echo str_replace('-', ' ', (string) getID('nameCategorie', 'string')) ?> </h1> <?php
                        $items = getTable('*', 'items', 'WHERE catID = ' . getID('catID') . " AND approve = 1" , 'itemID', 'DESC');
                        foreach($items as $item) {
                            // check if item approverd from admin or not
                            if(checkIfApproved($item['approve'])) {
                                ?>
                                    <div class="col-sm-6 col-md-3">
                                            <div class="thumbnail thumbnail-img">
                                                <span class="price"><?php echo $item['price'] ?></span>
                                                <span class="date"><?php echo $item['dateAdd'] ?></span>
                                                <!-- <img src="layout/images/download.png" alt=""/> -->
                                                <?php setPricterImage('index', 'ItemsPictuer', $item['pictureItem']) ?> <!-- Sit item -->
                                                <div class="caption">
                                                        <a href="items.php?itemid=<?php echo $item['itemID'] ?>"><h3><?php echo $item['nameItem']?></h3></a>
                                                        <!-- <p><?php echo $item['description'] ?></p> -->
                                                        
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                        }
                    } else {
                        redirect('<div class="alert alert-danger text-center">No Category in this ID</div>', 'back');
                    }
                ?>
            </div>
        </div>
    <?php
    }




// Start Main
    include('init.php');
    structerItemsInCategories();
    include($tpl . 'footer.php');
    ob_end_flush();
