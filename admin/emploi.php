<?php
  session_start();


  if (!isset($_SESSION['id_personnel'])) {
      header("Location: ../index.php");
      exit();
  }

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = require '../prof/database.php';

    $filiere = $_POST['filiere'];
    $semestre = $_POST['semestre'];
    $file = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_path = basename($file);

    if (empty($file)) {
        $error_message = "Veuillez télécharger un fichier.";
    } else {
        if (!move_uploaded_file($file_tmp, $file_path)) {
            $error_message = "Erreur lors du téléchargement du fichier.";
        } else {


            $sql = "INSERT INTO emploi_du_temps (id_filiere, id_semestre, fichier) 
                    VALUES ('$filiere', '$semestre', '$file_path')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Nouvel enregistrement créé avec succès";
            } else {
                $error_message = "Erreur: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partage des emplois du temps</title>
    <link rel="stylesheet" href="partage.css">
</head>
<body>
    <div class="container">
        <h1>PARTAGE DES EMPLOIS DU TEMPS</h1>
        <?php
        if (!empty($success_message)) {
            echo '<p style="color:green;">'.$success_message.'</p>';
        }
        if (!empty($error_message)) {
            echo '<p style="color:red;">'.$error_message.'</p>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="filiere">Filière :</label>
                <select name="filiere" id="filiere">
                    <?php
                    session_start();
                    $conn = require '../prof/database.php';

                    $id_professeur = $_SESSION['id_personnel'];
                    $sql_filiere = "SELECT id_filiere
                                    FROM filiere ";
                    $result_filiere = $conn->query($sql_filiere);

                    while ($row_filiere = $result_filiere->fetch_assoc()) {
                        echo "<option value='" . $row_filiere['id_filiere'] . "'>" . $row_filiere['id_filiere'] . "</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre :</label>
                <select name="semestre" id="semestre">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file">Télécharger un fichier :</label>
                <input type="file" name="file" id="file" required>
            </div>
            <button type="submit">Valider</button>
            <a href="espace_admin.php"><button type="button">Revenir à la page d'accueil</button></a>
        </form>
    </div>
</body>
</html>