document.addEventListener("DOMContentLoaded", () => {
    const gameBoard = document.getElementById("gameBoard");
    const startGameButton = document.getElementById("startGame");
    const timerElement = document.getElementById("timer");
    const quitButton = document.getElementById("quitButton");
    const replayButton = document.getElementById("replayButton");
    const gameOverModal = document.getElementById("gameOverModal");
    const gameOverMessage = document.getElementById("gameOverMessage");
    const passwordInput = document.getElementById("password");
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");

    let timerInterval;
    let startTime;
    let selectedCards = [];
    let matchedPairs = 0;
    let totalPairs;
    let endTime;

    // Bouton pour relancer une partie
    if (replayButton) {
        replayButton.addEventListener("click", () => {
            gameOverModal.style.display = "none"; // Fermer la popup
            const theme = document.getElementById("theme").value;
            const difficulty = parseInt(document.getElementById("difficulty").value);
            startGame(theme, difficulty); // Relancer le jeu avec les mêmes paramètres
        });
    }

    // Bouton pour quitter la popup
    if (quitButton) {
        quitButton.addEventListener("click", () => {
            gameOverModal.style.display = "none"; 
        });
    }

    // Bouton pour lancer une nouvelle partie depuis la page principale
    if (startGameButton) {
        startGameButton.addEventListener("click", () => {
            const theme = document.getElementById("theme").value;
            const difficulty = parseInt(document.getElementById("difficulty").value);
            startGame(theme, difficulty);
        });
    }

    // Vérification de la force du mot de passe
    if (passwordInput) {
        passwordInput.addEventListener("input", () => {
            const password = passwordInput.value;
            let strength = checkPasswordStrength(password);

            // Met à jour la classe et le texte en fonction de la force du mot de passe
            if (strength === "weak") {
                strengthBar.className = "strength-bar weak";
                strengthText.textContent = "Mot de passe faible";
            } else if (strength === "medium") {
                strengthBar.className = "strength-bar medium";
                strengthText.textContent = "Mot de passe moyen";
            } else if (strength === "strong") {
                strengthBar.className = "strength-bar strong";
                strengthText.textContent = "Mot de passe fort";
            } else {
                strengthBar.className = "strength-bar";
                strengthText.textContent = "";
            }
        });
    }

    // Fonction pour vérifier la force du mot de passe
    function checkPasswordStrength(password) {
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const isLongEnough = password.length >= 8;

        if (!isLongEnough || (!hasUpperCase && !hasNumber && !hasSpecialChar)) {
            return "weak";
        }

        if (isLongEnough && hasUpperCase && hasNumber && !hasSpecialChar) {
            return "medium";
        }

        if (isLongEnough && hasUpperCase && hasNumber && hasSpecialChar) {
            return "strong";
        }

        return "weak";
    }

    // Fonctionnalités du jeu
    function startGame(theme, gridSize) {
        clearInterval(timerInterval);
        resetGame();
        startTimer();

        const images = prepareImages(gridSize);
        generateGrid(images, gridSize, theme);
    }

    function prepareImages(gridSize) {
        const totalCells = gridSize * gridSize;
        totalPairs = totalCells / 2;

        const allImages = Array.from({ length: totalPairs }, (_, i) => `${i + 1}.png`);
        return shuffleArray([...allImages, ...allImages]);
    }

    function generateGrid(images, gridSize, theme) {
        gameBoard.style.gridTemplateColumns = `repeat(${gridSize}, 1fr)`;
        gameBoard.innerHTML = "";

        images.forEach((image, index) => {
            const cell = document.createElement("div");
            cell.classList.add("cell");

            const img = document.createElement("img");
            img.src = `../Themes/${theme}/${image}`;
            img.alt = "Memory Card";
            img.classList.add("hidden");

            cell.appendChild(img);
            cell.addEventListener("click", () => handleCardClick(cell));
            gameBoard.appendChild(cell);
        });
    }

    function handleCardClick(cell) {
        if (cell.classList.contains("flipped") || selectedCards.length >= 2) return;

        cell.classList.add("flipped");
        const img = cell.querySelector("img");
        img.classList.remove("hidden");
        selectedCards.push(cell);

        if (selectedCards.length === 2) {
            checkForMatch();
        }
    }

    function checkForMatch() {
        const [first, second] = selectedCards;
        const firstImage = first.querySelector("img").src;
        const secondImage = second.querySelector("img").src;

        if (firstImage === secondImage) {
            matchedPairs++;
            selectedCards = [];

            if (matchedPairs === totalPairs) {
                showGameOverModal();
                clearInterval(timerInterval);
            }
        } else {
            setTimeout(() => {
                first.classList.remove("flipped");
                second.classList.remove("flipped");
                first.querySelector("img").classList.add("hidden");
                second.querySelector("img").classList.add("hidden");
                selectedCards = [];
            }, 1000);
        }
    }

    function startTimer() {
        startTime = Date.now();
        timerInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            const milliseconds = elapsed % 1000;

            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, "0")}.${milliseconds.toString().padStart(3, "0")}`;
        }, 10);
    }

    function resetGame() {
        gameBoard.innerHTML = "";
        selectedCards = [];
        matchedPairs = 0;
        timerElement.textContent = "00:00.000";
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    function showGameOverModal() {
        endTime = timerElement.textContent;
        gameOverMessage.textContent = `Partie terminée ! Temps : ${endTime}`;
        gameOverModal.style.display = "flex";
    }
});
