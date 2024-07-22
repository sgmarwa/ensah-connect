<?php

// save_notes.php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['filiere'], $data['module'], $data['evaluation'], $data['pourcentage'], $data['notes'])) {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
    exit();
}

$filiere = $data['filiere'];
$module = $data['module'];
$evaluation = $data['evaluation'];
$pourcentage = $data['pourcentage'];
$notes = $data['notes'];

require 'database.php';

$sql_module = "SELECT id_module FROM module WHERE nom_module = '$module'";
$result_module = $conn->query($sql_module);
if ($result_module->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "Module non trouvé"]);
    exit();
}
$id_module = $result_module->fetch_assoc()['id_module'];

foreach ($notes as $note) {
    $id_etudiant = $note['id_etudiant'];
    $note_value = $note['note'];

    $sql = "INSERT INTO notes_evaluation (id_etudiant, id_module, type_evaluation, pourcentage, note)
            VALUES ('$id_etudiant', '$id_module', '$evaluation', '$pourcentage', '$note_value')
            ON DUPLICATE KEY UPDATE note = '$note_value', pourcentage = '$pourcentage'";

    if (!$conn->query($sql)) {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        exit();
    }
}

echo json_encode(["success" => true, "message" => "Notes enregistrées avec succès"]);
$conn->close();
?>






