<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\AbstractConcrete;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;

readonly class EmitterProvider implements ServiceProviderInterface {
    public function provides(string $identifier): bool {
        return $identifier === EmitterInterface::class;
    }

    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(new AbstractConcrete(EmitterInterface::class, fn () => new SapiEmitter()));
    }
}
