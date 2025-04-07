<?php
// database.php - Configuration MAMP (port 8888)
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $config = [
            'host'    => '127.0.0.1',
            'port'    => 8888,
            'dbname'  => 'parissport+',
            'user'    => 'root',
            'pass'    => 'root',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT            => 300,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION wait_timeout=300",
                PDO::MYSQL_ATTR_LOCAL_INFILE => true
            ]
        ];

        try {
            $pdo = new PDO(
                "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4",
                $config['user'],
                $config['pass'],
                $config['options']
            );
            
            // Configuration supplémentaire
            $pdo->exec("SET GLOBAL max_allowed_packet=128000000");
            
        } catch (PDOException $e) {
            error_log("DB Connection failed: " . $e->getMessage());
            throw new Exception("Database connection error. Check MAMP is running on port 8888.");
        }
    }
    
    return $pdo;
}
?>