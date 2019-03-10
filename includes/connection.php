<?php
    ob_start(); // Turns on output Buppering
    session_start();

    date_default_timezone_set('Asia/Dhaka');

    try {
        $connection = new PDO("mysql:dbname=VideoTube;host=localhost", "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } catch (PDOException $e){
        echo "Connection failed " . $e->getMessage();
    }

    ?>