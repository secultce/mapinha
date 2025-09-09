<?php

declare(strict_types=1);

namespace App\Command\Coverage;

use App\Command\Coverage\Enum\CoverageMetric;
use App\Command\Coverage\Enum\OutputFormat;
use App\Command\Coverage\Exception\CoverageException;
use App\Command\Coverage\Parser\TextCoverageParser;
use App\Command\Coverage\Renderer\CoverageResultRenderer;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:check-coverage',
    description: 'Checks that the test coverage meets the configured minimum threshold',
)]
class CheckCoverageCommand extends Command
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly TextCoverageParser $coverageParser,
        private readonly CoverageResultRenderer $resultRenderer,
        private readonly CoverageThresholds $thresholds
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('coverage-file', InputArgument::OPTIONAL, 'Cover file path', 'coverage.txt')
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Output format ('.implode('|', array_column(OutputFormat::cases(), 'value')).')',
                OutputFormat::TABLE->value
            )
            ->setHelp($this->getCommandHelp());

        foreach (CoverageMetric::cases() as $metric) {
            $this->addOption(
                "{$metric->value}-threshold",
                substr($metric->value, 0, 1).'t',
                InputOption::VALUE_REQUIRED,
                sprintf('%s minimum coverage threshold', CoverageMetric::getName($metric->value, 'capitalize')),
                $this->thresholds->{$metric->value}
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $filePath = $this->resolveCoverageFilePath($input->getArgument('coverage-file'));
            $format = $this->getOutputFormat($input);
            $thresholds = $this->resolveThresholds($input);

            $coverageData = $this->coverageParser->parse($filePath);
            $result = new CoverageResult($coverageData, $thresholds);

            $this->resultRenderer->render($io, $result, $format);

            return $result->isPassed() ? Command::SUCCESS : Command::FAILURE;
        } catch (CoverageException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        } catch (Exception $e) {
            $io->error("An unexpected error occurred: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }

    private function resolveCoverageFilePath(string $coverageFile): string
    {
        if (str_starts_with($coverageFile, '/')) {
            return $coverageFile;
        }

        return "{$this->projectDir}/{$coverageFile}";
    }

    private function getOutputFormat(InputInterface $input): OutputFormat
    {
        $format = $input->getOption('format');
        $formatEnum = OutputFormat::tryFrom($format);

        if (null === $formatEnum) {
            throw new CoverageException("Invalid output format: {$format}");
        }

        return $formatEnum;
    }

    private function resolveThresholds(InputInterface $input): CoverageThresholds
    {
        $thresholds = [];

        foreach (CoverageMetric::cases() as $metric) {
            $threshold = $input->getOption("{$metric->value}-threshold");

            if (null !== $threshold) {
                $thresholds[$metric->value] = (float) $threshold;
            }
        }

        return empty($thresholds)
            ? $this->thresholds
            : new CoverageThresholds(
                $thresholds['line'] ?? $this->thresholds->line,
                $thresholds['method'] ?? $this->thresholds->method,
                $thresholds['branch'] ?? $this->thresholds->branch,
                $thresholds['class'] ?? $this->thresholds->class,
                $thresholds['path'] ?? $this->thresholds->path
            );
    }

    private function getCommandHelp(): string
    {
        $examples = [
            'Basic usage:' => 'php bin/console app:check-coverage',
            'With custom file:' => 'php bin/console app:check-coverage coverage.xml',
            'With custom thresholds:' => 'php bin/console app:check-coverage --line-threshold=85 --method-threshold=75',
            'JSON output:' => 'php bin/console app:check-coverage --format=json',
        ];

        $help = "This command checks that the test coverage meets the configured minimum threshold.\n\n";
        $help .= "<info>Usage examples:</info>\n";

        foreach ($examples as $desc => $cmd) {
            $help .= "  <comment>{$cmd}</comment> - {$desc}\n";
        }

        return $help;
    }
}
