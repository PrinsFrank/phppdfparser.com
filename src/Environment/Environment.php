<?php declare(strict_types=1);

namespace PHPPDFParser\Environment;

readonly class Environment {
    public function get(string $param): ?string {
        if (is_string($value = $_ENV[$param] ?? null)) {
            return $value;
        }

        return null;
    }
}
