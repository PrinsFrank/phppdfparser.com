<?php declare(strict_types=1);

namespace PHPPDFParser\Provider;

use Override;
use PHPPDFParser\Model\PDFParserPackageInfo;
use PrinsFrank\Container\Container;
use PrinsFrank\Container\Definition\DefinitionSet;
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
                static function () {
                    $lockFile = dirname(__DIR__, 2) . '/composer.lock';
                    $fileContent = file_get_contents($lockFile);
                    if ($fileContent === false) {
                        throw new \RuntimeException('Unable to read ' . $lockFile);
                    }

                    $lockData = json_decode($fileContent, true, JSON_THROW_ON_ERROR);
                    foreach (is_array($lockData) && array_key_exists('packages', $lockData) && is_array($lockData['packages']) ? $lockData['packages'] : [] as $package) {
                        if (is_array($package) && ($package['name'] ?? null) === 'prinsfrank/pdfparser') {
                            return new PDFParserPackageInfo($package['version'] ?? 'unknown');
                        }
                    }

                    return new PDFParserPackageInfo('unknown');
                }
            )
        );
    }
}
