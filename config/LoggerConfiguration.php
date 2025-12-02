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
    public const KEY_PATH = 'logger_path';
    public const KEY_FORMAT = 'logger_format';
    public const KEY_NAME = 'logger_name';
    private $path;
    private $format;
    private $name;

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

    /**
     * @return mixed
     */
    public function getLoggerName(): ?string
    {
        return $this->get(self::KEY_NAME);
    }

    /**
     * @param mixed $name
     */
    public function setLoggerName($name): LoggerSetConfig
    {
        $this->set(self::KEY_NAME, $name);
        return $this;
    }

    /**
     * @param array{logger_name?:string,logger_path?:string,logger_format?:string} $config
     * @return void
     */
    public static function fromArray(array $config):self
    {
        $instance=new LoggerConfiguration();
        $instance->set(
            self::KEY_FORMAT,
                $config[self::KEY_FORMAT] ??
                $config['format'] ??
                self::FORMAT_DEFAULT
        );
        $instance->set(
            self::KEY_PATH,
                $config[self::KEY_PATH] ??
                $config['path'] ??
                null);
        $instance->set(
            self::KEY_NAME,
                $config[self::KEY_NAME] ??
                $config['name'] ??
                null);
        return $instance;
    }

}