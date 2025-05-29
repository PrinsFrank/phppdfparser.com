<?php declare(strict_types=1);

use PrinsFrank\Container\Container;

/** @var Container $container */
$router = $container->get(League\Route\Router::class) ?? throw new RuntimeException();

$router->map('GET', '/', \PHPPDFParser\Http\Controller\HomeController::class)->setName('home');
$router->map('POST', '/', \PHPPDFParser\Http\Controller\HomeController::class)->setName('home_post');
