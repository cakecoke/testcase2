<?php

namespace app;


use app\lib\downloader\Downloader;
use app\lib\exporter\Exporter;
use app\lib\logger\Logger;
use app\lib\parser\exception\DecodeException;
use app\lib\parser\Parser;
use DI\Container;
use NinjaMutex\Lock\FlockLock;
use Silly\Application;

class App
{

    /**
     * @var Container
     */
    private $container;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->container = require 'bootstrap.php';
    }

    public function run()
    {
        $app = new Application();
        $app->useContainer($this->container, $injectWithTypeHint = true);
        $app->command('update', [$this, 'update']);
        $app->run();
    }

    /**
     * todo fat action is fat
     *
     * @param Downloader $downloader
     * @param Logger     $logger
     *
     * @throws \Exception
     */
    public function update(Downloader $downloader, Logger $logger)
    {
        if (!(new FlockLock(APP_RUNTIME_DIR))->acquireLock('P0APP')) {
            throw new \Exception('Application already running');
        }

        $logger->info('update started');
        $i = 0;

        foreach ($downloader->download() as $downloadedDataModel) {
            try {
                $logger->info("processing {$downloadedDataModel}");

                /** @var Parser $parser */
                $parser    = $this->container->make(Parser::class, ['model' => $downloadedDataModel]);
                $dataModel = $parser->parse();

                /** @var Exporter $exporter */
                $exporter = $this->container->make(Exporter::class, ['model' => $dataModel]);
                $exporter->export();
                $i++;
            } catch (\app\lib\parser\exception\NoStrategyException $e) {
                $logger->error("parser could not recognize {$downloadedDataModel} skipping");
            } catch (\app\lib\exporter\exception\NoStrategyException $e) {
                $logger->error("exporter could not recognize {$downloadedDataModel} output format skipping");
            } catch (DecodeException $e) {
                $logger->error("could not decode {$downloadedDataModel} skipping");
            }
        }

        $logger->info("Done, processed $i sources");
    }
}