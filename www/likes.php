<?php
session_start();
include('config/database.php');
if (isset($_POST['element_id'])) {
    $element_id = $_POST['element_id']; 

    if (empty($element_id)) {
        echo json_encode(["status" => "error", "message" => "L'ID de l'élément est manquant ou invalide"]);
        exit();
    }

    if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
        echo json_encode(["status" => "error", "message" => "Veuillez vous connecter pour aimer cet élément"]);

        exit();
    }

    $user_id = $_SESSION['user']['id'];
    // Vérifier si l'élément existe
    $stmt = $conn->prepare("SELECT * FROM equipements_sportifs_paris WHERE id = :element_id");
    $stmt->execute(['element_id' => $element_id]);
    $element = $stmt->fetch();

    if (!$element) {
        echo json_encode(["status" => "error", "message" => "Élément non trouvé"]);
        exit();
    }

    // Vérifier si le like existe déjà pour cet élément et cet utilisateur
    $stmt = $conn->prepare("SELECT * FROM likes WHERE element_id = :element_id AND user_id = :user_id");
    $stmt->execute(['element_id' => $element_id, 'user_id' => $user_id]);
    $like = $stmt->fetch();

    // Si un like existe déjà on supprime le like (unlike)
    if ($like) {
        $stmt = $conn->prepare("DELETE FROM likes WHERE element_id = :element_id AND user_id = :user_id");
        $stmt->execute(['element_id' => $element_id, 'user_id' => $user_id]);
        echo json_encode(['status' => 'unliked']);
    } else {
        // Si aucun like n'existe on enregistre un nouveau like
        $stmt = $conn->prepare("INSERT INTO likes (element_id, user_id) VALUES (:element_id, :user_id)");
        $stmt->execute(['element_id' => $element_id, 'user_id' => $user_id]);
        echo json_encode(['status' => 'liked']);
    }
} else {
}
?>
