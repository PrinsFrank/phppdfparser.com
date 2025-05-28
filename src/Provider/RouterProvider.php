<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use PHPPDFParser\Environment\Environment;
use PrinsFrank\Container\Container;
use League\Route\Router;
use League\Route\Strategy\AbstractStrategy;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\Singleton;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;

readonly class RouterProvider implements ServiceProviderInterface {
    public function provides(string $identifier): bool {
        return $identifier === Router::class;
    }

    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new Singleton(
                Router::class,
                fn (AbstractStrategy $strategy, Environment $environment) => (new Router())
                    ->setHost($environment->get('HOST'))
                    ->setStrategy($strategy)
            )
        );
    }
}
