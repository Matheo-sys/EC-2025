<?php
require_once("config/database.php");
session_start();

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] != 5) { 
    header('Location: index.php');
    exit();
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

header('Location: admin_crud.php');
exit();
?>
