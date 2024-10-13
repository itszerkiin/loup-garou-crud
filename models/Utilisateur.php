<?php
// models/Utilisateur.php

class Utilisateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer un utilisateur par son pseudo ou email
    public function getUtilisateurByPseudoOrEmail($identifiant) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ? OR email = ?");
        $stmt->execute([$identifiant, $identifiant]);
        return $stmt->fetch();
    }

    // Inscrire un utilisateur avec le rôle par défaut 'user'
    public function registerUser($pseudo, $email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$pseudo, $email, $hashedPassword, $role]);
    }

    // Vérifier si l'utilisateur est admin
    public function isAdmin($userId) {
        $stmt = $this->pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        return $user && $user['role'] === 'admin';
    }

    // Fonction de connexion, vérifie l'email/pseudo et le mot de passe
    public function login($identifiant, $password) {
        $user = $this->getUtilisateurByPseudoOrEmail($identifiant);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}

