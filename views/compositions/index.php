<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Compositions</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/loup-garou-crud/public/js/composition.js"></script>
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

    <section id="top-liked-compositions">
        <h2>Les 5 compositions les plus aimées</h2>
        <div class="compositions-container">
            <?php foreach ($topLikedCompositions as $composition): ?>
                <div class="composition uniform-size">
                    <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                    <p><?= htmlspecialchars($composition['description']) ?></p>
                    <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                    <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                    <!-- Conteneur des cartes -->
                    <div class="cartes-conteneur">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item composition-carte" data-id="<?= htmlspecialchars($carte['id']); ?>">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Boutons J'aime, Modifier, Supprimer -->
                    <div class="like-button-container">
                        <?php
                        $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
                        ?>
<form method="POST" action="/loup-garou-crud/public/index.php?action=like" style="display: inline;">
    <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
    <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
        <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
    </button>
</form>


                        <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                            (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
                            <a href="/loup-garou-crud/public/index.php?action=edit&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
                            <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="filters-container" style="display: flex; justify-content: space-between;">
    <section id="filter-by-cards">
        <h2>Filtrer par cartes</h2>
        <div class="cartes-selection">
            
            <?php
            // Ordre des rôles définis
            $order = ['Villageois', 'Loup-Garou', 'Neutre'];
            
            // Filtrer les cartes par rôle et les stocker dans des tableaux séparés pour respecter l'ordre
            $sortedCards = ['Villageois' => [], 'Loup-Garou' => [], 'Neutre' => []];
            
            // Ranger les cartes dans les sous-tableaux en fonction du rôle
            foreach ($cartesDisponibles as $carte) {
                $role = isset($carte['role']) ? $carte['role'] : 'Neutre';
                
                // Ajouter la carte dans le tableau correspondant
                if (in_array($role, $order)) {
                    $sortedCards[$role][] = $carte;
                } else {
                    $sortedCards['Neutre'][] = $carte; // Si un rôle inconnu, classer en 'Neutre'
                }
            }
            
            // Afficher les cartes dans l'ordre de rôle
            foreach ($order as $role) {
                foreach ($sortedCards[$role] as $carte): ?>
                    <div class="carte-item filter-card" data-card-id="<?= htmlspecialchars($carte['id']) ?>" data-role="<?= htmlspecialchars($carte['role'] ?? 'Neutre') ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                    </div>
                <?php endforeach;
            }
            ?>
        </div>
    </section>
</div>



</section>


<section id="filter-by-players">
    <h2>Filtrer par nombre de joueurs</h2>
    <select id="nombre_joueurs">
        <option value="">Tous</option>
        <!-- Options pour différents nombres de joueurs de 5 à 30 -->
        <?php for ($i = 5; $i <= 30; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?> joueurs</option>
        <?php endfor; ?>
    </select>   
</section>

    </div>

    <section id="compositions-list">
    <div class="compositions-container">
        <?php foreach ($compositionsAlphabetical as $composition): ?>
            <div class="composition uniform-size" data-player-count="<?= htmlspecialchars($composition['nombre_joueurs']) ?>">
                <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                <p><?= htmlspecialchars($composition['description']) ?></p>
                <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                <div class="cartes-conteneur">
                    <?php 
                    $cartes = json_decode($composition['cartes'], true);
                    foreach ($cartes as $carte_id => $quantity):
                        if ($quantity > 0): 
                            $carte = $carteModel->getCarteById($carte_id);
                            if ($carte): ?>
                                <div class="carte-item composition-carte" data-id="<?= htmlspecialchars($carte['id']); ?>">
                                    <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                    <div class="carte-quantity"><?= $quantity ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="like-button-container">
                    <?php
                    $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
                    ?>
                    <form method="POST" action="/loup-garou-crud/public/index.php?action=like" style="display: inline;">
                        <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                        <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
                            <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                        </button>
                    </form>

                    <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                        (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
                        <a href="/loup-garou-crud/public/index.php?action=edit&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
                        <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<div id="carte-nom-affichage" ></div>

</body>
</html>
