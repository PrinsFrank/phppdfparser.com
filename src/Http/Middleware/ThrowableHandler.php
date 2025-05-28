<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly class ThrowableHandler implements MiddlewareInterface {
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
