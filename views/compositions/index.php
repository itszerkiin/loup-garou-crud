<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Compositions</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Pour AJAX -->
    <script src="/loup-garou-crud/public/js/filter.js"></script> <!-- Charger le JS pour le filtre -->
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
                    <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                    <!-- Affichage des cartes associées -->
                    <div class="cartes">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item" data-card-id="<?= $carte['id'] ?>">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

              <!-- Affichage du bouton "J'aime" avec un cadre harmonisé aux autres boutons -->
<div class="like-button-container">
    <?php
    $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
    ?>
    <form method="POST" action="/loup-garou-crud/public/index.php?action=like">
        <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
        <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
            <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
        </button>
    </form>

    <?php if ($userLiked): ?>
        <p class="liked-message">Vous avez déjà aimé cette composition</p>
    <?php endif; ?>
</div>

                                <!-- Afficher les boutons "Modifier" et "Supprimer" si l'utilisateur est admin ou l'auteur -->
                                <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
          (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
    <div class="composition-actions">
    <a href="/loup-garou-crud/public/index.php?action=edit&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
        <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
    </div>
<?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Filtres par cartes et joueurs -->
    <div class="filters-container" style="display: flex; justify-content: space-between;">
        <section id="filter-by-cards">
            <h2>Filtrer par cartes</h2>
            <div class="cartes-selection">
                <?php foreach ($cartesDisponibles as $carte): ?>
                    <a href="javascript:void(0)" class="carte-item" data-card-id="<?= $carte['id'] ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Section de filtrage par nombre de joueurs -->
        <section id="filter-by-players">
            <h2>Filtrer par nombre de joueurs</h2>
            <select id="nombre_joueurs">
                <option value="">Tous</option>
                <option value="5">5 joueurs</option>
                <option value="6">6 joueurs</option>
                <option value="7">7 joueurs</option>
                <option value="8">8 joueurs</option>
                <option value="9">9 joueurs</option>
                <option value="10">10 joueurs</option>
                <option value="11">11 joueurs</option>
                <option value="12">12 joueurs</option>
                <option value="13">13 joueurs</option>
                <option value="14">14 joueurs</option>
                <option value="15">15 joueurs</option>
                <option value="16">16 joueurs</option>
                <option value="17">17 joueurs</option>
                <option value="18">18 joueurs</option>
                <option value="19">19 joueurs</option>
                <option value="20">20 joueurs</option>
            </select>
        </section>
    </div>

    <div style="clear: both;"></div>

    <!-- Section des compositions filtrées -->
    <section id="compositions-list">
        <div class="compositions-container">
            <?php foreach ($compositionsAlphabetical as $composition): ?>
                <div class="composition uniform-size">
                    <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                    <p><?= htmlspecialchars($composition['description']) ?></p>
                    <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                    <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                    <!-- Affichage des cartes associées -->
                    <div class="cartes">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item" data-card-id="<?= $carte['id'] ?>">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

<!-- Affichage du bouton "J'aime" avec un cadre harmonisé aux autres boutons -->
<div class="like-button-container">
    <?php
    $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
    ?>
    <form method="POST" action="/loup-garou-crud/public/index.php?action=like">
        <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
        <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
            <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
        </button>
    </form>

    <?php if ($userLiked): ?>
        <p class="liked-message">Vous avez déjà aimé cette composition</p>
    <?php endif; ?>
</div>



                              <!-- Afficher les boutons "Modifier" et "Supprimer" si l'utilisateur est admin ou l'auteur -->
                              <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
          (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
    <div class="composition-actions">
    <a href="/loup-garou-crud/public/index.php?action=edit&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
        <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
    </div>
<?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
