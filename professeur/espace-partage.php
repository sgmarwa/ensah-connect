<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = require 'database.php';
   

    $filiere = $_POST['filiere'];
    $module = $_POST['module'];
    $action = $_POST['action'];
    $id_professeur = $_SESSION['id_professeur'];
    $file = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_path = "uploads/" . basename($file);
    $archive_date = $_POST['archive_date'];

    if ($action == 'annonce') {
        $titre_annonce = $_POST['titre_annonce'];
        $message = $_POST['message'];

        if (empty($message) && empty($file)) {
            $error_message = "Veuillez remplir le champ message ou télécharger un fichier.";
        } else {
            if (!empty($file) && !move_uploaded_file($file_tmp, $file_path)) {
                $error_message = "Erreur lors du téléchargement du fichier.";
            } else {
                $file_path = empty($file) ? NULL : $file_path;
                $sql_filiere = "SELECT id_filiere FROM filiere WHERE nom_filiere = '$filiere'";
                $result_filiere = $conn->query($sql_filiere);
                $row_filiere = $result_filiere->fetch_assoc();
                $id_filiere = $row_filiere['id_filiere'];

                $sql = "INSERT INTO annonce (titre_annonce, message, id_professeur, id_filiere, fichier_associé, archive_date) 
                        VALUES ('$titre_annonce', '$message', '$id_professeur', '$id_filiere', '$file_path', '$archive_date')";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Nouvel enregistrement créé avec succès";
                } else {
                    $error_message = "Erreur: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    } else {
        if (!move_uploaded_file($file_tmp, $file_path)) {
            $error_message = "Erreur lors du téléchargement du fichier.";
        } else {
            $sql_filiere = "SELECT id_filiere FROM filiere WHERE nom_filiere = '$filiere'";
            $result_filiere = $conn->query($sql_filiere);
            $row_filiere = $result_filiere->fetch_assoc();
            $id_filiere = $row_filiere['id_filiere'];

            $sql_module = "SELECT id_module FROM module WHERE nom_module = '$module'";
            $result_module = $conn->query($sql_module);
            $row_module = $result_module->fetch_assoc();
            $id_module = $row_module['id_module'];

            if ($action == 'cours' || $action == 'td' || $action == 'tp') {
                $nom_ressource = $_POST['nom_ressource'];
                $sql = "INSERT INTO ressources (id_professeur, id_module, id_filiere, type_ressource, fichier_ressource, nom_ressource, archive_date) 
                        VALUES ('$id_professeur', '$id_module', '$id_filiere', '$action', '$file_path', '$nom_ressource', '$archive_date')";
            } elseif ($action == 'devoir') {
                $nom_devoir = $_POST['devoir_name'];
                $date_limite = $_POST['deadline'];
                $sql = "INSERT INTO devoir (nom_devoir, fichier_devoir, id_module, id_filiere, date_limite, archive_date) 
                        VALUES ('$nom_devoir', '$file_path', '$id_module', '$id_filiere', '$date_limite', '$archive_date')";
            }

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
    <title>Espace partage</title>
    <link rel="stylesheet" href="partage.css">
</head>
<body>
    <div class="container">
        <h1>GESTION DES RESSOURCES</h1>
        <?php
        if (!empty($success_message)) {
            echo '<p style="color:green;">'.$success_message.'</p>';
        }
        if (!empty($error_message)) {
            echo '<p style="color:red;">'.$error_message.'</p>';
        }
        ?>
        <form id="resourceForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="filiere">Filière :</label>
                <select name="filiere" id="filiere">
                    <?php
                    session_start();
                    require 'database.php';

                    $id_professeur = $_SESSION['id_professeur'];
                    $sql_filiere = "SELECT DISTINCT f.nom_filiere
                                    FROM filiere f
                                    INNER JOIN professeur_filiere pf ON f.id_filiere = pf.id_filiere
                                    WHERE pf.id_professeur = '$id_professeur'";
                    $result_filiere = $conn->query($sql_filiere);

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
                    $sql = "SELECT m.nom_module
                            FROM module m
                            INNER JOIN professeur_filiere pf ON m.id_module = pf.id_module
                            WHERE pf.id_professeur = '$id_professeur'";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['nom_module'] . "'>" . $row['nom_module'] . "</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="action">Type de ressource :</label>
                <select name="action" id="action">
                    <option value="cours">COURS</option>
                    <option value="td">TD</option>
                    <option value="tp">TP</option>
                    <option value="devoir">DEVOIR</option>
                    <option value="annonce">ANNONCE</option>
                </select>
            </div>

            <div class="form-group devoir-info" style="display: none;">
                <label for="devoir_name">Nom du devoir :</label>
                <input type="text" name="devoir_name" id="devoir_name">
                <label for="deadline">Date limite :</label>
                <input type="date" name="deadline" id="deadline">
            </div>

            <div class="form-group annonce-info" style="display: none;">
                <label for="titre_annonce">Titre de l'annonce :</label>
                <input type="text" name="titre_annonce" id="titre_annonce">
                <label for="message">Message :</label>
                <textarea name="message" id="message" rows="5" cols="100"></textarea>
            </div>

            <div class="form-group ressource-info" style="display: none;">
                <label for="nom_ressource">Nom de la ressource :</label>
                <input type="text" name="nom_ressource" id="nom_ressource">
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
            <a href="espace-prof.php"><button type="button">Revenir à la page d'acceuil</button></a>
        </form>
    </div>
    <script>
        const actionSelect = document.querySelector('#action');
        const devoirInfo = document.querySelector('.devoir-info');
        const annonceInfo = document.querySelector('.annonce-info');
        const ressourceInfo = document.querySelector('.ressource-info');

        actionSelect.addEventListener('change', () => {
            if (actionSelect.value === 'devoir') {
                devoirInfo.style.display = 'block';
                annonceInfo.style.display = 'none';
                ressourceInfo.style.display = 'none';
            } else if (actionSelect.value === 'annonce') {
                annonceInfo.style.display = 'block';
                devoirInfo.style.display = 'none';
                ressourceInfo.style.display = 'none';
            } else if (actionSelect.value === 'cours' || actionSelect.value === 'td' || actionSelect.value === 'tp') {
                ressourceInfo.style.display = 'block';
                annonceInfo.style.display = 'none';
                devoirInfo.style.display = 'none';
            } else {
                devoirInfo.style.display = 'none';
                annonceInfo.style.display = 'none';
                ressourceInfo.style.display = 'none';
            }
        });

        function validateForm() {
            const action = actionSelect.value;
            const message = document.querySelector('#message').value.trim();
            const file = document.querySelector('#file').value;

            if (action === 'annonce' && !message && !file) {
                alert("Veuillez remplir le champ message ou télécharger un fichier.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
