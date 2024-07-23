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
        
        // Requête SQL pour récupérer le nom de la filière de l'étudiant
        $sql_nomfiliere = "SELECT nomfiliere FROM filiere WHERE id_filiere = '$id_filiere'";
        $result_nomfiliere = $conn->query($sql_nomfiliere);
        
        if ($result_nomfiliere->num_rows > 0) {
            // Récupérer le nom de la filière
            $row_nomfiliere = $result_nomfiliere->fetch_assoc();
            $nomfiliere = $row_nomfiliere['nomfiliere'];
            
             
            
            // Requête SQL pour récupérer les noms des modules et des professeurs en fonction du nom de la filière de l'utilisateur connecté
            $sql = "SELECT module.id_module, module.nom_module, professeur.nomETprenom
        FROM module
        INNER JOIN professeur_filiere ON module.id_module = professeur_filiere.id_module
        INNER JOIN professeur ON professeur_filiere.id_professeur = professeur.id_professeur 
        WHERE professeur_filiere.id_filiere = '$id_filiere' AND module.id_module LIKE CONCAT('$nomfiliere', '1%')";

                     
                     
            $result = $conn->query($sql);
            
            // Vérifier si le résultat de la requête est valide
            if ($result->num_rows > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Modules et Professeurs</title>
    <link rel="stylesheet" href="card.css">
    <?php include '../nav/navbar.html'; ?>
</head>
<body>
 
    <div class="wrapper">
        <div class="cols">
        <?php while($row = $result->fetch_assoc()) { ?>
    <div class="col" ontouchstart="this.classList.toggle('hover');">
        <div class="container">
            <div class="front" style="background-image: url(back.png)">
                <div class="inner">
                    <p><?php echo $row["nom_module"]; ?></p>
                    <span><?php echo $row["nomETprenom"]; ?></span>
                </div>
            </div>
            <div class="back">
                <div class="inner">
                    <!-- Lien vers la page 2 avec l'ID du module comme paramètre -->
                    <a href="../ressources?id_module=<?php echo $row['id_module'];?> ">Voir les ressources</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

        </div>
    </div>
</body>
</html>
<?php
            } else {
                echo "Aucun module trouvé pour cette filière.";
            }
        } else {
            echo "Aucun nom de filière trouvé pour cet étudiant";
        }
    } else {
        echo "Aucune filière trouvée pour cet étudiant";
    }

    $conn->close();
} else {
    header("Location: page_de_connexion.php");
    exit();
}
?>
