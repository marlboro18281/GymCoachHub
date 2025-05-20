/**
 * Функціонал для роботи з метриками користувача (вага, зріст)
 */
document.addEventListener('DOMContentLoaded', function() {
    // Отримання елементів DOM
    const updateBtn = document.getElementById('update-btn');
    const baseMetricsBtn = document.getElementById('base-metrics-btn');
    const modal = document.getElementById('metrics-modal');
    const modalCloseBtn = document.getElementById('modal-close');
    const modalForm = document.getElementById('metrics-form');
    const weightInput = document.getElementById('weight');
    const heightInput = document.getElementById('height');
    const baseWeightSpan = document.getElementById('base-weight');
    const baseHeightSpan = document.getElementById('base-height');
    const weightDiffSpan = document.getElementById('weight-diff');
    const heightDiffSpan = document.getElementById('height-diff');
    const metricsTable = document.getElementById('metrics-table');
    const progressChart = document.getElementById('progressChart');

    let metricsChart = null;

    // Відкриття модального вікна для оновлення поточної ваги та зросту
    if (updateBtn) {
        updateBtn.addEventListener('click', function() {
            openModal(false);
        });
    }

    // Відкриття модального вікна для оновлення базових метрик
    if (baseMetricsBtn) {
        baseMetricsBtn.addEventListener('click', function() {
            openModal(true);
        });
    }

    // Закриття модального вікна
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', function() {
            closeModal();
        });
    }

    // Закриття модального вікна при кліку поза ним
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Обробка відправки форми метрик
    if (modalForm) {
        modalForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const isBase = modalForm.dataset.isBase === 'true';
            const weight = parseFloat(weightInput.value);
            const height = parseInt(heightInput.value);

            // Валідація даних
            if (!weight || !height || weight <= 0 || height <= 0) {
                showMessage('Будь ласка, введіть коректні дані', 'error');
                return;
            }

            try {
                const response = await fetch('/src/pages/save-metrics.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'update_metrics',
                        weight: weight,
                        height: height,
                        is_base: isBase
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message, 'success');
                    closeModal();

                    // Оновлення відображення даних на сторінці
                    if (result.data) {
                        updateMetricsDisplay(result.data);
                    }

                    // Оновлення графіка і таблиці
                    fetchAndDisplayMetrics();
                } else {
                    showMessage(result.message || 'Помилка при збереженні', 'error');
                }
            } catch (error) {
                console.error('Помилка:', error);
                showMessage('Сталася помилка при збереженні даних', 'error');
            }
        });
    }

    // Ініціалізація - завантаження даних
    fetchAndDisplayMetrics();

    /**
     * Функція для відкриття модального вікна
     * @param {boolean} isBase - чи оновлюються базові метрики
     */
    function openModal(isBase) {
        if (modal && modalForm) {
            // Очищення форми
            weightInput.value = '';
            heightInput.value = '';

            // Встановлення типу оновлення
            modalForm.dataset.isBase = isBase;

            // Зміна заголовка
            const modalTitle = document.getElementById('modal-title');
            if (modalTitle) {
                modalTitle.textContent = isBase
                    ? 'Оновлення базових метрик'
                    : 'Додавання нових метрик';
            }

            // Відображення модального вікна
            modal.style.display = 'flex';
        }
    }

    /**
     * Закриття модального вікна
     */
    function closeModal() {
        if (modal) {
            modal.style.display = 'none';
        }
    }

    /**
     * Показ повідомлення користувачу
     * @param {string} message - текст повідомлення
     * @param {string} type - тип повідомлення (success, error)
     */
    function showMessage(message, type) {
        const messageElement = document.getElementById('message');
        if (messageElement) {
            messageElement.textContent = message;
            messageElement.className = `message message-${type}`;
            messageElement.style.display = 'block';

            // Автоматичне приховування повідомлення
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 3000);
        }
    }

    /**
     * Оновлення відображення метрик на сторінці
     * @param {Object} data - об'єкт з даними метрик
     */
    function updateMetricsDisplay(data) {
        // Оновлення базових метрик
        if (data.base && baseWeightSpan && baseHeightSpan) {
            baseWeightSpan.textContent = data.base.weight;
            baseHeightSpan.textContent = data.base.height;
        }

        // Оновлення різниці метрик
        if (data.difference && weightDiffSpan && heightDiffSpan) {
            const weightDiff = data.difference.weight;
            const heightDiff = data.difference.height;

            weightDiffSpan.textContent = weightDiff > 0 ? `+${weightDiff}` : weightDiff;
            weightDiffSpan.className = weightDiff > 0 ? 'increased' : (weightDiff < 0 ? 'decreased' : '');

            heightDiffSpan.textContent = heightDiff > 0 ? `+${heightDiff}` : heightDiff;
            heightDiffSpan.className = heightDiff > 0 ? 'increased' : (heightDiff < 0 ? 'decreased' : '');
        }
    }

    /**
     * Отримання та відображення даних метрик
     */
    async function fetchAndDisplayMetrics() {
        try {
            const response = await fetch('/src/pages/get-metrics.php');
            const data = await response.json();

            if (data.success) {
                // Оновлення відображення базових метрик і різниці
                if (data.base && data.current) {
                    updateMetricsDisplay({
                        base: data.base,
                        difference: data.difference
                    });
                }

                // Оновлення таблиці історії
                if (data.history && metricsTable) {
                    updateMetricsTable(data.history);
                }

                // Оновлення графіку
                if (data.history && progressChart) {
                    updateProgressChart(data.history);
                }
            }
        } catch (error) {
            console.error('Помилка отримання даних метрик:', error);
        }
    }

    /**
     * Оновлення таблиці історії метрик
     * @param {Array} history - масив з історією метрик
     */
    function updateMetricsTable(history) {
        if (!metricsTable) return;

        // Очищення таблиці
        const tbody = metricsTable.querySelector('tbody');
        if (tbody) {
            tbody.innerHTML = '';

            // Додавання нових рядків
            history.forEach(item => {
                const row = document.createElement('tr');

                // Форматування дати
                const date = new Date(item.recorded_at);
                const formattedDate = `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;

                row.innerHTML = `
                    <td>${formattedDate}</td>
                    <td>${item.weight} кг</td>
                    <td>${item.height} см</td>
                `;

                tbody.appendChild(row);
            });
        }
    }

    /**
     * Оновлення графіку прогресу
     * @param {Array} history - масив з історією метрик
     */
    function updateProgressChart(history) {
        if (!progressChart) return;

        // Підготовка даних для графіка
        const dates = [];
        const weights = [];
        const heights = [];

        // Перевертаємо масив, щоб дати йшли від ранніх до пізніх
        const reversedHistory = [...history].reverse();

        reversedHistory.forEach(item => {
            const date = new Date(item.recorded_at);
            dates.push(`${date.getDate()}.${date.getMonth() + 1}`);
            weights.push(item.weight);
            heights.push(item.height / 100); // Переводимо зріст у метри для кращого масштабування
        });

        // Якщо графік вже існує, оновлюємо його
        if (metricsChart) {
            metricsChart.data.labels = dates;
            metricsChart.data.datasets[0].data = weights;
            metricsChart.data.datasets[1].data = heights;
            metricsChart.update();
        } else {
            // Створюємо новий графік
            metricsChart = new Chart(progressChart, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Вага (кг)',
                            data: weights,
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Зріст (м)',
                            data: heights,
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            tension: 0.1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Вага (кг)'
                            }
                        },
                        y1: {
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Зріст (м)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }
    }
});