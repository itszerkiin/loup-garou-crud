<?php
// models/Composition.php

class Composition
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllCompositions($search = '')
    {
        $query = 'SELECT c.*, u.pseudo AS utilisateur 
                  FROM compositions c 
                  JOIN utilisateurs u ON c.utilisateur_id = u.id';
        if ($search) {
            $query .= ' WHERE c.nom LIKE :search';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query($query);
        }
        return $stmt->fetchAll();
    }

    public function getCompositionById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM compositions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id)
    {
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare('INSERT INTO compositions (nom, description, nombre_joueurs, cartes, utilisateur_id, likes, dislikes) VALUES (?, ?, ?, ?, ?, 0, 0)');
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $utilisateur_id]);
    }

    public function updateComposition($id, $nom, $description, $nombre_joueurs, $cartes)
    {
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare('UPDATE compositions SET nom = ?, description = ?, nombre_joueurs = ?, cartes = ? WHERE id = ?');
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $id]);
    }

    public function deleteComposition($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM compositions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function likeComposition($id, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO likes_dislikes (composition_id, user_id, type) VALUES (?, ?, "like")');
        return $stmt->execute([$id, $userId]);
    }

    public function dislikeComposition($id, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO likes_dislikes (composition_id, user_id, type) VALUES (?, ?, "dislike")');
        return $stmt->execute([$id, $userId]);
    }

    public function hasUserLikedOrDisliked($compositionId, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM likes_dislikes WHERE composition_id = ? AND user_id = ?');
        $stmt->execute([$compositionId, $userId]);
        return $stmt->fetch() !== false;
    }
}
?>
