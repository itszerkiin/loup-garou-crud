<?php
// controllers/compositionsController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Composition.php';
require_once __DIR__ . '/../models/Carte.php';

$compositionModel = new Composition($pdo);
$carteModel = new Carte($pdo);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if (!isset($_SESSION['user_id'])) {
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

    case 'like':
        if (!isset($_SESSION['user_id'])) {
            // Rediriger vers la page de connexion
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $userId = $_SESSION['user_id'];
        if ($id && !$compositionModel->hasUserLikedOrDisliked($id, $userId)) {
            $compositionModel->likeComposition($id, $userId);
        }
        header('Location: /loup-garou-crud/public/index.php');
        exit;

    case 'dislike':
        if (!isset($_SESSION['user_id'])) {
            // Rediriger vers la page de connexion
            header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $userId = $_SESSION['user_id'];
        if ($id && !$compositionModel->hasUserLikedOrDisliked($id, $userId)) {
            $compositionModel->dislikeComposition($id, $userId);
        }
        header('Location: /loup-garou-crud/public/index.php');
        exit;

    default:
        $search = $_GET['search'] ?? '';
        $compositions = $compositionModel->getAllCompositions($search);
        include '../views/compositions/index.php';
        break;
}
?>
