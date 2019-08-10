<?php

$router->group(
    ['prefix' => 'user'],
    function () use ($router) {

        $router->group(
            ['middleware' => 'role:super-admin,admin'],
            function () use ($router) {
                $router->post(
                    '',
                    [
                        'middleware' => 'permission:post',
                        'uses' => 'UserController@store'
                    ]
                );

                $router->put(
                    '{id}',
                    [
                        'middleware' => 'permission:put',
                        'uses' => 'UserController@update'
                    ]
                );
            }
        );

        $router->get('/', [
            'middleware' =>
            [
                'permission:get',
                'role:super-admin|admin'
            ],
            'uses' => 'UserController@index'
        ]);


        $router->get(
            '{id}',
            [
                'middleware' => 'permission:get',
                'uses' => 'UserController@show'
            ]
        );
    }
);
