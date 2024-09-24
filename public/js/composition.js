// public/js/composition.js

document.addEventListener('DOMContentLoaded', () => {
    const nombreJoueursInput = document.getElementById('nombre_joueurs');
    const submitButton = document.getElementById('submit-button');
    const errorMessage = document.getElementById('error-message');
    let totalSelectedCards = 0;

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

    nombreJoueursInput.addEventListener('change', updateSubmitButtonState);
});
