<?php
function write_log($action, $user, $status, $details = '') {
    $log_dir = __DIR__ . '/../logs';
    $log_file = $log_dir . '/app.log';

    // Créer le dossier s'il n'existe pas (sécurité supplémentaire)
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    // Format: [YYYY-MM-DD HH:MM:SS] | ACTION | USER | STATUS | DETAILS
    $date = date('Y-m-d H:i:s');
    $entry = sprintf("[%s] | %s | %s | %s | %s" . PHP_EOL, 
        $date, 
        str_pad($action, 15), 
        str_pad($user, 20), 
        str_pad($status, 10), 
        $details
    );

    file_put_contents($log_file, $entry, FILE_APPEND);
}
?>
