<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
</head>
<body>
    <h1>Inscription</h1>
    <form method="POST" action="/loup-garou-crud/public/index.php?action=register">
        <label for="pseudo">Pseudo:</label>
        <input type="text" name="pseudo" id="pseudo" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
