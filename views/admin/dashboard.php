<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/loup-garou-crud/public/index.php">Accueil</a> |
            <a href="/loup-garou-crud/public/index.php?action=logout">Déconnexion</a>
        </nav>
    </header>
    
    <h1>Tableau de Bord Admin</h1>
    
    <h2>Gestion des Utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['id']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['pseudo']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                    <td>
                        <a href="?action=delete_user&id=<?= $utilisateur['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
