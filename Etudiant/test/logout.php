<?php
session_start();

if (isset($_SESSION['id_etudiant'])) {
  session_destroy();

}
header("Location: ../../index.php");
exit();
?>