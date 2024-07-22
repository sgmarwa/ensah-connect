

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Fichiers d'Absence</title>
    <link rel="stylesheet" href="archive.css">
</head>
<body>
    <div class="container">
        <h1>Consulter Fichiers d'Absence</h1>
        <form action="archive_abs.php" method="post">
            <div class="form-group">
                <label for="date_absence">Date d'Absence :</label>
                <input type="date" name="date_absence" id="date_absence" required>
            </div>
            <button type="submit">Valider</button>
            <a href="espace-prof.php"><button type="button">Revenir à la page d'acceuil</button></a>
        </form>
        <?php
session_start();


if (!isset($_SESSION['id_professeur'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['module']) && !isset($_SESSION['module'])) {
    echo "Erreur : Le module n'est pas sélectionné.";
    exit();
}

if (isset($_GET['module'])) {
    $_SESSION['module'] = $_GET['module'];
}

$moduleNom = $_SESSION['module'];

require 'database.php';

if (isset($_POST['date_absence'])) {
   
    $date_absence = date('Y-m-d', strtotime($_POST['date_absence']));

    // Récupération de l'ID du module sélectionné
    $sql_module_id = "SELECT id_module FROM module WHERE nom_module = '$moduleNom'";
    $result_module_id = $conn->query($sql_module_id);

    if (!$result_module_id || $result_module_id->num_rows == 0) {
        echo "Erreur : Module non trouvé.";
        exit();
    }

    $row_module_id = $result_module_id->fetch_assoc();
    $id_module = $row_module_id['id_module'];

    // Requête pour récupérer les absences
    $sql_absences = "SELECT e.id_etudiant, e.nomETprenom, a.date_abscence ,  a.type_seance
                    FROM abscence a
                    INNER JOIN etudiant e ON a.id_etudiant = e.id_etudiant
                    INNER JOIN module m ON a.id_module = m.id_module
                    WHERE m.nom_module = '$moduleNom' AND a.date_abscence = '$date_absence'";

    $result_absences = $conn->query($sql_absences);

    if (!$result_absences) {
        echo "Erreur de requête : " . $conn->error;
        exit();
    }

    if ($result_absences->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID Etudiant</th><th>Nom et Prénom</th><th>Type de seance</th><th>Date d'Absence</th></tr>";
        while ($row = $result_absences->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_etudiant'] . "</td>";
            echo "<td>" . $row['nomETprenom'] . "</td>";
            echo "<td>" . $row['type_seance'] . "</td>";
            echo "<td>" . $row['date_abscence'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Aucune absence trouvée pour cette date.";
    }
}

$conn->close();
?>
    </div>
</body>
</html>
