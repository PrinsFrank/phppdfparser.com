<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Strategy;

use Laminas\Diactoros\Exception\InvalidArgumentException;
use Laminas\Diactoros\Response;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Route;
use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use Override;
use PHPPDFParser\Http\Middleware\ThrowableHandler;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Exception\UnresolvableException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class ApplicationStrategy extends AbstractStrategy implements StrategyInterface {
    public function __construct(
        readonly private Container $container,
    ) {
    }

    #[Override]
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface {
        return $this->throwThrowableMiddleware(404);
    }

    #[Override]
    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface {
        return $this->throwThrowableMiddleware(405);
    }

    /** @throws ContainerExceptionInterface|NotFoundExceptionInterface|RuntimeException */
    #[Override]
    public function getThrowableHandler(): MiddlewareInterface {
        return $this->container->get(ThrowableHandler::class)
            ?? throw new RuntimeException();
    }

    /** @throws InvalidArgumentException */
    #[Override]
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface {
        $controller = $route->getCallable($this->container);
        try {
            $response = $controller($request, $route->getVars());
        } catch (NotFoundException) {
            return new Response(status: 404);
        }

        return $this->decorateResponse($response);
    }

    protected function throwThrowableMiddleware(int $statusCode): MiddlewareInterface {
        return new readonly class ($statusCode) extends ThrowableHandler implements MiddlewareInterface {
            public function __construct(
                private int $statusCode,
            ) {
            }

            /** @throws InvalidArgumentException */
            #[Override]
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return new Response(status: $this->statusCode);
            }
        };
    }
}
