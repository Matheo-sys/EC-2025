 <?php
$serveur = "localhost";
$port = 8889;
$utilisateur = "root";
$motdepasse = "root";
$bdd = "parissport_bdd";

try {
    $conn = new PDO(
        "mysql:host=$serveur;port=$port;dbname=$bdd;charset=utf8",
        $utilisateur,
        $motdepasse,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>