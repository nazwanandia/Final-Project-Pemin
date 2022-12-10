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

//TUBES
$router->group(['prefix' => 'mahasiswa'], function () use ($router) {
    $router->post('/{nim}/matakuliah/{mkId}', ['uses' => 'mahasiswaController@addMatkulMahasiswa']);
    $router->put('/{nim}/matakuliah/{mkId}', ['uses' => 'mahasiswaController@delMatkulMahasiswa']);
    $router->get('/', ['uses' => 'mahasiswaController@getUsers']);
    $router->get('/profile', ['uses' => 'mahasiswaController@getUserByToken']);
    $router->get('/{nim}', ['uses' => 'mahasiswaController@getByNim']);
});

//EASY
$router->POST('/auth/register', ['uses' => 'mahasiswaController@register']);
$router->POST('/auth/login', ['uses' => 'mahasiswaController@login']);
$router->get('/mahasiswa', ['uses' => 'mahasiswaController@getUsers']);
$router->get('/mahasiswa/profile', ['uses' => 'mahasiswaController@getUsers']);

//HARD
$router->get('/mahasiswa/{nim}', ['uses' => 'mahasiswaController@getMahasiswaById']);
// $router->put('/mahasiswa/{nim}/matakuliah/{mkId}', ['middleware'=> 'auth', 'uses' => 'mahasiswaController@addMatkulMahasiswa']);
// $router->PUT('/mahasiswa/{nim}/matakuliah/{mkId}', ['uses' => 'mahasiswaController@getUsers']);

//EXPERT
$router->get('/prodi', ['uses' => 'mahasiswaController@getProdi']);
$router->get('/matakuliah', ['uses' => 'mahasiswaController@getMatkul']);

// TESTOS
$router->POST('/prodi/add', ['uses' => 'mahasiswaController@addprodi']);
$router->POST('/matkul/add', ['uses' => 'mahasiswaController@addmatkul']);
//Many to Many
$router->get('/posts/{nim}', ['uses' => 'mahasiswaController@getMahasiswaById']);
$router->put('/posts/{nim}/matakuliah/{mkId}', ['uses' => 'mahasiswaController@addMatkulMahasiswa']); //


$router->group(['prefix' => 'users'], function () use ($router) {
    $router->post('/default', ['uses' => 'mahasiswaController@defaultUser']);
    $router->post('/new', ['uses' => 'mahasiswaController@createUser']);
    $router->get('/all', ['uses' => 'mahasiswaController@getUsers']);
});
