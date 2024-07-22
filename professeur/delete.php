<?php

// delete.php
session_start();
require 'database_cal.php';

if (isset($_POST["id"])) {
    $id_professeur = $_SESSION['id_professeur'];
    $query = "DELETE FROM events WHERE id = :id AND id_professeur = :id_professeur";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id'],
        ':id_professeur' => $id_professeur
    ]);
    echo "Evenement supprimé avec succès.";
}
?>

