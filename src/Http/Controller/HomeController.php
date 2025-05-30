<?php declare(strict_types=1);

namespace PHPPDFParser\Http\Controller;

use Exception;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\UploadedFile;
use Override;
use PHPPDFParser\Http\Response\ResponseFactory;
use PHPPDFParser\Model\PDFParserPackageInfo;
use PrinsFrank\PdfParser\Document\Object\Decorator\Page;
use PrinsFrank\PdfParser\Exception\PdfParserException;
use PrinsFrank\PdfParser\PdfParser;
use Psr\Http\Message\ResponseInterface;

readonly class HomeController implements ControllerInterface {
    public function __construct(
        private ResponseFactory      $responseFactory,
        private PdfParser            $pdfParser,
        private PDFParserPackageInfo $pdfParserPackageInfo,
    ) {
    }

    /** @throws Exception */
    #[Override]
    public function __invoke(ServerRequest $request): ResponseInterface {
        try {
            $document = ($request->getMethod() === 'POST' && ($uploadedFile = $request->getUploadedFiles()['file'] ?? null) instanceof UploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK)
                ? $this->pdfParser->parseString($uploadedFile->getStream()->getContents())
                : null;
            $text = array_map(
                fn (Page $page) => $page->getText(),
                $document?->getPages() ?? [],
            );
            $title = $document?->getInformationDictionary()?->getTitle();
            $producer = $document?->getInformationDictionary()?->getProducer();
            $author = $document?->getInformationDictionary()?->getAuthor();
            $creator = $document?->getInformationDictionary()?->getCreator();
            $creationDate = $document?->getInformationDictionary()?->getCreationDate();
            $modificationDate = $document?->getInformationDictionary()?->getModificationDate();
        } catch (PdfParserException $e) {
            \Sentry\captureException($e);

            $exception = $e;
        }

        return $this->responseFactory->renderTemplate(
            'home.html.twig',
            [
                'pdfParserPackageInfo' => $this->pdfParserPackageInfo,
                'text' => $text ?? null,
                'title' => $title ?? null,
                'producer' => $producer ?? null,
                'author' => $author ?? null,
                'creator' => $creator ?? null,
                'creationDate' => $creationDate ?? null,
                'modificationDate' => $modificationDate ?? null,
                'exception' => $exception ?? null,
            ],
        );
    }
}
