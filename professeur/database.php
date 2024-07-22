<?php
    $servername = "sql108.infinityfree.com";
    $username = "if0_36621380";
    $password = "FQ97YioSyspXKW";
    $dbname = "if0_36621380_ensahconnect";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
?>