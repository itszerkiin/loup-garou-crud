<?php
// models/Carte.php

class Carte
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllCartes()
    {
        // Récupère toutes les cartes de la base de données
        $stmt = $this->pdo->query('SELECT * FROM cartes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCarteById($id)
    {
        // Récupère une carte par son ID
        $stmt = $this->pdo->prepare('SELECT * FROM cartes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCarte($nom, $description, $photo, $categorie)
    {
        // Insère une nouvelle carte dans la base de données
        $stmt = $this->pdo->prepare('INSERT INTO cartes (nom, description, photo, categorie) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$nom, $description, $photo, $categorie]);
    }
}
?>
