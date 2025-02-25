<?php
// tests/bootstrap.php
require_once __DIR__ . '/../vendor/autoload.php';

// Define la ruta base para incluir archivos
define('BASE_PATH', dirname(__DIR__));

// Función auxiliar para incluir archivos del proyecto
function includeFile($path) {
    $fullPath = BASE_PATH . '/' . $path;
    if (file_exists($fullPath)) {
        return require_once $fullPath;
    }
    return false;
}