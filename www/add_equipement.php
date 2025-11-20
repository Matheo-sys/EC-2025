<?php
require_once("config/database2.php");
require_once("includes/logger.php");
session_start();



if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) { 
    header('Location: index.php');
    exit();
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = trim($_POST['id']);
    $nom = htmlspecialchars(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $adresse = htmlspecialchars(trim($_POST['adresse']), ENT_QUOTES, 'UTF-8');
    $type_sport = htmlspecialchars(trim($_POST['type_sport']), ENT_QUOTES, 'UTF-8');
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $gratuit = htmlspecialchars(trim($_POST['gratuit']), ENT_QUOTES, 'UTF-8');
    $handicap_access = htmlspecialchars(trim($_POST['handicap_access']), ENT_QUOTES, 'UTF-8');
    $arrondissement = htmlspecialchars(trim($_POST['arrondissement']), ENT_QUOTES, 'UTF-8');

   // Validation serveur stricte
    if ($id === '' || $nom === '' || $adresse === '' || $type_sport === '' ||
        $latitude === '' || $longitude === '' || $gratuit === '' ||
        $handicap_access === '' || $arrondissement === '') {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!ctype_digit($id)) {
        $erreur = "ID invalide (doit être numérique).";
    } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
        $erreur = "Latitude et longitude doivent être des nombres valides.";
    } elseif (strlen($nom) > 255 || strlen($adresse) > 255) {
        $erreur = "Le nom et l'adresse ne doivent pas dépasser 255 caractères.";
    } elseif (strlen($type_sport) > 100) {
        $erreur = "Le type de sport ne doit pas dépasser 100 caractères.";
    } elseif (strlen($arrondissement) > 10) {
        $erreur = "L'arrondissement ne doit pas dépasser 10 caractères.";
    } else {
        // cast sécurisés
        $id = (int)$id;
        $latitude = (float)$latitude;
        $longitude = (float)$longitude;

        // Vérifier si l'id existe déjà
        $check = $conn->prepare("SELECT id FROM equipements_sportifs_paris WHERE id = ?");
        $check->execute([$id]);

        if ($check->rowCount() > 0) {
            $erreur = "Cet ID existe déjà, choisis un autre.";
        } else {
            $stmt = $conn->prepare("INSERT INTO equipements_sportifs_paris 
                (id, nom, adresse, type_sport, latitude, longitude, gratuit, handicap_access, arrondissement) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $nom, $adresse, $type_sport, $latitude, $longitude, $gratuit, $handicap_access, $arrondissement]);

            write_log('ADD_EQUIP', $_SESSION['user']['id'], 'SUCCESS', "ID: $id, Nom: $nom");
            header('Location: admin_crud.php');
            exit();
        }
    }
}
include('includes/header.php');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Equipement - ParisSport+</title>
    
    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Vos styles personnalisés -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Inclusion des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
</head>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Ajouter un équipement</h1>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>

    <form method="post" class="mx-auto" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">ID unique</label>
            <input type="text" name="id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse</label>
            <input type="text" name="adresse" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type de sport</label>
            <input type="text" name="type_sport" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gratuit (Oui / Non)</label>
            <input type="text" name="gratuit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Accès Handicap (Oui / Non)</label>
            <input type="text" name="handicap_access" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Arrondissement</label>
            <input type="text" name="arrondissement" class="form-control" required>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary" style="background-color: #2B9348; border-color: #2B9348;">Ajouter</button>
        </div>
    </form>
</div>
<?php include('includes/footer.php'); ?>