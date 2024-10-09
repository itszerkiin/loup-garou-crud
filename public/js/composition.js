document.addEventListener('DOMContentLoaded', () => {
    const nombreJoueursInput = document.getElementById('nombre_joueurs');
    const submitButton = document.getElementById('submit-button');
    const errorMessage = document.getElementById('error-message');
    let totalSelectedCards = 0;

    // Initialisation de totalSelectedCards avec les cartes déjà sélectionnées
    document.querySelectorAll('.carte').forEach((carte) => {
        const countElem = carte.querySelector('.card-count');
        const inputElem = carte.querySelector('input[type="hidden"]');
        const initialCount = parseInt(inputElem.value) || 0;

        // Mettre à jour totalSelectedCards selon les valeurs actuelles
        totalSelectedCards += initialCount;
        countElem.textContent = initialCount;

        // Affiche le compteur s'il est supérieur à 0
        if (initialCount > 0) {
            countElem.style.display = 'block';
        }

        // Mettre à jour data-count pour synchroniser avec l'input hidden
        carte.dataset.count = initialCount;
    });

    // Fonction pour mettre à jour l'état du bouton de soumission
    const updateSubmitButtonState = () => {
        const maxCards = parseInt(nombreJoueursInput.value);
        if (totalSelectedCards === maxCards) {
            submitButton.disabled = false;
            errorMessage.style.display = 'none';
        } else {
            submitButton.disabled = true;
            if (totalSelectedCards > maxCards) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        }
    };
    updateSubmitButtonState();

    // Gestion des boutons + pour chaque carte
    document.querySelectorAll('.btn-increase').forEach((btnIncrease) => {
        btnIncrease.addEventListener('click', (e) => {
            const carte = e.target.closest('.carte');
            const countElem = carte.querySelector('.card-count');
            const inputElem = carte.querySelector('input[type="hidden"]');
            let count = parseInt(carte.dataset.count) || 0;
            const maxCards = parseInt(nombreJoueursInput.value);

            if (totalSelectedCards < maxCards) {
                count++;
                countElem.textContent = count;
                carte.dataset.count = count;
                inputElem.value = count;
                totalSelectedCards++;
                updateSubmitButtonState();

                // Affiche le compteur s'il est supérieur à 0
                if (count > 0) {
                    countElem.style.display = 'block';
                }
            }
        });
    });

    // Gestion des boutons - pour chaque carte
    document.querySelectorAll('.btn-decrease').forEach((btnDecrease) => {
        btnDecrease.addEventListener('click', (e) => {
            const carte = e.target.closest('.carte');
            const countElem = carte.querySelector('.card-count');
            const inputElem = carte.querySelector('input[type="hidden"]');
            let count = parseInt(carte.dataset.count) || 0;

            if (count > 0) {
                count--;
                countElem.textContent = count;
                carte.dataset.count = count;
                inputElem.value = count;
                totalSelectedCards--;
                updateSubmitButtonState();

                // Masque le compteur si le nombre est 0
                if (count === 0) {
                    countElem.style.display = 'none';
                }
            }
        });
    });

    // Mise à jour de l'état du bouton de soumission lors de la modification du nombre de joueurs
    nombreJoueursInput.addEventListener('change', updateSubmitButtonState);
});
