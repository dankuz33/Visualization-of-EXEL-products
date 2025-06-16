<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();
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
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $dest = $uploadDir . '/' . uniqid('excel_', true) . '.xlsx';
            if (move_uploaded_file($tmpName, $dest)) {
                try {
                    $spreadsheet = IOFactory::load($dest);
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
                unlink($dest);
            } else {
                $errors[] = 'Не удалось сохранить файл.';
            }
        }
    } else {
        $errors[] = 'Файл не был загружен.';
    }
} else {
    $errors[] = 'Файл не был загружен.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Витрина товаров</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Витрина товаров</h1>
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
<p><a href="index.php">Назад</a></p>
</body>
</html>
