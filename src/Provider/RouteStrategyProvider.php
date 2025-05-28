<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use League\Route\Strategy\AbstractStrategy;
use PHPPDFParser\Http\Strategy\ApplicationStrategy;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\AbstractConcrete;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;

readonly class RouteStrategyProvider implements ServiceProviderInterface {
    public function provides(string $identifier): bool {
        return $identifier === AbstractStrategy::class;
    }

    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new AbstractConcrete(
                AbstractStrategy::class,
                fn (Container $container) => new ApplicationStrategy($container)
            )
        );
    }
}
