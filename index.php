<?php

// Start Gllobal  declarations
    ob_start();
    session_start();
    $TITLE = 'Amzone';
    include('init.php');
// End global Declarations


// Start Fork Fucntion

// End Fork Fucntion

// Start main functins
    function structerItems() {?>
        <div class="container">
            <h1 class="text-center"></h1>

            <div class="row container-item">
                <?php
                    $items = getTable('*', 'items', 'WHERE approve = 1', 'dateAdd', 'DESC');
                    foreach($items as $item) {
                        // check if item approverd from admin or not
                                ?>
                                    <div class="col-sm-6 col-md-3">
                                            <div class="thumbnail thumbnail-img">
                                                <span class="price">$<?php echo $item['price'] ?></span>
                                                <span class="date"><?php echo $item['dateAdd'] ?></span>
                                                <?php setPricterImage('index', 'ItemsPictuer', $item['pictureItem']) ?>
                                                <div class="caption">
                                                        <a href="items.php?itemid=<?php echo $item['itemID'] ?>"><h3><?php echo $item['nameItem']?></h3></a>
                                                        <!-- <p><?php echo $item['description'] ?></p> -->
                                                </div>
                                            </div>
                                    </div>
                                <?php 
                    }
                ?>
            </div>
        </div>
    <?php
    }

// End Main functions


    // Start main structer
    structerItems();
    // End main structer

    

    include($tpl . 'footer.php');
    ob_end_flush();
