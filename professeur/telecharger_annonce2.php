<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

if (isset($_GET['nom_devoir'])) {
    $nom_devoir = $_GET['nom_devoir'];

    require 'database.php';

    // Fetch the student's ID and file content based on the assignment name
    $sql = "SELECT id_etudiant, fichier_rendu FROM rendu WHERE nom_devoir = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nom_devoir);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_etudiant = $row['id_etudiant'];
        $file_content = $row["fichier_rendu"];

        // Fetch the student's name
        $sql_student = "SELECT nomETprenom FROM etudiant WHERE id_etudiant = ?";
        $stmt_student = $conn->prepare($sql_student);
        $stmt_student->bind_param("i", $id_etudiant);
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();

        if ($result_student->num_rows > 0) {
            $row_student = $result_student->fetch_assoc();
            $student_name = $row_student['nomETprenom'];

            // Set the filename as "student_name_nom_devoir.pdf"
            $filename = $nom_devoir . '.pdf';
            
            // Replace spaces with underscores for the filename
            $filename = str_replace(' ', '_', $filename);

            // Output headers to indicate a file download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($file_content));

            // Output the file content
            echo $file_content;
        } else {
            echo "Erreur : étudiant non trouvé.";
        }

        $stmt_student->close();
    } else {
        echo "Aucun fichier rendu trouvé pour ce devoir.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Nom du devoir non spécifié.";
}
?>