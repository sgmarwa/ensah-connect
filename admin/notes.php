<?php
  session_start();


  if (!isset($_SESSION['id_personnel'])) {
      header("Location: ../index.php");
      exit();
  }

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['filiere'])) {
        $error_message = "Aucune donnée trouvée pour la filière spécifiée";
    } else {
        $filiere = $_POST['filiere'];

        require '../prof/database.php';

        // Récupérer les étudiants et leurs notes pour les modules de la filière
        $sql = "SELECT nm.id_etudiant, nm.note_module, e.nomETprenom 
                FROM note_module nm
                JOIN etudiant e ON nm.id_etudiant = e.id_etudiant
                WHERE nm.id_filiere = '$filiere'";

        $result = $conn->query($sql);

        $students = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_etudiant = $row['id_etudiant'];
                $nomETprenom = $row['nomETprenom'];
                $note_module = $row['note_module'];

                if (!isset($students[$id_etudiant])) {
                    $students[$id_etudiant] = [
                        'nomETprenom' => $nomETprenom,
                        'notes' => []
                    ];
                }
                $students[$id_etudiant]['notes'][] = $note_module;
            }

            // Calculer la moyenne annuelle et mettre à jour la table `moyennes`
            foreach ($students as $id_etudiant => $data) {
                $nomETprenom = $data['nomETprenom'];
                $notes = $data['notes'];
                $moyenne_annuelle = array_sum($notes) / count($notes);

                // Insérer ou mettre à jour les enregistrements dans `moyennes`
                $sql_check = "SELECT * FROM moyennes WHERE id_etudiant = '$id_etudiant' AND id_filiere = '$filiere'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    // Mettre à jour la moyenne_annuelle existante
                    $sql_update = "UPDATE moyennes SET moyenne_annuelle = '$moyenne_annuelle' 
                                   WHERE id_etudiant = '$id_etudiant' AND id_filiere = '$filiere'";
                    $conn->query($sql_update);
                } else {
                    // Insérer une nouvelle moyenne_annuelle
                    $sql_insert = "INSERT INTO moyennes (id_etudiant, id_filiere, moyenne_annuelle) 
                                   VALUES ('$id_etudiant', '$filiere', '$moyenne_annuelle')";
                    $conn->query($sql_insert);
                }

                // Stocker les données des étudiants pour l'affichage
                $students[$id_etudiant]['moyenne_annuelle'] = $moyenne_annuelle;
            }
        } else {
            $error_message = "Les proffesseurs n'ont pas encore fourni toutes les notes.";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moyennes Annuelles des Étudiants</title>
    <link rel="stylesheet" href="notes.css">
</head>
<body>
    <div class="container">
        <h1>Moyennes Annuelles des Étudiants</h1>
        <?php
        if (!empty($success_message)) {
            echo '<p style="color:green;">' . $success_message . '</p>';
        }
        if (!empty($error_message)) {
            echo '<p style="color:red;">' . $error_message . '</p>';
        }
        ?>

        <?php if (isset($students) && count($students) > 0): ?>
        <h1>Pour la filière <?php echo htmlspecialchars($filiere); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>ID Étudiant</th>
                    <th>Nom et Prénom</th>
                    <th>Moyenne Annuelle</th>
                    <th>Résultat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $id_etudiant => $data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($id_etudiant); ?></td>
                    <td><?php echo htmlspecialchars($data['nomETprenom']); ?></td>
                    <td><?php echo htmlspecialchars($data['moyenne_annuelle']); ?></td>
                    <td class="<?php echo $data['moyenne_annuelle'] >= 12 ? 'valide' : 'ajourne'; ?>">
                        <?php echo $data['moyenne_annuelle'] >= 12 ? 'Validé' : 'Ajourné'; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <a href="espace_admin.php"><button type="button">Terminer</button></a>
    </div>
</body>
</html>
