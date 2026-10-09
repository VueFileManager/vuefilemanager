<?php
namespace App;

use Illuminate\Foundation\Application;

/**
 * Application code lives in "src/App" instead of the default "app" directory.
 */
class BaseApplication extends Application
{
    protected $namespace = 'App\\';

    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        $this->useAppPath($this->basePath('src/App'));
    }
}
