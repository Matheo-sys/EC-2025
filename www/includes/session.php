<?php
// Centralise l'initialisation de la session avec des cookies sécurisés
// Détermine si la connexion est en HTTPS
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

// Paramètres sécurisés pour le cookie de session
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
// Retire le port si présent (ex: localhost:8888 -> localhost)
$domain = preg_replace('/:\d+$/', '', $host);

$cookieParams = [
    'lifetime' => 0,
    'path' => '/',
    'domain' => $domain,
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax' // Choix conservateur; utiliser 'Strict' si applicable
];

// Applique les valeurs ini en complément (fallback pour certains environnements)
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_secure', $secure ? '1' : '0');
// session.cookie_samesite n'existe pas sur toutes les versions; on le tente en complément
if (PHP_VERSION_ID >= 70300) {
    ini_set('session.cookie_samesite', $cookieParams['samesite']);
}

// Sur PHP >= 7.3 on peut passer un tableau à session_set_cookie_params
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params($cookieParams);
} else {
    // Pour les versions antérieures, on concatène samesite dans path (workaround)
    $path = $cookieParams['path'];
    if (!empty($cookieParams['samesite'])) {
        $path .= '; samesite=' . $cookieParams['samesite'];
    }
    session_set_cookie_params(
        $cookieParams['lifetime'],
        $path,
        $cookieParams['domain'],
        $cookieParams['secure'],
        $cookieParams['httponly']
    );
}

// Démarre la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>
