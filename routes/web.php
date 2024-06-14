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

$router->group(['prefix' => 'api'], function () use ($router) {
  $router->get('orders',  ['uses' => 'OrderController@showAllorders']);

  $router->get('orders/{id}', ['uses' => 'OrderController@showOneOrder']);

  $router->post('orders', ['uses' => 'OrderController@create']);

  $router->delete('orders/{id}', ['uses' => 'OrderController@delete']);

  $router->put('orders/{id}', ['uses' => 'OrderController@update']);

  $router->post('register', 'AuthController@register');
  // $router->post('register', 'UserController@register');'
  $router->post('login', 'AuthController@login');
  // $router->post('login', 'UserController@login');
  // Matches "/api/profile
  $router->get('profile', 'UserController@profile');
  // $router->put('profile', 'UserController@updateProfile');

  // $router->get('profile', ['middleware' => 'auth', 'uses' => 'UserController@getProfile']);
  // $router->put('profile', ['middleware' => 'auth', 'uses' => 'UserController@updateProfile']);

});
