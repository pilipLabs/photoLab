<?php

namespace PilipLabs\PhotoLab;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class GalleryControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];


        $controllers->get('/', function () use ($app) {
            return $app['twig']->render('gallery/list.html.twig', array(
                'albums'     => "",
            ));
        });

        $controllers->match('/{galleryName}', function ($galleryName) use ($app) {
            return $app->abort(404);
        });

        $controllers->get('/{galleryName}/{pictureName}', function ($galleryName, $pictureName) use ($app) {
            return $app->abort(404);
        });




        return $controllers;

    }
}
