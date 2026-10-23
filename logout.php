<?php
session_start();
// Détruire toutes les variables de session
$_SESSION = array();
// Détruire la session elle-même
session_destroy();
// Rediriger vers la page d'accueil ou la page de connexion
header('Location: login.php');
exit();
?>
