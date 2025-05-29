<?php declare(strict_types=1);

namespace PHPPDFParser\Model;

readonly class PDFParserPackageInfo {
    public function __construct(
        public string $version,
    ) {
    }
}
