<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;


/**
 * @var ClassLoader $loader
 */

$loader = require __DIR__.'/../vendor/autoload.php';

/*
$loader->registerPrefixes(array(
    // Swift, Twig etc.
    'PHPExcel' => __DIR__ . '/../vendor/phpexcel/lib/PHPExcel'
));
*/

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
