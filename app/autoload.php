<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;


/**
 * @var ClassLoader $loader
 */

$loader = require __DIR__.'/../vendor/autoload.php';

//

/*
$loader->add( 'YOURNAMESPACE', __DIR__.'/../vendor/YOURVENDOR/src' );
if (!function_exists('intl_get_error_code')) {
	require_once  _DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

	$loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
return $loader;
*/
//

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
