<?php
session_start();


if (!isset($_SESSION['id_professeur'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['filiere'], $_GET['module'])) {
    echo "Erreur : Toutes les données nécessaires ne sont pas fournies.";
    exit();
}

$filiere = $_GET['filiere'];
$module = $_GET['module'];

require 'database.php';

// Récupérer les IDs de la filière et du module
$sql_filiere = "SELECT id_filiere FROM filiere WHERE nom_filiere = '$filiere'";
$result_filiere = $conn->query($sql_filiere);
$id_filiere = $result_filiere->fetch_assoc()['id_filiere'];

$sql_module = "SELECT id_module FROM module WHERE nom_module = '$module'";
$result_module = $conn->query($sql_module);
$id_module = $result_module->fetch_assoc()['id_module'];

// Étape 1: Récupérer les enregistrements pertinents de `notes_evaluation`
$sql = "SELECT ne.id_etudiant, ne.note, ne.pourcentage, ne.type_evaluation, e.nomETprenom
        FROM notes_evaluation ne
        JOIN etudiant e ON ne.id_etudiant = e.id_etudiant
        WHERE ne.id_module = '$id_module' ";

$result = $conn->query($sql);

$students = [];
if ($result->num_rows > 0) {
    $notes = [];
    // Organiser les notes par étudiant
    while ($row = $result->fetch_assoc()) {
        $id_etudiant = $row['id_etudiant'];
        $nomETprenom = $row['nomETprenom'];
        $note = $row['note'];
        $pourcentage = $row['pourcentage'];

        if (!isset($notes[$id_etudiant])) {
            $notes[$id_etudiant] = [
                'nomETprenom' => $nomETprenom,
                'weighted_notes' => []
            ];
        }

        $notes[$id_etudiant]['weighted_notes'][] = $note * ($pourcentage / 100);
    }

    // Étape 2: Calculer `note_module` pour chaque étudiant
    foreach ($notes as $id_etudiant => $data) {
        $nomETprenom = $data['nomETprenom'];
        $weighted_notes = $data['weighted_notes'];
        $note_module = array_sum($weighted_notes);
        // $note_module = $total_notes / count($weighted_notes);

        // Insérer ou mettre à jour les enregistrements dans `note_module`
        $sql_check = "SELECT * FROM note_module WHERE id_etudiant = '$id_etudiant' AND id_module = '$id_module' AND id_filiere = '$id_filiere'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            // Mettre à jour la note_module existante
            $sql_update = "UPDATE note_module SET note_module = '$note_module' 
                           WHERE id_etudiant = '$id_etudiant' AND id_module = '$id_module' AND id_filiere = '$id_filiere'";
            $conn->query($sql_update);
        } else {
            // Insérer une nouvelle note_module
            $sql_insert = "INSERT INTO note_module (id_etudiant, id_module, id_filiere, note_module) 
                           VALUES ('$id_etudiant', '$id_module', '$id_filiere', '$note_module')";
            $conn->query($sql_insert);
        }

        // Stocker les données des étudiants pour l'affichage
        $students[] = [
            'id_etudiant' => $id_etudiant,
            'nomETprenom' => $nomETprenom,
            'note_module' => $note_module
        ];
    }
} else {
    echo "Aucune donnée trouvée pour le module et la filière spécifiés.";
    $conn->close();
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul des Notes du Module</title>
    <link rel="stylesheet" href="note_module.css">
</head>
<body>
    <div class="container">
        <h1>Note du Module</h1>
        <table>
            <tr>
                <th>ID Etudiant</th>
                <th>Nom Etudiant</th>
                <th>Note Module</th>
            </tr>
            <?php foreach ($students as $student): ?>
                <tr>
                <td><?php echo $student['id_etudiant']; ?></td>
                <td><?php echo $student['nomETprenom']; ?></td>
                <td style="color: <?php echo ($student['note_module'] < 12) ? 'red' : 'black'; ?>">
                    <?php echo number_format($student['note_module'], 2); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div id="finish-button-container">
            <button onclick="window.location.href='note.php'">Retour</button>
        </div>
    </div>
</body>
</html>
