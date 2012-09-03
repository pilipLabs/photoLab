<?php

namespace PilipLabs\PhotoLab\Entity;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Gallery extends SplFileInfo
{

    public function getRealpath() {
        return 'storage://' . $this->getRelativePathname();
    }

    public function getPictures()
    {
        $finder   = new Finder();
        $elements = $finder
                    ->files()
                    ->name('*.jpg')
                    ->name('*.gif')
                    ->name('*.png')
                    ->in('storage://' . $this->getRelativePath());

        $pictures = array();

        foreach ($elements as $element) {
            $pictures[] = new Picture($element->getFilename(), $element->getRelativePath(), $element->getRelativePathname());
        }

        return new \ArrayIterator($pictures);
    }

    public function getPicture($name)
    {
        $finder   = new Finder();
        $elements = $finder
                    ->files()
                    ->name($name)
                    ->in('storage://' . $this->getRelativePath());

        if ($elements->count() === 0) {
            return false;
        }

        foreach ($elements as $element) {
            break;
        }

        return new Picture($element->getFilename(), $element->getRelativePath(), $element->getRelativePathname());
    }
}
