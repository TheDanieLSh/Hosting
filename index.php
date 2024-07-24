<?php
// Включение отображения ошибок для разработки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Папка, из которой будут отдаваться статические файлы
$root = __DIR__ . '/dist';

// Получение запрашиваемого URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Полный путь к файлу
$requestedFile = $root . $uri;

if (file_exists($requestedFile) && !is_dir($requestedFile)) {
    $mimeType = mime_content_type($requestedFile);
    header("Content-Type: $mimeType");
    readfile($requestedFile);
    exit;
} else {
    http_response_code(404);
    echo 'Файл не найден';
}
