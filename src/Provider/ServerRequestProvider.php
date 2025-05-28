<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use Laminas\Diactoros\ServerRequestFactory;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\AbstractConcrete;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class ServerRequestProvider implements ServiceProviderInterface {
    public function provides(string $identifier): bool {
        return $identifier === ServerRequestInterface::class;
    }

    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new AbstractConcrete(
                ServerRequestInterface::class,
                fn () => ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES)
            )
        );
    }
}
