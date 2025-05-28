<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Controller;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;

interface ControllerInterface {
    public function __invoke(ServerRequest $request): ResponseInterface;
}
