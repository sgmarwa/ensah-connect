<?php
session_start();

if (isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer l'ID du module sélectionné et les remarques
        $module_id = $_POST['module_id'];
        $remarques = $_POST['remarques'];
        
        // Gérer le fichier uploadé
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Créer le répertoire avec les permissions appropriées
        }
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est d'un format valide
        $valid_extensions = ["pdf", "doc", "docx", "zip"];
        if (!in_array($fileType, $valid_extensions)) {
            $_SESSION['message'] = "Seuls les fichiers PDF, DOC, DOCX et ZIP sont autorisés.";
            $_SESSION['message_type'] = 'error';
            $uploadOk = 0;
        }

        if ($uploadOk && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            
            require '../../../prof/database.php';

            if ($conn->connect_error) {
                $_SESSION['message'] = "Connection failed: " . $conn->connect_error;
                $_SESSION['message_type'] = 'error';
                header('Location: first_page.php'); // Rediriger vers la première page
                exit;
            }

            // Vérifier si le module existe
            $module_check_sql = "SELECT * FROM module WHERE id_module = ?";
            $stmt = $conn->prepare($module_check_sql);
            $stmt->bind_param("s", $module_id);
            $stmt->execute();
            $module_result = $stmt->get_result();

            if ($module_result->num_rows > 0) {
                $datestamp = date("Y-m-d H:i:s");

                // Insérer les données dans la table 'rendu'
                $sql = "INSERT INTO rendu (id_etudiant, id_module, nom_devoir, fichier_rendu, date_rendu) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $id_etudiant, $module_id, $remarques, $target_file, $datestamp);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Rendu soumis avec succès!";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "Erreur: " . $stmt->error;
                    $_SESSION['message_type'] = 'error';
                }

                $stmt->close();
            } else {
                $_SESSION['message'] = "Module non trouvé.";
                $_SESSION['message_type'] = 'error';
            }

            $conn->close();
        } else {
            if ($uploadOk == 0) {
                $_SESSION['message'] = "Erreur: Fichier non valide.";
                $_SESSION['message_type'] = 'error';
            } else {
                $_SESSION['message'] = "Erreur lors du téléchargement du fichier.";
                $_SESSION['message_type'] = 'error';
            }
        }

        header('Location: xx.php'); // Rediriger vers la première page
        exit;
    }
} else {
    $_SESSION['message'] = "ID étudiant non défini.";
    $_SESSION['message_type'] = 'error';
    header('Location: xx.php'); // Rediriger vers la première page
    exit;
}
?>
