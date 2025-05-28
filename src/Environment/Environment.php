<?php declare(strict_types=1);

namespace PHPPDFParser\Environment;

readonly class Environment {
    public function get(string $param): ?string {
        return $_ENV[$param] ?? null;
    }
}
