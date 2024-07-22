<?php

// update.php
session_start();

require 'database_cal.php';

if (isset($_POST["id"])) {
    $id_professeur = $_SESSION['id_professeur'];
    $query = "UPDATE events 
              SET title = :title, start_event = :start_event, end_event = :end_event 
              WHERE id = :id AND id_professeur = :id_professeur";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':title' => $_POST['title'],
        ':start_event' => $_POST['start'],
        ':end_event' => $_POST['end'],
        ':id' => $_POST['id'],
        ':id_professeur' => $id_professeur
    ]);
    echo "Evenement mis à jour avec succès.";
}
?>

