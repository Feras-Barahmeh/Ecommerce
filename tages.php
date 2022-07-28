<?php 

// Start Glabal Deffination
    ob_start(); // Output Buffering start
    session_start();
    $TITLE = 'Categories Depended Tages';
// End Glabal Deffination

    function loginSideBar() {
        
    }


    function structerItemsInCategories() {?>
        <div class="container">
            <div class="row container-item">
                <?php
                    if(getID('nameTage', 'string')) {
                        ?> <h1 class="text-center"> <?php echo strtoupper((string) getID('nameTage', 'string')) ?> </h1> <?php
                        $where =    "where tage LIKE '%" . getID('nameTage', 'string') . "%'" . "AND approve = 1";
                        $items = getTable('*', 'items', $where  , 'itemID');
                        foreach($items as $item) {
                            // check if item approverd from admin or not
                                ?>
                                    <div class="col-sm-6 col-md-3">
                                            <div class="thumbnail">
                                                <span class="price"><?php echo $item['price'] ?></span>
                                                <span class="date"><?php echo $item['dateAdd'] ?></span>
                                                <!-- <img src="layout/images/download.png" alt=""/> -->
                                                <?php setPricterImage('index', 'ItemsPictuer', $item['pictureItem'], '')   ?> <!-- Sit Image -->
                                                <div class="caption">
                                                        <a href="items.php?itemid=<?php echo $item['itemID'] ?>"><h3><?php echo $item['nameItem']?></h3></a>
                                                        <!-- <p><?php echo $item['description'] ?></p> -->
                                                </div>
                                            </div>
                                        </div>
                                <?php }
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
