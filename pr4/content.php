<?php

declare(strict_types=1);
session_start();

if(!isset($_SESSION['userName'])){
    header( "Location: Index.php" );
}

$userGroup = $_SESSION['group'];

define("PATH_TO_IMAGES", "images");

function getImages(): array{
    $images = null;
    $imagesFormatJPG = glob(PATH_TO_IMAGES . "/*.jpg");
    $imagesFormatPNG = glob(PATH_TO_IMAGES . "/*.png");
    foreach($imagesFormatJPG as $imageJPG){
        $images[] = $imageJPG;
    }
    foreach($imagesFormatPNG as $imagePNG){
        $images[] = $imagePNG;
    }
    return $images;
}

function getImageNames(array $images): array{
    $names = null;
    for($i = 0; $i < count($images); $i++){
        $names[$i] = basename($images[$i], ".jpg");
        $names[$i] = basename($names[$i], ".png");
    }
    return $names;
}

$images = getImages();
$imageNames = getImageNames($images);

$logFile = fopen("logs/" . date("Y-m-d") . ".log", 'a');
fwrite($logFile, "Час заходу: " . date("H:i:s") . ";  IP: " . $_SERVER['REMOTE_ADDR'] . ";  " .
"URI сторінки: " . $_SERVER [ 'REQUEST_URI' ] . ";  Referer: " . $_SERVER['HTTP_REFERER'] . "\r\n");
fclose($logFile);
?>

<!doctype html>
<html lang="uk">
  <head>
      <title>Fishman</title>
      <meta charset="UTF-8">
      <link rel="icon" type="image/x-icon" href="images/icons/Icon.ico">

      <style>
          h1, h2{
              text-align: center;
          }
          body{
              background: #8400ff;
          }
          header{
              background: #01a6a1;
          }
          aside{
              background: #d403da;
          }
          article{
              background: #ade8e7;
          }
          footer{
              background: #6e01d3;
              color: #e9b2ff;
          }
          hr{
              border-color: #000000;
          }
          #pages{
              background-color: #be87f3;
              padding-left: 20px;
          }
      </style>
  </head>
  <body>
    <header>
        <h1>Fishman</h1>
        <nav><b>Меню</b>
            <ol>
                <li><a href="#aboutFishing">Про рибальство</a></li>
                <li><a href="#fishingInUkraine">Рибальство в Україні</a></li>
                <li><a href="#kindOfFishInUkraine">Види риб України</a></li>
                <li><a href="#aboutAuthor">Про автора</a></li>
            </ol>
        </nav>
        <hr>
    </header>
    <main>
        <section>
            <div id="pages">
                <h2>Власні сторінки</h2>
                <?php
                    switch ($userGroup){
                        case "admin":{?>
                            <a href="adminPage.php">Сторінка адміністратора</a><br>
                        <?php }
                        case "moder":{?>
                            <a href="moderPage.php">Сторінка модератора</a><br>
                        <?php }
                        case "user":{ ?>
                            <a href="userPage.php">Сторінка користувача</a><br>
                        <?php }
                    }
                ?>
                <a href="clearData.php">Вихід з сторінки</a><br>
            </div>
        </section>
        <section>
            <article>
                <h2 id="aboutFishing">Про рибальство</h2>
                <p>
                    <i><span><dfn><strong>Рибальство</strong></dfn></span> — приватна або промислова ловля риби та інших
                    водних тварин. Під рибальством також розуміють полювання інших водних тварин — молюсків, кальмарів,
                    восьминогів, морських черепах, жаб, ракоподібних.</i>
                </p>
                <p>
                    Впродовж століть рибальство було в людини одним з головних способів <b>здобуття їжі</b>.
                    Зараз промислове рибальство використовує сучасні технології, а деякі види риб,
                    молюсків та ракоподібних вирощуються на спеціальних фермах. Приватне рибальство
                    залишається одною з найулюбленіших розваг і популярним видом спорту (див. спортивне рибальство).
                </p>
            </article>
            <hr>
            <article>
                <h2 id="fishingInUkraine">Рибальство в Україні</h2>
                <p>
                    Рибальство в Україні було відоме віддавна. Це підтверджують археологічні знахідки — глиняні та
                    кам'яні грузила для риболовних сітей, різноманітні гачки тощо, які належать до черняхівської
                    культури (II—V ст.) та наступних епох (VI—IX ст.). Поширенню рибальства сприяла велика кількість
                    річок та інших водоймищ, а також прадавня традиція використання риби у харчуванні.
                </p>
                <p>
                    У період феодалізму вилов риби належав до панщизняних повинностей кріпаків. Крім того, у деяких
                    районах України селяни зобов'язані були поставляти своїм поміщикам прядиво для риболовних снастей,
                    підводи для транспортування риби.
                </p>
                <p>
                    Джерела свідчать, що вже у XVI ст. в Україні статутом регламентувалися терміни риболовлі,
                    зазначалися види снастей, якими можна було виловлювати рибу.
                </p>
                <p>
                    Рибальство — це переважно додаткове заняття, яке було доступне у будь-яку пору року людям різного
                    віку, не вимагало складних знарядь праці та ін. Вільною ловлею риби користувалися, насамперед,
                    привілейовані класи та дрібні підприємці, а також чиновники, які це право купували. Селяни рибу
                    для власних потреб ловили потай, у вільний від сільськогосподарських робіт час. Малі хлопці й
                    підлітки мали дещо більшу свободу щодо вилову риби.
                </p>
                <p>
                    Для селян риболовля була певною підмогою в їх господарстві. Там, де було більше водних угідь,
                    риба входила у їх щоденний раціон, в інших районах її споживали передовсім у дні посту,
                    на певні релігійні свята. Отже, рибною ловлею селяни прагнули поповнити чи покращити своє
                    харчування. Риба була також предметом продажу й обміну на інші продукти й побутові речі
                    (льон, хліб, сіль).
                </p>
                <p>
                    Найбільшими районами рибальства в Україні були: пониззя Дніпра, Південного Бугу, Дністра, Пруту,
                    Прип'яті, Десни, узбережжя Чорного й Азовського морів. Тут рибальство вважалося основним заняттям
                    певної частини мешканців, яке переросло у промисел.
                </p>
                <p>
                    До 1917 по найбільших містах України існували різні рибальські товариства спортивно-любительського
                    характеру. В УРСР любителі рибальського спорту були об'єднані у районові відділи-секції
                    Українського товариства мисливців та рибалок (на 1 січня 1962 — 400 з 250 000 осіб).
                    У 2006 році було засновано всеукраїнське риболовне громадське об'єднання — Громада Рибалок
                    України (ГРУ), що вже має діючі осередки у більшості регіонів України.
                </p>
            </article>
            <hr>
            <article>
                <h2 id="kindOfFishInUkraine">Види риб України</h2>
                <?php
                for($i = 0; $i < count($images); $i++){ ?>
                    <figure>
                        <img src=" <?php echo $images[$i];?>" width="30%" height="30%">
                        <figcaption><?php echo $imageNames[$i];?></figcaption>
                    </figure>
                    <?php
                }
                ?>
            </article>
            <hr>
        </section>
    </main>

    <footer>
        <address id="aboutAuthor">
            Автор:<br>
            студент групи КН-43<br>
            Росоха Максим Валентинович<br>
        </address>
    </footer>

  </body>
</html>
