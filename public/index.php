<?php
    require_once __DIR__.'/vendor/autoLoad.php';
    
    use Vendor\EnvLoader;
    $env = new EnvLoader(__DIR__ . '/.env');
    $env->load();
?>