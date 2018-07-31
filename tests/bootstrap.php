<?php

use Illuminate\Contracts\Console\Kernel;

$app = require __DIR__ . '/../bootstrap/app.php';

$app->loadEnvironmentFrom('.env.testing');
$app->make(Kernel::class)->bootstrap();