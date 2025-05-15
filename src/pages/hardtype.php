<?php
include '../components/auth_check.php';
$page_title = "Силові тренування | Online Coach";
$include_main_js = true;
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <header>
        <h1>Силові тренування, <?= $username ?>!</h1>
        <hr class="custom-line">
        <p>Що таке силові тренування?<br>
            <span class="highlight">
               Силові тренування — це комплекс вправ, спрямованих на розвиток сили, витривалості та м’язової маси.
               Вони включають вправи з власною вагою, тренажерами або вільними вагами (штанги, гантелі).
               Такі заняття допомагають зміцнити м’язи, покращити поставу та підвищити загальну фізичну форму.
               Силові тренування ідеально підходять для тих, хто хоче досягти рельєфного тіла та покращити здоров’я.
           </span>
        </p>
    </header>

    <div class="random">
        <input type="hidden" id="trainingType" value="hardwork">
        <a href="#" id="generate">Згенерувати тренування</a>
    </div>

<?php include '../components/modal.php'; ?>

    <section class="hardexercise">
        <a href="#" data-id="back_extension">
            <img src="/src/images/back_extension.jpg" alt="Екстензія спини">
            <p>Екстензія спини</p>
        </a>
        <a href="#" data-id="t_bar_row">
            <img src="/src/images/t_bar_row.jpg" alt="Т-тяга">
            <p>Т-тяга</p>
        </a>
        <a href="#" data-id="dumbbell_row">
            <img src="/src/images/dumbbell_row.jpg" alt="Тяга з гантеллю">
            <p>Тяга з гантеллю</p>
        </a>
        <a href="#" data-id="seated_row">
            <img src="/src/images/seated_row.jpg" alt="Тяга сидячи">
            <p>Тяга сидячи</p>
        </a>
        <a href="#" data-id="barbell_row">
            <img src="/src/images/barbell_row.jpg" alt="Тяга штанги">
            <p>Тяга штанги</p>
        </a>
        <a href="#" data-id="barbell_upright_row">
            <img src="/src/images/barbell_upright_row.jpg" alt="Тяга штанги до підборіддя">
            <p>Тяга штанги до підборіддя</p>
        </a>
        <a href="#" data-id="stationary_bike">
            <img src="/src/images/stationary_bike.jpg" alt="Біг на велотренажері">
            <p>Біг на велотренажері</p>
        </a>
        <a href="#" data-id="barbell_lunges">
            <img src="/src/images/barbell_lunges.jpg" alt="Випади зі штангою">
            <p>Випади зі штангою</p>
        </a>
        <a href="#" data-id="leg_extension">
            <img src="/src/images/leg_extension.jpg" alt="Розгинання ніг">
            <p>Розгинання ніг</p>
        </a>
        <a href="#" data-id="crossover_leg_abduction">
            <img src="/src/images/crossover_leg_abduction.jpg" alt="Відведення ніг у кросовері">
            <p>Відведення ніг у кросовері</p>
        </a>
        <a href="#" data-id="barbell_squats">
            <img src="/src/images/barbell_squats.jpg" alt="Присідання зі штангою">
            <p>Присідання зі штангою</p>
        </a>
        <a href="#" data-id="leg_adduction">
            <img src="/src/images/leg_adduction.jpg" alt="Зведення ніг">
            <p>Зведення ніг</p>
        </a>
        <a href="#" data-id="leg_curl">
            <img src="/src/images/leg_curl.jpg" alt="Згинання ніг">
            <p>Згинання ніг</p>
        </a>
        <a href="#" data-id="arm_adduction">
            <img src="/src/images/arm_adduction.jpg" alt="Зведення рук">
            <p>Зведення рук</p>
        </a>
        <a href="#" data-id="dip_bars">
            <img src="/src/images/dip_bars.jpg" alt="Віджимання на брусах">
            <p>Віджимання на брусах</p>
        </a>
        <a href="#" data-id="crossover_arm">
            <img src="/src/images/crossover_arm.jpg" alt="Вправи на кросовері для рук">
            <p>Вправи на кросовері для рук</p>
        </a>
        <a href="#" data-id="barbell_bench_press">
            <img src="/src/images/barbell_bench_press.jpg" alt="Жим штанги">
            <p>Жим штанги</p>
        </a>
        <a href="#" data-id="dumbbell_bench_press">
            <img src="/src/images/dumbbell_bench_press.jpg" alt="Жим гантель">
            <p>Жим гантель</p>
        </a>
    </section>

<?php include '../components/footer.php'; ?>