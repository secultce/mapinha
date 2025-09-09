<?php

declare(strict_types=1);

namespace App\Command\Coverage\Parser;

use App\Command\Coverage\CoverageData;
use App\Command\Coverage\Exception\CoverageException;

class TextCoverageParser
{
    private const array PATTERNS = [
        'line' => '/Lines:\s*([\d.]+)%\s*\((\d+)\/(\d+)\)/',
        'branch' => '/Branches:\s*([\d.]+)%\s*\((\d+)\/(\d+)\)/',
        'path' => '/Paths:\s*([\d.]+)%\s*\((\d+)\/(\d+)\)/',
        'class' => '/Classes:\s*([\d.]+)%\s*\((\d+)\/(\d+)\)/',
        'method' => '/Methods:\s*([\d.]+)%\s*\((\d+)\/(\d+)\)/',
    ];

    public function parse(string $filePath): CoverageData
    {
        $content = file_get_contents($filePath);

        if (false === $content) {
            throw new CoverageException('The coverage text file could not be loaded');
        }

        $lines = array_filter(
            array_slice(explode("\n", $content), 0, 12),
            fn ($item) => null !== $item && '' !== $item
        );

        $data = $this->extractData($lines);

        return new CoverageData(
            $data['line_coverage'],
            $data['covered_line'],
            $data['line'],
            $data['branch_coverage'],
            $data['covered_branch'],
            $data['branch'],
            $data['path_coverage'],
            $data['covered_path'],
            $data['path'],
            $data['method_coverage'],
            $data['covered_method'],
            $data['method'],
            $data['class_coverage'],
            $data['covered_class'],
            $data['class']
        );
    }

    private function extractData(array $lines): array
    {
        $data = [];

        foreach ($lines as $line) {
            $this->processLine($line, $data);
        }

        if (empty($data)) {
            throw new CoverageException('No coverage data found in the text file');
        }

        return $data;
    }

    private function processLine(string $line, array &$data): void
    {
        foreach (self::PATTERNS as $type => $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                $data["{$type}_coverage"] = (float) $matches[1];
                $data["covered_{$type}"] = (int) $matches[2];
                $data["{$type}"] = (int) $matches[3];
                break;
            }
        }
    }
}
