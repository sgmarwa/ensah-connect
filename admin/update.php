<?php

// update.php
session_start();
require '../prof/database_cal.php';

if (isset($_POST["id"])) {
    $id_professeur = $_SESSION['id_personnel'];
    $query = "UPDATE events1 
              SET title = :title, start_event = :start_event, end_event = :end_event 
              WHERE id = :id AND id_personnel = :id_personnel";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':title' => $_POST['title'],
        ':start_event' => $_POST['start'],
        ':end_event' => $_POST['end'],
        ':id' => $_POST['id'],
        ':id_personnel' => $id_personnel
    ]);
    echo "Evenement mis à jour avec succès.";
}
?>

