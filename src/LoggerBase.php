<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/02/25
 *  Time:   10:06
*/


namespace Foamycastle\Utilities;

use Psr\Log\AbstractLogger;

class LoggerBase extends AbstractLogger
{

    /**
     * @inheritDoc
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {

    }
}