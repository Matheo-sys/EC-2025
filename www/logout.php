<?php
// Utilise l'initialisation centralisée pour assurer les mêmes paramètres de cookie
require_once __DIR__ . '/includes/session.php';

// Supprime toutes les variables de session
$_SESSION = [];

// Détruit la session côté serveur
if (session_id() !== '') {
	// Récupère le nom du cookie et ses paramètres pour le supprimer correctement
	$params = session_get_cookie_params();
	// Détruit la session
	session_unset();
	session_destroy();

	// Détruit aussi le cookie de session côté client
	setcookie(
		session_name(),
		'',
		time() - 42000,
		$params['path'] ?? '/',
		$params['domain'] ?? '',
		$params['secure'] ?? false,
		$params['httponly'] ?? true
	);
}

// Optionnel: message flash après logout (requiert un nouveau démarrage de session si utilisé)
// header redirection
header("Location: index.php");
exit();
?>
