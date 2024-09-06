<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger les contrôleurs en fonction de l'action
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'cartes':
    case 'create_carte':
        require_once '../controllers/cartesController.php';
        break;
    case 'create_composition':
        require_once '../controllers/compositionsController.php';
        break;
    case 'login':
    case 'register':
    case 'logout':
        require_once '../controllers/utilisateursController.php';
        break;
    default:
        require_once '../controllers/compositionsController.php';
        break;
}
?>
