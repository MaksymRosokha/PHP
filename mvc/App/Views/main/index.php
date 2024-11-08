<h1>Головна сторінка</h1>
<hr>
<h3>Дані користувача</h3>
<p>Імя: <?= $firstName ?></p>
<p>Прізвіще: <?= $lastName ?></p>

<hr>
<h3>Дані користувачів</h3>
<?php foreach ($users as $user) : ?>
    <p>Імя: <?= $user->getFirstName() ?></p>
    <p>Прізвіще: <?= $user->getLastName() ?></p>
    <hr>
<?php endforeach; ?>