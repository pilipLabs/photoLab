<?php
require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$image_token = "tralalaprout";

$images_dir = __DIR__.'/../images/';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$params = array(
    'site_title' => '_photo Lab',
    'title' => '',

    'navigation' => array(
        array(
            'href' => "/blog",
            'active' => (preg_match("/^\/blog\/?.*/", $_SERVER['REQUEST_URI']) ? 'active' : ''),
            "caption" => "Blog",
            "dropdown" => array(),
        ),
        array(
            'href' => "/albums",
            'active' => (preg_match("/^\/albums\/?.*/", $_SERVER['REQUEST_URI']) ? 'active' : ''),
            "caption" => "Albums",
            "dropdown" => array(),
        ),
    ),

    'albums' => array(
        "2012_end_of_the_world" => array(
            'href'      => '2012_end_of_the_world',
            'date'      => '2011-03-29',
            'author'    => '_pilip',
            'caption'     => '2012 la fin du monde',
            'pictures'  => array(),
        ),
        "2011" => array(
            'href'      => '2011',
            'date'      => '2011-03-29',
            'author'    => '_pilip',
            'caption'     => '2011',
            'pictures'  => array(),
        ),
    ),
);

foreach($params['navigation'] as $nav ){
    if ($nav['active'] == 'active') {
        $params['title'] = $params['site_title'] . " - " . $nav['caption'];
    }
}

$app->get('/less/bootstrap/{name}', function ($name) use ($app, $params) {

    $less_file = __DIR__.'/../../vendor/twitter/bootstrap/less/' .$app->escape($name);
    $css_file  = __DIR__.'/../cache/' . $app->escape($name) . '.css';
    if (!file_exists($css_file)) {
        $less = new lessc($less_file);
        $less->setFormatter("compressed");
        $css = $less->parse();
        file_put_contents($css_file, $less->parse());
    }

    if (!file_exists($css_file)) {
        return $app->abort(404, 'CSS file not found.');
    }

    $stream = function () use ($css_file) {
        readfile($css_file);
    };
    return $app->stream($stream, 200, array('Content-Type' => 'text/css'));
});

$app->get('/albums', function () use ($app, $params) {
    return $app['twig']->render('albums.twig', array(
        'params'     => $params,
        'albums'     => $params['albums'],
    ));
});

$app->match('/albums/{name}', function ($name) use ($app, $params) {
    if(array_key_exists($app->escape($name), $params['albums'])) {

        $params['title'] .= ' - ' . $params['albums'][$app->escape($name)]['caption'];

        return $app['twig']->render('main.twig', array(
            'params'     => $params,
            'content'    => 'Album : '.$app->escape($name),
        ));
    } else {
        $app->abort(404);
    }
    ;
});

$app->get('/images/{file}', function ($file) use ($app, $params) {
    if (!file_exists(__DIR__.'/../images/'.$app->escape($file))) {
        return $app->abort(404, 'The image was not found.');
    }

    $stream = function () use ($file) {
        readfile(__DIR__.'/../images/'.$file);
    };

    return $app->stream($stream, 200, array('Content-Type' => 'image/png'));
});

$app->match('/blog/{name}', function ($name) use ($app, $params) {

    return $app['twig']->render('hello.twig', array(
        'content' => $name,
    ));
});
$app->get('/blog', function () use ($app) {
    return $app->redirect('/');
});
$app->get('/', function () use ($app, $params) {
    return $app['twig']->render('main.twig', array(
        'params'     => $params,
        'content' => 'Hello',
    ));
});

$app->run();