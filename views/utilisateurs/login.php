<!-- views/utilisateurs/login.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/loup-garou-crud/public/index.php">Accueil</a> |
            <a href="/loup-garou-crud/public/index.php?action=cartes">Cartes</a>
        </nav>
    </header>

    <h1>Connexion</h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] == 'invalid_credentials'): ?>
            <p class="error">Pseudo ou mot de passe incorrect.</p>
        <?php elseif ($_GET['error'] == 'connect_required'): ?>
            <p class="error">Vous devez être connecté pour ajouter une composition.</p>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="/loup-garou-crud/public/index.php?action=login">
        <label for="identifiant">Pseudo ou Email:</label>
        <input type="text" name="identifiant" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
