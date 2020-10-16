<?php

defined('APP_DIR') or define('APP_DIR', dirname(__FILE__));
defined('APP_RUNTIME_DIR') or define('APP_RUNTIME_DIR', APP_DIR . '/runtime');
defined('START_TIME') or define('START_TIME', microtime(true));

$sourceParser = function (\app\lib\logger\Logger $logger) {
    $logger->info('parsing sources.ini');

    foreach (parse_ini_file(APP_DIR . '/sources.ini', true) as $outputToFile => $params) {

        $model = (new \app\lib\source\model\SourceModel());
        $model->setOutputToFile($outputToFile)
            ->setUrl($params['url']);

        if (!isset($params['header_array_path'])) {
            $logger->error("$outputToFile record doesn't have header_array_path, please provide one");
            throw new \Exception('Invalid header_array_path');
        }

        if (!isset($params['data_path'])) {
            $logger->error("$outputToFile record doesn't have data_path, please provide one");
            throw new \Exception('Invalid data_path');
        }

        $model->setHeaderArrayPath($params['header_array_path']);
        $model->setDataPath($params['data_path']);

        if (isset($params['header_array_key'])) {
            $model->setHeaderArrayKey($params['header_array_key']);
        }

        yield $model;
    }
};

return [
    \app\lib\downloader\Downloader::class => DI\object()
        ->property('sources', DI\get('sources'))
    ,
    \app\lib\logger\Logger::class         => DI\object()
        ->property('target', APP_DIR . '/runtime/applog.log')
        ->property('quiet', false)
        ->property('printWithTime', true)
    ,
    'sources'                             => $sourceParser,
];