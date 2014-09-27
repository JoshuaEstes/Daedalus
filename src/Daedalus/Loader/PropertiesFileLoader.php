<?php

namespace Daedalus\Loader;

use Symfony\Component\DependencyInjection\Loader\FileLoader;

class PropertiesFileLoader extends FileLoader
{
    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        $path       = $this->locator->locate($resource);
        $properties = $this->parsePropertiesFile($path);
    }

    /**
     * @inheritdoc
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'properties' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    /**
     * @param string $path
     */
    protected function parsePropertiesFile($path)
    {
        $properties = array();
        $file = new \SplFileObject($path);
        foreach ($file as $line) {
            $line = trim($line); // get rid of any whitespace
            // ignore empty lines and comment lines
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
