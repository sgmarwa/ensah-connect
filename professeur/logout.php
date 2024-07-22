<?php
session_start();

if (isset($_SESSION['id_professeur'])) {
  session_destroy();

}
header("Location: ../index.php");
exit();
?>
