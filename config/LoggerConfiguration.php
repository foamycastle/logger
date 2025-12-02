<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/02/25
 *  Time:   9:29
*/


namespace Foamycastle\Config;

use Foamycastle\config\Logger\Method;

final class LoggerConfiguration extends BaseConfig implements LoggerSetConfig, LoggerGetConfig
{
    public const FORMAT_DEFAULT = '%datetime% %level%: %message% %context% %extra%';
    private const KEY_PATH = 'logger_path';
    private const KEY_FORMAT = 'logger_format';
    private $path;
    private $format;

    public function __construct()
    {
        parent::__construct('_foamycastle_logger');
    }

    function setPath(string $path): LoggerSetConfig
    {
        $this->set(self::KEY_PATH, $path);
        return $this;
    }

    function setFormat(string $format): LoggerSetConfig
    {
        $this->set(self::KEY_FORMAT, $format);
        return $this;
    }

    static function fromConfigFile(string $path): static
    {
        if(!file_exists($path)){
            return new LoggerConfiguration();
        }
        $configFunction=\Closure::fromCallable(include($path));
        $config=new LoggerConfiguration();
        $configFunction($config);
        return $config;
    }

    function getPath(): ?string
    {
        return ($this->get(self::KEY_PATH) ?? null);
    }


    function getFormat(): ?string
    {
        return ($this->get(self::KEY_FORMAT) ?? null);
    }
}