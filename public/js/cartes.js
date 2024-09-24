document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    const nameDisplay = document.getElementById('card-name');
    const descriptionDisplay = document.getElementById('card-description');
    const overlay = document.getElementById('overlay');
    const closeButton = document.getElementById('close-button');
    const container = document.querySelector('.cards-container');
    let activeCard = null;

    // Gestion du clic sur chaque carte
    cards.forEach(card => {
        card.addEventListener('click', function() {
            if (activeCard) return; // Empêche l'activation d'une nouvelle carte si une autre est déjà ouverte
            
            // Récupère les données de la carte cliquée
            const cardName = card.getAttribute('data-nom');
            const cardDescription = card.getAttribute('data-description');

            // Met à jour les éléments d'affichage
            nameDisplay.textContent = cardName;
            descriptionDisplay.textContent = cardDescription;

            // Ajoute les classes nécessaires pour les transitions et les effets
            container.classList.add('blur');  // Floute l'arrière-plan
            card.classList.add('active');
            closeButton.style.display = 'block';
            document.getElementById('description-display').style.display = 'block';

            activeCard = card; // Définit la carte active
        });
    });

    // Gestion du clic sur le bouton de fermeture
    closeButton.addEventListener('click', function() {
        if (!activeCard) return; // Si aucune carte n'est active, ne fait rien

        // Retire les classes et réinitialise l'état
        container.classList.remove('blur');  // Retire le flou de l'arrière-plan
        activeCard.classList.remove('active');
        closeButton.style.display = 'none';
        document.getElementById('description-display').style.display = 'none';

        activeCard = null; // Réinitialise la carte active
    });
});
