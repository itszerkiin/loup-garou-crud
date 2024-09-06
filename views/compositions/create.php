<!-- views/compositions/create.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une Composition</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/style.css">
    <script>
        function updateCardSelection() {
            const maxCards = document.getElementById('nombre_joueurs').value;
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    selectedCount++;
                }
            });

            checkboxes.forEach((checkbox) => {
                if (selectedCount >= maxCards && !checkbox.checked) {
                    checkbox.disabled = true;
                } else {
                    checkbox.disabled = false;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('nombre_joueurs').addEventListener('input', updateCardSelection);
            document.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
                checkbox.addEventListener('change', updateCardSelection);
            });
        });
    </script>
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
    
    <h1>Créer une Nouvelle Composition</h1>

    <form method="POST" action="?action=create">
        <label for="nom">Nom de la Composition:</label>
        <input type="text" name="nom" required><br>

        <label for="description">Description:</label>
        <textarea name="description" rows="5" cols="40" required></textarea><br>

        <label for="nombre_joueurs">Nombre de Joueurs:</label>
        <input type="number" id="nombre_joueurs" name="nombre_joueurs" min="1" required><br>

        <label for="cartes">Sélectionner les Cartes (maximum égal au nombre de joueurs):</label><br>
        <div class="cartes-selection">
            <?php if (isset($cartesDisponibles) && is_array($cartesDisponibles)): ?>
                <?php foreach ($cartesDisponibles as $carte): ?>
                    <div class="carte">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" width="100">
                        <input type="checkbox" name="cartes[]" value="<?= $carte['id'] ?>" onchange="updateCardSelection()">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune carte disponible pour le moment. <a href="/loup-garou-crud/public/index.php?action=create_carte">Ajouter une carte</a></p>
            <?php endif; ?>
        </div>

        <!-- Champ caché pour l'ID de l'utilisateur -->
        <input type="hidden" name="utilisateur_id" value="1"> <!-- Remplacez 1 par l'ID de l'utilisateur connecté -->

        <button type="submit">Créer</button>
    </form>
</body>
</html>
