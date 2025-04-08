<?php
require_once("www/config/database.php");

try {
    $sql = $conn->query("SHOW TABLES");

    echo "<h2>Tables présentes dans la base :</h2><ul>";

    while ($table = $sql->fetch(PDO::FETCH_NUM)) {
        echo "<li>" . $table[0] . "</li>";
    }

    echo "</ul>";

} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
try {
    $sql = $conn->query("SELECT * FROM equipements_sportifs_paris LIMIT 10"); 
    echo "<h2>Liste des équipements :</h2><ul>";

    while ($equipement = $sql->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($equipement['nom']) . "</li>";
    }

    echo "</ul>";

} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

