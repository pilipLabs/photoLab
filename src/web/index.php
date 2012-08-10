<?php
/**
 * Constantes
 */
define('WEB_ROOT',  realpath(__DIR__));
define('APPS_ROOT', realpath(__DIR__  . '/../apps'));
define('VIEWS_DIR', realpath(WEB_ROOT . '/../views'));
define('CACHE_DIR', realpath(WEB_ROOT . '/../cache'));
define('IMAGES_DIR',realpath(WEB_ROOT . '/images'));
define('VENDOR_DIR',realpath(WEB_ROOT . '/../../vendor'));

ini_set('xdebug.var_display_max_depth', 5);

$loader = require __DIR__.'/../../vendor/autoload.php';
$loader->add('PilipLabs', APPS_ROOT);


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

$app->mount('/blog',    new PilipLabs\PhotoLab\BlogControllerProvider());
$app->mount('/gallery', new PilipLabs\PhotoLab\GalleryControllerProvider());

$app->get('/', function () use ($app) {
    return $app->redirect('/blog');
});

$app->run();