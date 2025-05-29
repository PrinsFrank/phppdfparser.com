<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Controller;

use Laminas\Diactoros\Exception\InvalidArgumentException;
use Laminas\Diactoros\ServerRequest;
use Override;
use PHPPDFParser\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class HomeController implements ControllerInterface {
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {
    }

    /** @throws LoaderError|RuntimeError|SyntaxError|InvalidArgumentException */
    #[Override]
    public function __invoke(ServerRequest $request): ResponseInterface {
        return $this->responseFactory->renderTemplate('home.html.twig');
    }
}
