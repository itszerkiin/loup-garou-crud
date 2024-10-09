<?php if (!empty($filteredCompositions)): ?>
    <div class="compositions-container">
        <?php foreach ($filteredCompositions as $composition): ?>
            <div class="composition uniform-size">
                <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                <p><?= htmlspecialchars($composition['description']) ?></p>
                <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

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
<?php else: ?>
    <p>Aucune composition ne correspond à ces critères.</p>
<?php endif; ?>
