

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace ABS</title>
    <link rel="stylesheet" href="abs3.css">
</head>
<body>
    <div class="container">
    <?php
      session_start();


      if (!isset($_SESSION['id_professeur'])) {
          header("Location: ../index.php");
          exit();
      }
require 'database.php';


$selectedFiliere = $_GET['filiere'];
$moduleNom = $_GET['module'];

$sql_absences = "SELECT a.id_etudiant, e.nomETprenom, a.id_module, m.nom_module, COUNT(*) as nombre_absences
                 FROM abscence a
                 INNER JOIN module m ON a.id_module = m.id_module
                 INNER JOIN etudiant e ON a.id_etudiant = e.id_etudiant
                 WHERE m.nom_module = '$moduleNom'
                 GROUP BY a.id_etudiant
                 HAVING COUNT(*) >= 4";

$result_absences = $conn->query($sql_absences);

if ($result_absences->num_rows > 0) {
    echo "<h1>Liste des étudiants avec plus de 3 absences pour le module $moduleNom :</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID Etudiant</th>
                <th>Nom et Prénom</th>
                <th>Nombre d'Absences</th>
            </tr>";

    while ($row_absence = $result_absences->fetch_assoc()) {
        echo "<tr>
                <td>" . $row_absence['id_etudiant'] . "</td>
                <td>" . $row_absence['nomETprenom'] . "</td>
                <td>" . $row_absence['nombre_absences'] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
     echo "<span class='aucun-etudiant'>Aucun étudiant trouvé avec plus de 3 absences pour le module $moduleNom.";
}

$conn->close();
?>
<div class="container1">
<a href="abscence.php"><button type="button">Retour</button></a>
</div>

    </div>
</body>
</html>
