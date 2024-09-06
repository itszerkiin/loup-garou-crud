<?php
// controllers/compositionsController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Composition.php';
require_once __DIR__ . '/../models/Carte.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// Vérifie si la session est déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$compositionModel = new Composition($pdo);
$carteModel = new Carte($pdo);
$utilisateurModel = new Utilisateur($pdo);

$action = $_GET['action'] ?? '';

// Vérifier si l'utilisateur est connecté uniquement si nécessaire
$isUserConnected = isset($_SESSION['user_id']);
$isAdmin = $isUserConnected && $utilisateurModel->isAdmin($_SESSION['user_id']);

switch ($action) {
    case 'create':
        // Redirige si l'utilisateur n'est pas connecté
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
        // Redirige si l'utilisateur n'est pas un administrateur
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
        // Redirige si l'utilisateur n'est pas connecté
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

    default:
        // Par défaut, afficher toutes les compositions (lecture seule, pas de redirection)
        $search = $_GET['search'] ?? '';
        $compositions = $compositionModel->getAllCompositions($search);
        include '../views/compositions/index.php';
        break;
}

// Fonction pour gérer "j'aime" ou "je n'aime pas"
function gererAvis($compositionId, $utilisateurId, $avis) {
    global $pdo;
    
    // Vérifie si l'utilisateur a déjà donné son avis pour cette composition
    $stmt = $pdo->prepare('SELECT * FROM avis WHERE composition_id = ? AND utilisateur_id = ?');
    $stmt->execute([$compositionId, $utilisateurId]);
    
    if ($stmt->rowCount() > 0) {
        // Si un avis existe déjà, on le met à jour
        $updateStmt = $pdo->prepare('UPDATE avis SET avis = ? WHERE composition_id = ? AND utilisateur_id = ?');
        $updateStmt->execute([$avis, $compositionId, $utilisateurId]);
    } else {
        // Sinon, on insère un nouvel avis
        $insertStmt = $pdo->prepare('INSERT INTO avis (composition_id, utilisateur_id, avis) VALUES (?, ?, ?)');
        $insertStmt->execute([$compositionId, $utilisateurId, $avis]);
    }
}

// Vérifier si un avis est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avis'], $_POST['composition_id'])) {
    // Redirige si l'utilisateur n'est pas connecté
    if (!$isUserConnected) {
        header('Location: /loup-garou-crud/public/index.php?action=login&error=connect_required');
        exit;
    }

    $compositionId = $_POST['composition_id'];
    $avis = $_POST['avis']; // Peut être 'like' ou 'dislike'

    // Gérer l'avis de l'utilisateur
    gererAvis($compositionId, $_SESSION['user_id'], $avis);

    // Rediriger après la soumission
    header('Location: /loup-garou-crud/public/index.php');
    exit;
}
?>
