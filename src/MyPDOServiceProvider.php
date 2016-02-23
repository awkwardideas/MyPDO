<?php namespace AwkwardIdeas\MyPDO;

use Illuminate\Support\ServiceProvider;

class MyPDOServiceProvider extends ServiceProvider
{

    public function register()
    {
        require_once __DIR__ . '/DBConnection.php';
        require_once __DIR__ . '/SQLParameter.php';
        require_once __DIR__ . '/MyPDO.php';
    }
}
