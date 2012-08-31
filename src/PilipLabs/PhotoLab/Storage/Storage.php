<?php

namespace PilipLabs\PhotoLab\Storage;

class Storage
{
    private $handlers = array();


    public function addHandler($scheme, StorageInterface $storage)
    {
        if (in_array($scheme, stream_get_wrappers())) {
            throw new \RuntimeException(sprintf('Protocol "%s" has already been registered.', $scheme));
        }

        $this->handlers[$scheme] = $storage;
        return stream_wrapper_register($scheme, get_class($storage));
    }

    public function removeHandler($scheme)
    {
        if (!in_array($scheme, stream_get_wrappers())) {
            throw new \RuntimeException(sprintf('Protocol "%s" has already been unregistered.', $scheme));
        }

        if (isset($this->handlers[$scheme])) {
            unset($this->handlers[$scheme]);
        }

        return stream_wrapper_unregister($scheme);
    }
}
