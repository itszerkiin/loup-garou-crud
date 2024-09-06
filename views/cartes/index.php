<!-- views/cartes/index.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cartes - Loup-Garou</title>
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
    
    <h1>Liste des Cartes</h1>

    <!-- Bouton "Ajouter une nouvelle carte" visible uniquement si l'utilisateur est connecté -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="?action=create_carte" class="btn">Ajouter une nouvelle carte</a>
    <?php endif; ?>

    <!-- Affichage des cartes par catégorie -->
    <div class="cartes-container">
        <!-- Section des cartes neutres -->
        <div class="cartes-section">
            <h2>Neutre</h2>
            <div class="cartes-liste">
                <?php foreach ($cartes as $carte): ?>
                    <?php if (isset($carte['categorie']) && $carte['categorie'] == 'neutre'): ?>
                        <div class="carte">
                            <div class="carte-image-wrapper">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>">
                            </div>
                            <h3><?= htmlspecialchars($carte['nom']) ?></h3>
                            <p><?= htmlspecialchars($carte['description']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section des cartes loups -->
        <div class="cartes-section">
            <h2>Loup</h2>
            <div class="cartes-liste">
                <?php foreach ($cartes as $carte): ?>
                    <?php if (isset($carte['categorie']) && $carte['categorie'] == 'loup'): ?>
                        <div class="carte">
                            <div class="carte-image-wrapper">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>">
                            </div>
                            <h3><?= htmlspecialchars($carte['nom']) ?></h3>
                            <p><?= htmlspecialchars($carte['description']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section des cartes villageoises -->
        <div class="cartes-section">
            <h2>Villageois</h2>
            <div class="cartes-liste">
                <?php foreach ($cartes as $carte): ?>
                    <?php if (isset($carte['categorie']) && $carte['categorie'] == 'villageois'): ?>
                        <div class="carte">
                            <div class="carte-image-wrapper">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>">
                            </div>
                            <h3><?= htmlspecialchars($carte['nom']) ?></h3>
                            <p><?= htmlspecialchars($carte['description']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
