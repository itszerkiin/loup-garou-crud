<?php
// Vérifier si la composition existe
if (isset($composition)) {
    echo "<h1>" . htmlspecialchars($composition->nom) . "</h1>";

    echo "<h2>Cartes associées :</h2>";
    if (!empty($cartes)) {
        foreach ($cartes as $carte) {
            echo "<div>";
            echo "<p>Carte : <strong>" . htmlspecialchars($carte->nom) . "</strong></p>";
            echo "<p>Quantité : " . htmlspecialchars($carte->quantity) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucune carte associée.</p>";
    }

    echo "<h2>Commentaires :</h2>";
    if (!empty($commentaires)) {
        foreach ($commentaires as $commentaire) {
            echo "<div>";
            echo "<p><strong>" . htmlspecialchars($commentaire->utilisateur_nom) . " :</strong> " . htmlspecialchars($commentaire->contenu) . "</p>";
            echo "<small>Posté le " . htmlspecialchars($commentaire->date_creation) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucun commentaire pour cette composition.</p>";
    }

    // Formulaire pour ajouter un commentaire
    if (isset($_SESSION['user_id'])) {
        echo "<form action='index.php?action=add_comment' method='POST'>";
        echo "<input type='hidden' name='composition_id' value='" . htmlspecialchars($composition->id) . "'>";
        echo "<textarea name='contenu' required placeholder='Votre commentaire'></textarea>";
        echo "<button type='submit'>Poster</button>";
        echo "</form>";
    } else {
        echo "<p>Connectez-vous pour commenter.</p>";
    }
} else {
    echo "<p>Composition introuvable.</p>";
}
?>
