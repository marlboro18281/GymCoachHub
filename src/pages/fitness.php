<?php
include '../components/auth_check.php';
$page_title = "Фітнес | Online Coach";
$include_main_js = true;
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <header>
        <h1>Фітнес, <?= $username ?>!</h1>
        <hr class="custom-line">
        <p>Що таке фітнес?<br>
            <span class="highlight">
               Фітнес — це комплекс вправ, спрямованих на покращення фізичної форми, витривалості та загального здоров’я.
               Він включає кардіо, силові вправи та вправи на гнучкість, які можна виконувати вдома чи в залі.
               Фітнес допомагає спалювати калорії, зміцнювати м’язи та підвищувати енергію.
               Це ідеальний вибір для тих, хто хоче підтримувати активний спосіб життя та досягати своїх фітнес-цілей.
           </span>
        </p>
    </header>

    <div class="random">
        <input type="hidden" id="trainingType" value="fitness">
        <a href="#" id="generate">Згенерувати тренування</a>
    </div>

<?php include '../components/modal.php'; ?>

    <section class="fitness">
        <a href="#" data-id="lunges_forward">
            <img src="/src/images/lunge(fit).jpg" alt="Випади вперед">
            <p>Випади вперед</p>
        </a>
        <a href="#" data-id="plank">
            <img src="/src/images/planl(fit).jpg" alt="Планка">
            <p>Планка</p>
        </a>
        <a href="#" data-id="squats">
            <img src="/src/images/squat(fit).jpg" alt="Присідання">
            <p>Присідання</p>
        </a>
        <a href="#" data-id="glute_bridge">
            <img src="/src/images/bridge(fit).jpg" alt="Сідничний міст">
            <p>Сідничний міст</p>
        </a>
        <a href="#" data-id="abs_exercises">
            <img src="/src/images/crunch(fit).jpg" alt="Вправи на прес">
            <p>Вправи на прес</p>
        </a>
        <a href="#" data-id="push_ups">
            <img src="/src/images/pushup(fit).jpg" alt="Віджимання">
            <p>Віджимання</p>
        </a>
        <a href="#" data-id="burpees">
            <img src="/src/images/burpees(fit).jpg" alt="Берпі">
            <p>Берпі</p>
        </a>
        <a href="#" data-id="mountain_climbers">
            <img src="/src/images/mountain(fit).jpg" alt="Альпініст">
            <p>Альпініст</p>
        </a>
        <a href="#" data-id="high_knee_run">
            <img src="/src/images/highknees(fit).jpg" alt="Біг із високим підніманням колін">
            <p>Біг із високим підніманням колін</p>
        </a>
    </section>

<?php include '../components/footer.php'; ?>