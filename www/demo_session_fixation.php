<?php
// Simplified demo panel matching the requested UI: shows current session ID and two buttons
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Démo — Session fixation</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .panel { max-width:520px; padding:20px; border:1px solid #ddd; border-radius:8px; }
    .panel h2 { margin-top:0 }
    .controls { margin-top:12px }
    .controls button { margin-right:8px }
    pre { background:#f8f9fa; padding:10px; border-radius:6px }
  </style>
</head>
<body class="container py-5">
  <div class="panel">
    <h2>Session ID actuel :</h2>
    <p><span id="sid">chargement...</span></p>

    <div class="controls">
      <button id="refresh" class="btn btn-secondary">🔄 Rafraîchir Session ID</button>
      <button id="login" class="btn btn-primary">🔐 Se connecter</button>
    </div>

    <hr>
    <p>Vérifiez dans DevTools → Network / Application → Cookies que le cookie envoyé contient <strong>HttpOnly</strong> et <strong>SameSite=Lax</strong> (et <strong>Secure</strong> si HTTPS).</p>
  </div>

  <script>
    async function loadSID() {
      try {
        const res = await fetch('session-info.php');
        const data = await res.json();
        const sid = data.sessionId || '(aucune session)';
        document.getElementById('sid').textContent = sid;
      } catch (e) {
        document.getElementById('sid').textContent = '(erreur)';
        console.error(e);
      }
    }

    document.getElementById('refresh').addEventListener('click', loadSID);

    document.getElementById('login').addEventListener('click', async () => {
      // Appelle l'endpoint qui régénère l'ID (simule une connexion réussie)
      await fetch('session-regenerate.php', { method: 'POST' });
      // Recharge l'ID — il doit avoir changé
      await loadSID();
    });

    // Charge initial
    loadSID();
  </script>
</body>
</html>
