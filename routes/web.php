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

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->group(['middleware' => 'cors'], function () use ($router) {
    //All the routes you want to allow CORS for
  
    $router->options('/{any:.*}', function (Request $req) {
      return;
    });
    
    // API route group
    $router->group(['prefix' => 'api'], function () use ($router) {
      $router->post('addContract', 'ContractController@addContract');
      $router->get('getAllContracts', 'ContractController@getAllContracts');
      $router->get('getContractById/{id}', 'ContractController@getContractById');
      $router->get('getContractByUserId/{id}', 'ContractController@getContractByUserId');
      
    });

});
