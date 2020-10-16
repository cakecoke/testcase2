<?php

namespace app\lib\exporter\strategy;

use Goodby\CSV\Export\Standard\CsvFileObject;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;

class CsvStrategy extends Strategy
{

    public function computeDiff()
    {
        $this->logger->info('computing diff');

        $exportPath = $this->model->getSourceModel()->getOutputToFileWithPath();

        if (!file_exists($exportPath)) {
            return;
        }

        $config             = new LexerConfig();
        $lexer              = new Lexer($config);
        $interpreter        = new Interpreter();
        $newEntries         = $this->dataToExport;
        $newEntriesCount    = count($this->dataToExport);
        $oldEntries         = [];
        $this->dataToExport = [];

        $interpreter->addObserver(
            function (array $row) use (&$oldEntries) {
                $oldEntries[] = $row;
            }
        );

        $lexer->parse($exportPath, $interpreter);

        $oldEntriesCount = count($oldEntries) - 1; // - header

        if ($newEntriesCount > $oldEntriesCount) {
            $this->dataToExport = array_slice($newEntries, $oldEntriesCount);
        }
    }

    public function export()
    {
        if ($this->dataToExport) {
            $this->logger->info(sprintf('exporting %s entries', count($this->dataToExport)));

            $exportPath = $this->model->getSourceModel()->getOutputToFileWithPath();

            // add headers
            if (!file_exists($exportPath)) {
                $this->dataToExport = array_merge([$this->model->getHeaders()], $this->dataToExport);
            }

            foreach ($this->dataToExport as &$entries) {
                foreach ($entries as &$entry) {
                    if (is_array($entry)) {
                        $entry = \GuzzleHttp\json_encode($entry);
                    }
                }
            }

            $config = new ExporterConfig();
            $config->setFileMode(CsvFileObject::FILE_MODE_APPEND);

            $exporter = new Exporter($config);
            $exporter->export($exportPath, $this->dataToExport);
        } else {
            $this->logger->info('no update needed');
        }
    }
}