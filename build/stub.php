<?php
Phar::mapPhar('daedalus.phar');
require_once 'phar://daedalus.phar/vendor/Symfony/Component/ClassLoader/ClassLoader.php';
use Symfony\Component\ClassLoader\ClassLoader;

$loader = new ClassLoader();
$loader->addPrefixes(
    array(
        'Daedalus' => 'phar://daedalus.phar/src',
        'Symfony' => 'phar://daedalus.phar/vendor',
    )
);
$loader->register();

use Daedalus\Application;
use Daedalus\Kernel;

$app = new Application(new Kernel());
$app->run();
__HALT_COMPILER();
