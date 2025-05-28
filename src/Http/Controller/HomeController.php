<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Controller;

use Laminas\Diactoros\ServerRequest;
use PHPPDFParser\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

readonly class HomeController implements ControllerInterface {
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(ServerRequest $request): ResponseInterface {
        return $this->responseFactory->renderTemplate('home.html.twig');
    }
}
