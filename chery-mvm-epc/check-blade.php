
<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$file = __DIR__ . '/resources/views/landing.blade.php';

try {
    $content = file_get_contents($file);

    app('blade.compiler')->compileString($content);

    echo "SUCCESS: Blade syntax is correct.";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo "FILE: " . $e->getFile() . PHP_EOL;
    echo "LINE: " . $e->getLine() . PHP_EOL;
}
