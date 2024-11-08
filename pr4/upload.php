<?php

declare(strict_types=1);

if(isset($_FILES['newFish'])){
    $errors = array();
    $name = $_FILES['newFish']['name'];

    $targetDir = "images/";
    $targetFile = $targetDir . basename($name);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if($name === ""){
        $errors[] = "Зображення не вибране";
    }
    if(file_exists($targetFile) && $name !== ""){
        $errors[] = "Зображення з такою назвою вже існує";
    }
    if($_FILES["newFish"]["size"] > 10000000){
        $errors[] = "Зображення не може бути важчим ніж 10мб";
    }
    switch ($imageFileType){
        case "png":{}
        case "jpg":{break;}
        default:{
            $errors[] = "Зображення повинно мати одне з наступних розширень: \"png\", \"jpg\"";
        }
    }

    if(empty($errors)) {
        if (move_uploaded_file($_FILES["newFish"]["tmp_name"], $targetFile)) {
            echo "Файл " . htmlspecialchars(basename($_FILES["newFish"]["name"])) . " був успішно завантажений.";
        } else {
            echo "Вибачте, не вдалося завантажити файл";
        }
    }
    else {
        foreach ($errors as $error){
            echo $error . "<br>";
        }
    }
}