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


    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Ignorer la ligne d'en-tête

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // Vérifier que la ligne contient exactement 7 colonnes
            if (count($data) != 9) {
                $error_message = "Erreur : La ligne suivante ne contient pas 7 colonnes : " . implode("; ", $data);
                break;
            }

            $id_etudiant = $data[0];
            $nomETprenom = $data[1];
            $email = $data[2];
            $password = hash('sha256',$data[3]);
            $photo = $data[4];
            $id_filiere = $data[5];
            $niveau_actuel = $data[6];
            $genre = $data[7];
            $tel = $data[8];

            $sql = "INSERT INTO etudiant (id_etudiant, nomETprenom, email, mot_de_passe, photo, id_filiere, niveau_actuel,genre,tel)
                    VALUES (?, ?, ?, ?, ?, ?, ?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $id_etudiant, $nomETprenom, $email, $password, $photo, $id_filiere, $niveau_actuel,$genre,$tel);

            if (!$stmt->execute()) {
                $error_message = "Erreur : " . $stmt->error;
                break;
            }
        }

        fclose($handle);

        if (empty($error_message)) {
            $success_message = "Étudiants importés avec succès.";
        }
    } else {
        $error_message = "Erreur lors de l'ouverture du fichier.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importer Étudiants</title>
    <link rel="stylesheet" href="importer.css">
</head>
<body>
    <div class="container">
        <h1>Importer Étudiants</h1>
        <?php
        if (!empty($success_message)) {
            echo '<p style="color:green;">' . $success_message . '</p>';
        }
        if (!empty($error_message)) {
            echo '<p style="color:red;">' . $error_message . '</p>';
        }
        ?>
        <form action="importer_etudiants.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Choisir un fichier CSV :</label>
                <input type="file" name="file" id="file" required>
            </div>
            <button type="submit">Importer</button>
            <a href="espace_admin.php"><button type="button">Quitter</button></a>
        </form>
    </div>
</body>
</html>


