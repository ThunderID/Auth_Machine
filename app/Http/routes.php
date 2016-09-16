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
		'uses'				=> 'ClientController@index',
		'middleware'		=> 'jwt|company:read-client',
	]
);

$app->post('/clients',
	[
		'uses'				=> 'ClientController@post',
		'middleware'		=> 'jwt|company:store-client',
	]
);

$app->delete('/clients',
	[
		'uses'				=> 'ClientController@delete',
		'middleware'		=> 'jwt|company:delete-client',
	]
);

$app->get('/users',
	[
		'uses'				=> 'UserController@index',
		'middleware'		=> 'jwt|company:read-user',
	]
);

$app->post('/users',
	[
		'uses'				=> 'UserController@post',
		'middleware'		=> 'jwt|company:store-user',
	]
);

$app->delete('/users',
	[
		'uses'				=> 'UserController@delete',
		'middleware'		=> 'jwt|company:delete-user',
	]
);

$app->post('/tokens/generate',
	[
		'uses'				=> 'AuthController@generate'
	]
);
