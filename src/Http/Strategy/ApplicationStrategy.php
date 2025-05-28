<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Strategy;

use Laminas\Diactoros\Response;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Route;
use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use PHPPDFParser\Http\Middleware\ThrowableHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApplicationStrategy extends AbstractStrategy implements StrategyInterface {
    public function __construct(
        readonly private ContainerInterface $container,
    ) {
    }

    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface {
        return $this->throwThrowableMiddleware(404);
    }

    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface {
        return $this->throwThrowableMiddleware(405);
    }

    public function getThrowableHandler(): MiddlewareInterface {
        return $this->container->get(ThrowableHandler::class);
    }

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

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return new Response(status: $this->statusCode);
            }
        };
    }
}
