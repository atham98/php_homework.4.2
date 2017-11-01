<?php
    error_reporting(E_ALL);
    require_once 'db_connect.php';
    if(isset($_GET['submit']) && !empty($_GET['business'])){
    $business = $_GET['business'];

    $insert = "INSERT INTO business(content, status) VALUES (?, ?)";
    $statement = $link->prepare($insert);
    $statement->execute([$business, 0]);
    header('Location: index.php');
    }

    $select = "SELECT * FROM business";
    $statement = $link->prepare($select);
    $statement->execute();

    // Изменение записи
    if(isset($_GET['id']) && !empty($_POST['business']) && isset($_POST['save'])){
        $update_content = "UPDATE business SET content = ? WHERE id=?";
        $statement = $link->prepare($update_content);
        $statement->execute([$_POST['business'], $_GET['id']]);
        header('Location: index.php');
    }
    // Изменение статуса
    if(isset($_GET['id']) && ($_GET['action']) == 'done'){
        $update_status = "UPDATE business SET status=1 WHERE id=?";
        $statement = $link->prepare($update_status);
        $statement->execute([$_GET['id']]);
        header('Location: index.php');
    }
    // Удаление записи
    if(isset($_GET['id']) && ($_GET['action']) == 'delete'){
        $delete = "DELETE FROM business WHERE id = ?";
        $statement = $link->prepare($delete);
        $statement->execute([$_GET['id']]);
        header('Location: index.php');
    }
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список дел!!!</title>
    <style>
        table {
            border-spacing: 0;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table td, table th {
            border: 1px solid #ccc;
            padding: 5px;
        }

        table th {
            background: #eee;
        }
    </style>
</head>
<body>
    <h1>Список дел на сегодня</h1>
    <?php if(isset($_GET['id']) && ($_GET['action']) == 'edit') :?>
        <form method="POST" action="index.php?id=<?= $_GET['id']?>">
            <input type="text" name="business" placeholder="Обновить запись">
            <input type="submit" name="save" value="Сохранить">
        </form>
    <?php else : ?>
        <form method="GET" action="index.php">
            <input type="text" name="business" placeholder="Новая запись">
            <input type="submit" name="submit" value="Добавить">
        </form>
    <?php endif ?>

    <table>
        <tr>
            <th>Описание задачи</th>
            <th>Статус</th>
            <th>Дата добавления</th>
            <th>Редактирование</th>
        </tr>
        <?php while($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['content']; ?></td>
                <td><?= $status = ($row['status'] == 0) ? 'Не выполнено' : 'Выполнено'; ?></td>
                <td><?= $row['date']?></td>
                <td>
                    <a href="?id=<?= $row['id']; ?>&action=edit">Изменить</a>
                    <a href="?id=<?= $row['id']; ?>&action=done">Выполнить</a>
                    <a href="?id=<?= $row['id']; ?>&action=delete">Удалить</a>
                </td>
            </tr>
        <?php endwhile ?>
    </table>
</body>
</html>