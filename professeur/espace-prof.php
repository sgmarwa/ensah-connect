<!-- espace-prof.php -->

<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }


require 'database.php';

$id_professeur = $_SESSION['id_professeur'];

$sql = "SELECT NomETPrenom, Photo FROM professeur WHERE id_professeur = '$id_professeur'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nom_professeur = $row['NomETPrenom'];
    $photo_professeur = base64_encode($row['Photo']); //  the photo is stored as a URL or base64 encoded string
} else {
    $nom_professeur = "Professeur";
    $photo_professeur = ""; // 
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESPACE PROFESSEUR</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.cdnfonts.com/css/brittany-signature" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script>

$(document).ready(function() {
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: 'load.php',
        selectable: true,
        selectHelper: true,
        select: function(start, end) {
            var title = prompt("Enter le titre de l'evenement ajouter");
            if (title) {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD ");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD ");
                $.ajax({
                    url: "insert.php",
                    type: "POST",
                    data: { title: title, start: start, end: end },
                    success: function() {
                        calendar.fullCalendar('refetchEvents');
                        alert("Ajouté avec succès");
                    }
                });
            }
        },
        eventResize: function(event) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD ");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD ");
            var title = event.title;
            var id = event.id;
            $.ajax({
                url: "update.php",
                type: "POST",
                data: { title: title, start: start, end: end, id: id },
                success: function() {
                    calendar.fullCalendar('refetchEvents');
                    alert('Evenement mis à jour avec succès');
                }
            });
        },
        eventDrop: function(event) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD ");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD ");
            var title = event.title;
            var id = event.id;
            $.ajax({
                url: "update.php",
                type: "POST",
                data: { title: title, start: start, end: end, id: id },
                success: function() {
                    calendar.fullCalendar('refetchEvents');
                    alert("Evenement mis à jour avec succès");
                }
            });
        },
        eventClick: function(event) {
            if (confirm("Etes-vous sûre de vouloir supprimer cet evenement?")) {
                var id = event.id;
                $.ajax({
                    url: "delete.php",
                    type: "POST",
                    data: { id: id },
                    success: function() {
                        calendar.fullCalendar('refetchEvents');
                        alert("Evenement supprimé avec succès");
                    }
                });
            }
        },
    });
});


  </script>

</head>

<body>
    <!-- =============== Navigation ================ -->
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">ENSAH-CONNECT </span>
                    </a>
                </li>

                <li>
                    <a href="espace_annonce.php">
                        <span class="icon">
                            <ion-icon name="newspaper-outline"></ion-icon>
                        </span>
                        <span class="title">Annonces</span>
                    </a>
                </li>

                <li>
                   <a href="abscence.php" class="dicon">
                     <span class="icon">
                        <ion-icon name="file-tray-full-outline"></ion-icon>
                     </span>
                      <span class="title">Abscences</span>
                  </a>
                </li>

                <li>
                    <a href="note.php">
                        <span class="icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </span>
                        <span class="title">Notes</span>
                    </a>
                </li>

                <li>
                    <a href="espace-partage.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Partager</span>
                    </a>
                </li>

                <li>
                    <a href="espace-rendus.php">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Consulter rendus</span>
                    </a>
                </li>

                <li>
                    <a href="emploi.php">
                        <span class="icon">
                        <ion-icon name="podium-outline"></ion-icon>
                        </span>
                        <span class="title">Emploi du temps</span>
                    </a>
                </li>

                <li class="dropdown">
                      <a href="modifier_mdp.php" class="dropbtn">
                      <span class="icon">
                             <ion-icon name="lock-closed-outline"></ion-icon>
                      </span>
                      <span class="title">Modifier mot de passe</span>
                     </a>
               </li>
               <li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Se deconnecter</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
               <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
            <div class="header-container">
                <div class="user">
                <img src="data:image/jpeg;base64,<?php echo $photo_professeur; ?>" alt="Photo du professeur">
                </div>
                <span class="welcome-msg"> Bienvenue, <?php echo $nom_professeur; ?> !</span>
            </div>
        </div>
        <div class="container1">
           <div id="calendar"></div>
        </div>
    <!-- =========== Scripts =========  -->
    <script src="main1.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>

