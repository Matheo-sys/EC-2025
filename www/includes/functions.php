function checkResourceExists($result, $error_message = 'Ressource non trouvée') {
    if (!$result) {
        http_response_code(404);
        include('404.php');
        exit();
    }
}