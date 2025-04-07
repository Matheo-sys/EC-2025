<?php
// 1. Chemin vers le CSV
$csvFile = __DIR__ . '/recensement_des_equipements_sportifs_a_paris.csv';
if (!file_exists($csvFile)) die("Fichier CSV introuvable");

// 2. Lire le CSV avec le bon séparateur
$csvData = array_map(function($line) {
    return str_getcsv($line, ',', '"', '\\');
}, file($csvFile));

$headers = array_shift($csvData);
$headerCount = count($headers);

// 3. Convertir en JSON avec vérification
$jsonData = [];
foreach ($csvData as $row) {
    if (count($row) === $headerCount) {
        $jsonData[] = array_combine($headers, $row);
    }
}

// 4. Sauvegarder
file_put_contents(__DIR__ . '/equipements.json', json_encode($jsonData, JSON_PRETTY_PRINT));
echo "Conversion réussie ! " . count($jsonData) . " enregistrements traités.";
?>