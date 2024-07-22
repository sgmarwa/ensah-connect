<?php
require 'database.php';

// Vérifier si c'est le 1er août
if (date("m-d") == "08-01") {
    $sql_delete_abscence = "DELETE FROM abscence";
    if ($conn->query($sql_delete_abscence) === TRUE) {
        echo "Contenu de la table 'abscence' effacé avec succès.<br>";
    } else {
        echo "Erreur lors de la suppression du contenu de la table 'abscence': " . $conn->error . "<br>";
    }

    $sql_delete_rendu = "DELETE FROM rendu";
    if ($conn->query($sql_delete_rendu) === TRUE) {
        echo "Contenu de la table 'rendu' effacé avec succès.<br>";
    } else {
        echo "Erreur lors de la suppression du contenu de la table 'rendu': " . $conn->error . "<br>";
    }
} else {
    echo "Ce n'est pas le 1er août. Les tables n'ont pas été effacées.<br>";
}

$conn->close();
?>
