<?php
require_once("config/database.php");

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
// Après la récupération des autres paramètres
if (!empty($_GET['sports'])) {
    $sportConditions = [];
    
    foreach ($_GET['sports'] as $sportKeywords) {
        $keywords = explode(',', $sportKeywords);
        $sportOrConditions = [];
        
        foreach ($keywords as $keyword) {
            $paramKey = 'sport_' . uniqid();
            $sportOrConditions[] = "type_sport LIKE :$paramKey";
            $params[$paramKey] = "%$keyword%";
        }
        
        if (!empty($sportOrConditions)) {
            $sportConditions[] = '(' . implode(' OR ', $sportOrConditions) . ')';
        }
    }
    
    if (!empty($sportConditions)) {
        $whereClauses[] = '(' . implode(' AND ', $sportConditions) . ')';
    }
}
if (count($whereClauses) > 0) {
    $sql .= " AND " . implode(" AND ", $whereClauses);
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$terrains = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userLikes = [];

if (isset($_SESSION['user']['id'])) {
    // Récupérer tous les likes de l'utilisateur
    $stmt = $conn->prepare("SELECT element_id FROM likes WHERE user_id = ?");
    $stmt->execute([$_SESSION['user']['id']]);
    $userLikes = $stmt->fetchAll(PDO::FETCH_COLUMN);
}


// Fonction slug
function slugify($text) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
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
        <form action="map.php" method="get" class="d-flex flex-column align-items-center">
            <div class="input-group w-50">
                <input type="text" name="q" class="form-control" placeholder="Rechercher un terrain sportif...">
                <button class="btn btn-primary" style="background-color: #2B9348; border-color:#2B9348" type="submit" href="map.php">Rechercher</button>
            </div>

            <!-- Checkbox filtres -->
            <div class="filters-container mb-3 mt-4"  width="100%">
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <?php
                    $sportsFilters = [
                        'Foot' => ['foot', 'soccer', 'football'],
                        'Basket' => ['basket', 'basketball'],
                        'Pétanque' => ['pétanque', 'boules'],
                        'Volley' => ['volley', 'volleyball'],
                        'Tennis' => ['tennis'],
                        'Musculation' => ['muscu', 'fitness', 'musculation','forme%','remise%'],
                        'Multi-sports/City' => ['multi', 'polyvalent','city%'],
                        'Piste Cyclabe' => ['cyclisme', 'vélo', 'piste%', 'cyclable'],
                    ];
                    
                    foreach ($sportsFilters as $label => $keywords): ?>
                        <div class="form-check">
                            <input class="form-check-input" 
                                type="checkbox" 
                                name="sports[]" 
                                value="<?= implode(',', $keywords) ?>" 
                                id="filter_<?= slugify($label) ?>"
                                <?= isset($_GET['sports']) && array_intersect($keywords, $_GET['sports']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="filter_<?= slugify($label) ?>">
                                <?= $label ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                <?php 
                    $alreadyLiked = in_array($terrain['id'], $userLikes);
                ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($terrain['nom']) ?></h5>
                            <p class="card-text">Adresse : <?= htmlspecialchars($terrain['adresse']) ?></p>
                            <p class="card-text">Type/Sport : <?= htmlspecialchars($terrain['type_sport']) ?></p>
                            <p class="card-text">Code Postal : <?= htmlspecialchars($terrain['arrondissement']) ?></p>
                            <p class="card-text">Accès handicap : <?= htmlspecialchars($terrain['handicap_access']) ?></p>
                            <?php if (isset($_SESSION['user']["id"])): ?>
                                <button class="like-btn" 
                        data-element-id="<?= $terrain['id'] ?>" 
                        data-liked="<?= $alreadyLiked ? 'true' : 'false' ?>">
                    <i class="heart-icon <?= $alreadyLiked ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                            <?php else: ?>
                                <p class="text-muted">Connectez-vous pour aimer ce terrain.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            
            <?php endforeach; ?>

    </section>
</main>


</body>
</html>
<script>
    var simpleIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [0, -41],
        shadowUrl: '',          
        shadowSize: [0, 0],
        shadowAnchor: [0, 0]
    });

    var dotIcon = L.icon({
        iconUrl: 'assets/circle-solid.svg',
        iconSize: [10, 10],
        iconAnchor: [10, 10],
        popupAnchor: [0, -10],
        shadowUrl: '',       
        shadowSize: [0, 0],
        shadowAnchor: [0, 0],
        className: 'dot-icon'
})

    var userLat = 48.8566;
    var userLon = 2.3522;

    // Si l'API de géolocalisation est disponible
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLat = position.coords.latitude;
            userLon = position.coords.longitude;

            map.setView([userLat, userLon], 12);

            // Position de l'utilisateur
            L.marker([userLat, userLon], {icon: dotIcon}).addTo(map)
                .bindPopup("<b>Vous êtes ici</b>")
                .openPopup();
        });
    }

    // Initialisation de la carte avec Leaflet
    var map = L.map('map').setView([userLat, userLon], 12);

    // Ajout de la carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Convertions des terrains (fourni via PHP) en tableau et calculer les distances
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

    // Ajouter des marqueurs pour les terrains triés, en utilisant l'icône simple
    distances.forEach(function(item) {
        var terrain = item.terrain;
        var marker = L.marker([terrain.latitude, terrain.longitude], {icon: simpleIcon}).addTo(map);
        var isLiked = <?= json_encode($userLikes) ?>.includes(terrain.id.toString());
        var popupContent = `
        <b>${terrain.nom}</b><br>
        Adresse: ${terrain.adresse}<br>
        Sport: ${terrain.type_sport}<br><br>
        <button class='like-btn' 
                data-element-id='${terrain.id}' 
                data-liked='${isLiked ? 'true' : 'false'}'>
            <i class='${isLiked ? 'fa-solid' : 'fa-regular'} fa-heart heart-icon'></i>
            <span class='like-text'>${isLiked ? 'Unlike' : 'Like'}</span>
        </button>
    `;
        marker.bindPopup(popupContent);
    });

    // Fonction pour calculer la distance en km entre deux points (formule de Haversine)
    function calculer_distance(lat1, lon1, lat2, lon2) {
        var R = 6371;
        var phi1 = deg2rad(lat1);
        var phi2 = deg2rad(lat2);
        var delta_phi = deg2rad(lat2 - lat1);
        var delta_lambda = deg2rad(lon2 - lon1);
        var a = Math.sin(delta_phi / 2) * Math.sin(delta_phi / 2) +
                Math.cos(phi1) * Math.cos(phi2) *
                Math.sin(delta_lambda / 2) * Math.sin(delta_lambda / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    document.addEventListener('DOMContentLoaded', function() {
      const likeButtons = document.querySelectorAll('.like-btn');

      likeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const elementId = button.getAttribute('data-element-id');
          const isLiked = button.getAttribute('data-liked') === 'true';

          if (!elementId) {
            console.error('ID d\'élément manquant');
            return;
          }
          const dataToSend = new URLSearchParams();
          dataToSend.append('element_id', elementId);
          
          fetch('likes.php', {
            method: 'POST',
            body: dataToSend
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'liked') {
              // Met à jour l'état du bouton en like
              button.setAttribute('data-liked', 'true');
              const icon = button.querySelector('i');
              icon.classList.remove('fa-regular');
              icon.classList.add('fa-solid');
            } else if (data.status === 'unliked') {
              // Met à jour l'état du bouton en unlike
              button.setAttribute('data-liked', 'false');
              const icon = button.querySelector('i');
              icon.classList.remove('fa-solid');
              icon.classList.add('fa-regular');
            } else {
              alert("Erreur: " + data.message);
            }
          })
          .catch(error => {
            console.error('Erreur AJAX:', error);
          });
        });
      });
    });

