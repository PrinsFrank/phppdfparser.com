<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use League\Route\Router;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\Concrete;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use PHPPDFParser\Environment\Environment as Env;

readonly class TwigProvider implements ServiceProviderInterface {
    public function provides(string $identifier): bool {
        return $identifier === Environment::class;
    }

    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new Concrete(
                Environment::class,
                function (Router $router, Env $env) {
                    $environment = new Environment(new FilesystemLoader([dirname(__DIR__) . '/Template']));
                    $environment->addGlobal('domain', $env->get('HOST'));
                    $environment->addGlobal('currentUrl', ($env->get('NO_HTTPS') ? 'http://' : 'https://') . $env->get('HOST') . ($_SERVER['REQUEST_URI'] ?? ''));
                    $environment->addGlobal('basePath', ($env->get('NO_HTTPS') ? 'http://' : 'https://') . $env->get('HOST'));
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
