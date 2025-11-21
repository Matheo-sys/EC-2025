<?php
// Page de test pour vérifier que le cookie de session est HttpOnly et non accessible via JS
require_once __DIR__ . '/includes/session.php';

// Assure qu'une valeur de session existe
if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = bin2hex(random_bytes(8));
}

$sessionName = session_name();
$hasCookie = isset($_COOKIE[$sessionName]);
// Récupère des informations de debug utiles
$ini_httponly = ini_get('session.cookie_httponly');
$ini_secure = ini_get('session.cookie_secure');
$ini_samesite = ini_get('session.cookie_samesite');
$cookie_params = session_get_cookie_params();
$sent_headers = headers_list();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Test cookie de session</title>
</head>
<body>
  <h1>Test cookie de session</h1>
  <p>Nom de session (côté serveur): <strong><?= htmlspecialchars($sessionName) ?></strong></p>
  <p>Cookie côté client présent : <strong><?= $hasCookie ? 'oui' : 'non' ?></strong></p>
  <p>Valeur de la session côté serveur (clé test) : <strong><?= htmlspecialchars($_SESSION['test']) ?></strong></p>

  <h2>Debug serveur</h2>
  <pre>
ini session.cookie_httponly: <?= htmlspecialchars($ini_httponly) ?>
ini session.cookie_secure: <?= htmlspecialchars($ini_secure) ?>
ini session.cookie_samesite: <?= htmlspecialchars($ini_samesite) ?>

session_get_cookie_params(): <?= htmlspecialchars(json_encode($cookie_params)) ?>

Headers envoyés (headers_list):
<?= htmlspecialchars(implode("\n", $sent_headers)) ?>
  </pre>

  <h2>Test JS: lecture de document.cookie</h2>
  <pre id="js-output">Lecture document.cookie...</pre>

  <script>
    // Ce script tente d'afficher tous les cookies accessibles via JS
    document.getElementById('js-output').textContent = document.cookie || '(vide)';
    console.log('document.cookie =', document.cookie);
    // Dans la console du navigateur, vérifier qu'aucun cookie de session (nom: <?= addslashes($sessionName) ?>) n'est présent
  </script>

  <p>Procédure :</p>
  <ol>
    <li>Ouvrir cette page dans le navigateur.</li>
    <li>Regarder la valeur affichée par JavaScript (document.cookie).</li>
    <li>Ouvrir la console JS (F12) et taper <code>document.cookie</code> — vérifier que le cookie de session (nom ci-dessus) n'apparaît pas.</li>
  </ol>
</body>
</html>
