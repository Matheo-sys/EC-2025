<?php
$serveur = "mysql-sportidf.alwaysdata.net";
$utilisateur = "sportidf";
$motdepasse = "qqRyDvP7G#r3crg";
$bdd = "sportidf_bdd";

try {
    $conn = new PDO(
        "mysql:host=$serveur;dbname=$bdd;charset=utf8",
        $utilisateur, 
        $motdepasse,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );


} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
