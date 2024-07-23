<?php
session_start();
require '../../prof/database.php';

$ID = $_POST['ID'];
$password = $_POST['password'];

// Hash the password
$hashed_password = hash('sha256', $password);  

$sql_etudiant = "SELECT * FROM etudiant WHERE id_etudiant = '$ID'";
$result_etudiant = $conn->query($sql_etudiant);

$sql_prof = "SELECT * FROM professeur WHERE id_professeur = '$ID'";
$result_prof = $conn->query($sql_prof);

$sql_admin = "SELECT * FROM personnel WHERE  role ='admin' AND id_personnel = '$ID'";
$result_admin = $conn->query($sql_admin);

if ($result_etudiant->num_rows > 0) {
    $row = $result_etudiant->fetch_assoc();
    // Verify hashed password
    if (hash_equals($row['mot_de_passe'], $hashed_password)) {
        $_SESSION['id_etudiant'] = $ID;
        $_SESSION['user_name'] = $row['nomETprenom'];
        header("Location: xx.php");
        exit();
    }
} elseif ($result_prof->num_rows > 0) {
    $row = $result_prof->fetch_assoc();
    // Verify hashed password
    if (hash_equals($row['mot_de_passe'], $hashed_password)) {
        $_SESSION['id_professeur'] = $ID;
        $_SESSION['user_name'] = $row['NomETPrenom']; 
        header("Location: ../../prof/espace-prof.php");
        exit();
    }
} elseif ($result_admin->num_rows > 0) {
    $row = $result_admin->fetch_assoc();
    // Verify hashed password
    if (hash_equals($row['mot_de_passe'], $hashed_password)) {
        $_SESSION['id_personnel'] = $ID;
        $_SESSION['user_name'] = $row['NomETPrenom']; 
        header("Location: ../../admin/espace_admin.php");
        exit();
    }
}

$_SESSION['login_error'] = "Identifiant ou mot de passe incorrect";

// Redirection vers la page de connexion
header("Location: ../../index.php");
exit();

// Fermeture de la connexion à la base de données
$conn->close();
?>
