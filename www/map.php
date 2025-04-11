<?php
require_once("config/database.php");

include('likes.php');
include('includes/header.php');

$sport = isset($_GET['sport']) ? $_GET['sport'] : '';
$arrondissement = isset($_GET['arrondissement']) ? $_GET['arrondissement'] : '';
$distance = isset($_GET['distance']) ? $_GET['distance'] : 0;
$query = isset($_GET['q']) ? $_GET['q'] : '';

$keywords = explode(' ', $query);

$sql = "SELECT * FROM equipements_sportifs_paris WHERE 1";

// Tableau pour stocker les conditions et les paramètres de la requête
$whereClauses = [];
$params = [];

// Ajouter la recherche par mots-clés si elle est définie
if ($query) {
    foreach ($keywords as $keyword) {
        if (!empty($keyword)) {
            $whereClauses[] = "(nom LIKE :keyword OR adresse LIKE :keyword OR type_sport LIKE :keyword OR arrondissement LIKE :keyword)";
            $params['keyword'] = '%' . $keyword . '%';
        }
    }
}

// Ajouter le filtre sur le sport
if ($sport) {
    $whereClauses[] = "type_sport = :sport";
    $params['sport'] = $sport;
}

// Ajouter le filtre sur l'arrondissement
if ($arrondissement) {
    $whereClauses[] = "arrondissement = :arrondissement";
    $params['arrondissement'] = $arrondissement;
}

if (count($whereClauses) > 0) {
    $sql .= " AND " . implode(" AND ", $whereClauses);
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$terrains = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour calculer la distance entre deux points (en km) en utilisant la formule de Haversine
function calculer_distance($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Rayon de la Terre en kilomètres
    $phi1 = deg2rad($lat1);
    $phi2 = deg2rad($lat2);
    $delta_phi = deg2rad($lat2 - $lat1);
    $delta_lambda = deg2rad($lon2 - $lon1);
    $a = sin($delta_phi / 2) * sin($delta_phi / 2) +
         cos($phi1) * cos($phi2) *
         sin($delta_lambda / 2) * sin($delta_lambda / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $R * $c; // Distance en kilomètres
    return $distance;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte - ParisSport+</title>
    
    <!-- CSS et Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">

    <!-- Leaflet pour la carte -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="js/script.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

<main id="main-content">
    <section class="container py-5">
        <h2 class="mb-5 text-center">Résultats de la recherche</h2>

        <!-- Formulaire de recherche -->
        <form action="map.php" method="get" class="d-flex justify-content-center mb-5">
            <div class="input-group w-50">
                <input type="text" name="q" class="form-control" placeholder="Rechercher un terrain sportif..." required>
                <button class="btn btn-primary" style="background-color: #2B9348; border-color:#2B9348" type="submit" href="map.php">Rechercher</button>
            </div>
        </form>

        <!-- Map -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div id="map" style="height: 400px; border-radius: 15px;"></div>
            </div>
        </div>
        <div id="terrains-data" data-terrains="<?= htmlspecialchars(json_encode($terrains), ENT_QUOTES, 'UTF-8') ?>"></div>

        <!-- Nombre de résultats -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <?php if ($query): ?>
                    <h4>Nombre de terrains trouvés : <?= count($terrains) ?></h4>
                <?php else: ?>
                    <h4>Liste de tous les terrains</h4>
                <?php endif; ?>
            </div>
        </div>

        <!-- Résultats -->
        <div class="row g-4"> 
            <?php foreach ($terrains as $terrain): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($terrain['nom']) ?></h5>
                            <p class="card-text">Adresse : <?= htmlspecialchars($terrain['adresse']) ?></p>
                            <p class="card-text">Type/Sport : <?= htmlspecialchars($terrain['type_sport']) ?></p>
                            <p class="card-text">Code Postal : <?= htmlspecialchars($terrain['arrondissement']) ?></p>
                            <p class="card-text">Accès handicap : <?= htmlspecialchars($terrain['handicap_access']) ?></p>
                            <button class="like-btn" data-element-id="<?= $terrain['id'] ?>" data-liked="false">
                            <i class="fa-regular fa-heart"></i> 
                            </button>




                        </div>
                    </div>
                </div>
            
            <?php endforeach; ?>

    </section>
</main>


</body>
</html>

<?php include('includes/footer.php'); ?>


