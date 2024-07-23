<?php
session_start();

if(isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];
    
    require '../../prof/database.php';
     
            // Requête SQL pour récupérer id_module, nom_module, note_module de l'utilisateur connecté
            $sql = "SELECT nomETprenom, email, photo
                    FROM professeur";
            $result = $conn->query($sql);
            $sql = "SELECT nomETprenom, email, photo
                    FROM personnel";
            $resultp = $conn->query($sql);
 
        
}     
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php include '../nav/navbar.html'; ?>
</head>

<body>
    <main class="table" id="notes_table">
        <section class="table__header">
            <h1>Personnel de l'ENSA Al-Hoceima </h1>
            <div class="input-group">
                <input type="search" placeholder=" chercher ...">
                <img src="loop.avif" alt="">
            </div>
             
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Nom et Prenom <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Email académique <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Photo  <span class="icon-arrow">&UpArrow;</span></th>
                         
                         
                    </tr>
                </thead>

                <tbody>
                <?php while($row = $resultp->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["nomETprenom"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['photo']); ?>" alt="Image de profil"></td>
                         
                    </tr>
                <?php } ?>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["nomETprenom"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['photo']); ?>" alt="Image de profil"></td>
                         
                    </tr>
                <?php } ?>
                
                
    
            </tbody>
                     
            </table>  
        </section>
    </main>

    <script src="main.js"></script>

</body>

</html>
