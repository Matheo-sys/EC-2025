<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/session.php';

// Simule une authentification réussie en régénérant l'ID de session
session_regenerate_id(true);

// Optionnel : marque l'utilisateur connecté pour la démo
$_SESSION['demo_user'] = ['email' => 'demo@example.test'];

echo json_encode(['sessionId' => session_id()]);
?>
