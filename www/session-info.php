<?php
header('Content-Type: application/json');

// Ne démarre pas automatiquement la session : si le cookie de session n'existe pas,
// on renvoie une valeur vide pour signifier "pas de session".
$sessionName = session_name();
$hasCookie = isset($_COOKIE[$sessionName]) && $_COOKIE[$sessionName] !== '';

if ($hasCookie) {
	// Démarre la session de façon sécurisée puis renvoie l'ID
	require_once __DIR__ . '/includes/session.php';
	echo json_encode(['sessionId' => session_id()]);
} else {
	echo json_encode(['sessionId' => '']);
}

?>
