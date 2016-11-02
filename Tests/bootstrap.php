<?php
/*
* This file is part of the resource-lock-bundle package.
*
* (c) Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

/**
 * @var ClassLoader $loader
 */
$loader = require $autoloadFile;

$loader->setPsr4('Abc\\DemoBundle\\', __DIR__.'/Fixtures/App/src/Abc/DemoBundle');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;