<?php

class Commentaire {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public static function findByComposition($composition_id) {
        global $pdo; // Utilisation de la connexion PDO globale
        $stmt = $pdo->prepare("
            SELECT c.*, u.pseudo AS utilisateur_nom 
            FROM commentaires c
            JOIN utilisateurs u ON c.utilisateur_id = u.id 
            WHERE c.composition_id = ?
        ");
        $stmt->execute([$composition_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function create($contenu, $utilisateur_id, $composition_id) {
        global $pdo; // Utilisation de la connexion PDO globale
        $stmt = $pdo->prepare("
            INSERT INTO commentaires (contenu, utilisateur_id, composition_id, date_creation)
            VALUES (:contenu, :utilisateur_id, :composition_id, NOW())
        ");
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $stmt->bindParam(':composition_id', $composition_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>
