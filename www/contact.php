<?php include('includes/header.php'); ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = "sportidf@alwaysdata.net";
    $subject = "Nouveau message de $nom via ParisSport+";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

    $body = "Nom : $nom\n";
    $body .= "Email : $email\n\n";
    $body .= "Message :\n$message\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script nonce='$nonce'>alert('Message envoyé avec succès !'); window.location.href='contact.php';</script>";
    } else {
        echo "<script nonce='$nonce'>alert('Erreur lors de l\'envoi du message.'); window.location.href='contact.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contact - ParisSport+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Ton CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <main class="d-flex align-items-center justify-content-center vh-100">
        <div class="container text-center">
            <h1 class="mb-4 fw-bold">Contactez-nous</h1>

            <p class="mb-4 text-muted">Une question ? Un bug ? Une idée ? N'hésitez pas à nous contacter via ce
                formulaire.</p>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="contact.php" method="post" class="p-4 border rounded shadow-sm bg-light">

                        <div class="mb-3 text-start">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill">Envoyer</button>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>