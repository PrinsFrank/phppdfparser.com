<?php declare(strict_types=1);

namespace PHPPDFParser;

use PHPPDFParser\Provider\EmitterProvider;
use PHPPDFParser\Provider\RouterProvider;
use PHPPDFParser\Provider\RouteStrategyProvider;
use PHPPDFParser\Provider\ServerRequestProvider;
use PHPPDFParser\Provider\TwigProvider;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

final class Kernel {
    private static ContainerInterface $container;

    public static function getContainer(): ContainerInterface {
        if (isset(self::$container)) {
            return self::$container;
        }

        $container = new Container();
        foreach (self::getServiceProviders() as $serviceProvider) {
            $container->addServiceProvider(new $serviceProvider());
        }

        return self::$container = $container;
    }

    /** @return array<class-string<ServiceProviderInterface>> */
    private static function getServiceProviders(): array {
        return [
            EmitterProvider::class,
            RouterProvider::class,
            RouteStrategyProvider::class,
            ServerRequestProvider::class,
            TwigProvider::class,
        ];
    }
}
