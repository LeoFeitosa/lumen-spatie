<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(
    ['prefix' => 'api'],
    function () use ($router) {
        $router->post('user/login', 'UserController@login');

        $router->group(
            ['middleware' => ['auth', 'jwt.refresh']],
            function () use ($router) {
                $path = '../routes/';
                if ($handle = @opendir($path)) {
                    while ($entry = readdir($handle)) {
                        $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                        if (in_array($ext, array('php')) && $entry != 'web.php')
                            require_once $path . $entry;
                    }
                    closedir($handle);
                }
            }
        );
    }
);
