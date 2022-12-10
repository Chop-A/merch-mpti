<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

    $router->get('/key', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', ['uses' => 'ProductController@showProduct']);

        $router->get('/id/{id}', ['uses' => 'ProductController@takeProduct']);

        $router->get('/type/{type}', ['uses' => 'ProductController@showProductType']);

        $router->group(['prefix' => 'transactions'], function () use ($router) {
            $router->get('/{id}',  ['uses' => 'TransactionController@takeTransaction']);
        });
    });

    $router->group(['prefix' => 'carts'], function () use ($router) {
        $router->get('/{id}', ['uses' => 'UserController@showCart']);

        $router->put('/{id}', ['uses' => 'UserController@updateCart']);

        $router->delete('/{id}', ['uses' => 'UserController@deleteCart']);
    });

    $router->group(['prefix' => 'auth'], function () use ($router) {
      $router->post('/register', ['uses' => 'AuthController@register']);
      $router->post('/login', ['uses' => 'AuthController@login']);
  });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->post('/', ['uses' => 'TransactionController@transaction']);
    });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->get('/', ['uses' => 'TransactionController@showTransaction']);

        $router->put('/{id}',  ['uses' => 'TransactionController@updateTransaction']);
    });

        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/{id}', ['uses' => 'UserController@showUser']);

            $router->put('/{id}', ['uses' => 'UserController@updateUser']);

            $router->delete('/{id}', ['uses' => 'UserController@deleteUser']);
        });

            $router->group(['prefix' => 'users'], function () use ($router) {
                $router->get('/', ['uses' => 'UserController@adminUser']);
            });

            $router->group(['prefix' => 'products'], function () use ($router) {
                $router->post('/', ['uses' => 'ProductController@insertProduct']);

                $router->put('/{id}', ['uses' => 'ProductController@updateProduct']);

                $router->delete('/{id}', ['uses' => 'ProductController@deleteProduct']);
            });
