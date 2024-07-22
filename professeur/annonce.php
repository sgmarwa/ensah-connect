 <!-- annonce.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace partage</title>
    <link rel="stylesheet" href="annonce.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("renduTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>CONSULTATION DES ANNONCES</h1>
        <div class="search-container">
            <div class="search-bar">
                <i class="fa fa-search"></i>
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Rechercher par mot clé">
            </div>
        </div>
        <table id="renduTable">
            <thead>
                <tr>
                    <th>Filière</th>
                    <th>Titre de l'annonce</th>

                    <th>Date de publication</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
session_start();


if (!isset($_SESSION['id_professeur'])) {
    header("Location: ../index.php");
    exit();
}

require 'database.php';

// Récupérer le nom de la filière depuis l'URL
if (!isset($_GET['filiere'])) {
    echo "Erreur : la filière n'est pas spécifiée.";
    exit();
}
$filiere = $_GET['filiere'];


$sql_id_filiere = "SELECT id_filiere FROM filiere WHERE nom_filiere = ?";
$stmt_id_filiere = $conn->prepare($sql_id_filiere);
$stmt_id_filiere->bind_param("s", $filiere);
$stmt_id_filiere->execute();
$result_id_filiere = $stmt_id_filiere->get_result();


if ($result_id_filiere->num_rows == 0) {
    echo "Erreur : la filière spécifiée n'existe pas.";
    exit();
}
$row_id_filiere = $result_id_filiere->fetch_assoc();
$id_filiere = $row_id_filiere['id_filiere'];


$stmt = ("SELECT id_filiere, titre_annonce, fichier_associé, date_publication
        FROM ( SELECT id_filiere, titre_annonce ,fichier_associé, date_publication
                 FROM annonce
                 WHERE id_filiere = '$id_filiere' 
                UNION
                SELECT id_filiere, titre_annonce, fichier_associé, date_publication
                FROM annonce_admin
                 WHERE id_filiere = '$id_filiere' )
        as combined
        ORDER BY date_publication DESC");

$result_annonce = $conn->query($stmt);

// Affichage des annonces dans le tableau
if ($result_annonce->num_rows > 0) {
    while ($row_annonce = $result_annonce->fetch_assoc()) {
        $id_filiere = $row_annonce['id_filiere'];
        $titre_annonce = $row_annonce['titre_annonce'];
        $date_publication = $row_annonce['date_publication'];   

        echo "<tr>
                <td>{$id_filiere}</td>
                <td>{$titre_annonce}</td>
                <td>{$date_publication}</td>
                <td><a href='telecharger_annonce.php?titre_annonce=" . urlencode($row_annonce["titre_annonce"]) . "'>Télécharger</a></td>;
            </tr>";
    }
} else {
    echo "<tr><td colspan='5'>Aucune annonce trouvée pour la filière sélectionnée.</td></tr>";
}


$conn->close();
?>
            </tbody>

        </table>
        <div id="finish-button-container">
            <button onclick="window.location.href='espace-prof.php'">Revenir à la page d'acceuil</button>
        </div>
    </div>
</body>
</html>