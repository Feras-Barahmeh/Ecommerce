<?php
// PDO: PHP Data OBject.
    $dsn = 'mysql:host=localhost;dbname=ecommerce'; // Data Sourece Name
    $user = 'root';
    $password = '';
    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ];
    try {
        $db = new PDO($dsn, $user, $password, $options); // Start new Conaction in database;
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOEXCEPTION $th) {
        echo 'Faild Connect To Database ' . $th->getMessage();
    }