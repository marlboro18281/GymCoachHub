-- Таблиця users: інформація про користувачів
                                CREATE TABLE IF NOT EXISTS users (
                                                                     id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор користувача',
                                                                     user_name VARCHAR(50) NOT NULL COMMENT 'Ім’я користувача',
                                    surname VARCHAR(50) NOT NULL COMMENT 'Прізвище користувача',
                                    phone_number VARCHAR(15) NOT NULL COMMENT 'Номер телефону користувача',
                                    age INT NOT NULL CHECK (age >= 12 AND age <= 100) COMMENT 'Вік користувача (12-100 років)',
                                    registration_date DATE NOT NULL COMMENT 'Дата реєстрації',
                                    CONSTRAINT uk_phone_number UNIQUE (phone_number)
                                    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Таблиця для зберігання даних користувачів';

                                -- Таблиця fitness: дані про фітнес-вправи
                                CREATE TABLE IF NOT EXISTS fitness (
                                                                       id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                                                       user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (зв’язок з таблицею users)',
                                                                       lunges_forward INT NOT NULL DEFAULT 0 COMMENT 'Кількість випадів вперед',
                                                                       plank INT NOT NULL DEFAULT 0 COMMENT 'Час утримання планки (секунди)',
                                                                       squats INT NOT NULL DEFAULT 0 COMMENT 'Кількість присідань',
                                                                       glute_bridge INT NOT NULL DEFAULT 0 COMMENT 'Кількість підйомів тазу (місток)',
                                                                       abs_exercises INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ на прес',
                                                                       push_ups INT NOT NULL DEFAULT 0 COMMENT 'Кількість віджимань',
                                                                       burpees INT NOT NULL DEFAULT 0 COMMENT 'Кількість берпі',
                                                                       mountain_climbers INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "альпінізм"',
                                                                       high_knee_run INT NOT NULL DEFAULT 0 COMMENT 'Кількість бігу з підняттям колін',
                                                                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                                    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Таблиця для зберігання даних про фітнес-вправи';

                                -- Таблиця hardwork: дані про силові тренування
                                CREATE TABLE IF NOT EXISTS hardwork (
                                                                        id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                                                        user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (зв’язок з таблицею users)',
                                                                        back_extension INT NOT NULL DEFAULT 0 COMMENT 'Кількість екстензій спини',
                                                                        t_bar_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість Т-тяги',
                                                                        dumbbell_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг з гантеллю',
                                                                        seated_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг сидячи',
                                                                        barbell_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг штанги',
                                                                        barbell_upright_row INT NOT NULL DEFAULT 0 COMMENT 'Кількість тяг штанги до підборіддя',
                                                                        stationary_bike INT NOT NULL DEFAULT 0 COMMENT 'Час бігу на велотренажері (хвилини)',
                                                                        barbell_lunges INT NOT NULL DEFAULT 0 COMMENT 'Кількість випадів зі штангою',
                                                                        leg_extension INT NOT NULL DEFAULT 0 COMMENT 'Кількість розгинань ніг',
                                                                        crossover_leg_abduction INT NOT NULL DEFAULT 0 COMMENT 'Кількість відведень ніг у кросовері',
                                                                        barbell_squats INT NOT NULL DEFAULT 0 COMMENT 'Кількість присідань зі штангою',
                                                                        leg_adduction INT NOT NULL DEFAULT 0 COMMENT 'Кількість зведень ніг',
                                                                        leg_curl INT NOT NULL DEFAULT 0 COMMENT 'Кількість згинань ніг',
                                                                        arm_adduction INT NOT NULL DEFAULT 0 COMMENT 'Кількість зведень рук',
                                                                        dip_bars INT NOT NULL DEFAULT 0 COMMENT 'Кількість віджимань на брусах',
                                                                        crossover_arm INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ на кросовері для рук',
                                                                        barbell_bench_press INT NOT NULL DEFAULT 0 COMMENT 'Кількість жиму штанги',
                                                                        dumbbell_bench_press INT NOT NULL DEFAULT 0 COMMENT 'Кількість жиму гантель',
                                                                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                                    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Таблиця для зберігання даних про силові тренування';

                                -- Таблиця pilates: дані про заняття пілатесом
                                CREATE TABLE IF NOT EXISTS pilates (
                                                                       id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Унікальний ідентифікатор запису',
                                                                       user_id INT NOT NULL COMMENT 'Ідентифікатор користувача (зв’язок з таблицею users)',
                                                                       spine_stretch INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ на розтягування хребта',
                                                                       leg_lift_stretch INT NOT NULL DEFAULT 0 COMMENT 'Кількість піднімань ноги та розтяжки',
                                                                       scissor_twist INT NOT NULL DEFAULT 0 COMMENT 'Кількість скручувань "ножиці"',
                                                                       swan_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "лебідь"',
                                                                       ball_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "м’ячик"',
                                                                       pelvic_circles INT NOT NULL DEFAULT 0 COMMENT 'Кількість кругових рухів тазом',
                                                                       swimming_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "плавання"',
                                                                       kneeling_leg_extension INT NOT NULL DEFAULT 0 COMMENT 'Кількість розгинань ніг у положенні на колінах',
                                                                       shoulder_bridge INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "берізка"',
                                                                       push_ups INT NOT NULL DEFAULT 0 COMMENT 'Кількість віджимань',
                                                                       basket_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "кошик"',
                                                                       boomerang_exercise INT NOT NULL DEFAULT 0 COMMENT 'Кількість вправ "бумеранг"',
                                                                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                                    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Таблиця для зберігання даних про заняття пілатесом';

                                CREATE TABLE `pilates_data` (
                                                                `id` INT AUTO_INCREMENT PRIMARY KEY,
                                                                `data_id` VARCHAR(50) NOT NULL,
                                                                `img` VARCHAR(255) NOT NULL,
                                                                `title` VARCHAR(255) NOT NULL,
                                                                `movie` VARCHAR(255) DEFAULT NULL,
                                                                `link` VARCHAR(255) DEFAULT NULL,
                                                                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                                                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                INSERT INTO `pilates_data` (`data_id`, `img`, `title`, `movie`) VALUES
                                ('spine_stretch', '/src/images/spine.jpg', 'Розтягнення хребта', 'https://youtu.be/XZGuNaEV-nM?si=emX68HRUHk-2wKKK'),
                                ('leg_lift_stretch', '/src/images/rocker.jpg', 'Піднімання ніг з розтягненням', 'https://youtu.be/E4FSgzPlUcs?si=hpm7yEqzCKb7JeLW'),
                                ('scissor_twist', '/src/images/cross.jpg', 'Скручування ножиць', 'https://youtu.be/a2L7tfx8XbU?si=QwC_idUQ3dIju7S6'),
                                ('swan_exercise', '/src/images/dive.jpg', 'Вправа лебідь', 'https://youtu.be/mjZZ22GLcDc?si=4izh0KsOHNPHKDrh'),
                                ('ball_exercise', '/src/images/ball.jpg', 'Вправа з мячем', 'https://youtu.be/elkcXFPyaW8?si=1Zkibf6S3u8aphlM'),
                                ('pelvic_circles', '/src/images/circles.jpg', 'Кола тазом', 'https://youtu.be/YDM1g6f9aDA?si=Sc_bJUxTRP5SZGp4'),
                                ('swimming_exercise', '/src/images/swim.jpg', 'Вправа плавання', 'https://youtu.be/rbcvbbtB6E0?si=FYV9-RbofNIhtJnM'),
                                ('kneeling_leg_extension', '/src/images/kneeling.jpg', 'Розгинання ніг на колінах', 'https://youtu.be/hgLDMHCcw4k?si=65ZDMZiLMYeHiiev'),
                                ('shoulder_bridge', '/src/images/knife.jpg', 'Плечовий міст', 'https://youtu.be/SXYp9AJ1uWM?si=HiIa12NKse2foJK_'),
                                ('push_ups', '/src/images/pushup.jpg', 'Віджимання', 'https://youtu.be/Ny0qFffcemg?si=Ob60hWwUWqblo4k1'),
                                ('basket_exercise', '/src/images/rocking.jpg', 'Вправа кошик', 'https://youtu.be/KQWHWl7yV9g?si=fiIj2x97jTZ01cjZ'),
                                ('boomerang_exercise', '/src/images/boomerang.jpg', 'Вправа бумеранг', 'https://youtu.be/SRonJI25raE?si=bloV4weQvtb4bTmj');