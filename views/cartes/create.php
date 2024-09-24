<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Carte</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css"> <!-- Ajoutez votre fichier CSS ici -->
</head>
<body>
    <header>
        <nav>
            <a href="/loup-garou-crud/public/index.php">Accueil</a> | 
            <a href="/loup-garou-crud/public/index.php?action=cartes">Cartes</a> |
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?action=logout">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
            <?php else: ?>
                <a href="?action=login">Connexion</a> | 
                <a href="?action=register">Créer un compte</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <div class="form-container"> <!-- Conteneur du formulaire pour le centrer et le styliser -->
        <h1>Ajouter une Nouvelle Carte</h1>

        <form method="POST" action="?action=create_carte" enctype="multipart/form-data"> <!-- Action pour ajouter la carte -->
            <label for="nom">Nom de la carte:</label>
            <input type="text" name="nom" required> <!-- Champ pour le nom -->

            <label for="description">Description:</label>
            <textarea name="description" required></textarea> <!-- Champ pour la description -->

            <label for="categorie">Catégorie:</label>
            <select name="categorie" required> <!-- Sélecteur pour la catégorie -->
                <option value="neutre">Neutre</option>
                <option value="loup">Loup</option>
                <option value="villageois">Villageois</option>
            </select>

            <label for="photo">Photo :</label>
            <input type="file" name="photo" accept="image/*" required> <!-- Champ pour la photo -->

            <button type="submit">Ajouter la carte</button> <!-- Bouton de soumission -->
        </form>

        <a href="/loup-garou-crud/public/index.php?action=cartes" class="btn-back">Retour à la liste des cartes</a> <!-- Bouton de retour -->
    </div>
</body>
</html>
