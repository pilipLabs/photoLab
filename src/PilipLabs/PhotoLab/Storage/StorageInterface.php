<?php

namespace PilipLabs\PhotoLab\Storage;

interface StorageInterface
{
    public function stream_cast($cast);
    public function stream_close();
    public function stream_stat();
    public function url_stat($path, $flags);

}
