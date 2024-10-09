<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cartes - Loup-Garou</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css"> <!-- Lien vers le fichier CSS du header -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/cartes.css"> <!-- Lien vers le fichier CSS des cartes -->
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
    
<h1>Liste des Cartes</h1>

<!-- Bouton d'administration visible uniquement pour les administrateurs -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="admin-actions">
        <a href="/loup-garou-crud/public/index.php?action=create_carte" class="btn">Ajouter une carte</a>
    </div>
<?php endif; ?>

<!-- Conteneur principal des cartes -->
<div class="cards-container">
    <!-- Colonne des cartes villageoises -->
    <div class="cards-column">
        <h2>Villageois</h2>
        <div class="cards-list">
            <?php foreach ($cartes as $carte): ?>
                <?php if (isset($carte['categorie']) && $carte['categorie'] == 'villageois'): ?>
                    <div class="card" data-nom="<?= htmlspecialchars($carte['nom']); ?>" data-description="<?= htmlspecialchars($carte['description']); ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="card-image">
                        
                        <!-- Boutons modifier et supprimer visibles au survol (pour admin) -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="admin-card-actions">
                                <a href="/loup-garou-crud/public/index.php?action=edit_carte&id=<?= $carte['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="/loup-garou-crud/public/index.php?action=delete_carte&id=<?= $carte['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Colonne des cartes neutres -->
    <div class="cards-column">
        <h2>Neutre</h2>
        <div class="cards-list">
            <?php foreach ($cartes as $carte): ?>
                <?php if (isset($carte['categorie']) && $carte['categorie'] == 'neutre'): ?>
                    <div class="card" data-nom="<?= htmlspecialchars($carte['nom']); ?>" data-description="<?= htmlspecialchars($carte['description']); ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="card-image">
                        
                        <!-- Boutons admin -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="admin-card-actions">
                                <a href="/loup-garou-crud/public/index.php?action=edit_carte&id=<?= $carte['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="/loup-garou-crud/public/index.php?action=delete_carte&id=<?= $carte['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Colonne des cartes loups-garous -->
    <div class="cards-column">
        <h2>Loups-Garous</h2>
        <div class="cards-list">
            <?php foreach ($cartes as $carte): ?>
                <?php if (isset($carte['categorie']) && $carte['categorie'] == 'loup'): ?>
                    <div class="card" data-nom="<?= htmlspecialchars($carte['nom']); ?>" data-description="<?= htmlspecialchars($carte['description']); ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="card-image">
                        
                        <!-- Boutons admin -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="admin-card-actions">
                                <a href="/loup-garou-crud/public/index.php?action=edit_carte&id=<?= $carte['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="/loup-garou-crud/public/index.php?action=delete_carte&id=<?= $carte['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Description de la carte agrandie -->
<div id="description-display" style="display: none;">
    <h3 id="card-name"></h3>
    <p id="card-description"></p>
</div>

<!-- Bouton pour fermer la vue agrandie -->
<div id="close-button" style="display: none;">✖</div>

<script src="/loup-garou-crud/public/js/cartes.js"></script> <!-- Fichier JS pour interactivité -->
</body>
</html>
