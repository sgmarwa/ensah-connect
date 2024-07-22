<?php

// load.php
session_start();
require 'database_cal.php';

$id_professeur = $_SESSION['id_professeur'];
$data = array();

$query = "SELECT * FROM events WHERE id_professeur = :id_professeur ORDER BY id";
$statement = $connect->prepare($query);
$statement->execute([':id_professeur' => $id_professeur]);
$result = $statement->fetchAll();


foreach ($result as $row) {
    $data[] = array(
        'id' => $row["id"],
        'title' => $row["title"],
        'start' => $row["start_event"],
        'end' => $row["end_event"]
    );
}

echo json_encode($data);
?>

