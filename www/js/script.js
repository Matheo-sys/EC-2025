document.addEventListener("DOMContentLoaded", () => {
    // Configuration de la carte
    const simpleIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [0, -41],
        shadowUrl: '',
        shadowSize: [0, 0],
        shadowAnchor: [0, 0]
    });

    let userLat = 48.8566;
    let userLon = 2.3522;
    const map = L.map('map').setView([userLat, userLon], 12);

    // Couche carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Géolocalisation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            userLat = position.coords.latitude;
            userLon = position.coords.longitude;
            map.setView([userLat, userLon], 12);
            L.marker([userLat, userLon], { icon: simpleIcon })
                .addTo(map)
                .bindPopup("<b>Vous êtes ici</b>")
                .openPopup();
        });
    }

    // Gestion des terrains
    const terrainsData = document.getElementById('terrains-data');
    const terrains = JSON.parse(terrainsData.dataset.terrains);
    const distances = terrains.map(terrain => ({
        terrain,
        distance: calculerDistance(userLat, userLon, terrain.latitude, terrain.longitude)
    })).sort((a, b) => a.distance - b.distance);

    distances.forEach(({ terrain }) => {
        const marker = L.marker([terrain.latitude, terrain.longitude], { icon: simpleIcon }).addTo(map);
        marker.bindPopup(createPopupContent(terrain));
    });

    // Gestion des likes
    function handleLikeClick(event) {
        const button = event.currentTarget;
        const elementId = button.dataset.elementId;
        const isLiked = button.dataset.liked === 'true';
        const heartIcon = button.querySelector('.heart-icon');
        const likeText = button.querySelector('.like-text');

        button.dataset.liked = (!isLiked).toString();
        heartIcon.classList.toggle('fa-regular', isLiked);
        heartIcon.classList.toggle('fa-solid', !isLiked);
        likeText.textContent = isLiked ? 'Like' : 'Unlike';

        fetch('likes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ element_id: elementId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'liked' && data.status !== 'unliked') {
                console.error("Erreur: " + data.message);
                button.dataset.liked = isLiked.toString(); // Annulation visuelle
            }
        })
        .catch(console.error);
    }

    map.on('popupopen', e => {
        const likeButton = e.popup.getElement().querySelector('.like-btn');
        if (likeButton) likeButton.addEventListener('click', handleLikeClick);
    });

    // Fonctions utilitaires
    function createPopupContent(terrain) {
        return `<b>${terrain.nom}</b><br>
                Adresse: ${terrain.adresse}<br>
                Sport: ${terrain.type_sport}<br><br>
                <button class="like-btn" data-element-id="${terrain.id}" data-liked="false">
                    <i class="fa-regular fa-heart heart-icon"></i>
                    <span class="like-text">Like</span>
                </button>`;
    }

    function calculerDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const φ1 = deg2rad(lat1);
        const φ2 = deg2rad(lat2);
        const Δφ = deg2rad(lat2 - lat1);
        const Δλ = deg2rad(lon2 - lon1);
        const a = Math.sin(Δφ/2) ** 2 + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    // Validation mot de passe
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const submitButton = document.getElementById("submitBtn");
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");

    function checkPasswordStrength(password) {
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const isLong = password.length >= 8;

        if (!isLong) return "weak";
        return [hasUpper, hasNumber, hasSpecial].filter(Boolean).length === 3 ? "strong" :
               [hasUpper, hasNumber, hasSpecial].filter(Boolean).length >= 2 ? "medium" : "weak";
    }

    function updateStrengthDisplay(strength) {
        strengthBar.className = `strength-bar ${strength}`;
        strengthText.textContent = `Mot de passe ${['faible', 'moyen', 'fort'][['weak', 'medium', 'strong'].indexOf(strength)]}`;
    }

    function validateForm() {
        const isValid = passwordInput.value === confirmPasswordInput.value && 
                        checkPasswordStrength(passwordInput.value) === "strong";
        submitButton.disabled = !isValid;
    }

    passwordInput.addEventListener("input", () => {
        updateStrengthDisplay(checkPasswordStrength(passwordInput.value));
        validateForm();
    });

    confirmPasswordInput.addEventListener("input", validateForm);
});