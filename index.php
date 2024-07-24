<?php
// Включение отображения ошибок для разработки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Папка, из которой будут отдаваться статические файлы
$root = __DIR__ . '/dist';

// Получение запрашиваемого URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Если запрашивается корневая директория, переадресуем на index.html
if ($uri === '/') {
    $uri = '/index.html';
}

// Полный путь к файлу
$requestedFile = $root . $uri;

// Проверка, существует ли файл и не является ли он директорией
if (file_exists($requestedFile) && !is_dir($requestedFile)) {
    // Определение MIME-типа файла и установка заголовков
    $mimeType = mime_content_type($requestedFile);
    header("Content-Type: $mimeType");
    readfile($requestedFile);
    exit;
} else {
    // Если файл не найден, вернуть 404 ошибку
    http_response_code(404);
    echo 'Файл не найден';
}
