<?php
// models/Utilisateur.php

class Utilisateur
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUtilisateurByPseudoOrEmail($identifiant)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE pseudo = ? OR email = ?');
        $stmt->execute([$identifiant, $identifiant]);
        return $stmt->fetch();
    }

    public function createUtilisateur($pseudo, $email, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, 'user')");
        return $stmt->execute([$pseudo, $email, $passwordHash]);
    }

    public function isAdmin($userId)
    {
        $stmt = $this->pdo->prepare('SELECT role FROM utilisateurs WHERE id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result && $result['role'] === 'admin';
    }
}
?>
