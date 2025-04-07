<?php
// import-data.php
require_once 'www/config/database.php';

try {
    $pdo = getDbConnection();
    
    // 1. Vérification/Création de la table
    $pdo->exec("CREATE TABLE IF NOT EXISTS equipements (
        id VARCHAR(20) PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        adresse TEXT NOT NULL,
        type_sport VARCHAR(50) NOT NULL,
        latitude DECIMAL(10,8),
        longitude DECIMAL(11,8),
        gratuit TINYINT(1) DEFAULT 0,
        handicap_access TINYINT(1) DEFAULT 0,
        arrondissement VARCHAR(20),
        date_maj TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (type_sport),
        INDEX (arrondissement)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 2. Chargement du JSON
    $jsonFile = 'www/equipements-sportifs-paris.json';
    $data = json_decode(file_get_contents($jsonFile), true);
    
    if (!$data || !isset($data['data'])) {
        throw new Exception("Invalid JSON format or missing 'data' key");
    }

    // 3. Préparation requête
    $sql = "INSERT INTO equipements VALUES (
        :id, :nom, :adresse, :type_sport,
        :latitude, :longitude,
        :gratuit, :handicap_access, :arrondissement
    ) ON DUPLICATE KEY UPDATE 
        nom = VALUES(nom),
        adresse = VALUES(adresse)";
    
    $stmt = $pdo->prepare($sql);

    // 4. Import par lots (100 à la fois)
    $batchSize = 100;
    $total = count($data['data']);
    $imported = 0;
    
    for ($i = 0; $i < $total; $i += $batchSize) {
        $batch = array_slice($data['data'], $i, $batchSize);
        
        try {
            $pdo->beginTransaction();
            
            foreach ($batch as $eq) {
                $stmt->execute([
                    ':id' => $eq['id'] ?? null,
                    ':nom' => $eq['nom'] ?? 'Inconnu',
                    ':adresse' => $eq['adresse'] ?? '',
                    ':type_sport' => $eq['type_sport'] ?? 'autre',
                    ':latitude' => $eq['latitude'] ?? 0,
                    ':longitude' => $eq['longitude'] ?? 0,
                    ':gratuit' => (int)($eq['gratuit'] ?? 0),
                    ':handicap_access' => (int)($eq['handicap_access'] ?? 0),
                    ':arrondissement' => $eq['arrondissement'] ?? null
                ]);
                $imported++;
            }
            
            $pdo->commit();
            echo "Lot " . ($i/$batchSize + 1) . " importé (" . min($i + $batchSize, $total) . "/$total)\n";
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Batch " . ($i/$batchSize + 1) . " failed: " . $e->getMessage());
            file_put_contents('import_errors.log', print_r($batch, true), FILE_APPEND);
        }
    }

    echo "Import terminé : $imported/$total équipements\n";
    echo "Vérifiez 'import_errors.log' pour les éventuelles erreurs\n";

} catch (Exception $e) {
    die("ERREUR: " . $e->getMessage());
}