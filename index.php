<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$errors = [];
$products = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $tmpName = $_FILES['excel_file']['tmp_name'] ?? '';
    if ($tmpName && is_uploaded_file($tmpName)) {
        $mime = mime_content_type($tmpName);
        $allowed = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel.sheet.macroEnabled.12'
        ];
        if (!in_array($mime, $allowed)) {
            $errors[] = 'Недопустимый тип файла.';
        } else {
            try {
                $spreadsheet = IOFactory::load($tmpName);
                $sheet = $spreadsheet->getActiveSheet();

                $colTitle = strtoupper($_POST['col_title'] ?? '');
                $colOld = strtoupper($_POST['col_old'] ?? '');
                $colNew = strtoupper($_POST['col_new'] ?? '');
                $colImg = strtoupper($_POST['col_img'] ?? '');
                $colLink = strtoupper($_POST['col_link'] ?? '');

                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $item = [
                        'title' => $colTitle ? $sheet->getCell($colTitle.$row)->getFormattedValue() : '',
                        'old_price' => $colOld ? $sheet->getCell($colOld.$row)->getFormattedValue() : '',
                        'new_price' => $colNew ? $sheet->getCell($colNew.$row)->getFormattedValue() : '',
                        'image' => $colImg ? $sheet->getCell($colImg.$row)->getFormattedValue() : '',
                        'link' => $colLink ? $sheet->getCell($colLink.$row)->getFormattedValue() : ''
                    ];
                    if (implode('', $item) !== '') {
                        foreach ($item as $k => $v) {
                            $item[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
                        }
                        $products[] = $item;
                    }
                }
            } catch (Exception $e) {
                $errors[] = 'Ошибка чтения файла: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            }
        }
    } else {
        $errors[] = 'Файл не был загружен.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Excel Viewer</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f0f0;margin:0;padding:20px;}
form{background:#fff;padding:15px;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);margin-bottom:20px;}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:15px;}
.card{background:#fff;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);padding:15px;text-align:center;transition:transform .2s;}
.card:hover{transform:translateY(-3px);}
.card img{max-width:100%;height:auto;border-radius:4px;margin-bottom:10px;}
.old{text-decoration:line-through;color:#777;}
.new{font-weight:bold;color:#000;}
button{padding:8px 12px;border:none;background:#007bff;color:#fff;border-radius:4px;cursor:pointer;}
button:hover{background:#0056b3;}
</style>
</head>
<body>
<h1>Загрузка Excel-файла</h1>
<?php if ($errors): ?>
    <div style="color:red;">
        <?php foreach ($errors as $error) echo '<p>'.$error.'</p>'; ?>
    </div>
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
    <div>
        <label>Excel файл: <input type="file" name="excel_file" accept=".xls,.xlsx" required></label>
    </div>
    <div>
        <label>Колонка названия: <input type="text" name="col_title" placeholder="A"></label>
        <label>Колонка старой цены: <input type="text" name="col_old" placeholder="B"></label>
        <label>Колонка новой цены: <input type="text" name="col_new" placeholder="C"></label>
        <label>Колонка изображения: <input type="text" name="col_img" placeholder="D"></label>
        <label>Колонка ссылки: <input type="text" name="col_link" placeholder="E"></label>
    </div>
    <button type="submit">Отобразить</button>
</form>
<?php if ($products): ?>
<div class="grid">
    <?php foreach ($products as $p): ?>
    <div class="card">
        <?php if ($p['image']): ?><img src="<?php echo $p['image']; ?>" alt="Image"><?php endif; ?>
        <h3><?php echo $p['title']; ?></h3>
        <?php if ($p['old_price']): ?><div class="old"><?php echo $p['old_price']; ?></div><?php endif; ?>
        <?php if ($p['new_price']): ?><div class="new"><?php echo $p['new_price']; ?></div><?php endif; ?>
        <?php if ($p['link']): ?><p><a href="<?php echo $p['link']; ?>" target="_blank"><button>Перейти</button></a></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</body>
</html>
