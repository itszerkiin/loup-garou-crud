<!-- views/compositions/index.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Compositions</title>
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
    
    <h1>Liste des Compositions</h1>

    <!-- Bouton "Nouvelle Composition" visible pour tout le monde -->
    <a href="/loup-garou-crud/public/index.php?action=create" class="btn">Nouvelle Composition</a>

    <div class="compositions-container">
        <?php foreach ($compositions as $composition): ?>
            <div class="composition">
                <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                <p><?= htmlspecialchars($composition['description']) ?></p>
                <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>

                <!-- Affichage des boutons "J'aime" et "Je n'aime pas" -->
                <div class="like-dislike-buttons">
                    <!-- Formulaire "J'aime" -->
                    <form method="POST" action="../controllers/compositionsController.php">
                        <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                        <input type="hidden" name="avis" value="like">
                        <button type="submit" class="btn">J'aime (<?= $composition['likes'] ?>)</button>
                    </form>

                    <!-- Formulaire "Je n'aime pas" -->
                    <form method="POST" action="../controllers/compositionsController.php">
                        <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                        <input type="hidden" name="avis" value="dislike">
                        <button type="submit" class="btn">Je n'aime pas (<?= $composition['dislikes'] ?>)</button>
                    </form>
                </div>

                <!-- Affiche les images des cartes associées à la composition -->
                <div class="cartes">
                    <?php 
                    $cartes = json_decode($composition['cartes'], true);
                    foreach ($cartes as $carte_id):
                        $carte = $carteModel->getCarteById($carte_id);
                    ?>
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
                        