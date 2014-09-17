<?php

namespace JoshuaEstes\Daedalus\Loader;

use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;

class PropertiesFileLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $path       = $this->locator->locate($resource);
        $properties = $this->parsePropertiesFile($path);
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'properties' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    protected function parsePropertiesFile($path)
    {
        $properties = array();
        $file = new \SplFileObject($path);
        foreach ($file as $line) {
            $line = trim($line); // get rid of any whitespace
            if (0 === strlen($line) || 0 === strpos($line, '#')) {
                continue;
            }
            list (
                $name,
                $value
            ) = explode('=', $line);
            $this->container->setParameter($name, $value);
        }
    }
}
