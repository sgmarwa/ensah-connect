
<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }


if (!isset($_POST['filiere'])) {
    echo "Erreur : les données nécessaires ne sont pas fournies.";
    exit();
}


$filiere = $_POST['filiere'];


header("Location: annonce.php?filiere=$filiere");
?>