map.on('popupopen', function(e) {
    const popupNode = e.popup.getElement();

    const likeButton = popupNode.querySelector('.like-btn');

    if (likeButton) {
        likeButton.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut
            const elementId = likeButton.getAttribute('data-element-id');
            const isLiked = likeButton.getAttribute('data-liked') === 'true';
            const heartIcon = likeButton.querySelector('.heart-icon');
            const likeText = likeButton.querySelector('.like-text');

            // Inverse l'état dans l'interface
            const newLikeState = !isLiked;
            likeButton.setAttribute('data-liked', newLikeState ? 'true' : 'false');

            if (newLikeState) {
                heartIcon.classList.remove('fa-regular');
                heartIcon.classList.add('fa-solid');
                likeText.textContent = 'Unlike';
            } else {
                heartIcon.classList.remove('fa-solid');
                heartIcon.classList.add('fa-regular');
                likeText.textContent = 'Like';
            }

            // Envoyer l'ID et le nouvel état via AJAX à likes.php
            fetch('likes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'element_id': elementId,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'liked') {
                    likeButton.setAttribute('data-liked', 'true');
                    heartIcon.classList.remove('fa-regular');
                    heartIcon.classList.add('fa-solid');
                    likeText.textContent = 'Unlike';
                } else if (data.status === 'unliked') {
                    likeButton.setAttribute('data-liked', 'false');
                    heartIcon.classList.remove('fa-solid');
                    heartIcon.classList.add('fa-regular');
                    likeText.textContent = 'Like';
                } else {
                    console.error("Erreur: " + data.message);
                }
            })
            .catch(error => console.error('Erreur AJAX:', error));
        });
    }
});
</script>
<script src="js/script.js"></script>
<?php include('includes/footer.php'); ?>


