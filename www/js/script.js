document.addEventListener("DOMContentLoaded", () => {
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");
    const passwordInput = document.getElementById("password");

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
});
