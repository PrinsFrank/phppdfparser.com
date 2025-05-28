<?php declare(strict_types=1);

if (!in_array($_SERVER['HTTP_HOST'], ['phppdfparser.localhost', 'phppdfparser.com'], true)) {
    http_response_code(418);
    exit;
}

require_once '../bootstrap.php';

/** @var \PrinsFrank\Container\Container $container */
$container->get(\Laminas\HttpHandlerRunner\Emitter\EmitterInterface::class)
    ->emit(
        /** @var \League\Route\Router $router */
        $router->dispatch(
            $container->get(\Psr\Http\Message\ServerRequestInterface::class)
        )
    );
