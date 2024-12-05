<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Commentaire.php';

// Démarre une session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_comment':
        $composition_id = $_POST['composition_id'] ?? null;
        $contenu = trim($_POST['contenu'] ?? '');
        $utilisateur_id = $_SESSION['user_id'] ?? null;
    
        if ($composition_id && $utilisateur_id && strlen($contenu) >= 5 && strlen($contenu) <= 250) {
            Commentaire::create($contenu, $utilisateur_id, $composition_id);
            header("Location: index.php?action=show&id={$composition_id}");
        } else {
            echo "Erreur : Le commentaire doit contenir entre 5 et 250 caractères.";
        }
        break;
    
}
?>
