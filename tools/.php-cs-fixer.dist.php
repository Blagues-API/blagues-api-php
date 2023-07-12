<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$finder = \PhpCsFixer\Finder::create()
    ->ignoreDotFiles(false)
    ->ignoreVCSIgnored(true)
    ->in(dirname(__DIR__))
;

$config = (new \PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        'single_line_empty_body' => true,
    ])
    ->setFinder($finder)
;

return $config;
