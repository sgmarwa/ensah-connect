<?php
session_start();

if(isset($_GET['titre_annonce'])) {
    // Récupérer l'ID du module depuis l'URL
    $titre_annonce = $_GET['titre_annonce'];

    require '../../prof/database.php';
    
    // Requête SQL pour récupérer le fichier ressource en fonction de l'ID du module
    $sql = "SELECT fichier_associé FROM annonce WHERE titre_annonce = '$titre_annonce'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Récupérer le contenu du fichier ressource depuis la base de données
        $file_content = $row["fichier_associé"];
        
        // Envoyer les en-têtes HTTP pour indiquer un téléchargement de fichier
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="fichier_annonce.pdf"');
        
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
