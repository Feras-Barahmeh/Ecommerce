<?php 

// Start Glabal Deffination
    ob_start(); // Output Buffering start
    session_start();
    $TITLE = 'Categories';
// End Glabal Deffination


    function structerItemsInCategories() {
        ?>
            <div class="container">
                <div class="row container-item">
                    <?php
                        if(getID('catID')) {
                            ?> <h1 class="text-center"> <?php echo str_replace('-', ' ', (string) getID('nameCategorie', 'string')) ?> </h1> <?php
                            $items = getTable('*', 'items', 'WHERE catID = ' . getID('catID') . " AND approve = 1" , 'itemID', 'DESC');
                            foreach($items as $item) {
                                    ?>
                                        <div class="col-sm-6 col-md-3">
                                                <div class="thumbnail thumbnail-img">
                                                    <span class="price"><?php echo $item['price'] ?></span>
                                                    <span class="date"><?php echo $item['dateAdd'] ?></span>
                                                    <!-- put item -->
                                                    <?php setPricterImage('index', 'ItemsPictuer', $item['pictureItem']) ?> 
                                                    <div class="caption">
                                                            <a href="items.php?itemid=<?php echo $item['itemID'] ?>"><h3><?php echo $item['nameItem']?></h3></a>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php 
                            }
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
