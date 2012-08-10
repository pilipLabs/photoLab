<?php

namespace PilipLabs\PhotoLab;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class BlogControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {
            return $app['twig']->render('blog/list.html.twig', array(
                'content' => '$name',
            ));;
        });

        $controllers->match('/{year}/{month}/{day}/{slug}', function ($year, $month, $day, $slug) use ($app) {
            return $app['twig']->render('blog/post.html.twig', array(
                'content' => $slug,
            ));
        });

        return $controllers;

    }
}
