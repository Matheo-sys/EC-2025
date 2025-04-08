<?php
require_once("config/database.php");
include('includes/header.php');

$query = isset($_GET['q']) ? $_GET['q'] : '';

$sql = $conn->prepare("SELECT * FROM equipements_sportifs_paris");
$sql->execute();
$terrains = $sql->fetchAll(PDO::FETCH_ASSOC);

if ($query) {
    $sql = $conn->prepare("SELECT * FROM equipements_sportifs_paris WHERE nom LIKE :query OR adresse LIKE :query OR type_sport LIKE :query");
    $sql->execute(['query' => '%' . $query . '%']);
    $terrains = $sql->fetchAll(PDO::FETCH_ASSOC);
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

            <!-- Formulaire de recherche -->
            <form action="recherche.php" method="get" class="d-flex justify-content-center mb-4">
                <div class="input-group w-50">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher un terrain sportif..." value="<?= htmlspecialchars($query) ?>" required>
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
            </form>

                        <!-- Carte -->
                        <div class="row mt-4">
                <div class="col-md-12">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Affichage des résultats -->
            <div class="row">
                <div class="col-md-12">
                    <h3>Terrains trouvés : <?= count($terrains) ?></h3>
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

    <!-- JavaScript -->
    <script>
        // Initialisation de la carte avec Leaflet
        var map = L.map('map').setView([48.8566, 2.3522], 12); // Paris par défaut

        // Carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ajouter les marqueurs des terrains sur la carte
        <?php foreach ($terrains as $terrain): ?>
            var marker = L.marker([<?= $terrain['latitude'] ?>, <?= $terrain['longitude'] ?>]).addTo(map);
            marker.bindPopup("<b><?= htmlspecialchars($terrain['nom']) ?></b><br>Adresse: <?= htmlspecialchars($terrain['adresse']) ?><br>Sport: <?= htmlspecialchars($terrain['type_sport']) ?>");
        <?php endforeach; ?>
    </script>

</body>
</html>

<?php include('includes/footer.php'); ?>
