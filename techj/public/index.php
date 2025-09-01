<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();

// Home
$router->get('/', 'HomeController@index');

// Auth
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

// Perfil
$router->get('/perfil', 'ClienteController@profile');
$router->get('/perfil/editar', 'ClienteController@edit');
$router->post('/perfil/update', 'ClienteController@update');

// Skills, certificates, education
$router->post('/skill/add', 'ClienteController@addSkill');
$router->post('/skill/delete', 'ClienteController@deleteSkill');
$router->post('/certificate/add', 'ClienteController@addCertificate');
$router->post('/education/add', 'ClienteController@addEducation');

// Vagas
$router->get('/vagas', 'VagaController@index');
$router->post('/vagas/apply', 'VagaController@apply');
$router->post('/vagas/create', 'VagaController@create');

// Courses
$router->get('/courses', 'CourseController@index');
$router->post('/courses/enroll', 'CourseController@enroll');
$router->post('/courses/progress', 'CourseController@updateProgress');

// Publications
$router->post('/publicacao/create', 'PublicationController@create');

// Follow
$router->post('/follow', 'ClienteController@follow');

$router->dispatch();
