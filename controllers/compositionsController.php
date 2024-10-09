<?php
// controllers/compositionsController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Composition.php';
require_once __DIR__ . '/../models/Carte.php';
require_once __DIR__ . '/../models/Utilisateur.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$compositionModel = new Composition($pdo);
$carteModel = new Carte($pdo);
$utilisateurModel = new Utilisateur($pdo);

$action = $_GET['action'] ?? '';

$isUserConnected = isset($_SESSION['user_id']);
$isAdmin = $isUserConnected && $_SESSION['role'] === 'admin';

switch ($action) {
    // Créer une nouvelle composition
    case 'create':
        if (!$isUserConnected) {
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $nombre_joueurs = $_POST['nombre_joueurs'];
            $cartes = $_POST['cartes'] ?? [];
            $utilisateur_id = $_SESSION['user_id'];

            if ($nom && $description && $nombre_joueurs && !empty($cartes)) {
                $compositionModel->createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id);
                header('Location: /loup-garou-crud/public/index.php');
                exit;
            } else {
                echo "Erreur : Tous les champs sont requis.";
            }
        } else {
            $cartesDisponibles = $carteModel->getAllCartes();
            include '../views/compositions/create.php';
        }
        break;

        case 'delete_composition':
            $id = $_GET['id'] ?? null;
        
            // Vérifie si l'utilisateur est connecté et est l'auteur ou un administrateur
            if ($id && isset($_SESSION['user_id']) && ($compositionModel->isAuthor($_SESSION['user_id'], $id) || $isAdmin)) {
                $compositionModel->deleteComposition($id);
                header('Location: /loup-garou-crud/public/index.php');
                exit;
            } 
        
            case 'edit':
                if (!$isUserConnected) {
                    header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
                    exit;
                }
            
                $id = $_GET['id'] ?? null;
            
                if ($id && isset($_SESSION['user_id']) && ($compositionModel->isAuthor($_SESSION['user_id'], $id) || $isAdmin)) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $nom = $_POST['nom'];
                        $description = $_POST['description'];
                        $nombre_joueurs = $_POST['nombre_joueurs'];
                        $cartes = $_POST['cartes'];
            
                        // Met à jour la composition dans la base de données
                        $compositionModel->updateComposition($id, $nom, $description, $nombre_joueurs, $cartes);
                        header('Location: /loup-garou-crud/public/index.php');
                        exit;
                    } else {
                        // Récupère la composition à éditer
                        $composition = $compositionModel->getCompositionById($id);
            
                        // Vérifie si la composition existe
                        if (!$composition) {
                            echo "Erreur : Composition introuvable.";
                            exit;
                        }
            
                        // Récupère toutes les cartes disponibles
                        $cartesDisponibles = $carteModel->getAllCartes();
            
                        // Inclut la vue avec les données nécessaires
                        include __DIR__ . '/../views/compositions/edit.php';
                        exit;
                    }
                } else {
                    echo "Vous n'avez pas la permission de modifier cette composition.";
                    exit;
                }
            
   
        
        

    // Filtrer les compositions
    case 'filter':
        $nombre_joueurs = $_GET['nombre_joueurs'] ?? null;
        $card_ids = $_GET['card_ids'] ?? [];

        if ($nombre_joueurs && !empty($card_ids)) {
            $filteredCompositions = $compositionModel->filterByCardsAndPlayers($card_ids, $nombre_joueurs);
        } elseif ($nombre_joueurs) {
            $filteredCompositions = $compositionModel->filterByNumberOfPlayers($nombre_joueurs);
        } elseif (!empty($card_ids)) {
            $filteredCompositions = $compositionModel->filterByExactCards($card_ids);
        } else {
            $filteredCompositions = $compositionModel->getAllCompositionsAlphabetical();
        }

        include '../views/compositions/_compositions_list.php';
        break;

    // Affichage par défaut
    default:
        $topLikedCompositions = $compositionModel->getTopLikedCompositions();
        $compositionsAlphabetical = $compositionModel->getAllCompositionsAlphabetical();
        $cartesDisponibles = $carteModel->getAllCartes();
        include '../views/compositions/index.php';
        break;
        // controllers/compositionsController.php

// Gérer le "like" d'une composition
case 'like':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $compositionId = $_POST['composition_id'];
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId) {
            // Ajouter un like si l'utilisateur n'a pas encore aimé la composition
            if (!$compositionModel->hasUserLiked($compositionId, $userId)) {
                $compositionModel->likeComposition($compositionId, $userId);
            }
        }
        // Rediriger vers la page précédente après avoir traité la requête
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    break;

}
