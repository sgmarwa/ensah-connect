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
    $id_personnel = $_SESSION['id_personnel'];
    $titre_annonce = $_POST['titre_annonce'];
    $file = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_path = basename($file);
    $archive_date = $_POST['archive_date'];

    if (empty($message) && empty($file)) {
        $error_message = "Veuillez remplir le champ message ou télécharger un fichier.";
    } else {
        if (!empty($file) && !move_uploaded_file($file_tmp, $file_path)) {
            $error_message = "Erreur lors du téléchargement du fichier.";
        } else {
            $file_path = empty($file) ? NULL : $file_path;

            $sql = "INSERT INTO annonce_admin (titre_annonce, id_personnel, id_filiere, fichier_associé, archive_date) 
                    VALUES ('$titre_annonce',  '$id_personnel', '$filiere', '$file_path', '$archive_date')";

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
    <title>Espace annonces</title>
    <link rel="stylesheet" href="partage.css">
</head>
<body>
    <div class="container">
        <h1>PARTAGE D'ANNONCES</h1>
        <?php
        if (!empty($success_message)) {
            echo '<p style="color:green;">'.$success_message.'</p>';
        }
        if (!empty($error_message)) {
            echo '<p style="color:red;">'.$error_message.'</p>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="filiere">Filière :</label>
                <select name="filiere" id="filiere">
                    <?php
                    session_start();
                    $conn = require '../prof/database.php';

                    $id_professeur = $_SESSION['id_professeur'];
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
                <label for="titre_annonce">Titre de l'annonce :</label>
                <input type="text" name="titre_annonce" id="titre_annonce" required>
            </div>
            <div class="form-group">
                <label for="archive_date">Date d'archivage :</label>
                <input type="date" name="archive_date" id="archive_date" required>
            </div>
            <div class="form-group">
                <label for="file">Télécharger un fichier :</label>
                <input type="file" name="file" id="file">
            </div>
            <button type="submit">Valider</button>
            <a href="espace_admin.php"><button type="button">Revenir à la page d'accueil</button></a>
        </form>
    </div>
    <script>
        function validateForm() {
            const message = document.querySelector('#message').value.trim();
            const file = document.querySelector('#file').value;

            if (!message && !file) {
                alert("Veuillez remplir le champ message ou télécharger un fichier.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
