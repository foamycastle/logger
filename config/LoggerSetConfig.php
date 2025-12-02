<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/02/25
 *  Time:   9:30
*/


namespace Foamycastle\Config;

interface LoggerSetConfig
{
    function setPath(string $path): LoggerSetConfig;
    function setFormat(string $format): LoggerSetConfig;
    function setLoggerName(string $name): LoggerSetConfig;
}