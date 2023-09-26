<?php
use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(
    [
        'namespace' => 'App\Modules\Property\Controllers',
        'prefix' => 'properties',
    ],
    function () use ($router) {
        $router->get('/', 'PropertyController@index');
        $router->get('/{id}', 'PropertyController@view');
        $router->post('/', 'PropertyController@create');
        $router->post('/{id}/sell', 'PropertyController@sell');
        $router->delete('/{id}', 'PropertyController@delete');
    }
);
