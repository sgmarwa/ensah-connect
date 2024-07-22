<!-- rendus.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation des Rendus</title>
    <link rel="stylesheet" href="rendu.css">
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
        <h1>Consultation des Rendus</h1>
        <div class="search-container">
            <div class="search-bar">
                <i class="fa fa-search"></i>
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Rechercher par mot clé">
            </div>
        </div>
        <table id="renduTable">
            <thead>
                <tr>
                    <th>ID Etudiant</th>
                    <th>Nom et Prénom</th>
                    <th>Nom du Devoir</th>
                    <th>Date Dépôt</th>
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


$nom_module = $_POST['module'];


$sql_module = "SELECT id_module FROM module WHERE nom_module = '$nom_module'";
$result_module = $conn->query($sql_module);
$row_module = $result_module->fetch_assoc();
$id_module = $row_module['id_module'];


$sql_rendu = "SELECT * FROM rendu WHERE id_module = '$id_module'";
$result_rendu = $conn->query($sql_rendu);

while ($row_rendu = $result_rendu->fetch_assoc()) {
    // Récupération de nomETprenom
    $id_etudiant = $row_rendu['id_etudiant'];
    $sql_etudiant = "SELECT nomETprenom FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result_etudiant = $conn->query($sql_etudiant);
    $row_etudiant = $result_etudiant->fetch_assoc();
    $nomETprenom = $row_etudiant['nomETprenom'];

    // Récupération de date_limite
    $nom_devoir = $row_rendu['nom_devoir'];
    $sql_devoir = "SELECT date_limite FROM devoir WHERE nom_devoir = '$nom_devoir' AND id_module = '$id_module'";
    $result_devoir = $conn->query($sql_devoir);
    $row_devoir = $result_devoir->fetch_assoc();
    $date_limite = $row_devoir['date_limite'];

    // Comparaison des dates
    $date_rendu = $row_rendu['date_rendu'];
    $late_class = strtotime($date_rendu) > strtotime($date_limite) ? 'late' : '';

    echo "<tr>";
    echo "<td>" . $id_etudiant . "</td>";
    echo "<td>" . $nomETprenom . "</td>";
    echo "<td>" . $nom_devoir . "</td>";
    echo "<td class='$late_class'>" . $date_rendu . "</td>";
    echo "<td><a href='telecharger_annonce2.php?nom_devoir=" . urlencode($row_rendu["nom_devoir"]) . "'>Télécharger</a></td>";
    echo "</tr>";
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
