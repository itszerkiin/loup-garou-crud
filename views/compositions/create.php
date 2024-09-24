<!-- views/compositions/create.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une Composition</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/composition.css"> <!-- Lien vers le fichier CSS -->
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
    
    <h1>Créer une Nouvelle Composition</h1>

    <form method="POST" action="?action=create">
        <label for="nom">Nom de la Composition:</label>
        <input type="text" name="nom" required><br>

        <label for="description">Description:</label>
        <textarea name="description" rows="5" cols="40" required></textarea><br>

        <label for="nombre_joueurs">Nombre de Joueurs (Minimum : 5):</label>
        <input type="number" id="nombre_joueurs" name="nombre_joueurs" min="5" value="5" required><br>

        <p id="error-message" style="color: red; display: none;">Le nombre de cartes ne peut pas dépasser le nombre de joueurs. Augmentez le nombre de joueurs.</p>

        <label for="cartes">Sélectionner les Cartes (exactement égal au nombre de joueurs):</label><br>
        
        <!-- Conteneur pour les catégories de cartes -->
        <div class="cartes-selection">
            <div>
                <div class="category-header">Villageois</div>
                <div class="cards-category villageois">
                    <?php foreach ($cartesDisponibles as $carte): ?>
                        <?php if ($carte['categorie'] == 'villageois'): ?>
                            <div class="carte">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                <div class="card-actions">
                                    <button type="button" class="btn-decrease">-</button>
                                    <button type="button" class="btn-increase">+</button>
                                </div>
                                <div class="card-count" data-count="0">0</div>
                                <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="0">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <div class="category-header">Neutre</div>
                <div class="cards-category neutre">
                    <?php foreach ($cartesDisponibles as $carte): ?>
                        <?php if ($carte['categorie'] == 'neutre'): ?>
                            <div class="carte">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                <div class="card-actions">
                                    <button type="button" class="btn-decrease">-</button>
                                    <button type="button" class="btn-increase">+</button>
                                </div>
                                <div class="card-count" data-count="0">0</div>
                                <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="0">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <div class="category-header">Loup</div>
                <div class="cards-category loup">
                    <?php foreach ($cartesDisponibles as $carte): ?>
                        <?php if ($carte['categorie'] == 'loup'): ?>
                            <div class="carte">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                <div class="card-actions">
                                    <button type="button" class="btn-decrease">-</button>
                                    <button type="button" class="btn-increase">+</button>
                                </div>
                                <div class="card-count" data-count="0">0</div>
                                <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="0">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <button type="submit" id="submit-button" disabled>Créer</button> <!-- Bouton désactivé tant que la sélection n'est pas correcte -->
    </form>

    <script src="/loup-garou-crud/public/js/composition.js"></script> <!-- Lien vers le fichier JS -->
</body>
</html>
