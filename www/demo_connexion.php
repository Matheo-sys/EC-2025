<?php
// Page de démonstration : création d'une session sécurisée
require_once __DIR__ . '/includes/session.php';

$message = '';
$created = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Demo : n'importe quelles crédentielles non vides sont acceptées
    if ($email !== '' && $password !== '') {
        // Régénère l'ID de session après "authentification"
        session_regenerate_id(true);
        $_SESSION['demo_user'] = ['email' => $email];
        $created = true;
        $message = 'Session créée';
    } else {
        $message = 'Veuillez renseigner un email et un mot de passe.';
    }
}

// Informations serveur utiles pour la démonstration (sans exposer l'ID de session)
$sessionName = session_name();
$cookieParams = session_get_cookie_params();
$setCookies = array_filter(headers_list(), function($h) { return stripos($h, 'Set-Cookie:') !== false; });

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Démo connexion — Session sécurisée</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="container py-5">

  <h1 class="mb-4">Démo — Connexion et cookie de session sécurisé</h1>

  <?php if ($created): ?>
    <div class="alert alert-success" role="alert"><?= htmlspecialchars($message) ?></div>
  <?php elseif ($message !== ''): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <?php if (!$created): ?>
  <form method="post" style="max-width:480px">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input id="email" name="email" type="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Mot de passe</label>
      <input id="password" name="password" type="password" class="form-control" required>
    </div>
    <button class="btn btn-primary" type="submit">Se connecter</button>
  </form>
  <?php endif; ?>

  <hr>

  <h2>Instructions de vérification</h2>
  <ol>
    <li>Ouvrez les DevTools → Network (ou Application/Storage → Cookies).</li>
    <li>Après connexion, recherchez le cookie portant le nom <code><?= htmlspecialchars($sessionName) ?></code>.</li>
    <li>Vérifiez les attributs du cookie : <strong>HttpOnly</strong>, <strong>Secure</strong> (si vous êtes en HTTPS) et <strong>SameSite=Lax</strong> (ou Strict selon config).</li>
    <li>Dans la console JS, tapez <code>document.cookie</code> : vous ne devriez pas voir le cookie de session (HttpOnly empêche sa lecture par JS).</li>
  </ol>

  <h3>Affichage côté serveur (pour preuve)</h3>
  <p>Nom de cookie de session : <strong><?= htmlspecialchars($sessionName) ?></strong></p>
  <pre>session_get_cookie_params(): <?= htmlspecialchars(json_encode($cookieParams, JSON_PRETTY_PRINT)) ?></pre>

  <h4>Set-Cookie envoyés (filtrés)</h4>
  <pre><?= htmlspecialchars(implode("\n", $setCookies)) ?></pre>

  <h3>Test JS (document.cookie)</h3>
  <p>Valeur retournée par <code>document.cookie</code> (affichée ci-dessous) :</p>
  <pre id="js-output">(en attente)</pre>

  <script>
    // Affiche document.cookie — si le cookie de session est HttpOnly, il ne sera pas présent ici
    document.getElementById('js-output').textContent = document.cookie || '(vide)';
    console.log('document.cookie =', document.cookie);
  </script>

</body>
</html>
