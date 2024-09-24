<?php
// models/Composition.php

class Composition
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllCompositions($search = '') {
        $query = 'SELECT c.*, u.pseudo AS utilisateur, COUNT(ld.id) AS likes 
                  FROM compositions c 
                  JOIN utilisateurs u ON c.utilisateur_id = u.id
                  LEFT JOIN likes_dislikes ld ON ld.composition_id = c.id AND ld.type = "like"
                  GROUP BY c.id';
                  
        if ($search) {
            $query .= ' WHERE c.nom LIKE :search';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query($query);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $stmt = $this->pdo->prepare('INSERT INTO compositions (nom, description, nombre_joueurs, cartes, utilisateur_id, likes) VALUES (?, ?, ?, ?, ?, 0)');
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

    public function likeComposition($compositionId, $userId) {
        $stmt = $this->pdo->prepare('SELECT * FROM likes_dislikes WHERE composition_id = ? AND user_id = ?');
        $stmt->execute([$compositionId, $userId]);
        
        if ($stmt->rowCount() == 0) {
            // Ajouter un like si l'utilisateur n'a pas déjà aimé
            $stmt = $this->pdo->prepare('INSERT INTO likes_dislikes (composition_id, user_id, type) VALUES (?, ?, "like")');
            return $stmt->execute([$compositionId, $userId]);
        } else {
            return false; // L'utilisateur a déjà aimé cette composition
        }
    }
    
    
    
    public function hasUserLiked($compositionId, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM likes_dislikes WHERE composition_id = ? AND user_id = ?');
        $stmt->execute([$compositionId, $userId]);
        return $stmt->fetch() !== false;
    }

    public function getLikesCount($compositionId)
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS likes_count FROM likes_dislikes WHERE composition_id = ? AND type = "like"');
        $stmt->execute([$compositionId]);
        $result = $stmt->fetch();
        return $result['likes_count'] ?? 0;
    }

    // Récupérer les 5 compositions les plus aimées
    public function getTopLikedCompositions()
    {
        $stmt = $this->pdo->query('
            SELECT c.*, u.pseudo AS utilisateur, COUNT(ld.id) AS like_count 
            FROM compositions c 
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            LEFT JOIN likes_dislikes ld ON c.id = ld.composition_id AND ld.type = "like"
            GROUP BY c.id
            ORDER BY like_count DESC
            LIMIT 5
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les compositions triées par ordre alphabétique
    public function getAllCompositionsAlphabetical() {
        // Ajout de la jointure avec la table utilisateurs pour récupérer le pseudo
        $stmt = $this->pdo->query('
            SELECT c.*, u.pseudo AS utilisateur
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            ORDER BY c.nom ASC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filtrer les compositions par carte
    public function filterCompositionsByCard($cardId)
    {
        $stmt = $this->pdo->prepare('
            SELECT c.*, u.pseudo AS utilisateur 
            FROM compositions c 
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            WHERE c.cartes LIKE ?
            ORDER BY c.nom ASC
        ');
        $stmt->execute(['%"' . $cardId . '"%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
