<?php

// load.php
session_start();
require '../prof/database_cal.php';

$id_personnel = $_SESSION['id_personnel'];
$data = array();

$query = "SELECT * FROM events1 WHERE id_personnel = :id_personnel ORDER BY id";
$statement = $connect->prepare($query);
$statement->execute([':id_personnel' => $id_personnel]);
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

