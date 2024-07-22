

<?php
session_start();

if (!isset($_SESSION['id_professeur'])) {
    header("Location: ../index.php");
    exit();
}
require 'database.php';

// Vérifier si le titre de l'annonce est spécifié dans l'URL
if (!isset($_GET['titre_annonce'])) {
    echo "Erreur : le titre de l'annonce n'est pas spécifié.";
    exit();
}
$titre_annonce = $_GET['titre_annonce'];

// Préparer la requête pour récupérer le fichier associé à l'annonce
$sql = "SELECT fichier_associé FROM annonce WHERE titre_annonce = ? UNION SELECT fichier_associé FROM annonce_admin WHERE titre_annonce = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $titre_annonce, $titre_annonce);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si le fichier est trouvé
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fichier = $row['fichier_associé'];

    // Spécifier les en-têtes pour le téléchargement du fichier
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($fichier) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fichier));

    // Nettoyer la sortie du tampon et lire le fichier pour le téléchargement
    ob_clean();
    flush();
    readfile($fichier);

    exit();
} else {
    echo "Erreur : le fichier associé à l'annonce n'a pas été trouvé.";
}

$conn->close();
?>
