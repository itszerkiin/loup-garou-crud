<!-- views/compositions/edit.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Composition</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/style.css">
</head>
<body>
    <h1>Modifier la Composition</h1>

    <form method="POST" action="?action=edit&id=<?= $composition['id'] ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($composition['nom']) ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required><?= htmlspecialchars($composition['description']) ?></textarea><br>

        <label for="nombre_joueurs">Nombre de Joueurs:</label>
        <input type="number" name="nombre_joueurs" value="<?= htmlspecialchars($composition['nombre_joueurs']) ?>" required><br>

        <label for="cartes">Cartes (JSON):</label>
        <textarea name="cartes" required><?= htmlspecialchars($composition['cartes']) ?></textarea><br>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
