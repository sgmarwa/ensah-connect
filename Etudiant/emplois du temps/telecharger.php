<?php
session_start();

if(isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];
    
    require '../../prof/database.php';
    
    // Requête SQL pour récupérer l'ID de la filière de l'étudiant
    $sql_filiere = "SELECT id_filiere FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result_filiere = $conn->query($sql_filiere);
    
    if ($result_filiere->num_rows > 0) {
        // Récupérer l'ID de la filière
        $row_filiere = $result_filiere->fetch_assoc();
        $id_filiere = $row_filiere['id_filiere'];
        
        $sql_semestre = "SELECT id_semestre FROM semestre LIMIT 1"; // Ajout d'une limite pour obtenir une seule valeur
        $result_semestre = $conn->query($sql_semestre); 
        if ($result_semestre->num_rows > 0) {
            $row_semestre = $result_semestre->fetch_assoc();
            $id_semestre = $row_semestre['id_semestre'];
        } else {
            echo "Valeur de l'ID du semestre invalide";
            exit;
        }

        
        // Requête SQL pour récupérer le fichier d'emploi du temps 
        $sql = "SELECT fichier FROM emploi_du_temps WHERE id_filiere = '$id_filiere' AND id_semestre ='$id_semestre' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch the file content
            $row = $result->fetch_assoc();
            $file_content = $row["fichier"];
            
            // Send HTTP headers for file download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="emploi_du_temps.pdf"');
            
            // Output the file content
            echo $file_content;
        } else {
            echo "Aucun fichier trouvé pour cette filière.";
        }
    } else {
        echo "ID de l'étudiant non spécifié.";
    }

    $conn->close();
} else {
    echo "ID de l'étudiant non spécifié.";
}
?>
