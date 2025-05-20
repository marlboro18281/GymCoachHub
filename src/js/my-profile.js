// JavaScript для керування модальним вікном і валідації форми
document.addEventListener('DOMContentLoaded', function () {
    // Отримуємо посилання на елементи модального вікна
    const modal = document.getElementById('base-measurements-modal'); // Модальне вікно
    const openModalBtn = document.getElementById('update-base-btn'); // Кнопка відкриття модального вікна
    const closeModalBtn = document.getElementById('close-modal'); // Кнопка закриття модального вікна

    // Встановлюємо обробник події для відкриття модального вікна при кліку на кнопку
    openModalBtn.addEventListener('click', function () {
        modal.style.display = 'block'; // Показуємо модальне вікно, змінюючи CSS-властивість display
    });

    // Встановлюємо обробник події для закриття модального вікна при кліку на кнопку закриття
    closeModalBtn.addEventListener('click', function () {
        modal.style.display = 'none'; // Ховаємо модальне вікно
    });

    // Встановлюємо обробник події для закриття модального вікна при кліку поза ним
    window.addEventListener('click', function (event) {
        if (event.target === modal) { // Якщо клік був саме по фоновому елементу (не по вмісту вікна)
            modal.style.display = 'none'; // Ховаємо модальне вікно
        }
    });

    // Валідація форми поточних вимірів
    const measurementsForm = document.getElementById('measurements-form'); // Отримуємо форму
    measurementsForm.addEventListener('submit', function (event) {
        const weight = document.getElementById('weight').value; // Отримуємо введену вагу
        const height = document.getElementById('height').value; // Отримуємо введений зріст

        // Перевіряємо, чи значення більші за 0
        if (weight <= 0 || height <= 0) {
            event.preventDefault(); // Відміняємо стандартну дію форми (відправку)
            alert('Вага та зріст мають бути більше 0!'); // Виводимо повідомлення про помилку
        }
    });

    // Валідація форми базових вимірів (аналогічно до поточних вимірів)
    const baseMeasurementsForm = document.getElementById('base-measurements-form'); // Отримуємо форму
    baseMeasurementsForm.addEventListener('submit', function (event) {
        const baseWeight = document.querySelector('input[name="base_weight"]').value; // Отримуємо введену базову вагу
        const baseHeight = document.querySelector('input[name="base_height"]').value; // Отримуємо введений базовий зріст

        // Перевіряємо, чи значення більші за 0
        if (baseWeight <= 0 || baseHeight <= 0) {
            event.preventDefault(); // Відміняємо стандартну дію форми
            alert('Базова вага та зріст мають бути більше 0!'); // Виводимо повідомлення про помилку
        }
    });
});
