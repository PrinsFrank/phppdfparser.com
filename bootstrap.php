<?php declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require_once 'vendor/autoload.php';

(new Dotenv())
    ->load(__DIR__ . '/.env');

\Sentry\init([
    'dsn' => $_ENV['SENTRY_DSN'] ?? null,
]);

$container = PHPPDFParser\Kernel::getContainer();

require_once 'config/routes.php';
