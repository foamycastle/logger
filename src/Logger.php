<?php
/*
 *  Author: Aaron Sollman
 *  Email:  unclepong@gmail.com
 *  Date:   12/02/25
 *  Time:   9:10
*/


namespace Foamycastle\Utilities;

use Foamycastle\Config\LoggerConfiguration;
use Foamycastle\Config\LoggerGetConfig;
use Foamycastle\Config\LoggerSetConfig;

class Logger extends LoggerBase
{

    public const STATUS_OPEN = 1;
    public const STATUS_CLOSED = 0;

    /**
     * @var resource|null $stream
     */
    private $stream;
    /**
     * The path to the log file.
     * @var string $path
     */
    private string $path;
    /**
     * The format to use for logging.
     * @var string
     */
    private string $format;
    /**
     *
     * @var int<value-of::STATUS_*> $streamStatus
     */
    private int $streamStatus = self::STATUS_CLOSED;

    private LoggerConfiguration $config;

    /**
     * @inheritDoc
     */
    public function __construct(LoggerGetConfig&LoggerSetConfig $config)
    {
        $this->config = $config;
        $this->path = realpath($config->getPath()) ?: sys_get_temp_dir().DIRECTORY_SEPARATOR.basename($config->getPath());
        if($this->path != realpath($config->getPath())){
            $config->setPath($this->path);
        }
        $this->format = $config->getFormat();
        $this->stream = @fopen($this->path, 'a');
        if(!$this->stream){
            $createTouch = (@touch($this->path));
            $createTouchSysTemp=(@touch(sys_get_temp_dir().DIRECTORY_SEPARATOR.basename($this->path)));
            if(!$createTouch && !$createTouchSysTemp){
                env('MODE') === 'DEBUG' && (throw new \Exception("Unable to create log file at {$this->path} or ".basename($this->path)." in temp directory"));
            }
            if(!$createTouch && $createTouchSysTemp){
                $this->path = sys_get_temp_dir().DIRECTORY_SEPARATOR.basename($this->path);
                $config->setPath($this->path);
            }
            $this->stream = @fopen($this->path, 'a');
        }
        $this->stream && $this->streamStatus = self::STATUS_OPEN;
    }

    public function withPath(string $path): self
    {
        if ($this->streamStatus === self::STATUS_OPEN) {
            fclose($this->stream);
            $this->streamStatus = self::STATUS_CLOSED;
        }
        if (!file_exists($path) && !@touch($path)) {
            $e = new \Exception("Unable to create log file at {$path}");
            (($_ENV['MODE'] ?? "DEBUG") === 'DEBUG') && (throw $e);
        }
        $this->path = $path;
        $this->stream = @fopen($this->path, 'a');
        if (!$this->stream) {
            $e = new \Exception("Unable to open log file at {$path}");
            (($_ENV['MODE'] ?? "DEBUG") === 'DEBUG') && (throw $e);
        }
        return $this;
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $message = $this->prepareMessage($level, $message, $context);
        $this->stream && fwrite($this->stream, $message . PHP_EOL);
    }

    private function prepareMessage(string $level, string $message, array $context = [],string $extra=''): string
    {
        $timezone = new \DateTimeZone(env('TZ', 'UTC'));
        return strtr($this->format, [
            '%level%' => $level,
            '%context%' => json_encode($context),
            '%message%' => $message,
            '%datetime%' => new \DateTimeImmutable('now', $timezone)->format('Y-m-d H:i:s'),
            '%extra%' => $extra
        ]);
    }
}