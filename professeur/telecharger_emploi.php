<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }
require 'database.php';

$nom_filiere = $_POST['filiere'];


// Récupérer l'id_filiere à partir du nom de la filière
$sql_filiere = "SELECT id_filiere FROM filiere WHERE nom_filiere = ?";
$stmt_filiere = $conn->prepare($sql_filiere);
$stmt_filiere->bind_param("s", $nom_filiere);
$stmt_filiere->execute();
$stmt_filiere->bind_result($id_filiere);
$stmt_filiere->fetch();
$stmt_filiere->close();

if (!$id_filiere) {
    die("Filière non trouvée.");
}

$sql_semestre = "SELECT id_semestre FROM semestre LIMIT 1"; // Ajout d'une limite pour obtenir une seule valeur
        $result_semestre = $conn->query($sql_semestre); 
        if ($result_semestre->num_rows > 0) {
            $row_semestre = $result_semestre->fetch_assoc();
            $id_semestre = $row_semestre['id_semestre'];
        } else {
            echo "Valeur de l'ID du semestre invalide";
            exit;
        }

// Requête pour obtenir le fichier et le nom du fichier
$sql = "SELECT fichier FROM emploi_du_temps WHERE id_filiere = ? AND id_semestre ='$id_semestre'";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $id_filiere);
$stmt->execute();
$stmt->bind_result($fichier);
$stmt->fetch();
$nom_fichier ="emploi_du_temps";

if ($fichier) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $nom_fichier . '.pdf' );
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($fichier));
    echo $fichier;
    exit;
} else {
    echo "Aucun fichier trouvé pour la sélection donnée.";
}

$stmt->close();
$conn->close();
?>