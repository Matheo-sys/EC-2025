<?php
require_once("config/database.php");
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) { 
    header('Location: index.php');
    exit();
}

$erreur = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_equipements.php');
    exit();
}

$id = $_GET['id'];

// Récupérer les infos actuelles de l'équipement
$stmt = $conn->prepare("SELECT * FROM equipements_sportifs_paris WHERE id = :id");
$stmt->execute(['id' => $id]);
$equipement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$equipement) {
    header('Location: admin_crud.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $type_sport = htmlspecialchars(trim($_POST['type_sport']));
    $latitude = htmlspecialchars(trim($_POST['latitude']));
    $longitude = htmlspecialchars(trim($_POST['longitude']));
    $gratuit = htmlspecialchars(trim($_POST['gratuit']));
    $handicap_access = htmlspecialchars(trim($_POST['handicap_access']));
    $arrondissement = htmlspecialchars(trim($_POST['arrondissement']));

    if (
        !empty($nom) && !empty($adresse) && !empty($type_sport) &&
        !empty($latitude) && !empty($longitude) && !empty($gratuit) &&
        !empty($handicap_access) && !empty($arrondissement)
    ) {
        $stmt = $conn->prepare("UPDATE equipements_sportifs_paris 
            SET nom = :nom, adresse = :adresse, type_sport = :type_sport, latitude = :latitude, 
                longitude = :longitude, gratuit = :gratuit, handicap_access = :handicap_access, arrondissement = :arrondissement
            WHERE id = :id
        ");

        $stmt->execute([
            'nom' => $nom,
            'adresse' => $adresse,
            'type_sport' => $type_sport,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'gratuit' => $gratuit,
            'handicap_access' => $handicap_access,
            'arrondissement' => $arrondissement,
            'id' => $id
        ]);

        header('Location: admin_crud.php');
        exit();
    } else {
        $erreur = "Merci de compléter tous les champs.";
    }
}

?>

<?php include('includes/header.php'); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Equipement - ParisSport+</title>
    
    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles perso -->

    <link rel="stylesheet" href="css/style.css">
    
    <!-- Inclusion des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
</head>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Modifier un Équipement Sportif</h1>

    <?php if(!empty($erreur)): ?>
        <div class="alert alert-danger"><?= $erreur ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($equipement['nom']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Adresse</label>
            <textarea name="adresse" class="form-control"><?= htmlspecialchars($equipement['adresse']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Type de Sport</label>
            <input type="text" name="type_sport" class="form-control" value="<?= htmlspecialchars($equipement['type_sport']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($equipement['latitude']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($equipement['longitude']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Gratuit</label>
            <input type="text" name="gratuit" class="form-control" value="<?= htmlspecialchars($equipement['gratuit']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Accès Handicapé</label>
            <input type="text" name="handicap_access" class="form-control" value="<?= htmlspecialchars($equipement['handicap_access']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Arrondissement</label>
            <input type="text" name="arrondissement" class="form-control" value="<?= htmlspecialchars($equipement['arrondissement']) ?>">
        </div>

        <button type="submit" class="btn btn-success w-100">Modifier</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
