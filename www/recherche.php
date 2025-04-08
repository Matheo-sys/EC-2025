<?php
require_once("config/database.php");
include('includes/header.php');

// Récupérer les filtres
$sport = isset($_GET['sport']) ? $_GET['sport'] : '';
$arrondissement = isset($_GET['arrondissement']) ? $_GET['arrondissement'] : '';
$distance = isset($_GET['distance']) ? $_GET['distance'] : 0;
$query = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT * FROM equipements_sportifs_paris WHERE 1";

if ($query) {
    $sql .= " AND (nom LIKE :query OR adresse LIKE :query OR type_sport LIKE :query)";
}
if ($sport) {
    $sql .= " AND type_sport = :sport";
}
if ($arrondissement) {
    $sql .= " AND arrondissement = :arrondissement";
}

// Exécution de la requête
$stmt = $conn->prepare($sql);
$params = [];
if ($query) $params['query'] = '%' . $query . '%';
if ($sport) $params['sport'] = $sport;
if ($arrondissement) $params['arrondissement'] = $arrondissement;

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
    <title>Recherche - ParisSport+</title>
    
    <!-- CSS et Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">

    <!-- Leaflet pour la carte -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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
            <h2 class="mb-4 text-center">Résultats de la recherche</h2>

            <!-- Formulaire de recherche avec filtres -->
            <form action="recherche.php" method="get" class="d-flex justify-content-center mb-4">
                <div class="input-group w-50">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher un terrain sportif..." value="<?= htmlspecialchars($query) ?>" required>
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
            </form>

            <!-- Filtres supplémentaires -->

            <!-- Carte -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Affichage du nombre de résultats -->
            <div class="row">
                <div class="col-md-12">
                    <?php if ($query): ?>
                        <h3>Nombre de terrains trouvés : <?= count($terrains) ?></h3>
                    <?php else: ?>
                        <h3>Liste de tous les terrains</h3>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Affichage des résultats -->
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <?php foreach ($terrains as $terrain): ?>
                            <li class="list-group-item">
                                <h5><?= htmlspecialchars($terrain['nom']) ?></h5>
                                <p>Adresse : <?= htmlspecialchars($terrain['adresse']) ?></p>
                                <p>Sport : <?= htmlspecialchars($terrain['type_sport']) ?></p>
                                <p>Arrondissement : <?= htmlspecialchars($terrain['arrondissement']) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        </section>
    </main>

    <!-- JavaScript map -->
    <script>
        // Variables pour stocker la position de l'utilisateur
        var userLat = 48.8566; // Paris par défaut
        var userLon = 2.3522;

        // Utilisation de l'API de géolocalisation du navigateur pour obtenir la position
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;

                // Centrer la carte sur la position de l'utilisateur
                map.setView([userLat, userLon], 12);

                // Ajouter un marqueur pour la position de l'utilisateur
                L.marker([userLat, userLon]).addTo(map)
                    .bindPopup("<b>Vous êtes ici</b>")
                    .openPopup();
            });
        }

        // Initialisation de la carte avec Leaflet
        var map = L.map('map').setView([userLat, userLon], 12); // Paris par défaut

        // Carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ajouter tous les marqueurs des terrains sur la carte et les classer par distance
        var terrains = <?php echo json_encode($terrains); ?>;
        var distances = [];

        terrains.forEach(function(terrain) {
            var distance = calculer_distance(userLat, userLon, terrain.latitude, terrain.longitude);
            distances.push({terrain: terrain, distance: distance});
        });

        // Trier les terrains par distance (du plus proche au plus éloigné)
        distances.sort(function(a, b) {
            return a.distance - b.distance;
        });

        // Ajouter des marqueurs pour les terrains triés
        distances.forEach(function(item) {
            var terrain = item.terrain;
            var marker = L.marker([terrain.latitude, terrain.longitude]).addTo(map);
            marker.bindPopup("<b>" + terrain.nom + "</b><br>Adresse: " + terrain.adresse + "<br>Sport: " + terrain.type_sport);
        });

        // Fonction pour calculer la distance entre deux points (en km)
        function calculer_distance(lat1, lon1, lat2, lon2) {
            var R = 6371; // Rayon de la Terre en kilomètres
            var phi1 = deg2rad(lat1);
            var phi2 = deg2rad(lat2);
            var delta_phi = deg2rad(lat2 - lat1);
            var delta_lambda = deg2rad(lon2 - lon1);
            var a = Math.sin(delta_phi / 2) * Math.sin(delta_phi / 2) +
                    Math.cos(phi1) * Math.cos(phi2) *
                    Math.sin(delta_lambda / 2) * Math.sin(delta_lambda / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = R * c; // Distance en kilomètres
            return distance;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }
    </script>

</body>
</html>

<?php include('includes/footer.php'); ?>
