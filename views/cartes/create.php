<!-- views/cartes/create.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Carte</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/style.css">
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
    
    <h1>Ajouter une Nouvelle Carte</h1>

    <form method="POST" action="?action=create_carte" enctype="multipart/form-data">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="photo">Photo :</label>
        <input type="file" name="photo" accept="image/*" required><br>

        <label for="categorie">Catégorie:</label>
        <select name="categorie" required>
            <option value="neutre">Neutre</option>
            <option value="loup">Loup</option>
            <option value="villageois">Villageois</option>
        </select><br>

        <button type="submit">Ajouter la carte</button>
    </form>
</body>
</html>
