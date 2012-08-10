<?php
require_once __DIR__.'/../../vendor/autoload.php';

define('WEB_ROOT',  realpath(__DIR__));
define('VIEWS_DIR', realpath(WEB_ROOT . '/../views'));
define('CACHE_DIR', realpath(WEB_ROOT . '/../cache'));
define('IMAGES_DIR',realpath(WEB_ROOT . '/images'));
define('VENDOR_DIR',realpath(WEB_ROOT . '/../../vendor'));

$app = new Silex\Application();
$app['debug'] = true;

$image_token = "tralalaprout";

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => VIEWS_DIR,
    'twig.options' => array(
        'cache' => CACHE_DIR . '/twig',
    ),
));
$app->register(new SilexExtension\AsseticExtension(), array(
    'assetic.path_to_web' => WEB_ROOT,
    'assetic.options' => array(
        'debug' => false,
        'formulae_cache_dir' => CACHE_DIR . '/assetic',
        'auto_dump_assets' => true,
    ),
    'assetic.filters' => $app->protect(
        function($fm) {
            // Activate LESS Filter
            $fm->set('less', new Assetic\Filter\LessphpFilter());
            $fm->set('css_rewrite', new Assetic\Filter\CssRewriteFilter());
        }
    )
));


$params = array(
    'site_title' => '_photo Lab',
    'title' => '_photo Lab',

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

$app->get('/albums', function () use ($app, $params) {
    return $app['twig']->render('albums.html.twig', array(
        'params'     => $params,
        'albums'     => $params['albums'],
    ));
});

$app->match('/albums/{name}', function ($name) use ($app, $params) {
    if(array_key_exists($app->escape($name), $params['albums'])) {

        $params['title'] .= ' - ' . $params['albums'][$app->escape($name)]['caption'];

        return $app['twig']->render('main.html.twig', array(
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
    return $app['twig']->render('hello.html.twig', array(
        'content' => $name,
    ));
});

$app->get('/blog', function () use ($app) {
    return $app->redirect('/');
});



$app->get('/', function () use ($app, $params) {
    return $app['twig']->render('main.html.twig', array(
        'params'     => $params,
        'content' => 'Hello',
    ));
});

$app->run();