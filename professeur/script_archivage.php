<?php

require 'database.php';

$current_date = date("Y-m-d");

$tables = ['ressources', 'devoir', 'annonce'];
foreach ($tables as $table) {
    $sql = "UPDATE $table SET archive = 1 WHERE archive_date <= '$current_date'";
    $conn->query($sql);
}

$conn->close();
?>
