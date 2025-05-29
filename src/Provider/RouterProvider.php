<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use Override;
use PHPPDFParser\Environment\Environment;
use PrinsFrank\Container\Container;
use League\Route\Router;
use League\Route\Strategy\AbstractStrategy;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\Singleton;
use PrinsFrank\Container\Exception\InvalidArgumentException;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;

readonly class RouterProvider implements ServiceProviderInterface {
    #[Override]
    public function provides(string $identifier): bool {
        return $identifier === Router::class;
    }

    /** @throws InvalidArgumentException */
    #[Override]
    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new Singleton(
                Router::class,
                static function (AbstractStrategy $strategy, Environment $environment) { // @phpstan-ignore argument.type
                    $router = (new Router());
                    $router->setHost($environment->get('HOST') ?? '');
                    $router->setStrategy($strategy);

                    return $router;
                }
            )
        );
    }
}
