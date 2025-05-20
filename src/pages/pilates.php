<?php
                        include '../components/auth_check.php';
                        $page_title = "Пілатес | Online Coach";
                        $include_main_js = true;

                        $username = $_SESSION['username'] ?? 'Гость';

                        include '../components/header.php';
                        include '../components/navbar.php';
                        ?>

                        <header>
                            <h1>Пілатес, <?= $username ?>!</h1>
                            <hr class="custom-line">
                            <p>Що таке пілатес?<br>
                                <span class="highlight">
                                   Пілатес — це система вправ, спрямованих на зміцнення м'язів кора, покращення гнучкості та постави.
                                   Він поєднує контрольовані рухи, дихання та концентрацію, що сприяє гармонійному розвитку тіла.
                                   Пілатес підходить для всіх рівнів підготовки, допомагаючи зняти напругу, покращити координацію
                                   та досягти балансу між силою і гнучкістю.
                               </span>
                            </p>
                        </header>

                        <div class="random">
                            <input type="hidden" id="trainingType" value="pilates">
                            <a href="#" id="generate">Згенерувати тренування</a>
                        </div>

                        <?php include '../components/modal.php'; ?>
                        <?php include '../components/modal-movie.php'; ?>

                        <section class="pilates">
                            <?php
                            // Запит до бази даних для отримання всіх вправ пілатесу
                            $sql = "SELECT * FROM pilates_data ORDER BY id ASC";
                            $result = $mysqli->query($sql);

                            // Перевіряємо, чи є результати
                            if ($result->num_rows > 0) {
                                // Виводимо кожну вправу у вигляді посилання з картинкою та назвою
                                while ($row = $result->fetch_assoc()) {
                                    // Якщо є посилання на відео, додаємо атрибут data-movie
                                    $dataMovie = $row['movie'] ? 'data-movie="' . $row['movie'] . '"' : '';
                                    echo '<a href="#" data-id="' . $row['data_id'] . '" ' . $dataMovie . ' class="pilates-link">';
                                    echo '<img src="' . $row['img'] . '" alt="' . $row['title'] . '">';
                                    echo '<p>' . $row['title'] . '</p>';
                                    echo '</a>';
                                }
                            } else {
                                // Якщо вправ немає — показуємо відповідне повідомлення
                                echo '<p>Немає доступних вправ</p>';
                            }
                            ?>
                        </section>


    <script>

    </script>


                        <?php include '../components/footer.php'; ?>