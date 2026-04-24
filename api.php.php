<?php
// api.php - Простая синхронизация данных через общий файл
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$file = 'data.json';
// Данные по умолчанию, если файл data.json ещё не создан
$default = '{"pages":{"page_1":{"name":"Таблица 1","headers":["Targeleon","Popunder","Push","PushInPage"],"rows":[{"name":"BY MTS","values":["","","",""]},{"name":"BY LIFE","values":["64","8","Х",""]},{"name":"BY A1","values":["0","12","","67"]},{"name":"UZ Beeline","values":["","","т",""]},{"name":"UZ Mobiuz","values":["","","",""]},{"name":"UZ Ucell","values":["","","т",""]},{"name":"UZ Uzmobile","values":["","","",""]},{"name":"AM Tcell","values":["","","",""]},{"name":"AM Vivacell","values":["","","",""]},{"name":"RU Megafon","values":["","","",""]},{"name":"TJ ZetMobile","values":["14","27","","187"]},{"name":"TJ Tcell","values":["","","",""]}]},"activePage":"page_1"}';

// 🔹 Чтение данных (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_exists($file) ? file_get_contents($file) : $default;
    exit;
}

// 🔹 Запись данных (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input);
    if ($data) {
        // Блокировка файла на время записи предотвращает конфликты при одновременном сохранении
        $fp = fopen($file, 'w');
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $input);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
        echo '{"status":"ok"}';
    } else {
        echo '{"status":"error","msg":"Invalid JSON"}';
    }
    exit;
}
?>