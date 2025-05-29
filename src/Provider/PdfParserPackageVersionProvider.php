<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use Override;
use PHPPDFParser\Model\PDFParserPackageInfo;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
use PrinsFrank\Container\Definition\Item\AbstractConcrete;
use PrinsFrank\Container\Definition\Item\Concrete;
use PrinsFrank\Container\Exception\InvalidArgumentException;
use PrinsFrank\Container\ServiceProvider\ServiceProviderInterface;

readonly class PdfParserPackageVersionProvider implements ServiceProviderInterface {
    #[Override]
    public function provides(string $identifier): bool {
        return $identifier === PDFParserPackageInfo::class;
    }

    /** @throws InvalidArgumentException */
    #[Override]
    public function register(string $identifier, DefinitionSet $resolvedSet, Container $container): void {
        $resolvedSet->add(
            new Concrete(
                PDFParserPackageInfo::class,
                static function () { // @phpstan-ignore argument.type
                    $lockFile = dirname(__DIR__, 2) . '/composer.lock';
                    $lockData = json_decode(file_get_contents($lockFile), true, JSON_THROW_ON_ERROR);
                    foreach ($lockData['packages'] as $package) {
                        if ($package['name'] === 'prinsfrank/pdfparser') {
                            return new PDFParserPackageInfo($package['version']);
                        }
                    }

                    return new PDFParserPackageInfo('unknown');
                }
            )
        );
    }
}
