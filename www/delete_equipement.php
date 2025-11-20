<?php
require_once("config/database2.php");
require_once("includes/logger.php");
require_once("includes/csrf.php");
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) { 
    header('Location: index.php');
    exit();
}

// Vérification CSRF pour la suppression via GET
if (!isset($_GET['token']) || !verify_csrf_token($_GET['token'])) {
    write_log('CSRF', $_SESSION['user']['id'], 'FAILURE', 'Invalid token on Delete Equip');
    die("Erreur de sécurité (CSRF). Lien invalide.");
}

// Vérifie si un ID est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_crud.php');
    exit();
}

$id = $_GET['id'];

// Vérifier que l'équipement existe
$stmt = $conn->prepare("SELECT * FROM equipements_sportifs_paris WHERE id = :id");
$stmt->execute(['id' => $id]);
$equipement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$equipement) {

    header('Location: admin_crud.php');
    exit();
}

// Suppression
$stmt = $conn->prepare("DELETE FROM equipements_sportifs_paris WHERE id = :id");
$stmt->execute(['id' => $id]);

write_log('DELETE_EQUIP', $_SESSION['user']['id'], 'SUCCESS', "ID: $id");

header('Location: admin_crud.php');
exit();
?>
