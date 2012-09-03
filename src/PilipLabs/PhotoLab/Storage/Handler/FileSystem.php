<?php

namespace PilipLabs\PhotoLab\Storage\Handler;

use PilipLabs\PhotoLab\Storage\ReadableInterface;

class FileSystem implements ReadableInterface {

    static private $root;
    private $handle;
    private $uri;

    public function __construct($root = false)
    {
        if (!isset(self::$root)) {
            self::$root = realpath($root);
            if (!self::$root) {
                throw new \RuntimeException(sprintf('Root path "%s" does not exists', self::$root));
            }
        }
    }

    private function getLocalPath()
    {
        $realpath = false;
        if (preg_match('@(?<scheme>\w+)://(?<path>.*)@', $this->uri, $parts)) {
            $realpath = self::$root . DIRECTORY_SEPARATOR . $parts['path'];
        }
        return $realpath;
    }

    public function dir_opendir($uri, $options){
        $this->uri = $uri;
        $this->handle = opendir($this->getLocalPath($this->uri));
        return (bool) $this->handle;
    }

    public function dir_readdir(){
        return readdir($this->handle);
    }

    public function dir_rewinddir(){
        rewinddir($this->handle);
        return true;
    }

    public function dir_closedir(){
        closedir($this->handle);
        return true;
    }

    public function stream_open($uri, $mode, $options, &$openedPath) {
        $this->uri = $uri;
        $path = $this->getLocalPath();
        $this->handle = ($options & STREAM_REPORT_ERRORS) ? fopen($path, $mode) : @fopen($path, $mode);
        if ((bool) $this->handle && $options & STREAM_USE_PATH) {
            $openedPath = $path;
        }
        return (bool) $this->handle;
    }

    public function stream_read($count) {
        return fread($this->handle, $count);
    }

    public function stream_eof() {
         return feof($this->handle);
    }

    public function stream_seek($offset, $whence = 0) {
        // fseek returns 0 on success and -1 on a failure.
        // stream_seek   1 on success and  0 on a failure.
        return !fseek($this->handle, $offset, $whence);
    }

    public function stream_tell() {
        return ftell($this->handle);
    }
    public function stream_cast($cast) {
        var_dump(__METHOD__);
    }
    public function stream_close() {
        return fclose($this->handle);
    }

    public function stream_stat() {
        return fstat($this->uri);
    }

    public function url_stat($uri, $flags) {
        $this->uri = $uri;
        if ($flags & STREAM_URL_STAT_QUIET || !file_exists($this->uri)) {
            return @stat($this->getLocalPath($this->uri));
        } else {
            return stat($this->getLocalPath($this->uri));
        }

    }

}
