<!-- process_selection1.php -->
<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

if (!isset($_POST['filiere'], $_POST['module'], $_POST['evaluation'])) {
    echo "Erreur : Toutes les données nécessaires ne sont pas fournies.";
    exit();
}

$filiere = $_POST['filiere'];
$module = $_POST['module'];
$evaluation = $_POST['evaluation'];



if ($evaluation == "notefinale") {

    header("Location: calcul_note_module.php?filiere=$filiere&module=$module&evaluation=$evaluation");
    exit();

} else{
    header("Location: espace_notes.html?filiere=$filiere&module=$module&evaluation=$evaluation");
    exit();
}

?>
