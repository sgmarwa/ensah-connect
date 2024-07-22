

<?php

// process_selection.php
session_start();


if (!isset($_SESSION['id_professeur'])) {
    header("Location: ../index.php");
    exit();
}


if (!isset($_POST['filiere'])) {
    echo "Erreur : La filière n'est pas sélectionnée.";
    exit();
}


if (!isset($_POST['module'])) {
    echo "Erreur : Le module n'est pas sélectionné.";
    exit();
}

$selectedFiliere = $_POST['filiere'];
$moduleNom = $_POST['module'];
$action = $_POST['action'];

if ($action === "marquer_absence") {

    $typeSeance = $_POST['type_seance'];
   
    header("Location: souka.html?filiere=$selectedFiliere&module=$moduleNom&action=$action&type_seance=$typeSeance");
    exit();
} elseif ($action === "consulter_absence") {
   
    header("Location: archive_abs.php?filiere=$selectedFiliere&module=$moduleNom&action=$action");
    exit();
}elseif ($action === "abs+3") {
   
    header("Location: plus3abs.php?filiere=$selectedFiliere&module=$moduleNom&action=$action");
    exit();
} else {
    
    echo "Action non reconnue.";
    exit();
}
?>


