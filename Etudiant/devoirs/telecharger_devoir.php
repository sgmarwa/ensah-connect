<?php
session_start();

if(isset($_GET['id_module'])) {
    // Récupérer l'ID du module depuis l'URL
    $id_module = $_GET['id_module'];

    require '../../prof/database.php';
    
    // Requête SQL pour récupérer le fichier ressource en fonction de l'ID du module
    $sql = "SELECT fichier_devoir FROM devoir WHERE id_module = '$id_module'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Récupérer le contenu du fichier ressource depuis la base de données
        $file_content = $row["fichier_devoir"];
        
        // Envoyer les en-têtes HTTP pour indiquer un téléchargement de fichier
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="fichier_ressource.pdf"');
        
        // Sortir le contenu du fichier ressource
        echo $file_content;
    } else {
        echo "Aucun fichier ressource trouvé.";
    }
    
    $conn->close();
} else {
    echo "ID du module non spécifié.";
}
?>
