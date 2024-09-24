<!-- views/compositions/edit.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Composition</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
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

        <label for="cartes">Sélectionner les Cartes:</label><br>
        <div class="cartes-selection">
            <?php foreach ($cartesDisponibles as $carte): ?>
                <div class="carte">
                    <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" width="100">
                    <input type="checkbox" name="cartes[]" value="<?= $carte['id'] ?>" <?= in_array($carte['id'], json_decode($composition['cartes'], true)) ? 'checked' : '' ?>>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
