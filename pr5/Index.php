<?php

declare(strict_types=1);

$countries = $cities = $country = $city = $cityLng = $cityLat = null;

if(!empty($_GET["country"])){
    $country = $_GET["country"];
}
if(!empty($_GET["city"])){
    $city = $_GET["city"];
}

try {
    $countriesFile = fopen("location/countries.json", 'r');
    $countries = json_decode(fread($countriesFile, filesize("location/countries.json")), true);
    fclose($countriesFile);
} catch (Exception $ex){
    echo "Не вдалося завантажити список країн";
    exit();
}
try {
    $citiesFile = fopen("location/current.city.list.json", 'r');
    $cities = json_decode(fread($citiesFile, filesize("location/current.city.list.json")), true);
    fclose($citiesFile);
} catch (Exception $ex){
    echo "Не вдалося завантажити список міст даної країни";
    exit();
}
echo strlen("Студенти!");
?>

<!doctype html>
<html lang="uk">
    <head>
        <title>Погода</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/mainStyle.css">
    </head>
    <body>
        <header>
            <h1>Погода</h1>
        </header>
        <main>
            <?php if(empty($city)){ ?>
            <section>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
                    Країна: <select id="country" name="country" <?php if(!empty($country)) {?> disabled  <?php } ?>>
                        <?php
                        try{
                            foreach ($countries as $key => $value){ ?>
                                <option value="<?php echo $key; ?>"
                                    <?php
                                    if(!empty($country) && $country == $key){ ?>
                                        selected
                                    <?php }?>
                                ><?php echo $value; ?>
                                </option>
                        <?php }
                        } catch (Exception $ex){}?>
                    </select><br>
                    <?php
                    if(!empty($country)){ ?>
                        Місто: <select id="city" name="city">
                            <?php
                            try{
                                foreach ($cities as $c){
                                    if($c['country'] === $country){ ?>
                                        <option value="<?php echo $c['name']; ?>"><?php echo $c['name']; ?></option><?php
                                    }
                                }
                            } catch (Exception $ex){}?>
                        </select><br> <?php
                    } ?>
                    <input type="submit">
                </form>
            </section>
            <?php
            }
            else {
                try {
                    foreach ($cities as $c) {
                        if ($c['name'] === $city) {
                            $cityLat = $c['coord']["lat"];
                            $cityLng = $c['coord']["lon"];
                        }
                    }
                } catch (Exception $ex){ ?>
                    <h2 class="error">Не вдалося отримати координати міста</h2>
                    <?php
                    exit();
                }

                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt(
                        $ch,
                        CURLOPT_URL,
                        "https://api.openweathermap.org/data/2.5/weather?lat=" . $cityLat . "&lon=" . $cityLng . "&appid=6d203fc1c41be3288a91cbd46bd8f70f"
                    );
                    $weatherData = json_decode(curl_exec($ch), true);
                    curl_close($ch);
                } catch (Exception $ex){ ?>
                    <h2 class="error">Не має доступу до сервру</h2>
                    <?php
                    exit();
                } catch (Error $er){ ?>
                    <h2 class="error">Не має доступу до сервру</h2>
                    <?php
                    exit();
                }

                try {?>
                    <section>
                        <div id="showWeather">
                            <h2><?php echo $city; ?></h2>
                            <a href="https://maps.google.com/?q=<?php echo $cityLat; ?>,<?php echo $cityLng; ?>"
                               target="_blank">Google maps</a>
                            <br>
                            <a href="https://en.wikipedia.org/wiki/<?php echo $city; ?>" target="_blank">Wikipedia</a>
                            <br>
                            <img src="images/<?php echo $weatherData['weather'][0]['main']; ?>.jpg" alt="weather">
                            <br>
                            Температура: <?php echo $weatherData['main']['temp'] - 272.15; ?>
                            <br>
                            Відчувається як: <?php echo $weatherData['main']['feels_like'] - 272.15; ?>
                            <br>
                            Мінімальна температура: <?php echo $weatherData['main']['temp_min'] - 272.15; ?>
                            <br>
                            Максимальна температура: <?php echo $weatherData['main']['temp_max'] - 272.15; ?>
                            <br>
                            Вологість: <?php echo $weatherData['main']['humidity'] . '%'; ?>
                            <br>
                            Тиск: <?php echo $weatherData['main']['pressure']; ?>
                            <br>
                            Видимість: <?php echo $weatherData['visibility'] - 272.15; ?>
                            <br>
                            Дата та час: <?php echo date("Y-m-d H:i", $weatherData['dt']); ?>
                            <br>
                            Часовий пояс: <?php echo $weatherData['timezone']; ?>
                            <br>
                            Sunrise: <?php echo date("Y-m-d H:i", $weatherData['sys']['sunrise']); ?>
                            <br>
                            Sunset: <?php echo date("Y-m-d H:i", $weatherData['sys']['sunset']); ?>
                            <br>
                            <iframe src="https://maps.google.com/maps?q=<?php echo $cityLat; ?>,<?php echo $cityLng; ?>&hl=uk&z=14&amp;output=embed">
                        </div>
                    </section>
                <?php
                } catch (Exception $ex){
                    ?>
                    <h2 class="error">Не має доступу до даних серверу</h2>
                    <?php
                } catch (Error $er){
                    ?>
                    <h2 class="error">Не має доступу до даних серверу</h2>
                    <?php
                }
            } ?>
        </main>
    </body>
</html>