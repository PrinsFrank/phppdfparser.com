<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Response;

use Laminas\Diactoros\Exception\InvalidArgumentException;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class ResponseFactory {
    public function __construct(
        private Environment   $twig,
        private StreamFactory $streamFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     * @throws SyntaxError|RuntimeError|LoaderError|InvalidArgumentException
     */
    public function renderTemplate(string $template, array $context = [], int $status = 200): ResponseInterface {
        return new Response(
            $this->streamFactory->createStream(
                $this->twig->render($template, $context)
            ),
            $status,
        );
    }
}
