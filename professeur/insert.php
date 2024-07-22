<?php

// insert.php
session_start();
require 'database_cal.php';

if (isset($_POST["title"])) {
    $id_professeur = $_SESSION['id_professeur'];
    $query = "INSERT INTO events (title, start_event, end_event, id_professeur) 
              VALUES (:title, :start_event, :end_event, :id_professeur)";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':title' => $_POST['title'],
        ':start_event' => $_POST['start'],
        ':end_event' => $_POST['end'],
        ':id_professeur' => $id_professeur
    ]);
    echo "Evenement ajouté avec succès.";
}
?>
