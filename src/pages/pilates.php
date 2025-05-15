<?php
include '../components/auth_check.php';
$page_title = "Пілатес | Online Coach";
$include_main_js = true;
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

    <header>
        <h1>Пілатес, <?= $username ?>!</h1>
        <hr class="custom-line">
        <p>Що таке пілатес?<br>
            <span class="highlight">
               Пілатес — це система вправ, спрямованих на зміцнення м’язів кора, покращення гнучкості та постави.
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
        <a href="#" data-id="spine_stretch">
            <img src="/src/images/spine.jpg" alt="Розтягнення хребта">
            <p>Розтягнення хребта</p>
        </a>
        <a href="#" data-id="leg_lift_stretch">
            <img src="/src/images/rocker.jpg" alt="Піднімання ніг з розтягненням">
            <p>Піднімання ніг з розтягненням</p>
        </a>
        <a href="#" data-id="scissor_twist">
            <img src="/src/images/cross.jpg" alt="Скручування ножиць">
            <p>Скручування ножиць</p>
        </a>
        <a href="#" data-id="swan_exercise">
            <img src="/src/images/dive.jpg" alt="Вправа лебідь">
            <p>Вправа лебідь</p>
        </a>
        <a href="#" data-id="ball_exercise">
            <img src="/src/images/ball.jpg" alt="Вправа з м’ячем">
            <p>Вправа м’ячик</p>
        </a>
        <a href="#" data-id="pelvic_circles">
            <img src="/src/images/circles.jpg" alt="Кола тазом">
            <p>Кола тазом</p>
        </a>
        <a href="#" data-id="swimming_exercise">
            <img src="/src/images/swim.jpg" alt="Вправа плавання">
            <p>Вправа плавання</p>
        </a>
        <a href="#" data-id="kneeling_leg_extension">
            <img src="/src/images/kneeling.jpg" alt="Розгинання ніг на колінах">
            <p>Розгинання ніг на колінах</p>
        </a>
        <a href="#" data-id="shoulder_bridge">
            <img src="/src/images/knife.jpg" alt="Плечовий міст">
            <p>Плечовий міст</p>
        </a>
        <a href="#" data-id="push_ups">
            <img src="/src/images/pushup.jpg" alt="Віджимання">
            <p>Віджимання</p>
        </a>
        <a href="#" data-id="basket_exercise">
            <img src="/src/images/rocking.jpg" alt="Вправа кошик">
            <p>Вправа кошик</p>
        </a>
        <a href="#" data-id="boomerang_exercise">
            <img src="/src/images/boomerang.jpg" alt="Вправа бумеранг">
            <p>Вправа бумеранг</p>
        </a>
    </section>

<?php include '../components/footer.php'; ?>