<?php
// models/Composition.php

class Composition
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer toutes les compositions avec le nombre de likes
    public function getAllCompositions($search = '') {
        $query = 'SELECT c.*, u.pseudo AS utilisateur, 
                  (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
                  FROM compositions c
                  JOIN utilisateurs u ON c.utilisateur_id = u.id';

        if ($search) {
            $query .= ' WHERE c.nom LIKE :search';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query($query);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une composition par son ID
    public function getCompositionById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM compositions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    

    // Créer une nouvelle composition
    public function createComposition($nom, $description, $nombre_joueurs, $cartes, $utilisateur_id)
    {
        $cartes_json = json_encode($cartes);
        $stmt = $this->pdo->prepare('INSERT INTO compositions (nom, description, nombre_joueurs, cartes, utilisateur_id) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $utilisateur_id]);
    }

    public function updateComposition($id, $nom, $description, $nombre_joueurs, $cartes)
    {
        $cartes_json = json_encode($cartes); // Assurez-vous que les nouvelles cartes sont en JSON
        $stmt = $this->pdo->prepare('UPDATE compositions SET nom = ?, description = ?, nombre_joueurs = ?, cartes = ? WHERE id = ?');
        return $stmt->execute([$nom, $description, $nombre_joueurs, $cartes_json, $id]);
    }
    

    // Supprimer une composition
    public function deleteComposition($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM compositions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // Ajouter un like pour une composition
    public function likeComposition($compositionId, $userId) {
        if (!$this->hasUserLiked($compositionId, $userId)) {
            $stmt = $this->pdo->prepare('INSERT INTO likes (composition_id, user_id, type) VALUES (?, ?, "like")');
            return $stmt->execute([$compositionId, $userId]);
        }
        return false;
    }

    // Vérifier si un utilisateur a déjà liké une composition
    public function hasUserLiked($compositionId, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT id FROM likes WHERE composition_id = ? AND user_id = ? AND type = "like"');
        $stmt->execute([$compositionId, $userId]);
        return $stmt->fetch() !== false;
    }

    // Récupérer les 5 compositions les plus aimées
    public function getTopLikedCompositions()
    {
        $stmt = $this->pdo->query('
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            ORDER BY likes DESC
            LIMIT 5
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les compositions triées par ordre alphabétique
    public function getAllCompositionsAlphabetical() {
        $stmt = $this->pdo->query('
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            ORDER BY c.nom ASC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filtrer par nombre de joueurs
    public function filterByNumberOfPlayers($nombre_joueurs)
    {
        $stmt = $this->pdo->prepare('
            SELECT c.*, u.pseudo AS utilisateur, 
                   (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
            FROM compositions c
            JOIN utilisateurs u ON c.utilisateur_id = u.id
            WHERE c.nombre_joueurs = ?
            ORDER BY c.nom ASC
        ');
        $stmt->execute([$nombre_joueurs]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// Filtrer par plusieurs cartes
public function filterByExactCards($cardIds)
{
    $query = '
        SELECT c.*, u.pseudo AS utilisateur, 
               (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
        FROM compositions c
        JOIN utilisateurs u ON c.utilisateur_id = u.id
        WHERE 1=1
    ';

    // Si une ou plusieurs cartes sont sélectionnées, ajouter une condition pour chaque carte
    if (!empty($cardIds)) {
        foreach ($cardIds as $cardId) {
            // Vérifier si la carte est présente dans l'objet JSON avec une quantité > 0
            $query .= ' AND CAST(JSON_UNQUOTE(JSON_EXTRACT(c.cartes, \'$."' . $cardId . '"\')) AS UNSIGNED) > 0';
        }
    }

    $query .= ' ORDER BY c.nom ASC';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// Filtrer par cartes et nombre de joueurs
// Filtrer par cartes et nombre de joueurs
public function filterByCardsAndPlayers($cardIds, $nombre_joueurs)
{
    $query = '
        SELECT c.*, u.pseudo AS utilisateur, 
               (SELECT COUNT(*) FROM likes ld WHERE ld.composition_id = c.id AND ld.type = "like") AS likes
        FROM compositions c
        JOIN utilisateurs u ON c.utilisateur_id = u.id
        WHERE c.nombre_joueurs = :nombre_joueurs
    ';

    // Si des cartes sont sélectionnées, ajouter une condition pour chaque carte
    if (!empty($cardIds)) {
        foreach ($cardIds as $cardId) {
            // Vérifier si la carte est présente dans l'objet JSON avec une quantité > 0
            $query .= ' AND CAST(JSON_UNQUOTE(JSON_EXTRACT(c.cartes, \'$."' . $cardId . '"\')) AS UNSIGNED) > 0';
        }
    }

    $query .= ' ORDER BY c.nom ASC';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['nombre_joueurs' => $nombre_joueurs]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérifier si un utilisateur est l'auteur d'une composition
public function isAuthor($userId, $compositionId)
{
    $stmt = $this->pdo->prepare('SELECT utilisateur_id FROM compositions WHERE id = ?');
    $stmt->execute([$compositionId]);
    $composition = $stmt->fetch();
    
    return $composition && $composition['utilisateur_id'] == $userId;
}

public function getAllCartes()
{
    $stmt = $this->pdo->query('SELECT * FROM cartes');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
