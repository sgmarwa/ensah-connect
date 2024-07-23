<?php
session_start();

if (isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate new password and confirm password
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // You may add additional validation here
        
        if ($new_password === $confirm_password) {
            // Hash the password before storing it using SHA-256
            $hashed_password = hash('sha256', $new_password);
            
            // Update the password in the database
            require '../../prof/database.php';

            $sql = "UPDATE etudiant SET mot_de_passe='$hashed_password' WHERE id_etudiant='$id_etudiant'";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Password updated successfully";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating password: " . $conn->error;
                $_SESSION['message_type'] = "error";
            }
            
            $conn->close();
        } else {
            $_SESSION['message'] = "Les mots de passe ne correspondent pas";
            $_SESSION['message_type'] = "error";
        }
        
        header("Location: index.php"); // Redirect to the same page to show the message
        exit;
    }
} else {
    $_SESSION['message'] = "Vous n'êtes pas connecté";
    $_SESSION['message_type'] = "error";
    header("Location: login.php"); // Redirect to the login page
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <?php include '../nav/navbar.html'; ?>
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
        </div>
        
        <?php
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; // Default to 'info' if not set
            
            // Determine CSS class based on message type
            $messageClass = 'message';
            if ($messageType === 'success') {
                $messageClass .= ' success';
            } elseif ($messageType === 'error') {
                $messageClass .= ' error';
            }
            
            echo '<div class="'. $messageClass .'">'. $message .'</div>';
            unset($_SESSION['message']); // Clear the message after displaying it
            unset($_SESSION['message_type']); // Clear the message type after displaying it
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box">
                <input type="password" name="new_password" class="input-field" placeholder="Nouveau mot de passe" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" class="input-field" placeholder="Confirmer le nouveau mot de passe" autocomplete="off" required>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn" id="submit">Mettre à jour</button>
            </div>
        </form>
    </div>
</body>
</html>
