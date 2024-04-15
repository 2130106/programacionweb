<?php
session_start();
// Destruir todas las variables de sesión
$_SESSION = array();

session_destroy();

header("Location: ../index.html");
exit();
?>
