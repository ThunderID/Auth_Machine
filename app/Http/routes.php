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

$app->get('/clients',
	[
		'uses'				=> 'ClientController@index'
	]
);

$app->post('/clients',
	[
		'uses'				=> 'ClientController@post'
	]
);

$app->delete('/clients',
	[
		'uses'				=> 'ClientController@delete'
	]
);

$app->get('/users',
	[
		'uses'				=> 'UserController@index'
	]
);

$app->post('/users',
	[
		'uses'				=> 'UserController@post'
	]
);

$app->delete('/users',
	[
		'uses'				=> 'UserController@delete'
	]
);
