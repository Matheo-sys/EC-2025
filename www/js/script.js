document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const submitButton = document.getElementById("submitBtn");
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");

    // Fonction pour vérifier la force du mot de passe
    function checkPasswordStrength(password) {
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const isLongEnough = password.length >= 8;

        console.log('Vérification de la force du mot de passe:', password); // Débogage

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

    // Vérifier la force du mot de passe et mettre à jour la barre de force
    passwordInput.addEventListener("input", () => {
        const password = passwordInput.value;
        const strength = checkPasswordStrength(password);

        // Mettre à jour la force du mot de passe
        console.log('Force du mot de passe:', strength); // Débogage

        if (strength === "weak") {
            strengthBar.className = "strength-bar weak";
            strengthText.textContent = "Mot de passe faible";
        } else if (strength === "medium") {
            strengthBar.className = "strength-bar medium";
            strengthText.textContent = "Mot de passe moyen";
        } else if (strength === "strong") {
            strengthBar.className = "strength-bar strong";
            strengthText.textContent = "Mot de passe fort";
        }

        // Vérifier si le bouton doit être activé
        validateSubmitButton();
    });

    // Vérifier si les mots de passe correspondent et si la force est "strong"
    confirmPasswordInput.addEventListener("input", () => {
        console.log('Confirm Password:', confirmPasswordInput.value); // Débogage
        validateSubmitButton();
    });

    // Fonction pour valider si le bouton de soumission doit être activé
    function validateSubmitButton() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const strength = checkPasswordStrength(password);

        console.log('Mot de passe:', password); // Débogage
        console.log('Confirm Mot de passe:', confirmPassword); // Débogage
        console.log('Force du mot de passe:', strength); // Débogage

        // Désactiver le bouton si les mots de passe ne correspondent pas ou si la force n'est pas "strong"
        if (password !== confirmPassword || strength !== "strong") {
            submitButton.disabled = true;
            console.log('Bouton désactivé'); // Débogage
        } else {
            submitButton.disabled = false;
            console.log('Bouton activé'); // Débogage
        }
    }
});
