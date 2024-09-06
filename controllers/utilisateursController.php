<?php
// controllers/utilisateursController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// La session est déjà démarrée dans index.php

$utilisateurModel = new Utilisateur($pdo);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = $_POST['identifiant']; // Pseudo ou email
            $password = $_POST['password'];

            $utilisateur = $utilisateurModel->getUtilisateurByPseudoOrEmail($identifiant);

            if ($utilisateur && password_verify($password, $utilisateur['password'])) {
                // Initialiser la session après la vérification réussie
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['pseudo'] = $utilisateur['pseudo'];

                // Redirection après connexion réussie
                header('Location: /loup-garou-crud/public/index.php'); // Redirige vers la page d'accueil
                exit;
            } else {
                // Message d'erreur en cas de mauvaise connexion
                header('Location: /loup-garou-crud/public/index.php?action=login&error=invalid_credentials');
                exit;
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

            // Vérifie si l'utilisateur existe déjà par pseudo ou email
            if ($utilisateurModel->getUtilisateurByPseudoOrEmail($pseudo) || $utilisateurModel->getUtilisateurByPseudoOrEmail($email)) {
                header('Location: /loup-garou-crud/public/index.php?action=register&error=user_exists');
                exit;
            }

            // Création de l'utilisateur
            if ($utilisateurModel->createUtilisateur($pseudo, $email, $password)) {
                // Initialiser la session après inscription réussie
                $utilisateur = $utilisateurModel->getUtilisateurByPseudoOrEmail($pseudo);
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['pseudo'] = $utilisateur['pseudo'];

                // Redirection après inscription réussie
                header('Location: /loup-garou-crud/public/index.php'); // Redirige vers la page d'accueil
                exit;
            } else {
                // Message d'erreur en cas d'inscription échouée
                header('Location: /loup-garou-crud/public/index.php?action=register&error=registration_failed');
                exit;
            }
        } else {
            include '../views/utilisateurs/register.php';
        }
        break;

    case 'logout':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /loup-garou-crud/public/index.php');
        exit;

    default:
        header('Location: /loup-garou-crud/public/index.php');
        break;
}
?>
