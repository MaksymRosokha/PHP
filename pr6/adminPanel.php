<?php

declare(strict_types=1);

session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "guest_book";
$conn = null;

try{
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception();
    }
} catch (Exception $ex){
    die("Помилка підключення до бази даних");
}

$page = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1;
$sortAttribute = isset($_GET['sort_attribute']) && !empty($_GET['sort_attribute']) ? $_GET['sort_attribute'] : "user_name";
$sortByDescendingOrAscending = isset($_GET['sort_by_descending_or_ascending']) && !empty($_GET['sort_by_descending_or_ascending'])
    ? $_GET['sort_by_descending_or_ascending']
    : "DESC";
define("LIMIT", 10);
define("OFFSET", LIMIT * ($page - 1));
$responses = getResponses(LIMIT, OFFSET, $sortAttribute, $sortByDescendingOrAscending);
$numberOfResponses = getCountOfResponses();

function getResponses(int $limit, int $offset, string $sortAttribute, string $sortByDescendingOrAscending):array{
    global $conn;
    $sql = "";
    if($sortAttribute == "user_name" || $sortAttribute == "email" || $sortAttribute == "date_of_writing"){
        if($sortByDescendingOrAscending == "DESC"){
            $sql = "SELECT users.user_name, users.email, users.user_group, responses.image_or_file, responses.content, responses.date_of_writing
                    FROM users
                INNER JOIN responses
                        ON users.id = responses.id_user
                    ORDER BY {$sortAttribute} DESC
                    LIMIT ?
                    OFFSET ?";
        } else {
            $sql = "SELECT users.user_name, users.email, users.user_group, responses.image_or_file, responses.content, responses.date_of_writing
                    FROM users
                    INNER JOIN responses
                        ON users.id = responses.id_user
                    ORDER BY {$sortAttribute}
                    LIMIT ?
                    OFFSET ?";
        }
    }

    try{
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $responses = [];
            while($response = $result->fetch_assoc()){
                $responses[] = $response;
            }
            return $responses;
        }
    } catch (Exception $ex){
        echo $ex->getMessage();
    }
}

function getCountOfResponses(): int{
    global $conn;
    $sql = "SELECT COUNT(id) AS numner_of_responses
              FROM responses;";
    try{
        if($stmt = $conn->prepare($sql)){
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $responses = [];
            if($number = $result->fetch_assoc()){
                return $number['numner_of_responses'];
            }
        }
    } catch (Exception $ex){
        echo $ex->getMessage();
    }
}
?>


<!doctype html>
<html lang="uk">

    <head>
        <title>Адмін-панель</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo time(); ?>">
    </head>

    <body>
        <header>
            <h1>Адмін-панель</h1>
        </header>
        <main>
            <section>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                    <label for="html">Сторінка:</label>
                    <select name="page">
                        <?php
                            for($i = 1; $i <= ceil($numberOfResponses / LIMIT); $i++) { ?>
                                <option value="<?php echo $i; ?>"
                                    <?php
                                    if($i == $page){?>
                                        selected <?php
                                    }
                                    ?> ><?php echo $i; ?>
                                </option> <?php
                            } ?>
                    </select>

                    <label for="html">Сортувати по:</label>
                    <select name="sort_attribute">
                        <option value="user_name" <?php if($sortAttribute === "user_name") { ?> selected <?php } ?>>Логін</option>
                        <option value="email" <?php if($sortAttribute === "email") { ?> selected <?php } ?>>E-mail</option>
                        <option value="date_of_writing" <?php if($sortAttribute === "date_of_writing") { ?> selected <?php } ?>>Дата</option>
                    </select>

                    <label for="html">Сортувати по:</label>
                    <select name="sort_by_descending_or_ascending">
                        <option value="DESC" <?php if($sortByDescendingOrAscending === "DESC") { ?> selected <?php } ?>>Спаданню</option>
                        <option value="ASC" <?php if($sortByDescendingOrAscending === "ASC") { ?> selected <?php } ?>>Зростанню</option>
                    </select>
                    <input type="submit" value="Вибрати">
                </form>
            </section>
            <section>
                <table>
                    <tr>
                        <th>Логін</th>
                        <th>E-mail</th>
                        <th>Група</th>
                        <th>Дата</th>
                        <th>Повідомлення</th>
                        <th>Картинка або текстовий файл</th>
                    </tr>
                    <?php
                        foreach($responses as $resp){ ?>
                            <tr>
                                <td><?php echo $resp['user_name']; ?></td>
                                <td><?php echo $resp['email']; ?></td>
                                <td><?php echo $resp['user_group']; ?></td>
                                <td><?php echo $resp['date_of_writing']; ?></td>
                                <td style="word-wrap: break-word"><?php echo $resp['content']; ?></td>
                                <td class="user-image-or-text">
                                    <?php
                                        switch (strtolower(pathinfo("images or files/" . $resp['image_or_file'], PATHINFO_EXTENSION))) {
                                            case "txt":{
                                                $file = fopen("images or files/" . $resp['image_or_file'], 'r');
                                                echo fread($file,filesize("images or files/" . $resp['image_or_file']));
                                                fclose($file);
                                                break;
                                            }
                                            case "png":{}
                                            case "gif":{}
                                            case "jpg":{ ?>
                                                <img src="images or files/<?php echo $resp['image_or_file']; ?>"
                                                    alt="Не вдалося завантажити зображення"> <?php
                                                break;
                                            }
                                            default:{
                                                echo "Картинка або текстовий файл не додані";
                                            }
                                        }
                                    ?>
                                </td>
                            </tr> <?php
                        }
                    ?>
                </table>
            </section>
        </main>
    </body>

</html>
