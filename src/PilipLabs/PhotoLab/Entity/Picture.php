<?php

namespace PilipLabs\PhotoLab\Entity;

use Symfony\Component\Finder\SplFileInfo;

class Picture extends SplFileInfo
{
    public function getRealpath() {
        return 'storage://' . $this->getRelativePathname();
    }

    public function getContentType()
    {
        $infos = getimagesize($this->getRealpath());
        if ($infos === false) {
            return false;
        } else {
            return $infos['mime'];
        }
    }
}
