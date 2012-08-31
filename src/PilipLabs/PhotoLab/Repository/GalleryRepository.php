<?php

namespace PilipLabs\PhotoLab\Repository;

use Symfony\Component\Finder\Finder;

use PilipLabs\PhotoLab\Storage\Storage;
use PilipLabs\PhotoLab\Storage\Handler\FileSystem;

class GalleryRepository implements Repository
{
    public function __construct()
    {
        $storage = new Storage();
        $storage->addHandler('storage', new FileSystem('../data'));
    }

    public function findAll()
    {
        $finder = new Finder();
        return $finder
            ->directories()
            ->depth(0)
            ->in('storage://');
    }

    public function findByName($name)
    {
        $finder = new Finder();
        $galleries = $finder
                    ->directories()
                    ->name($name)
                    ->in('storage://');
        if ($galleries->count() == 0) {
            return false;
        }

        // Get first element
        foreach ($galleries as $gallery) {
            break;
        }

        return $gallery;
    }

    public function findPicturesInGallery($name)
    {
        $finder   = new Finder();
        $pictures = $finder
                    ->files()
                    ->name('*.jpg')
                    ->name('*.gif')
                    ->name('*.png')
                    ->in('storage://' . $name);
        return $pictures;
    }
}
