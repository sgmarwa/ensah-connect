<?php

// delete.php
session_start();
require '../prof/database_cal.php';

if (isset($_POST["id"])) {
    $id_professeur = $_SESSION['id_personnel'];
    $query = "DELETE FROM events1 WHERE id = :id AND id_personnel = :id_personnel";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id'],
        ':id_personnel' => $id_personnel
    ]);
    echo "Evenement supprimé avec succès.";
}
?>

