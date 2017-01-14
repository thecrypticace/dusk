<?php

namespace Laravel\Dusk;

use RuntimeException;
use Symfony\Component\Process\Process;

trait SupportsSafari
{
    /**
     * The Safari driver process instance.
     */
    protected static $safariProcesses = [];

    /**
     * The number of opened Safari processes.
     */
    protected static $safariInstances = 0;

    /**
     * Start the Safari driver process.
     *
     * @return int
     */
    public static function startSafariDriver($port = 9515)
    {
        if (! file_exists('/usr/bin/safaridriver')) {
            throw new RuntimeException('You must have at least Safari 10 installed to use the Safari Driver');
        }

        $port = $port + static::$safariInstances - 1;

        $process = new Process("/usr/bin/safaridriver --port={$port}", realpath(__DIR__.'/../bin'), null, null, null);

        $process->start();

        static::$safariProcesses[$port] = $process;

        static::afterClass(function () use ($port) {
            static::stopSafariDriver($port);
        });

        return $port;
    }

    /**
     * Stop the Safari driver process.
     *
     * @return void
     */
    public static function stopSafariDriver($port)
    {
        if (isset(static::$safariProcesses[$port])) {
            static::$safariProcesses[$port]->stop();
        }
    }

    /**
     * Create the remote web driver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function createWebDriver()
    {
        static::$safariInstances += 1;

        return parent::createWebDriver();
    }
}
