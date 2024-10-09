<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/login.css">
</head>
<body>
    <div class="form-container">
        <h1>Connexion</h1>
        <form method="POST" action="/loup-garou-crud/public/index.php?action=login">
            <label for="identifiant">Pseudo ou Email:</label>
            <input type="text" name="identifiant" id="identifiant" class="form-input" required><br>

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" class="form-input" required><br>

            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>
</body>
</html>
