<?php

namespace JoshuaEstes\Daedalus;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{

    const VERSION = '0.1.0';

    public function __construct()
    {
        parent::__construct('Daedalus', self::VERSION);
    }
}
