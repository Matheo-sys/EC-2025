
<?php
require 'config/database.php';

echo "<h2>Test de connexion à la BDD</h2>";

try {
    // 1. Connexion
    $pdo = connectToDatabase();
    echo "<p style='color:green'>✓ Connexion établie avec succès</p>";
    
    // 2. Vérification table equipements
    $tables = $pdo->query("SHOW TABLES LIKE 'equipements'")->fetchAll();
    
    if (count($tables) > 0) {
        echo "<p style='color:green'>✓ Table 'equipements' trouvée</p>";
        
        // 3. Comptage des enregistrements
        $count = $pdo->query("SELECT COUNT(*) FROM equipements")->fetchColumn();
        echo "<p>Nombre d'équipements : $count</p>";
        
        // 4. Affichage de 3 exemples
        $equipements = $pdo->query("SELECT nom, type_sport FROM equipements LIMIT 3")->fetchAll();
        echo "<h3>Exemples d'équipements :</h3>";
        echo "<ul>";
        foreach ($equipements as $eq) {
            echo "<li>{$eq['nom']} ({$eq['type_sport']})</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:orange'>⚠ Table 'equipements' non trouvée</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    
    // Debug avancé
    echo "<h3>Debug :</h3>";
    echo "<pre>Version PHP : " . phpversion() . "\n";
    echo "Extensions PDO disponibles : " . implode(', ', PDO::getAvailableDrivers());
    
    // Solution pour MAMP
    echo "\n\n<b>Solutions pour MAMP :</b>";
    echo "\n1. Vérifie que MySQL est démarré dans l'interface MAMP";
    echo "\n2. Confirme le port MySQL dans MAMP > Préférences > Ports";
    echo "\n3. Teste la connexion en terminal :";
    echo "\n   <code>mysql -uroot -proot -P8889 -h127.0.0.1</code>";
}