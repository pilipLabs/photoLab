<?php

namespace PilipLabs\PhotoLab\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Symfony\Component\HttpFoundation\Response;

class GalleryControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];


        $controllers->get('/', function () use ($app) {
            $galleries = $app['galleries']->findAll();

            return $app['twig']->render('gallery/list.html.twig', array(
                'galleries'     => $galleries,
            ));
        });

        $controllers->match('/{galleryName}', function ($galleryName) use ($app) {
            $gallery = $app['galleries']->findByName($galleryName);

            if ($gallery) {
                $pictures = $gallery->getPictures();
                return $app['twig']->render('gallery/gallery.html.twig', array(
                    'gallery'     => $gallery,
                    'pictures'    => $pictures,
                ));
            } else {
                return $app->abort(404, "Gallery '{$galleryName}'' not found");
            }

        });

        $controllers->get('/{galleryName}/{pictureName}', function ($galleryName, $pictureName) use ($app) {
            $picture = $app['galleries']->findByName($galleryName)->getPicture($pictureName);
            if ($picture) {

                return new Response(
                    $picture->getContents(),
                    200,
                    array('Content-Type' => $picture->getContentType())
                );

            } else {
                return $app->abort(404,  "Picture '{$pictureName}'' not found in gallery '{$galleryName}'.");
            }
        });

        return $controllers;

    }
}
