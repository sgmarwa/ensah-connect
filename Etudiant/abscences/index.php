<?php
session_start();

if (isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];
    
    require '../../prof/database.php';
    
    // Requête SQL pour récupérer l'ID de la filière de l'étudiant
    $sql_filiere = "SELECT id_filiere FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result_filiere = $conn->query($sql_filiere);
    
    if ($result_filiere->num_rows > 0) {
        // Récupérer l'ID de la filière
        $row_filiere = $result_filiere->fetch_assoc();
        $id_filiere = $row_filiere['id_filiere'];
        
        // Requête SQL pour récupérer le nom de la filière de l'étudiant
        $sql_nomfiliere = "SELECT nomfiliere FROM filiere WHERE id_filiere = '$id_filiere'";
        $result_nomfiliere = $conn->query($sql_nomfiliere);
        
        if ($result_nomfiliere->num_rows > 0) {
            // Récupérer le nom de la filière
            $row_nomfiliere = $result_nomfiliere->fetch_assoc();
            $nomfiliere = $row_nomfiliere['nomfiliere'];

            // Requête SQL pour récupérer l'ID du semestre
            $sql_S = "SELECT id_semestre FROM semestre LIMIT 1"; // Ajout d'une limite pour obtenir une seule valeur
            $result_S = $conn->query($sql_S);
            if ($result_S->num_rows > 0) {
                $row_S = $result_S->fetch_assoc();
                $id_S = $row_S['id_semestre'];
            } else {
                echo "Valeur de l'ID du semestre invalide";
                exit;
            }

            // Requête SQL pour récupérer id_module, nom_module, nombre_absences de l'utilisateur connecté
            $sql = "SELECT m.id_module, m.nom_module, COUNT(a.id_abscence) AS nombre_absences
                    FROM abscence a 
                    JOIN module m ON a.id_module = m.id_module
                    WHERE a.id_etudiant = '$id_etudiant'   AND m.id_module LIKE CONCAT('$nomfiliere', '$id_S', '%')
                    AND m.id_filiere ='$id_filiere'  
                    GROUP BY m.id_module, m.nom_module";
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
    <title>Mes absences</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php include '../nav/navbar.html'; ?>
    
</head>
<body>
    <main class="table" id="notes_table">
        <section class="table__header">
            <h1>Mes absences</h1>
            <div class="input-group">
                <input type="search" placeholder="chercher ...">
                <img src="..\les ressources\loop.avif" alt="">
            </div>
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Id-module <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Nom du module <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Nombre d'absences <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($result) && $result->num_rows > 0) { ?>
                        <?php while($row = $result->fetch_assoc()) { ?>
                            <tr class="<?php echo ($row["nombre_absences"] > 3) ? 'high-absence' : ''; ?>">
                                <td><?php echo $row["id_module"]; ?></td>
                                <td><?php echo $row["nom_module"]; ?></td>
                                <td><?php echo $row["nombre_absences"]; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3">Aucune absence trouvée pour cet étudiant.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="..\les ressources\main.js"></script>
</body>
</html>

