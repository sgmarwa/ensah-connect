<?php
  session_start();


  if (!isset($_SESSION['id_professeur'])) {
      header("Location: ../index.php");
      exit();
  }

// Définir une variable pour stocker le message de succès
$success_message = "";

if(isset($_SESSION['id_professeur'])) {
    $id_professeur = $_SESSION['id_professeur'];
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate new password and confirm password
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($new_password === $confirm_password) {
            $hashed_password = hash('sha256', $new_password);
            
            require 'database.php';

            $sql = "UPDATE professeur SET mot_de_passe='$hashed_password' WHERE id_professeur='$id_professeur'";

            
            if ($conn->query($sql) === TRUE) {
                $success_message = "Password updated successfully";
            } else {
                echo "Error updating password: " . $conn->error;
            }
            
            $conn->close();
        } else {
            echo "Les mots de passe ne correspondent pas";
        }
    }
} else {
    echo "Vous n'êtes pas connecté";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Changer le mot de passe</title>
</head>
<body>
    <video id="myVideo" autoplay muted loop>
        <source src="backnewmdp.mp4" type="video/mp4">
        <source src="backnewmdp.mp4" type="video/webm">   
    </video>
    <div class="login-box">
        <div class="login-header">
            <header>Changer le mot de passe</header>
            <?php
            
            if (!empty($success_message)) {
                echo '<p style="color:green;">'.$success_message.'</p>';
            }
            ?>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box">
                <input type="password" name="new_password" class="input-field" placeholder="Nouveau mot de passe" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" class="input-field" placeholder="Confirmer le nouveau mot de passe" autocomplete="off" required>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn" id="submit">Mettre à jour</button>
                <button  onclick="window.location.href='espace-prof.php'" type="submit" class="submit-btn" id="submit">Revenir à la page d'acceuil</button>
            </div>
        </form>
    </div>
</body>
</html>
