<?php
session_start();

if (!isset($_SESSION['id_etudiant'])) {
    header("Location: xx.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="xx.css">
    <link href="https://fonts.cdnfonts.com/css/brittany-signature" rel="stylesheet">
</head>
<body>
    <div class="header">
        <nav>
            <p class="id1">---------------------------</p>
            <a href="../profil/index.php">Profil <span></span></a>
            <a href="../Personnel/index.php">Personnel <span></span></a>
            <a href="#">Guide d'utilisation <span></span></a>
            <a href="../pomodoro/index.php">Pomodoro <span></span></a>
            <nav1>
                <a href="#" class="user-icon">
                    <img src="compte-utilisateur-1.png" alt="User Icon">
                </a>
            </nav1>
            <ul id="user-list" class="hidden">
                <li><a href="../new_mdp/index.php">Modifier le mot de passe</a></li>
                <li><a href="logout.php">Se deconnecter</a></li>
            </ul>
        </nav>
    </div>
    <p class="id2">Bienvenue sur la plateforme ENSA-H Connect</p>
    <div class="boutons">
        <button class="btn1">Emplois du temps
            <ul id="edt-list" class="hidden">
                <li><a href="../emplois du temps/telecharger.php" download>Télécharger</a></li>
            </ul>
        </button>
        <button class="btn2">Mes cours
            <ul id="semestre-list" class="hidden">
                <li><a href="../mes_cours/s1.php">S1</a></li>
                <li><a href="../mes_cours/s2.php">S2</a></li>
            </ul>
        </button>
        <button class="btn3">Consultation des notes
            <ul id="notes-list" class="hidden">
                <li><a href="../mes_notes/notes-s1.php">S1</a></li>
                <li><a href="../mes_notes/notes-s2.php">S2</a></li>
            </ul>
        </button>
        <button class="btn4">
            <a href="../absences/index.php">Absences</a>
        </button>
        <button class="btn5">Mes devoirs
            <ul id="devoirs-list" class="hidden">
                <li><a href="../devoirs/voir_mes_devoirs.php">Voir devoirs</a></li>
                <li><a href="../devoirs/depot/xx.php">Dépot des devoirs</a></li>
            </ul>
        </button>
        <button class="btn6">
            <a href="../annonces/index.php">Annonces</a>
        </button>
    </div>
    <script src="main1.js"></script>
</body>
</html>
