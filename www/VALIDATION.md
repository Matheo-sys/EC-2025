# Documentation de Validation des Données (US 3)

Ce document liste les règles de validation appliquées côté serveur pour garantir l'intégrité des données et la sécurité de l'application.

## 1. Formulaire d'Ajout / Modification d'Équipement
Fichiers concernés : `add_equipement.php`, `edit_equipement.php`

| Champ | Type Attendu | Requis | Format / Contraintes | Longueur Max |
|-------|--------------|--------|----------------------|--------------|
| **ID** | Entier | Oui | Doit être numérique (`ctype_digit`). Unique (vérifié en DB). | 11 chiffres |
| **Nom** | Chaîne | Oui | Pas de HTML. | 255 car. |
| **Adresse** | Chaîne | Oui | Pas de HTML. | 255 car. |
| **Type de Sport** | Chaîne | Oui | Pas de HTML. | 100 car. |
| **Latitude** | Décimal | Oui | Nombre valide (`is_numeric`). | - |
| **Longitude** | Décimal | Oui | Nombre valide (`is_numeric`). | - |
| **Gratuit** | Chaîne | Oui | "Oui" ou "Non" (recommandé) ou texte libre court. | 50 car. |
| **Accès Handicap** | Chaîne | Oui | "Oui" ou "Non" (recommandé) ou texte libre court. | 50 car. |
| **Arrondissement** | Chaîne | Oui | Code postal ou nom d'arrondissement. | 10 car. |

## 2. Formulaire d'Inscription
Fichier concerné : `register.php`

| Champ | Type Attendu | Requis | Format / Contraintes | Longueur Max |
|-------|--------------|--------|----------------------|--------------|
| **Nom** | Chaîne | Oui | Pas de HTML. | 100 car. |
| **Prénom** | Chaîne | Oui | Pas de HTML. | 100 car. |
| **Email** | Email | Oui | Format email valide (`FILTER_VALIDATE_EMAIL`). Unique. | 255 car. |
| **Mot de passe** | Chaîne | Oui | Min 8 caractères. Doit correspondre à la confirmation. | - |

## 3. Formulaire de Connexion
Fichier concerné : `login.php`

| Champ | Type Attendu | Requis | Format / Contraintes |
|-------|--------------|--------|----------------------|
| **Email** | Email | Oui | Format email valide. |
| **Mot de passe** | Chaîne | Oui | Vérifié contre le hash en base. |

## Sécurité Générale
- **Injections SQL** : Toutes les requêtes utilisent `PDO::prepare()` avec des paramètres liés.
- **XSS** : Les affichages de données utilisateur utilisent `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
