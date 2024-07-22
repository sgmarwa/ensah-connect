<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

require 'database.php';


if (!isset($_GET['module'])) {
    echo json_encode([]);
    exit();
}

$moduleNom = $_GET['module'];

// Récupération de l'ID du module
$sql_module_id = "SELECT id_module FROM module WHERE nom_module = ?";
$stmt = $conn->prepare($sql_module_id);
$stmt->bind_param("s", $moduleNom);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_module = $row['id_module'];

// Récupération des noms de devoirs
$sql_devoirs = "SELECT nom_devoir FROM rendu WHERE id_module = ?";
$stmt = $conn->prepare($sql_devoirs);
$stmt->bind_param("i", $id_module);
$stmt->execute();
$result = $stmt->get_result();

$devoirs = [];
while ($row = $result->fetch_assoc()) {
    $devoirs[] = $row['nom_devoir'];
}

echo json_encode($devoirs);

$stmt->close();
$conn->close();
?>
