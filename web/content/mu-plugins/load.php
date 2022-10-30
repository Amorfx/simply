<?php
/**
 * Plugin Name:  Simply framework
 * Plugin URI:   https://github.com/Amorfx/simply
 * Description:  Load Simply Framework and
 * Version:      0.2
 * Author:       Clément Décou
 * Author URI:   https://www.clement-decou.fr/
 * License:      MIT License
 */
require __DIR__ . '/simply-framework/simply-framework.php';
$extensions = require APP_ROOT_CONFIG . '/extensions.php';
foreach ($extensions as $file) {
    $file = __DIR__ . $file;
    if (file_exists($file)) {
        require $file;
    } else {
        throw new RuntimeException('The extension to load ' . $file . ' does not exist.');
    }
}
