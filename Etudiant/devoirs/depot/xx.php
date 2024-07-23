<?php
session_start();

if (isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];

    require '../../../prof/database.php';

    // Récupérer l'ID du semestre de l'étudiant
    $sql_semestre = "SELECT id_semestre FROM semestre LIMIT 1"; // Ajout d'une limite pour obtenir une seule valeur
    $result_semestre = $conn->query($sql_semestre); 
    if ($result_semestre->num_rows > 0) {
        $row_semestre = $result_semestre->fetch_assoc();
        $id_semestre = $row_semestre['id_semestre'];
    } else {
        echo "Valeur de l'ID du semestre invalide";
        exit;
    }

    // Récupérer l'ID de la filière de l'étudiant
    $sql_filiere = "SELECT id_filiere FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result_filiere = $conn->query($sql_filiere);
    
    if ($result_filiere->num_rows > 0) {
        $row_filiere = $result_filiere->fetch_assoc();
        $id_filiere = $row_filiere['id_filiere'];

        // Récupérer le nom de la filière
        $sql_nomfiliere = "SELECT nomfiliere FROM filiere WHERE id_filiere = '$id_filiere'";
        $result_nomfiliere = $conn->query($sql_nomfiliere);
        
        if ($result_nomfiliere->num_rows > 0) {
            $row_nomfiliere = $result_nomfiliere->fetch_assoc();
            $nomfiliere = $row_nomfiliere['nomfiliere'];

            // Requête SQL pour récupérer les noms des modules en fonction du nom de la filière et du semestre de l'utilisateur connecté
            $sql = "SELECT nom_module, id_module FROM module WHERE id_module LIKE '$nomfiliere$id_semestre%' AND id_filiere='$id_filiere'";
            $result = $conn->query($sql);

        } else {
            echo "Aucun nom de filière trouvé pour cet étudiant";
            exit;
        }
    } else {
        echo "Aucune filière trouvée pour cet étudiant";
        exit;
    }

    $conn->close();
} else {
    echo "ID étudiant non défini";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Devoir</title>
  <link rel="stylesheet" href="xx.css">
  <?php include 'nav/navbar.html'; ?>
</head>
<body>
  

  <form action="envois_de_rendu.php" method="post" enctype="multipart/form-data">  
    <div class="container">
        <?php
   

  if (isset($_SESSION['message'])) {
      $message = $_SESSION['message'];
      $messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; // Par défaut à 'info' si non défini
      
      // Déterminer la classe CSS basée sur le type de message
      $messageClass = 'message';
      if ($messageType === 'success') {
          $messageClass .= ' success';
      } elseif ($messageType === 'error') {
          $messageClass .= ' error';
      }
      
      echo '<div class="'. $messageClass .'">'. $message .'</div>';
      unset($_SESSION['message']); // Supprimer le message après l'affichage
      unset($_SESSION['message_type']); // Supprimer le type de message après l'affichage
  }
  ?>
      <div class="mdl">
        <h2>Module</h2>
        <div class="select-box">
          <select name="module_id">
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { 
                    $nom_module = $row['nom_module'];
                    $id_module = $row['id_module'];  
            ?>
                    <option value="<?php echo $id_module; ?>">
                        <?php echo $nom_module; ?>
                    </option>
            <?php 
                }
            } else {
                echo "<option value=''>Aucun module trouvé pour cette filière et ce semestre.</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <div class="wrapper">
          <h2>le nom du devoir </h2>
          <textarea class="textarea" name="remarques" placeholder="Entrez le nom du rendu ..."></textarea>
      </div>

      <div class="wrapper">
          <h2>Sélectionnez un fichier de rendu :</h2>
          <input type="file" name="fileToUpload">
      </div>
      
      <input type="submit" value="Soumettre le rendu" name="submit">
    </div>
  </form>

  <script src="main.js"></script>
</body>
</html>
