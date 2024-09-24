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
        $stmt = $this->pdo->query('SELECT * FROM cartes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCarteById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM cartes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createCarte($nom, $description, $photo, $categorie)
    {
        $stmt = $this->pdo->prepare('INSERT INTO cartes (nom, description, photo, categorie) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$nom, $description, $photo, $categorie]);
    }

    public function updateCarte($id, $nom, $description, $photo, $categorie)
    {
        $stmt = $this->pdo->prepare('UPDATE cartes SET nom = ?, description = ?, photo = ?, categorie = ? WHERE id = ?');
        return $stmt->execute([$nom, $description, $photo, $categorie, $id]);
    }

    public function deleteCarte($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM cartes WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
