<?php
session_start();
// Vérifie si l'utilisateur est connecté en vérifiant si l'ID de l'étudiant est dans la session
if(isset($_SESSION['id_etudiant'])) {
    // Récupère l'ID de l'étudiant depuis la session
    $id_etudiant = $_SESSION['id_etudiant'];
    
    require '../../prof/database.php';

    // Requête SQL pour récupérer les données de l'étudiant, y compris l'image de profil
    $sql = "SELECT *, photo FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupération des données de l'étudiant
        $row = $result->fetch_assoc();
        // Vous pouvez accéder aux données de l'étudiant à partir de $row ici
        
    } else {
        echo "Aucune donnée d'étudiant trouvée";
    }
    
    // Fermeture de la connexion à la base de données
    $conn->close();
} 

else {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: page_de_connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.cdnfonts.com/css/brittany-signature" rel="stylesheet">
    <?php include '../nav/navbar.html'; ?>
</head>
<body>
    <div class="profile-container"> 
        <p class="id2"> mon profil </p>
        <div class="profile-picture">
            <!-- Affichage de l'image de profil -->
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['photo']); ?>" alt="Image de profil">
        </div>
        <div class="profile-info">
            <div class="info">
                <label for="fullname">Nom Complet:</label>
                <span id="fullname" contenteditable="false">  <?php echo isset($row['nomETprenom']) ? $row['nomETprenom'] : ''; ?> </span>
            </div>
            <div class="info">
                <label for="phone">Num de Tel :</label>
                <span id="phone" contenteditable="false"><?php echo isset($row['tel']) ? $row['tel'] : ''; ?></span>
            </div>
            <div class="info">
                <label for="gender">genre :</label>
                <span id="gender" contenteditable="false"><?php echo isset($row['genre']) ? $row['genre'] : ''; ?></span>
            </div>
            <div class="info">
                <label for="email">email :</label>
                <span id="email" contenteditable="false"><?php echo isset($row['email']) ? $row['email'] : ''; ?></span>
            </div>
            <div class="info">
                <label for="filiere">filiere :</label>
                <span id="filiere" contenteditable="false"><?php echo isset($row['id_filiere']) ? $row['id_filiere'] : ''; ?></span>
            </div>
        </div>
    </div>
    
   
</body>
</html>
