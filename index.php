<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$_SESSION['errors'] = [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Excel Viewer</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Загрузка Excel-файла</h1>
<?php if ($errors): ?>
    <div class="errors">
        <?php foreach ($errors as $e) echo '<p>'.$e.'</p>'; ?>
    </div>
<?php endif; ?>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <label>Excel файл: <input type="file" name="excel_file" accept=".xls,.xlsx" required></label>
    </div>
    <div class="row">
        <label>Колонка названия: <input type="text" name="col_title" placeholder="A"></label>
        <label>Колонка старой цены: <input type="text" name="col_old" placeholder="B"></label>
        <label>Колонка новой цены: <input type="text" name="col_new" placeholder="C"></label>
        <label>Колонка изображения: <input type="text" name="col_img" placeholder="D"></label>
        <label>Колонка ссылки: <input type="text" name="col_link" placeholder="E"></label>
    </div>
    <button type="submit">Отобразить</button>
</form>
</body>
</html>
