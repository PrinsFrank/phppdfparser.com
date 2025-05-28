<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Response;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

readonly class ResponseFactory {
    public function __construct(
        private Environment   $twig,
        private StreamFactory $streamFactory,
    ) {
    }

    public function renderTemplate(string $template, array $context = [], $status = 200): ResponseInterface {
        return new Response(
            $this->streamFactory->createStream(
                $this->twig->render($template, $context)
            ),
            $status,
        );
    }
}
