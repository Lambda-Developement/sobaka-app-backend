<?php
require 'Config.php';
require 'Exceptions.php';
require 'Database.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Trip AR Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
<button class="btn btn-primary">Добавить</button>
<div style="margin: 10px;">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Широта</th>
            <th>Долгота</th>
            <th>Адрес</th>
            <th>Краткое описание</th>
            <th>Детальное описание</th>
            <th>Ссылка на изображение</th>
            <!--<th>Ссылка на аудио</th>
            <th>Субтитры</th>-->
        </tr>
        </thead>
        <tbody>
        <?php
        $q = (new Database())->query("SELECT * FROM points");
        while ($row = $q->fetch_row()): ?>
            <tr>
                <td><?= $row[0] ?></td>
                <td><?= $row[1] ?></td>
                <td><?= $row[2] ?></td>
                <td><?php echo $row[3] ?? '<span class="badge bg-secondary">NULL</span>'; ?></td>
                <td><?= $row[4] ?></td>
                <td><?php echo $row[5] ?? '<span class="badge bg-secondary">NULL</span>'; ?></td>
                <td><?php echo $row[6] ?? '<span class="badge bg-secondary">NULL</span>'; ?></td>
                <!--<td><?php //$row[7] ?></td>
                <td><?php //$row[8] ?></td>-->
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
