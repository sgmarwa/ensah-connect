<?php
session_start();

if (isset($_SESSION['id_personnel'])) {
  session_destroy();

}
header("Location: ../index.php");
exit();
?>