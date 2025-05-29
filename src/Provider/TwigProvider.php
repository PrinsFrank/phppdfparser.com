<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use League\Route\Router;
use Override;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\Concrete;
use PrinsFrank\Container\Exception\InvalidArgumentException;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use PHPPDFParser\Environment\Environment as Env;

readonly class TwigProvider implements ServiceProviderInterface {
    #[Override]
    public function provides(string $identifier): bool {
        return $identifier === Environment::class;
    }

    /** @throws InvalidArgumentException */
    #[Override]
    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new Concrete(
                Environment::class,
                function (Router $router, Env $env) { // @phpstan-ignore argument.type
                    $environment = new Environment(new FilesystemLoader([dirname(__DIR__) . '/Template']));
                    $environment->addGlobal('basePath', ($env->get('NO_HTTPS') === 'true' ? 'http://' : 'https://') . ($env->get('HOST') ?? ''));
                    $environment->setCache(false);
                    $environment->addFunction(
                        new TwigFunction(
                            'route',
                            fn (string $name, array $params = []) => $router->getNamedRoute($name)->getPath($params),
                        )
                    );

                    return $environment;
                }
            )
        );
    }
}
