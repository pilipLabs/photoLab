<?php

namespace PilipLabs\PhotoLab\Storage;

interface WriteableInterface extends StorageInterface
{

    public function rename($origin, $target);
    public function stream_flush();
    public function stream_set_option($options, $arg1, $arg2);
    public function stream_write($data);
    public function unlink($path);

}
