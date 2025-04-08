<?php include('includes/header.php'); ?>

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
        <h1 class="mb-4 fw-bold">Contactez-moi</h1>

        <p class="mb-4 text-muted">Une question ? Un bug ? Une idée ? N'hésitez pas à me contacter via ce formulaire.</p>

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
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-secondary w-50" style="background-color: #2B9348; border-color: #2B9348;">Envoyer</button>
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
