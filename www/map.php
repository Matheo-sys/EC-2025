<?php
require_once("config/database.php");

include('likes.php');
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
                            <button class="like-btn" data-element-id="<?= $terrain['id'] ?>" data-liked="false">
                            <i class="fa-regular fa-heart"></i> 
                            </button>




                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

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

    // Créer le contenu HTML de la popup avec le bouton like
    var popupContent = 
        "<b>" + terrain.nom + "</b><br>" +
        "Adresse: " + terrain.adresse + "<br>" +
        "Sport: " + terrain.type_sport + "<br><br>" +
        "<button class='like-btn' data-element-id='" + terrain.id + "' data-liked='false'>" +
            "<i class='fa-regular fa-heart heart-icon'></i> " +
            "<span class='like-text'>Like</span>" +
        "</button>";

    marker.bindPopup(popupContent);
});// Ajouter des marqueurs pour les terrains triés
distances.forEach(function(item) {
    var terrain = item.terrain;
    var marker = L.marker([terrain.latitude, terrain.longitude]).addTo(map);

    // Créer le contenu HTML de la popup avec le bouton like positionné en haut à droite
    var popupContent = 
        "<div style='position: relative; padding: 10px;'>" +

            "<div>" +
                "<b>" + terrain.nom + "</b><br>" +
                "Adresse: " + terrain.adresse + "<br>" +
                "Sport: " + terrain.type_sport +
            "</div>" +
                "<button class='like-btn' data-element-id='" + terrain.id + "' data-liked='false'>" +
                    "<i class='fa-regular fa-heart heart-icon'></i> " +
                    "<span class='like-text'>Like</span>" +
                "</button>" +
        "</div>";

    marker.bindPopup(popupContent);
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
    <!-- Likes -->
    <script>
    // Quand le DOM est chargé
    document.addEventListener('DOMContentLoaded', function() {
      // Sélectionne tous les boutons like (si plusieurs)
      const likeButtons = document.querySelectorAll('.like-btn');

      likeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const elementId = button.getAttribute('data-element-id');
          // Récupère l'état actuel du like
          const isLiked = button.getAttribute('data-liked') === 'true';

          // Vérifier que l'elementId est présent
          if (!elementId) {
            console.error('ID d\'élément manquant');
            return;
          }

          // Prépare les données à envoyer en POST
          const dataToSend = new URLSearchParams();
          dataToSend.append('element_id', elementId);
          // Ici, on envoie uniquement l'ID ; le serveur gère l'ajout ou la suppression
          
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

    // Lorsque la popup s'ouvre, attacher l'événement "click" au bouton like qu'elle contient
map.on('popupopen', function(e) {
    // Récupère l'élément DOM de la popup ouverte
    const popupNode = e.popup.getElement();

    // Recherche le bouton like dans la popup
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
                    // Vous pouvez envoyer l'état si nécessaire, par exemple 'liked': newLikeState ? 'true' : 'false'
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Si nécessaire, ajuster l'interface en fonction de la réponse
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
<?php include('js/script.js'); ?>
</body>
</html>

<?php include('includes/footer.php'); ?>


