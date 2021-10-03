<?php

use Core\Route;

require_once 'vendor/autoload.php';

try {
    Route::init();
} catch (Exception $e) {
    die($e->getMessage());
}