<?php

$vendor = dirname(__DIR__, 2) . '/vendor/autoload.php';

if (file_exists($vendor)) {
    @require $vendor;
}
