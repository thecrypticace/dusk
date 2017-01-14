<?php

namespace Laravel\Dusk;

use RuntimeException;
use Symfony\Component\Process\Process;

trait SupportsSafari
{
    /**
     * The Safari driver process instance.
     */
    protected static $safariProcess;

    /**
     * Start the Safari driver process.
     *
     * @return void
     */
    public static function startSafariDriver()
    {
        if (! file_exists('/usr/bin/safaridriver')) {
            throw new RuntimeException('You must have at least Safari 10 installed to use the Safari Driver');
        }

        static::$safariProcess = new Process('/usr/bin/safaridriver --port=9515', realpath(__DIR__.'/../bin'), null, null, null);

        static::$safariProcess->start();

        static::afterClass(function () {
            static::stopSafariDriver();
        });
    }

    /**
     * Stop the Safari driver process.
     *
     * @return void
     */
    public static function stopSafariDriver()
    {
        if (static::$safariProcess) {
            static::$safariProcess->stop();
        }
    }
}
