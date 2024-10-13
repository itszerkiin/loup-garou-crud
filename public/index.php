<?php
// Vérifie si la session est déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger les contrôleurs en fonction de l'action
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'cartes':
    case 'create_carte':
    case 'edit_carte': 
    case 'delete_carte':
        require_once '../controllers/cartesController.php';
        break;
    
    case 'create_composition':
    case 'edit':
    case 'delete_composition':
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

