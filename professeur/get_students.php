

<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }


if (!isset($_GET['filiere'])) {
    echo json_encode(['error' => 'Erreur : La filière n\'est pas spécifiée.']);
    exit();
}

$selectedFiliere = $_GET['filiere'];

require 'database.php';

$sql = "SELECT etudiant.id_etudiant, etudiant.nomETprenom
        FROM etudiant
        JOIN filiere ON etudiant.id_filiere = filiere.id_filiere
        WHERE filiere.nom_filiere = '$selectedFiliere'";

$result = $conn->query($sql);
$students = array();

if ($result === FALSE) {
    die("Erreur SQL : " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Output students as JSON
echo json_encode($students);

$conn->close();
