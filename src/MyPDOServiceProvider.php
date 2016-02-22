<?php namespace AwkwardIdeas\MyPDO;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MyPDOServiceProvider extends ServiceProvider
{

    public function register()
    {
        require_once __DIR__ . '/DBConnection.php';
        require_once __DIR__ . '/SQLParameter.php';
        require_once __DIR__ . '/MyPDO.php';

        $this->app->register(MyPDOServiceProvider::class);
    }
}
