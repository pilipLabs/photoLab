<?php

namespace PilipLabs\PhotoLab\Storage\Handler;

use PilipLabs\PhotoLab\Storage\ReadableInterface;

class FileSystem implements ReadableInterface {

    static private $root;
    private $handle;
    private $uri;

    public function __construct($root)
    {
        self::$root = realpath($root);
        if (!self::$root) {
            throw new \RuntimeException(sprintf('Root path "%s" does not exists', self::$root));
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

    public function stream_eof() {
        var_dump(__METHOD__);
    }
    public function stream_open($path, $mode, $options, &$openedPath) {
        var_dump(__METHOD__);
    }
    public function stream_read($count) {
        var_dump(__METHOD__);
    }
    public function stream_seek($offset, $whence = 0) {
        var_dump(__METHOD__);
    }
    public function stream_tell() {
        var_dump(__METHOD__);
    }
    public function stream_cast($cast) {
        var_dump(__METHOD__);
    }
    public function stream_close() {
        var_dump(__METHOD__);
    }

    public function stream_stat() {
        var_dump(__METHOD__);
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
