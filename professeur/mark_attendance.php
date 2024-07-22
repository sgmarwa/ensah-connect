<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }
header('Content-Type: application/json'); // S'assurer que la réponse est de type JSON

require 'database.php';

$moduleNom = $_GET['module'];
$studentId = $_GET['id'];
$typeSeance = $_GET['type_seance'];
$action = $_GET['action'];

// Obtenir l'ID du module
$sql_module_id = "SELECT id_module FROM module WHERE nom_module = '$moduleNom'";
$result_module_id = $conn->query($sql_module_id);
if ($result_module_id->num_rows > 0) {
    $row_module_id = $result_module_id->fetch_assoc();
    $id_module = $row_module_id['id_module'];

    // Marquer l'absence si l'action est "ABS"
    if ($action === "ABS") {
        $sql_insert_absence = "INSERT INTO abscence (id_etudiant, id_module , type_seance) VALUES ('$studentId', '$id_module', '$typeSeance')";
        if ($conn->query($sql_insert_absence) === TRUE) {
            $response = ['success' => true, 'message' => 'Absence marquée avec succès.'];
        } else {
            $response = ['success' => false, 'message' => 'Erreur lors de l\'insertion dans la table d\'absence: ' . $conn->error];
        }
    } else {
        $response = ['success' => false, 'message' => 'L\'étudiant n\'est pas marqué absent.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Module non trouvé.'];
}

$conn->close();
echo json_encode($response); // Envoyer la réponse JSON
?>