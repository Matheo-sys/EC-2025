<?php
// Démarrer la session
session_start();

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Ajouter un message de succès dans la session
$_SESSION['message'] = "Vous avez bien été déconnecté.";

// Rediriger l'utilisateur vers la page d'accueil
header("Location: index.php");
exit();
?>
