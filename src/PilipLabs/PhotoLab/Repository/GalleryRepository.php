<?php

namespace PilipLabs\PhotoLab\Repository;

use Symfony\Component\Finder\Finder;

use PilipLabs\PhotoLab\Entity\Gallery;
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
        $elements = $finder
            ->directories()
            ->depth(0)
            ->in('storage://');

        $galleries = array();
        foreach ($elements as $element) {
            $galleries[] = new Gallery($element->getfilename(), $element->getRelativePath(), $element->getRelativePathname());
        }

        return new \ArrayIterator($galleries);
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

        return new Gallery($gallery->getfilename(), $gallery->getRelativePath(), $gallery->getRelativePathname());
    }

}
