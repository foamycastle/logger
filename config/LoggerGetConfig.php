<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/02/25
 *  Time:   10:55
*/


namespace Foamycastle\Config;

interface LoggerGetConfig
{
    function getPath(): ?string;
    function getFormat(): ?string;
    function getLoggerName(): ?string;
    function getVars(): array;
}