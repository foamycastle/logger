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
    public const NAME='logger';
    public const FORMAT_DEFAULT = '%datetime% %level%: %message% %context% %extra%';
    public const KEY_PATH = 'logger_path';
    public const KEY_FORMAT = 'logger_format';
    public const KEY_NAME = 'logger_name';
    private $path;
    private $format;
    private $name;
    private $vars=[];

    public function __construct(?string $name = null)
    {
        parent::__construct($name ?? self::NAME);
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

    function getVars(): array
    {
        return $this->get('vars') ?? [];
    }

    function setVars(array $vars): LoggerSetConfig
    {
        $this->set('vars', $vars);
        return $this;
    }


    /**
     * @param array{logger_name?:string,logger_path?:string,logger_format?:string} $config
     * @return self
     */
    public static function fromArray(array $config): static
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