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

                        <section class="pilates">
                            <?php
                            $sql = "SELECT * FROM pilates_data ORDER BY id ASC";
                            $result = $mysqli->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $dataMovie = $row['movie'] ? 'data-movie="' . $row['movie'] . '"' : '';
                                    echo '<a href="#" data-id="' . $row['data_id'] . '" ' . $dataMovie . ' class="pilates-link">';
                                    echo '<img src="' . $row['img'] . '" alt="' . $row['title'] . '">';
                                    echo '<p>' . $row['title'] . '</p>';
                                    echo '</a>';
                                }
                            } else {
                                echo '<p>Немає доступних вправ</p>';
                            }
                            ?>
                        </section>


    <script>

    </script>


                        <?php include '../components/footer.php'; ?>