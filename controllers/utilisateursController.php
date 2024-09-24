<?php
// controllers/utilisateursController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// Vérifier si une session est déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$utilisateurModel = new Utilisateur($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = $_POST['identifiant'];
            $password = $_POST['password'];
            $utilisateur = $utilisateurModel->login($identifiant, $password);

            if ($utilisateur) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['pseudo'] = $utilisateur['pseudo'];
                $_SESSION['role'] = $utilisateur['role']; // Stocker le rôle de l'utilisateur dans la session

                // Rediriger en fonction du rôle de l'utilisateur
                if ($utilisateur['role'] === 'admin') {
                    header('Location: /loup-garou-crud/public/index.php?action=admin');
                } else {
                    header('Location: /loup-garou-crud/public/index.php');
                }
                exit;
            } else {
                echo "Erreur : identifiant ou mot de passe incorrect.";
            }
        } else {
            include '../views/utilisateurs/login.php';
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Enregistrer un utilisateur avec le rôle par défaut 'user'
            if ($utilisateurModel->registerUser($pseudo, $email, $password, 'user')) {
                header('Location: /loup-garou-crud/public/index.php?action=login');
                exit;
            } else {
                echo "Erreur : inscription échouée.";
            }
        } else {
            include '../views/utilisateurs/register.php';
        }
        break;

    case 'admin':
        // Seulement l'admin peut accéder à cette action
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            include '../views/admin/dashboard.php';
        } else {
            echo "Accès refusé.";
        }
        break;

    case 'logout':
        // Détruire la session et rediriger vers la page de connexion
        session_destroy();
        header('Location: /loup-garou-crud/public/index.php?action=login');
        exit;

    default:
        include '../views/utilisateurs/login.php';
        break;
}
