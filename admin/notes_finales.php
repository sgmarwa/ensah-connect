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
        <h1>GESTION DES NOTES </h1>
        <form action="notes.php" method="post">
            <div class="form-group">
                <label for="filiere">Filière :</label>
                <select name="filiere" id="fiiere">
                    <?php
                    session_start();


                    if (!isset($_SESSION['id_personnel'])) {
                        header("Location: ../index.php");
                        exit();
                    }
                    require '../prof/database.php';

                    // Récupération des filières 
                    $sql_filiere = "SELECT id_filiere
                                     FROM filiere ";
                    $result_filiere = $conn->query($sql_filiere);

                    // Affichage des filières dans la liste déroulante
                    while ($row_filiere = $result_filiere->fetch_assoc()) {
                        echo "<option value='" . $row_filiere['id_filiere'] . "'>" . $row_filiere['id_filiere'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Valider</button>
        </form>
    </div>
</body>
</html>