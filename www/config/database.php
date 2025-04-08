<?php
$serveur = "127.0.0.1";
$utilisateur = "root";
$motdepasse = "root";
$bdd = "parissport";

try {
    $conn = new PDO(
        "mysql:host=$serveur;dbname=$bdd;charset=utf8",
        $utilisateur, 
        $motdepasse,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connexion réussie à la base de données !";

} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
