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

    case 'delete':
        if (!$isAdmin) {
            header('Location: /loup-garou-crud/public/index.php?action=login&error=access_denied');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $compositionModel->deleteComposition($id);
        }
        header('Location: /loup-garou-crud/public/index.php');
        exit;

    case 'edit':
        if (!$isUserConnected) {
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $nombre_joueurs = $_POST['nombre_joueurs'];
            $cartes = $_POST['cartes'];

            $compositionModel->updateComposition($id, $nom, $description, $nombre_joueurs, $cartes);
            header('Location: /loup-garou-crud/public/index.php');
        } else {
            $id = $_GET['id'] ?? null;
            $composition = $compositionModel->getCompositionById($id);
            $cartesDisponibles = $carteModel->getAllCartes();
            include '../views/compositions/edit.php';
        }
        break;

    case 'like':
        if (!$isUserConnected) {
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        $compositionId = $_POST['composition_id'] ?? null;
        if ($compositionId) {
            $userId = $_SESSION['user_id'];
            if (!$compositionModel->hasUserLiked($compositionId, $userId)) {
                $compositionModel->likeComposition($compositionId, $userId);
                header('Location: /loup-garou-crud/public/index.php');
                exit;
            } else {
                echo "Vous avez déjà aimé cette composition.";
            }
        }
        break;

    case 'top_liked':
        $topLikedCompositions = $compositionModel->getTopLikedCompositions();
        include '../views/compositions/top_liked.php';
        break;

    case 'filter_by_card':
        $cardId = $_GET['card_id'] ?? '';
        if ($cardId) {
            $filteredCompositions = $compositionModel->filterCompositionsByCard($cardId);
            include '../views/compositions/filter.php';
        } else {
            header('Location: /loup-garou-crud/public/index.php');
        }
        break;

    default:
        $search = $_GET['search'] ?? '';
        $topLikedCompositions = $compositionModel->getTopLikedCompositions();
        $compositionsAlphabetical = $compositionModel->getAllCompositionsAlphabetical();
        $cartesDisponibles = $carteModel->getAllCartes(); // Assurer la disponibilité des cartes
        include '../views/compositions/index.php';
        break;
}
