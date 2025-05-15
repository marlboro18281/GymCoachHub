/*
 * main.js - Основний JavaScript-файл для інтерактивних елементів сторінок
 * Використовується для анімацій, генерації вправ та збереження даних
 */



// Закоментований код для фіксації навбара при скролі
/*
window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (navbar) {
        if (window.scrollY > 0) {
            navbar.classList.add("fixed");
        } else {
            navbar.classList.remove("fixed");
        }
    }
});
*/

// Закоментований код для анімації фрази на головній сторінці
/*
window.addEventListener("DOMContentLoaded", function () {
    const tagline = document.querySelector(".tagline");
    if (tagline) {
        tagline.style.opacity = "0";
        tagline.style.transform = "translateX(-100%)";

        setTimeout(() => {
            tagline.style.transition = "opacity 1s ease-out, transform 1s ease-out";
            tagline.style.opacity = "1";
            tagline.style.transform = "translateX(0)";
        }, 600);
    }
});
*/

// Додавання анімації до кнопки "Розпочати" на головній сторінці
window.addEventListener("DOMContentLoaded", function () {
    const start = document.querySelector('.start a');
    if (start) {
        start.classList.add('pulse');
        start.classList.add('glow');
    }
});

// Обробка генерації та збереження вправ
document.addEventListener("DOMContentLoaded", function () {
    // Елементи DOM
    const allExercises = Array.from(document.querySelectorAll(".hardexercise a, .pilates a, .fitness a"));
    const generateBtn = document.getElementById("generate");
    const modal = document.getElementById("modal");
    const closeModal = modal ? modal.querySelector(".close") : null;
    const exerciseList = document.getElementById("exercise-list");
    const saveBtn = document.getElementById("save-exercises");
    const messageDiv = document.getElementById("save-message");

    // Перевірка наявності необхідних елементів
    if (!generateBtn || !modal || !closeModal || !exerciseList || !saveBtn || !messageDiv) {
        console.warn("Один або кілька елементів DOM не знайдено. Перевірте HTML-структуру.");
        return;
    }

    // Функція для обробки відповідей сервера
    async function handleResponse(response) {
        if (!response) {
            throw new Error("Відповідь сервера не отримана (response is undefined)");
        }

        if (!response.ok) {
            const text = await response.text();
            console.error("Неваліний HTTP-статус:", response.status, "Текст відповіді:", text);
            throw new Error(`Сервер повернув помилку ${response.status}: ${text.substring(0, 100)}...`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error("Неваліний Content-Type:", contentType, "Текст відповіді:", text);
            throw new Error(`Сервер повернув не JSON (Content-Type: ${contentType}): ${text.substring(0, 100)}...`);
        }

        try {
            const data = await response.json();
            return data;
        } catch (err) {
            const text = await response.text();
            console.error("Помилка парсингу JSON. Текст відповіді:", text);
            throw new Error(`Помилка парсингу JSON: ${err.message}. Текст відповіді: ${text.substring(0, 100)}...`);
        }
    }

    // Генерація випадкових вправ
    generateBtn.addEventListener("click", function (e) {
        e.preventDefault();
        if (allExercises.length === 0) {
            showMessage("❌ Немає доступних вправ для генерації", "red");
            return;
        }

        const selected = [...allExercises]
            .sort(() => 0.5 - Math.random())
            .slice(0, 5);

        exerciseList.innerHTML = "";
        selected.forEach(item => {
            const name = item.getAttribute("data-id").replace(/_/g, " ");
            const div = document.createElement("div");
            div.className = "exercise-item";
            div.setAttribute("data-id", item.getAttribute("data-id"));

            const text = document.createElement("span");
            text.textContent = name;

            const icon = document.createElement("span");
            icon.textContent = "✅";
            icon.className = "done-icon";
            icon.style.display = "none";
            icon.style.marginLeft = "10px";

            div.appendChild(text);
            div.appendChild(icon);
            exerciseList.appendChild(div);

            div.addEventListener("click", () => {
                icon.style.display = icon.style.display === "none" ? "inline" : "none";
            });
        });

        saveBtn.style.display = "block";
        modal.style.display = "block";
    });

    // Закриття модального вікна
    closeModal.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", (e) => e.target === modal && (modal.style.display = "none"));

    // Збереження вправ
    saveBtn.addEventListener("click", async function () {
        try {
            const chosen = Array.from(document.querySelectorAll(".exercise-item"))
                .filter(item => item.querySelector(".done-icon")?.style.display !== "none")
                .map(item => item.getAttribute("data-id").replace(/_/g, " "));

            if (chosen.length === 0) {
                throw new Error("Виберіть хоча б одну вправу");
            }

            const trainingType = document.querySelector("#trainingType");
            const dataBody = JSON.stringify({
                trainingType: trainingType.value,
                exercises: chosen,
            });
            // Збереження вправ
            console.log("Відправка запиту до save_exercise.php...");

            const saveResponse = await fetch("/save_exercise.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: dataBody
            });

            console.log("Відповідь отримана:", saveResponse);
            // Використовуємо message із серверного відповіді
            showMessage(
                saveResponse.status == 200 ? "✅ Вправи успішно збережено!" : "❌ Помилка збереження вправ"
            );

        } catch (err) {
            console.error("Помилка:", err);
            showMessage(`❌ ${err.message}`, "red");
        }
    });

    // Функція для показу повідомлень
    function showMessage(text, color) {
        messageDiv.textContent = text;
        messageDiv.style.color = color;
        setTimeout(() => messageDiv.textContent = "", 3000);
    }
});