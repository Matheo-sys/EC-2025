<?php
require_once("config/database.php");
session_start();

include('includes/header.php');

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] != 5) { 
    header('Location: index.php');
    exit();
}

// Récupération des équipements
$stmt = $conn->prepare("SELECT * FROM equipements_sportifs_paris ORDER BY id DESC");
$stmt->execute();
$equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - ParisSport+</title>
    
    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Inclusion des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
</head>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Gestion des Équipements Sportifs</h1>

    <div class="text-end mb-3">
        <a href="add_equipement.php" class="btn btn-primary" style="background-color: #2B9348; border-color: #2B9348;">Ajouter un équipement</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Sport</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($equipements as $eq): ?>
                <tr>
                    <td><?= htmlspecialchars($eq['id']) ?></td>
                    <td><?= htmlspecialchars($eq['nom']) ?></td>
                    <td><?= htmlspecialchars($eq['adresse']) ?></td>
                    <td><?= htmlspecialchars($eq['type_sport']) ?></td>
                    <td>
                        <a href="edit_equipement.php?id=<?= $eq['id'] ?>" class="btn btn-warning btn-sm me-2">Modifier</a>
                        <a href="delete_equipement.php?id=<?= $eq['id'] ?>" onclick="return confirm('Supprimer cet équipement ?')" class="btn btn-danger btn-sm me-2">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
<?php include('includes/footer.php'); ?>
