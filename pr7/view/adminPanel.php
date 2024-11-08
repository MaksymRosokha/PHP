<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/ConnectionManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/entity/User.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dao/UserDAO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/impl/UserDAOImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/entity/Response.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dao/ResponseDAO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/impl/ResponseDAOImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/factory/DAOFactory.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/factory/DAOFactoryImpl.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/dto/UserDTO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/dto/CurrentUserDTO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/dto/ResponseDTO.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/service/AdminPanelService.php";

session_start();

$responses = AdminPanelService::getResponses($_GET);
$numberOfResponses = AdminPanelService::getNumberOfResponses();

?>


<!doctype html>
<html lang="uk">

    <head>
        <title>Адмін-панель</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../data/css/style.css?v=<?php echo time(); ?>">
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
                            for($i = 1; $i <= ceil($numberOfResponses / AdminPanelService::getLimit()); $i++) { ?>
                                <option value="<?php echo $i; ?>"
                                    <?php
                                    if($i == AdminPanelService::getPage()){?>
                                        selected <?php
                                    }
                                    ?> ><?php echo $i; ?>
                                </option> <?php
                            } ?>
                    </select>

                    <label for="html">Сортувати по:</label>
                    <select name="sort_attribute">
                        <option value="user_name" <?php if(AdminPanelService::getSortAttribute() === "user_name")
                        { ?> selected <?php } ?>>Логін</option>
                        <option value="email" <?php if(AdminPanelService::getSortAttribute() === "email")
                        { ?> selected <?php } ?>>E-mail</option>
                        <option value="date_of_writing" <?php if(AdminPanelService::getSortAttribute() === "date_of_writing") {
                            ?> selected <?php } ?>>Дата</option>
                    </select>

                    <label for="html">Сортувати по:</label>
                    <select name="sort_by_descending_or_ascending">
                        <option value="DESC" <?php if(AdminPanelService::getSortByDescendingOrAscending() === "DESC")
                        { ?> selected <?php } ?>>Спаданню</option>
                        <option value="ASC" <?php if(AdminPanelService::getSortByDescendingOrAscending() === "ASC")
                        { ?> selected <?php } ?>>Зростанню</option>
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
                                <td><?php echo $resp->getAuthor()->getUserName(); ?></td>
                                <td><?php echo $resp->getAuthor()->getEmail(); ?></td>
                                <td><?php echo $resp->getAuthor()->getUserGroup(); ?></td>
                                <td><?php echo $resp->getDateOfWriting(); ?></td>
                                <td style="word-wrap: break-word"><?php echo $resp->getContent(); ?></td>
                                <td class="user-image-or-text">
                                    <?php
                                        switch (strtolower(pathinfo("../data/images or files/" . $resp->getImageOrFile(), PATHINFO_EXTENSION))) {
                                            case "txt":{
                                                $file = fopen("../data/images or files/" . $resp->getImageOrFile(), 'r');
                                                echo fread($file, filesize("../data/images or files/" . $resp->getImageOrFile()));
                                                fclose($file);
                                                break;
                                            }
                                            case "png":{}
                                            case "gif":{}
                                            case "jpg":{ ?>
                                                <img src="../data/images or files/<?php echo $resp->getImageOrFile(); ?>"
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
