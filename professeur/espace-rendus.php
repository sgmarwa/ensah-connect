<!-- espace-rendus.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace rendus</title>
    <link rel="stylesheet" href="abscence.css">
</head>
<body>
    <div class="container">
        <h1>GESTION DES RENDUS</h1>
        <form action="rendus.php" method="post">
            <div class="form-group">
                <label for="filiere">Filière :</label>
                <select name="filiere" id="fiiere">
                    <?php
                   
                   session_start();


                   if (!isset($_SESSION['id_professeur'])) {
                       header("Location: ../index.php");
                       exit();
                   }
                    require 'database.php';

                    // Récupération des filières enseignées par le professeur
                    $id_professeur = $_SESSION['id_professeur'];
                    $sql_filiere = "SELECT DISTINCT f.nom_filiere
                                     FROM filiere f
                                     INNER JOIN professeur_filiere pf ON f.id_filiere = pf.id_filiere
                                      WHERE pf.id_professeur = '$id_professeur'";
                    $result_filiere = $conn->query($sql_filiere);

                    // Affichage des filières dans la liste déroulante
                    while ($row_filiere = $result_filiere->fetch_assoc()) {
                        echo "<option value='" . $row_filiere['nom_filiere'] . "'>" . $row_filiere['nom_filiere'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="module">Module :</label>
                <select name="module" id="module">
                    <?php
                  require 'database.php';

                    // Requête SQL pour récupérer les modules enseignés par le professeur connecté
                    $sql = "SELECT m.nom_module
                            FROM module m
                            INNER JOIN professeur_filiere pf ON m.id_module = pf.id_module
                            WHERE pf.id_professeur = '$id_professeur'";

                    $result = $conn->query($sql);

                    // Affichage des modules dans la liste déroulante
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['nom_module'] . "'>" . $row['nom_module'] . "</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            
            <button type="submit">Valider</button>
        </form>
    </div>
</body>
</html>