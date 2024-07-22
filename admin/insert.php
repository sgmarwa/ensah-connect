<?php

// insert.php
session_start();
require '../prof/database_cal.php';

if (isset($_POST["title"])) {
    $id_personnel = $_SESSION['id_personnel'];
    $query = "INSERT INTO events1 (title, start_event, end_event, id_personnel) 
              VALUES (:title, :start_event, :end_event, :id_personnel)";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':title' => $_POST['title'],
        ':start_event' => $_POST['start'],
        ':end_event' => $_POST['end'],
        ':id_personnel' => $id_personnel
    ]);
    echo "Evenement ajouté avec succès.";
}
?>
