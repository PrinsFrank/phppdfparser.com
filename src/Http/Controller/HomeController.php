<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Controller;

use Composer\Composer;
use Laminas\Diactoros\Exception\InvalidArgumentException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\UploadedFile;
use Override;
use PHPPDFParser\Http\Response\ResponseFactory;
use PHPPDFParser\Model\PDFParserPackageInfo;
use PrinsFrank\PdfParser\Exception\PdfParserException;
use PrinsFrank\PdfParser\PdfParser;
use Psr\Http\Message\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class HomeController implements ControllerInterface {
    public function __construct(
        private ResponseFactory      $responseFactory,
        private PDFParser            $pdfParser,
        private PDFParserPackageInfo $pdfParserPackageInfo,
    ) {
    }

    /** @throws LoaderError|RuntimeError|SyntaxError|InvalidArgumentException */
    #[Override]
    public function __invoke(ServerRequest $request): ResponseInterface {
        $uploadedFile = $exception = null;
        try {
            $document = ($request->getMethod() === 'POST' && ($uploadedFile = $request->getUploadedFiles()['file'] ?? null) instanceof UploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK)
                ? $this->pdfParser->parseString($uploadedFile->getStream()->getContents())
                : null;
        } catch (PdfParserException $e) {
            $exception = $e;
        }

        return $this->responseFactory->renderTemplate(
            'home.html.twig',
            [
                'pdfParserPackageInfo' => $this->pdfParserPackageInfo,
                'document' => $document ?? null,
                'error' => $uploadedFile?->getError() ?? null,
                'exception' => $exception,
            ],
        );
    }
}
