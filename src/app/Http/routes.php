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

$app->get('/', function () use ($app) 
{
    return 'Welcome Thunder Lock Service';
});

$app->get('/locks',
	[
		'uses'				=> 'LockController@index',
	]
);

$app->post('/locks',
	[
		'uses'				=> 'LockController@post',
	]
);

$app->delete('/locks',
	[
		'uses'				=> 'LockController@delete',
	]
);
