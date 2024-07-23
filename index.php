<?php
// Démarrer la session en haut du fichier
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="etudiant/test/finale.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
     
</head>
<body>
        <div class="container">
            <div class="login__content">
                <form action="etudiant/test/verifier_connexion.php" method="post" class="login__form">
                    <div>
                        <h1 class="login__title">
                            <span>ENSAH-CONNECT</span> 
                        </h1>
                        <p class="login__description">
                            Bienvenue! Veiullez vous connecter pour continuer.
                            
                         </p> 
                         <div class="<?php if (isset($_SESSION['login_error'])) echo 'messageerror'; ?>">
                        <?php
                         
                        // Vérifie si une variable de session contenant le message d'erreur est définie
                        if (isset($_SESSION['login_error'])) {
                            // Affiche le message d'erreur
                            echo $_SESSION['login_error'];
                            
                            // Supprime la variable de session pour éviter qu'elle ne soit affichée à nouveau
                            unset($_SESSION['login_error']);
                        }
                        ?>
                    </div>


                    </div>
                    <div>
                        <div class="login__inputs">
                            <div>
                                <label for="input-ID" class="login__label">Identifiant</label>
                                <input type="text" placeholder="Saisir votre CNE" required class="login__input" id="input-ID" name="ID">
                            </div>
                            <div>
                                <label for="input-pass" class="login__label">Mot de passe</label>
                                <div class="login__box">
                                    <input type="password" placeholder="Saisir votre mot de passe" required class="login__input" id="input-pass" name="password">
                                    <i class="ri-eye-off-line login__eye" id="input-icon"></i>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <div>
                        <div class="login__buttons">
                            <button type="submit" class="login__button">Se connecter</button>
                        </div>
                        <a href="#" class="login__forgot">Mot de passe oublié?</a>
                    </div>
                    <div class="icon-buttons">
                        <button class="ensa-h-button location-button">
                            <span class="icon1"><i class="fas fa-map-marker-alt"></i></span>
                            <a id="source-link" class="meta-link" href="https://ensah.ma/" target="site"></a>   
                        </button>
                        <button class="ensa-h-button site-button">
                            <span class="icon2"><i class="fas fa-globe"></i></span>
                            <a id="source-link1" class="meta-link" href="https://www.google.com/maps/place/%C3%89cole+nationale+des+sciences+appliqu%C3%A9es+d'Alhoceima/@35.1727096,-3.8620479,15z/data=!4m6!3m5!1s0xd742cdef50e1b29:0x19897f71ba224d93!8m2!3d35.1727096!4d-3.8620479!16s%2Fm%2F0m0jx64?entry=ttu" target="_blank"></a>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script src="etudiant/test/finale.js"></script>            
</body>
 </html>