<!-- views/utilisateurs/register.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/loup-garou-crud/public/index.php">Accueil</a> |
            <a href="/loup-garou-crud/public/index.php?action=cartes">Cartes</a>
        </nav>
    </header>

    <h1>Inscription</h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] == 'registration_failed'): ?>
            <p class="error">L'inscription a échoué. Veuillez réessayer.</p>
        <?php elseif ($_GET['error'] == 'user_exists'): ?>
            <p class="error">Le pseudo ou l'email est déjà utilisé. Veuillez en choisir un autre.</p>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="/loup-garou-crud/public/index.php?action=register">
        <label for="pseudo">Pseudo:</label>
        <input type="text" name="pseudo" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
