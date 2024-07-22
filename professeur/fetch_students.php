<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

if (!isset($_GET['filiere'])) {
    echo json_encode(["error" => "La filière n'est pas spécifiée."]);
    exit();
}

$filiere = $_GET['filiere'];

require 'database.php';

$sql = "SELECT etudiant.photo, etudiant.id_etudiant, etudiant.nomETprenom
        FROM etudiant
        JOIN filiere ON etudiant.id_filiere = filiere.id_filiere
        WHERE filiere.nom_filiere = '$filiere'";

$result = $conn->query($sql);
$students = array();

if ($result === FALSE) {
    die(json_encode(["error" => "Erreur SQL : " . $conn->error]));
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Encode the photo in base64
        if ($row['photo']) {
            $row['photo'] = base64_encode($row['photo']);
        }
        $students[] = $row;
    }
}

// Output students as JSON
echo json_encode($students);

$conn->close();
?>


