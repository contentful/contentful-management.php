<?php

$config = require __DIR__.'/scripts/php-cs-fixer.php';

return $config(
    'contentful-management',
    true,
    ['scripts', 'src', 'tests'],
    ['Fixtures/E2E', 'Fixtures/Integration']
);
