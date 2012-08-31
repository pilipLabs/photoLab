<?php

namespace PilipLabs\PhotoLab\Storage;

interface ReadableInterface extends StorageInterface
{

    public function stream_eof();
    public function stream_open($path, $mode, $options, &$openedPath);
    public function stream_read($count);
    public function stream_seek($offset, $whence = SEEK_SET);
    public function stream_tell();
}
