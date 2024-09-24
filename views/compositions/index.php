<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Compositions</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/loup-garou-crud/public/index.php">Compositions</a> | 
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

    <!-- Bouton "Nouvelle Composition" visible pour les utilisateurs connectés -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/loup-garou-crud/public/index.php?action=create" class="btn">Nouvelle Composition</a>
    <?php endif; ?>

    <!-- Section "Les plus aimées" -->
    <section id="top-liked-compositions">
        <h2>Les 5 compositions les plus aimées</h2>
        <div class="compositions-container">
            <?php foreach ($topLikedCompositions as $composition): ?>
                <div class="composition">
                    <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                    <p><?= htmlspecialchars($composition['description']) ?></p>
                    <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>

                    <!-- Affichage du bouton "J'aime" avec compteur -->
                    <div class="like-button-container">
                        <?php
                        $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
                        ?>
                        <form method="POST" action="../controllers/compositionsController.php">
                            <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                            <input type="hidden" name="avis" value="like">
                            <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : '' ?>">
                                <?= $userLiked ? 'Aimé' : 'J\'aime' ?> (<?= $composition['likes'] ?>)
                            </button>
                        </form>

                        <?php if ($userLiked): ?>
                            <p class="liked-message">Vous avez déjà aimé cette composition</p>
                        <?php endif; ?>
                    </div>

                    <!-- Affiche les images des cartes associées à la composition -->
                    <div class="cartes">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section de filtrage par cartes -->
    <section id="filter-by-cards">
        <h2>Filtrer par cartes</h2>
        <div class="cartes-selection">
            <?php foreach ($cartesDisponibles as $carte): ?>
                <a href="/loup-garou-crud/public/index.php?action=filter_by_card&card_id=<?= $carte['id'] ?>" class="carte-item">
                    <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                    <p><?= htmlspecialchars($carte['nom']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section pour toutes les compositions triées par ordre alphabétique -->
    <section id="all-compositions">
        <h2>Toutes les compositions (triées par ordre alphabétique)</h2>
        <div class="compositions-container">
            <?php foreach ($compositionsAlphabetical as $composition): ?>
                <div class="composition uniform-size"> <!-- Ajout d'une classe pour uniformiser la taille -->
                    <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                    <p><?= htmlspecialchars($composition['description']) ?></p>
                    <p><strong>Posté par :</strong>   <?= isset($composition['utilisateur']) ? htmlspecialchars($composition['utilisateur']) : 'Utilisateur inconnu' ?></p>


<!-- Affichage du bouton "J'aime" avec compteur -->
<div class="like-button-container">
    <?php
    $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
    ?>
    <form method="POST" action="../controllers/compositionsController.php?action=like">
        <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
        <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : '' ?>">
            <?= $userLiked ? 'Aimé' : 'J\'aime' ?> (<?= $composition['likes'] ?>)
        </button>
    </form>

    <?php if ($userLiked): ?>
        <p class="liked-message">Vous avez déjà aimé cette composition</p>
    <?php endif; ?>
</div>


                    <!-- Affiche les images des cartes associées à la composition -->
                    <div class="cartes">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
