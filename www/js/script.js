document.addEventListener("DOMContentLoaded", () => {
    // Module Carte (uniquement si l'élément map existe)
    if (document.getElementById('map')) {
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

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

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

        const terrainsData = document.getElementById('terrains-data');
        if (terrainsData) {
            const terrains = JSON.parse(terrainsData.dataset.terrains);
            const distances = terrains.map(terrain => ({
                terrain,
                distance: calculerDistance(userLat, userLon, terrain.latitude, terrain.longitude)
            })).sort((a, b) => a.distance - b.distance);

            distances.forEach(({ terrain }) => {
                const marker = L.marker([terrain.latitude, terrain.longitude], { icon: simpleIcon }).addTo(map);
                marker.bindPopup(createPopupContent(terrain));
            });
        }

        function createPopupContent(terrain) {
            return `<b>${terrain.nom}</b><br>
                    Adresse: ${terrain.adresse}<br>
                    Sport: ${terrain.type_sport}<br><br>
                    <button class="like-btn" data-element-id="${terrain.id}" data-liked="false">
                        <i class="fa-regular fa-heart heart-icon"></i>
                        <span class="like-text">Like</span>
                    </button>`;
        }

        map.on('popupopen', e => {
            const likeButton = e.popup.getElement().querySelector('.like-btn');
            if (likeButton) likeButton.addEventListener('click', handleLikeClick);
        });
    }

    // Module Likes (uniquement si boutons like existent)
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
                console.error("Erreur: ", data.message);
                button.dataset.liked = isLiked.toString();
            }
        })
        .catch(console.error);
    }

    // Module Password (uniquement sur la page register)
    if (document.getElementById('password')) {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const submitButton = document.getElementById('submitBtn');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        const checkStrength = (password) => {
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            const passed = Object.values(requirements).filter(Boolean).length;
            
            if (!requirements.length) return 'weak';
            if (passed === 4) return 'strong';
            return passed >= 3 ? 'medium' : 'weak';
        };

        const updateUI = (strength) => {
            strengthBar.className = `strength-bar ${strength}`;
            strengthText.textContent = `Mot de passe ${{
                weak: 'faible', 
                medium: 'moyen', 
                strong: 'fort'
            }[strength]}`;
        };

        const validateForm = () => {
            const valid = passwordInput.value === confirmInput.value && 
                         checkStrength(passwordInput.value) === 'strong';
            submitButton.disabled = !valid;
        };

        passwordInput.addEventListener('input', () => {
            updateUI(checkStrength(passwordInput.value));
            validateForm();
        });

        confirmInput.addEventListener('input', validateForm);
    }

    // Fonctions utilitaires globales
    function calculerDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2 - lat1) * Math.PI/180;
        const Δλ = (lon2 - lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) ** 2 + 
                 Math.cos(φ1) * Math.cos(φ2) * 
                 Math.sin(Δλ/2) ** 2;
        
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }
});