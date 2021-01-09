<?php
require __DIR__ . '/../wp-load.php';

$application = new \Symfony\Component\Console\Application();
$application->add(new \Simply\Maker\MakeCommand());
$application->run();
