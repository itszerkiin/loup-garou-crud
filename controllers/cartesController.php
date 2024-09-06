<?php
// controllers/cartesController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Carte.php';

$carteModel = new Carte($pdo);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create_carte':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $categorie = $_POST['categorie'] ?? ''; // Utiliser une valeur par défaut vide si non définie

            if (empty($categorie)) {
                echo "Erreur : La catégorie est requise.";
                exit;
            }

            // Vérification de l'upload du fichier
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
                $photoTmpPath = $_FILES['photo']['tmp_name'];
                $photoName = basename($_FILES['photo']['name']);
                $photoDestinationPath = __DIR__ . '/../uploads/' . $photoName;

                // Créer le répertoire 'uploads' s'il n'existe pas
                if (!file_exists(__DIR__ . '/../uploads')) {
                    mkdir(__DIR__ . '/../uploads', 0777, true);
                }

                // Déplacer le fichier uploadé dans le répertoire 'uploads'
                if (move_uploaded_file($photoTmpPath, $photoDestinationPath)) {
                    $photoUrl = '/loup-garou-crud/uploads/' . $photoName;

                    // Créer la carte avec le chemin de la photo et la catégorie
                    $result = $carteModel->createCarte($nom, $description, $photoUrl, $categorie);
                    
                    if ($result) {
                        header('Location: /loup-garou-crud/public/index.php?action=cartes');
                        exit;
                    } else {
                        echo "Erreur : Échec de l'ajout de la carte.";
                    }
                } else {
                    echo "Erreur : Impossible de télécharger la photo.";
                }
            } else {
                echo "Erreur : Tous les champs sont requis, y compris la photo.";
            }
        } else {
            include '../views/cartes/create.php';
        }
        break;

    default:
        $cartes = $carteModel->getAllCartes();
        include '../views/cartes/index.php';
        break;
}
?>
