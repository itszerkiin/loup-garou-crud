<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Carte</title>
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
    
    <div class="form-container">
        <h1>Modifier la Carte</h1>

        <form method="POST" action="?action=edit_carte&id=<?= $carte['id'] ?>" enctype="multipart/form-data"> <!-- Action pour modifier la carte -->
            <label for="nom">Nom de la carte:</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($carte['nom']) ?>" required> <!-- Pré-remplir avec le nom existant -->

            <label for="description">Description:</label>
            <textarea name="description" required><?= htmlspecialchars($carte['description']) ?></textarea> <!-- Pré-remplir avec la description existante -->

            <label for="categorie">Catégorie:</label>
            <select name="categorie" required> <!-- Sélectionner la catégorie actuelle -->
                <option value="neutre" <?= $carte['categorie'] === 'neutre' ? 'selected' : '' ?>>Neutre</option>
                <option value="loup" <?= $carte['categorie'] === 'loup' ? 'selected' : '' ?>>Loup</option>
                <option value="villageois" <?= $carte['categorie'] === 'villageois' ? 'selected' : '' ?>>Villageois</option>
            </select>

            <label for="photo">Changer la photo :</label>
            <input type="file" name="photo" accept="image/*"> <!-- Champ pour une nouvelle photo -->

            <!-- Afficher la photo actuelle -->
            <div>
                <p>Photo actuelle :</p>
                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="Aperçu de la carte" width="150"> <!-- Aperçu de la photo actuelle -->
            </div>

            <button type="submit">Modifier la carte</button> <!-- Bouton de soumission -->
        </form>

        <a href="/loup-garou-crud/public/index.php?action=cartes" class="btn-back">Retour à la liste des cartes</a> <!-- Bouton de retour -->
    </div>
</body>
</html>
