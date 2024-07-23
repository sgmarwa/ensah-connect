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
            
           
            $sql = "SELECT annonce.titre_annonce, annonce.date_publication, annonce.fichier_associé , professeur.nomETprenom
                    FROM annonce JOIN professeur 
                    WHERE  annonce.id_professeur = professeur.id_professeur AND archive  = 0 AND annonce.id_filiere ='$id_filiere'";
            $result = $conn->query($sql);
            $sql = "SELECT annonce_admin.titre_annonce, annonce_admin.date_publication, annonce_admin.fichier_associé , personnel.nomETprenom
                    FROM annonce_admin JOIN personnel 
                    WHERE  annonce_admin.id_personnel = personnel.id_personnel AND archive  = 0 AND annonce_admin.id_filiere ='$id_filiere'";
            $result_admin = $conn->query($sql);
             
        
        }   
    }
}     
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>annonces</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php include '../nav/navbar.html'; ?>

</head>

<body>
    <main class="table" id="notes_table">
        <section class="table__header">
            <h1>Annonces </h1>
            <div class="input-group">
                <input type="search" placeholder=" chercher ...">
                <img src="../les ressources/loop.avif" alt="">
            </div>
             
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Titre de l'annonce  <span class="icon-arrow">&UpArrow;</span></th>
                        <th> le nom de l'annonceur  <span class="icon-arrow">&UpArrow;</span></th>
                        <th> date de publication <span class="icon-arrow">&UpArrow;</span></th>
                        <th> fichier de l'annonce   <span class="icon-arrow">&UpArrow;</span></th>
                         
                         
                    </tr>
                </thead>

                <tbody>
                
                 
                <?php 
                if (isset($result) && $result->num_rows > 0) {
                    // Afficher les annonces dans un tableau HTML
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["titre_annonce"] . "</td>";
                        echo "<td>" . $row["nomETprenom"] . "</td>"; 
                        echo "<td>" . $row["date_publication"] . "</td>";
                        // Utiliser $row["titre_annonce"] pour le lien de téléchargement
                        echo "<td><a href='telecharger_annonce.php?titre_annonce=" . urlencode($row["titre_annonce"]) . "'>Télécharger</a></td>";
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune annonce trouvée</td></tr>";
                }
                ?>
                <?php 
                if (isset($result_admin) && $result_admin->num_rows > 0) {
                    // Afficher les annonces dans un tableau HTML
                    while($row = $result_admin->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["titre_annonce"] . "</td>";
                        echo "<td>" . $row["nomETprenom"] . "</td>"; 
                        echo "<td>" . $row["date_publication"] . "</td>";
                        // Utiliser $row["titre_annonce"] pour le lien de téléchargement
                        echo "<td><a href='telecharger_annonce.php?titre_annonce=" . urlencode($row["titre_annonce"]) . "'>Télécharger</a></td>";
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune annonce_admin trouvée</td></tr>";
                }
                ?>
                
            </tbody>
                     
            </table>  
        </section>
    </main>

    <script src="../les ressources/main.js"></script>

</body>

</html>
