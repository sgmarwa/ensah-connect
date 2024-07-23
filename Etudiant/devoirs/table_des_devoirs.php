<?php
session_start();

if(isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];
    $id_module = $_GET['id_module'];

    require '../../prof/database.php';
    
    // Requête SQL pour récupérer l'ID de la filière de l'étudiant
    $sql_filiere = "SELECT id_filiere FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result_filiere = $conn->query($sql_filiere);
    
    if ($result_filiere->num_rows > 0) {
        $row_filiere = $result_filiere->fetch_assoc();
        $id_filiere = $row_filiere['id_filiere'];
        
        // Requête SQL pour récupérer les ressources pédagogiques
        $sql_devoir = "SELECT nom_devoir, date_limite, fichier_devoir
                          FROM devoir
                          WHERE id_filiere = '$id_filiere' AND id_module = '$id_module' AND archive  = 0 ";
        
        $result_devoir = $conn->query($sql_devoir); // Exécute la requête SQL pour récupérer les ressources

        if ($result_devoir === false) {
            die("Erreur SQL: " . $conn->error);
        }
    }  
    $conn->close();
} else {
    header("Location: page_de_connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mes ressources</title>
    <link rel="stylesheet" href="../ressources/style.css">
    <?php include '../nav/navbar.html'; ?>
</head>

<body>
    <main class="table" id="notes_table">
        <section class="table__header">
            <h1>les devoirs </h1>
            <div class="input-group">
                <input type="search" placeholder=" chercher ...">
                <img src="../ressources/loop.avif" alt="">   
            </div>
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Titre du devoir <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Date limite de soumission <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Document du devoir  <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody> 
                <?php 
                if (isset($result_devoir) && $result_devoir->num_rows > 0) {
                    // Afficher les ressources dans un tableau HTML
                    while($row = $result_devoir->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["nom_devoir"] . "</td>";
                        echo "<td>" . $row["date_limite"] . "</td>";
                        // Vérifier si $id_module est défini et afficher sa valeur
                        if(isset($id_module)) {
                            echo "<td><a href='telecharger_devoir.php?id_module=" . $id_module . "&nom_devoir=" . urlencode($row["nom_devoir"]) . "'>Télécharger</a></td>";
                        } else {
                            echo "<td>Erreur: id_module non défini</td>";
                        }
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucune ressource trouvée</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
    <script src="../ressources/main.js"></script>
</body>

</html>
