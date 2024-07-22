<?php
session_start();

if(isset($_SESSION['id_etudiant'])) {
    $id_etudiant = $_SESSION['id_etudiant'];
    
    require 'database.php';
    
 
    $sql_filiere = "SELECT id_filiere FROM etudiant WHERE id_etudiant = '$id_etudiant'";
    $result_filiere = $conn->query($sql_filiere);
    
    if ($result_filiere->num_rows > 0) {
      
        $row_filiere = $result_filiere->fetch_assoc();
        $id_filiere = $row_filiere['id_filiere'];
        
        // Requête SQL pour récupérer le nom de la filière de l'étudiant
        $sql_nomfiliere = "SELECT nomfiliere FROM filiere WHERE id_filiere = '$id_filiere'";
        $result_nomfiliere = $conn->query($sql_nomfiliere);
        
        if ($result_nomfiliere->num_rows > 0) {
            // Récupérer le nom de la filière
            $row_nomfiliere = $result_nomfiliere->fetch_assoc();
            $nomfiliere = $row_nomfiliere['nomfiliere'];
            
            // Requête SQL pour récupérer id_module, nom_module, note_module de l'utilisateur connecté
            $sql = "SELECT module.id_module, module.nom_module, note_module.note 
            FROM module
            INNER JOIN note_module ON module.id_module = note_module.id_module
            WHERE module.id_filiere = '$id_filiere' AND id_etudiant = '$id_etudiant'
            AND module.id_module LIKE CONCAT('$nomfiliere', '2%') ";
    $result = $conn->query($sql);

            // Requête SQL pour récupérer moyenne_semestrielle1
            $sql_moyenne = "SELECT moyenne_semestrielle1
                            FROM moyennes
                            WHERE id_etudiant = '$id_etudiant'
                            AND id_filiere = '$id_filiere'";
            $result_moyenne = $conn->query($sql_moyenne);
            $row_moyenne = $result_moyenne->fetch_assoc();
            $moyenne_semestrielle1 = $row_moyenne['moyenne_semestrielle1'];
        }   
    }
}     
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Convert | Export html Table to CSV & EXCEL File</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php include '../nav/navbar.html'; ?>
</head>

<body>
    <main class="table" id="notes_table">
        <section class="table__header">
            <h1>Mes notes </h1>
            <div class="input-group">
                <input type="search" placeholder=" chercher ...">
                <img src="loop.avif" alt="">
            </div>
             
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Id-module <span class="icon-arrow">&UpArrow;</span></th>
                        <th> nom du module <span class="icon-arrow">&UpArrow;</span></th>
                        <th> La note  <span class="icon-arrow">&UpArrow;</span></th>
                        <th> v / R <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Classement <span class="icon-arrow">&UpArrow;</span></th>
                         
                    </tr>
                </thead>

                <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["id_module"]; ?></td>
                        <td><?php echo $row["nom_module"]; ?></td>
                        <td><?php echo $row["note_module"]; ?></td>
                        <td><?php 
                            if ($row["note_module"] >= 12) {
                                echo "VLD";
                            } else {
                                echo "RAT";
                            }
                        ?></td>
                    </tr>
                <?php } ?>
                <tr>
                <tr>
                 
                    <td colspan="5">Moyenne Semestrielle: <?php echo $moyenne_semestrielle1; ?></td>
                
                </tr>
    
            </tbody>
                     
            </table>  
        </section>
    </main>

    <script src="main.js"></script>

</body>

</html>
